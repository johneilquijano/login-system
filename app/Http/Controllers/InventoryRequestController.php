<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\User;
use App\Models\OrderingTask;
use App\Models\OrderingTaskItem;
use App\Services\AuditLogService;
use App\Notifications\InventoryRequestSubmittedNotification;
use App\Notifications\InventoryRequestApprovedNotification;
use App\Events\InventoryRequestSubmitted;
use App\Events\InventoryRequestApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class InventoryRequestController extends Controller
{
    /**
     * Display all inventory requests for the employee
     */
    public function index()
    {
        $orgId = Auth::user()->org_id;
        $statuses = ['draft', 'submitted', 'approved', 'denied', 'fulfilled', 'cancelled'];
        
        $requests = InventoryRequest::forOrganization($orgId)
            ->where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employee.inventory-requests.index', compact('requests', 'statuses'));
    }

    /**
     * Show create request form (modal will be handled via JavaScript)
     */
    public function create()
    {
        return view('employee.inventory-requests.create');
    }

    /**
     * Store a new inventory request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_title' => 'required|string|max:255',
            'reason' => 'nullable|string|max:1000',
            'priority' => 'required|in:normal,urgent',
            'needed_by_date' => 'nullable|date|after:today',
            'status' => 'required|in:draft,submitted',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.job_number' => 'required|string|max:255',
            'items.*.model_number' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        $orgId = Auth::user()->org_id;

        // Check if any admin in the organization has auto-approve enabled (only matters if status is submitted)
        $autoApprove = false;
        $adminWithAutoApprove = null;
        
        if ($validated['status'] === 'submitted') {
            $adminWithAutoApprove = User::where('org_id', $orgId)
                ->where('role', 'admin')
                ->where('auto_approve_requests', true)
                ->first();
            
            $autoApprove = $adminWithAutoApprove !== null;
        }

        // Determine final status
        $finalStatus = $validated['status'];
        if ($autoApprove) {
            $finalStatus = 'approved';
        }

        // Create the request
        $inventoryRequest = Auth::user()->inventoryRequests()->create([
            'org_id' => $orgId,
            'request_title' => $validated['request_title'],
            'reason' => $validated['reason'],
            'priority' => $validated['priority'],
            'needed_by_date' => $validated['needed_by_date'],
            'status' => $finalStatus,
            'submitted_at' => $validated['status'] === 'submitted' ? now() : null,
            'approved_at' => $autoApprove ? now() : null,
            'approved_by' => $autoApprove ? $adminWithAutoApprove->id : null,
        ]);

        // Create the items
        foreach ($validated['items'] as $item) {
            InventoryRequestItem::create([
                'inventory_request_id' => $inventoryRequest->id,
                'item_name' => $item['item_name'],
                'job_number' => $item['job_number'],
                'model_number' => $item['model_number'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'],
            ]);
        }

        // Log the creation of the inventory request
        AuditLogService::logAction(
            Auth::user(),
            'create',
            'inventory_request',
            $inventoryRequest->id,
            "Created inventory request: " . $inventoryRequest->request_title . ($autoApprove ? ' (auto-approved)' : ''),
            metadata: [
                'item_count' => count($validated['items']),
                'priority' => $validated['priority'],
                'initial_status' => $validated['status'],
                'auto_approved' => $autoApprove
            ]
        );

        // Send notification to all admins if submitted and not auto-approved
        if ($validated['status'] === 'submitted' && !$autoApprove) {
            $admins = User::where('org_id', $orgId)->where('role', 'admin')->get();
            Notification::send($admins, new InventoryRequestSubmittedNotification($inventoryRequest));
            
            // Fire event for AppNotification creation
            event(new InventoryRequestSubmitted($inventoryRequest));
        }

        // If auto-approved, create ordering task items and notify employee
        if ($autoApprove) {
            $this->createOrderingTaskItems($inventoryRequest);
            
            // Send approval notification to employee
            $inventoryRequest->user->notify(new InventoryRequestApprovedNotification($inventoryRequest));
            
            // Fire event for notifications
            event(new InventoryRequestApproved($inventoryRequest));
        }

        $message = $autoApprove
            ? 'Inventory request submitted and automatically approved!'
            : ($validated['status'] === 'submitted' 
                ? 'Inventory request submitted successfully' 
                : 'Inventory request saved as draft');

        return redirect()->route('inventory-requests.index')
            ->with('success', $message);
    }

    /**
     * Display a specific inventory request
     */
    public function show(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization and is user's request
        if ($inventoryRequest->org_id !== Auth::user()->org_id || $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $inventoryRequest->load(['items', 'approver', 'denier']);

        return view('employee.inventory-requests.show', compact('inventoryRequest'));
    }

    /**
     * Show edit form for draft request
     */
    public function edit(InventoryRequest $inventoryRequest)
    {
        // Only allow editing draft requests
        if ($inventoryRequest->status !== 'draft' || 
            $inventoryRequest->org_id !== Auth::user()->org_id || 
            $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $inventoryRequest->load('items');

        return view('employee.inventory-requests.edit', compact('inventoryRequest'));
    }

    /**
     * Update an inventory request (draft only)
     */
    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        // Only allow updating draft requests
        if ($inventoryRequest->status !== 'draft' || 
            $inventoryRequest->org_id !== Auth::user()->org_id || 
            $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'request_title' => 'required|string|max:255',
            'reason' => 'nullable|string|max:1000',
            'priority' => 'required|in:normal,urgent',
            'needed_by_date' => 'nullable|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.job_number' => 'required|string|max:255',
            'items.*.model_number' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        // Update request
        $inventoryRequest->update([
            'request_title' => $validated['request_title'],
            'reason' => $validated['reason'],
            'priority' => $validated['priority'],
            'needed_by_date' => $validated['needed_by_date'],
        ]);

        // Delete existing items and create new ones
        $inventoryRequest->items()->delete();

        foreach ($validated['items'] as $item) {
            InventoryRequestItem::create([
                'inventory_request_id' => $inventoryRequest->id,
                'item_name' => $item['item_name'],
                'job_number' => $item['job_number'],
                'model_number' => $item['model_number'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'],
            ]);
        }

        return redirect()->route('inventory-requests.show', $inventoryRequest)
            ->with('success', 'Request updated successfully');
    }

    /**
     * Cancel an inventory request (if not yet approved)
     */
    public function cancel(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to user
        if ($inventoryRequest->org_id !== Auth::user()->org_id || 
            $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel if not approved or fulfilled
        if (!in_array($inventoryRequest->status, ['draft', 'submitted'])) {
            return redirect()->route('inventory-requests.show', $inventoryRequest)
                ->with('error', 'Cannot cancel a request that has been approved or fulfilled');
        }

        $oldStatus = $inventoryRequest->status;
        $inventoryRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Log the cancellation
        AuditLogService::logInventoryStatusChange(
            Auth::user(),
            $inventoryRequest->id,
            $oldStatus,
            'cancelled'
        );

        return redirect()->route('inventory-requests.index')
            ->with('success', 'Request cancelled successfully');
    }

    /**
     * Submit a draft request
     */
    public function submit(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to user
        if ($inventoryRequest->org_id !== Auth::user()->org_id || 
            $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only submit draft requests
        if ($inventoryRequest->status !== 'draft') {
            return redirect()->route('inventory-requests.show', $inventoryRequest)
                ->with('error', 'Only draft requests can be submitted');
        }

        $oldStatus = $inventoryRequest->status;
        $orgId = Auth::user()->org_id;

        // Check if any admin in the organization has auto-approve enabled
        $adminWithAutoApprove = User::where('org_id', $orgId)
            ->where('role', 'admin')
            ->where('auto_approve_requests', true)
            ->first();

        if ($adminWithAutoApprove) {
            // Auto-approve the request
            $inventoryRequest->update([
                'status' => 'approved',
                'submitted_at' => now(),
                'approved_at' => now(),
                'approved_by' => $adminWithAutoApprove->id,
            ]);

            // Log inventory request submission
            AuditLogService::logAction(
                Auth::user(),
                'submit',
                'inventory_request',
                $inventoryRequest->id,
                "Submitted inventory request (auto-approved)",
                metadata: ['item_count' => $inventoryRequest->items()->count(), 'auto_approved' => true]
            );

            // Log the status changes
            AuditLogService::logInventoryStatusChange(
                Auth::user(),
                $inventoryRequest->id,
                $oldStatus,
                'submitted'
            );

            AuditLogService::logInventoryStatusChange(
                $adminWithAutoApprove,
                $inventoryRequest->id,
                'submitted',
                'approved'
            );

            // Create ordering task items for each approved request item
            $this->createOrderingTaskItems($inventoryRequest);

            // Send notification to employee (auto-approved)
            $inventoryRequest->user->notify(new InventoryRequestApprovedNotification($inventoryRequest));

            // Dispatch event for notifications
            event(new InventoryRequestApproved($inventoryRequest));

            return redirect()->route('inventory-requests.show', $inventoryRequest)
                ->with('success', 'Request submitted and automatically approved!');
        } else {
            // Regular submission without auto-approve
            $inventoryRequest->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Log inventory request submission
            AuditLogService::logAction(
                Auth::user(),
                'submit',
                'inventory_request',
                $inventoryRequest->id,
                "Submitted inventory request",
                metadata: ['item_count' => $inventoryRequest->items()->count()]
            );

            // Also log the status change
            AuditLogService::logInventoryStatusChange(
                Auth::user(),
                $inventoryRequest->id,
                $oldStatus,
                'submitted'
            );

            return redirect()->route('inventory-requests.show', $inventoryRequest)
                ->with('success', 'Request submitted successfully');
        }
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
}
