<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryRequest;
use App\Models\OrderingTask;
use App\Models\OrderingTaskItem;
use App\Models\User;
use App\Events\InventoryRequestApproved;
use App\Events\InventoryRequestDenied;
use App\Events\InventoryRequestFulfilled;
use App\Notifications\InventoryRequestApprovedNotification;
use App\Notifications\InventoryRequestDeniedNotification;
use App\Notifications\InventoryRequestFulfilledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class InventoryRequestController extends Controller
{
    /**
     * Display all inventory requests for the organization
     */
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;
        
        $query = InventoryRequest::forOrganization($orgId)
            ->with('user', 'items', 'approver', 'denier');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter by employee
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->input('date_to'));
        }

        // Filter by needed by date (overdue)
        if ($request->input('show_overdue') === '1') {
            $query->whereDate('needed_by_date', '<', now()->toDateString())
                ->where('status', '!=', 'fulfilled')
                ->where('status', '!=', 'cancelled');
        }

        $requests = $query->orderBy('submitted_at', 'desc')->paginate(20);

        // Dashboard stats
        $stats = [
            'pending' => InventoryRequest::forOrganization($orgId)->where('status', 'submitted')->count(),
            'approved' => InventoryRequest::forOrganization($orgId)->where('status', 'approved')->count(),
            'overdue' => InventoryRequest::forOrganization($orgId)
                ->whereDate('needed_by_date', '<', now()->toDateString())
                ->whereIn('status', ['submitted', 'approved'])
                ->count(),
            'fulfilled_today' => InventoryRequest::forOrganization($orgId)
                ->where('status', 'fulfilled')
                ->whereDate('fulfilled_at', now()->toDateString())
                ->count(),
        ];

        $employees = User::forOrganization($orgId)->where('role', 'employee')->get();

        return view('admin.inventory-requests.index', compact('requests', 'stats', 'employees'));
    }

    /**
     * Display a specific inventory request
     */
    public function show(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization
        if ($inventoryRequest->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $inventoryRequest->load(['items', 'user', 'approver', 'denier']);

        return view('admin.inventory-requests.show', compact('inventoryRequest'));
    }

    /**
     * Approve an inventory request
     */
    public function approve(Request $request, InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization
        if ($inventoryRequest->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        // Can only approve submitted requests
        if ($inventoryRequest->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted requests can be approved');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $inventoryRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Create ordering task items for each approved request item
        $this->createOrderingTaskItems($inventoryRequest);

        // Send notification to employee
        $inventoryRequest->user->notify(new InventoryRequestApprovedNotification($inventoryRequest));

        // Dispatch event for notifications
        event(new InventoryRequestApproved($inventoryRequest));

        return redirect()->route('admin.inventory-requests.show', $inventoryRequest)
            ->with('success', 'Request approved successfully');
    }

    /**
     * Create ordering task items for approved request
     */
    private function createOrderingTaskItems(InventoryRequest $inventoryRequest)
    {
        try {
            // Get or create open ordering task for organization
            $orderingTask = OrderingTask::forOrganization($inventoryRequest->org_id)
                ->where('status', 'open')
                ->first();

            if (!$orderingTask) {
                $orderingTask = OrderingTask::create([
                    'org_id' => $inventoryRequest->org_id,
                    'status' => 'open',
                    'opened_at' => now(),
                ]);
                \Log::info("Created new OrderingTask #{$orderingTask->id} for org {$inventoryRequest->org_id}");
            } else {
                \Log::info("Using existing OrderingTask #{$orderingTask->id} for org {$inventoryRequest->org_id}");
            }

            // Create ordering task item for each inventory request item
            foreach ($inventoryRequest->items as $item) {
                \Log::info("Creating OrderingTaskItem for request item #{$item->id}: {$item->item_name}");
                
                OrderingTaskItem::create([
                    'org_id' => $inventoryRequest->org_id,
                    'ordering_task_id' => $orderingTask->id,
                    'inventory_request_id' => $inventoryRequest->id,
                    'inventory_request_item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'job_number' => !empty($item->job_number) ? $item->job_number : 'N/A',
                    'model_number' => !empty($item->model_number) ? $item->model_number : 'N/A',
                    'quantity_approved' => $item->quantity,
                    'notes' => $item->notes,
                    'status' => 'pending',
                ]);
            }
            
            \Log::info("Successfully created ordering task items for request #{$inventoryRequest->id}");
        } catch (\Exception $e) {
            \Log::error("Error creating ordering task items: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Deny an inventory request
     */
    public function deny(Request $request, InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization
        if ($inventoryRequest->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        // Can only deny submitted requests
        if ($inventoryRequest->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted requests can be denied');
        }

        $validated = $request->validate([
            'denied_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $inventoryRequest->update([
            'status' => 'denied',
            'denied_at' => now(),
            'denied_by' => Auth::id(),
            'denied_reason' => $validated['denied_reason'],
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Send notification to employee
        $inventoryRequest->user->notify(new InventoryRequestDeniedNotification($inventoryRequest));

        // Dispatch event for notifications
        event(new InventoryRequestDenied($inventoryRequest));

        return redirect()->route('admin.inventory-requests.show', $inventoryRequest)
            ->with('success', 'Request denied successfully');
    }

    /**
     * Fulfill an inventory request (issue items)
     */
    public function fulfill(Request $request, InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization
        if ($inventoryRequest->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        // Can only fulfill approved requests
        if ($inventoryRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved requests can be fulfilled');
        }

        $validated = $request->validate([
            'fulfilled_quantities' => 'required|array',
            'fulfilled_quantities.*' => 'required|integer|min:0',
            'fulfillment_notes' => 'nullable|string|max:1000',
        ]);

        // Update item fulfilled quantities
        foreach ($validated['fulfilled_quantities'] as $itemId => $quantity) {
            $item = $inventoryRequest->items()->find($itemId);
            if ($item) {
                $item->update(['fulfilled_quantity' => $quantity]);
            }
        }

        // Mark as fulfilled
        $inventoryRequest->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
            'admin_notes' => $validated['fulfillment_notes'] ?? $inventoryRequest->admin_notes,
        ]);

        // Send notification to employee
        $inventoryRequest->user->notify(new InventoryRequestFulfilledNotification($inventoryRequest));

        // Dispatch event for notifications
        event(new InventoryRequestFulfilled($inventoryRequest));

        return redirect()->route('admin.inventory-requests.show', $inventoryRequest)
            ->with('success', 'Request fulfilled successfully');
    }

    /**
     * Add admin note to a request
     */
    public function addNote(Request $request, InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization
        if ($inventoryRequest->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        // Append note to existing notes
        $currentNotes = $inventoryRequest->admin_notes ?? '';
        $newNote = "[" . Auth::user()->name . " - " . now()->format('M d, Y H:i') . "]\n" . $validated['note'];
        
        $inventoryRequest->update([
            'admin_notes' => $currentNotes ? $currentNotes . "\n\n" . $newNote : $newNote,
        ]);

        return redirect()->back()->with('success', 'Note added successfully');
    }
}
