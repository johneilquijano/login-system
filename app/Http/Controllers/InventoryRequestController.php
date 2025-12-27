<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\User;
use App\Notifications\InventoryRequestSubmittedNotification;
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
            'items.*.category' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        $orgId = Auth::user()->org_id;

        // Create the request
        $inventoryRequest = Auth::user()->inventoryRequests()->create([
            'org_id' => $orgId,
            'request_title' => $validated['request_title'],
            'reason' => $validated['reason'],
            'priority' => $validated['priority'],
            'needed_by_date' => $validated['needed_by_date'],
            'status' => $validated['status'],
            'submitted_at' => $validated['status'] === 'submitted' ? now() : null,
        ]);

        // Create the items
        foreach ($validated['items'] as $item) {
            InventoryRequestItem::create([
                'inventory_request_id' => $inventoryRequest->id,
                'item_name' => $item['item_name'],
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'],
            ]);
        }

        // Send notification to all admins if submitted
        if ($validated['status'] === 'submitted') {
            $admins = User::where('org_id', $orgId)->where('role', 'admin')->get();
            Notification::send($admins, new InventoryRequestSubmittedNotification($inventoryRequest));
        }

        $message = $validated['status'] === 'submitted' 
            ? 'Inventory request submitted successfully' 
            : 'Inventory request saved as draft';

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
            'items.*.category' => 'nullable|string|max:255',
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
                'category' => $item['category'],
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

        $inventoryRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

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

        $inventoryRequest->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('inventory-requests.show', $inventoryRequest)
            ->with('success', 'Request submitted successfully');
    }

    /**
     * Acknowledge receipt of a fulfilled request
     */
    public function acknowledge(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to user
        if ($inventoryRequest->org_id !== Auth::user()->org_id || 
            $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only acknowledge fulfilled requests
        if ($inventoryRequest->status !== 'fulfilled') {
            return redirect()->route('inventory-requests.show', $inventoryRequest)
                ->with('error', 'Only fulfilled requests can be acknowledged');
        }

        // Mark as acknowledged (you might add an acknowledged_at timestamp if needed)
        // For now, we'll just return success

        return redirect()->route('inventory-requests.show', $inventoryRequest)
            ->with('success', 'Receipt acknowledged');
    }
}
