<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderingTask;
use App\Models\OrderingTaskItem;
use App\Models\AppNotification;
use App\Notifications\InventoryRequestFulfilledNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderingTaskController extends Controller
{
    /**
     * Display ordering tasks list
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->org_id;
        $status = $request->query('status', 'open');

        $tasks = OrderingTask::forOrganization($orgId)
            ->where('status', $status)
            ->with('items')
            ->orderBy('opened_at', 'desc')
            ->paginate(10);

        // Get count summaries for each task
        $taskCounts = [];
        foreach ($tasks as $task) {
            $taskCounts[$task->id] = [
                'pending' => $task->getStatusCount('pending'),
                'ordered' => $task->getStatusCount('ordered'),
                'received' => $task->getStatusCount('received'),
                'total' => $task->getTotalItemCount(),
            ];
        }

        return view('admin.ordering-tasks.index', [
            'tasks' => $tasks,
            'taskCounts' => $taskCounts,
            'status' => $status,
        ]);
    }

    /**
     * Show ordering task details
     */
    public function show($taskId, Request $request)
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        // Explicitly find the task by ID and org_id for better control
        $task = OrderingTask::where('id', $taskId)
            ->where('org_id', $orgId)
            ->with(['items' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        // Get status filter
        $statusFilter = $request->query('status', '');

        $items = $task->items;
        if ($statusFilter) {
            $items = $items->filter(function($item) use ($statusFilter) {
                return $item->status === $statusFilter;
            });
        }

        return view('admin.ordering-tasks.show', [
            'task' => $task,
            'items' => $items,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Mark item as ordered
     */
    public function markOrdered($itemId, Request $request)
    {
        try {
            $user = Auth::user();

            // Find item and verify org access
            $item = OrderingTaskItem::where('id', $itemId)
                ->where('org_id', $user->org_id)
                ->firstOrFail();

            $item->update([
                'status' => 'ordered',
                'ordered_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item marked as ordered',
            ]);
        } catch (\Exception $e) {
            \Log::error('Mark ordered error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark item as ordered',
            ], 500);
        }
    }

    /**
     * Receive items (partial or full receiving)
     */
    public function receive($itemId, Request $request)
    {
        try {
            $user = Auth::user();

            // Validate request
            $validated = $request->validate([
                'receive_quantity' => 'required|integer|min:1',
                'receive_date' => 'nullable|date',
                'receive_notes' => 'nullable|string|max:1000',
            ]);

            // Find item and verify org access
            $item = OrderingTaskItem::where('id', $itemId)
                ->where('org_id', $user->org_id)
                ->firstOrFail();

            // Record the receipt
            $item->receiveItems(
                $validated['receive_quantity'],
                $validated['receive_notes'] ?? null
            );

            // Check if parent request should be auto-fulfilled
            $this->checkAndFulfillRequest($item->inventoryRequest);

            return response()->json([
                'success' => true,
                'message' => 'Items received successfully',
                'item' => [
                    'id' => $item->id,
                    'quantity_received' => $item->quantity_received,
                    'quantity_approved' => $item->quantity_approved,
                    'status' => $item->status,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Receive error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to receive items',
            ], 500);
        }
    }

    /**
     * Check if request should be auto-fulfilled
     */
    private function checkAndFulfillRequest($inventoryRequest)
    {
        // Get all approved items for this request
        $approvedItems = $inventoryRequest->items()->get();

        if ($approvedItems->isEmpty()) {
            return;
        }

        // Check if all approved items have received >= approved quantity
        $allFullyReceived = $approvedItems->every(function($item) {
            // Check if there's a corresponding ordering task item that is fully received
            $taskItems = OrderingTaskItem::where('inventory_request_item_id', $item->id)
                ->get();

            if ($taskItems->isEmpty()) {
                return false; // No ordering task item found, not ready
            }

            // All task items for this request item must be fully received
            return $taskItems->every(function($taskItem) {
                return $taskItem->isFullyReceived();
            });
        });

        // Auto-fulfill if all items are fully received
        if ($allFullyReceived && $inventoryRequest->status === 'approved') {
            $inventoryRequest->update([
                'status' => 'fulfilled',
                'fulfilled_at' => now(),
            ]);

            // Update fulfilled_quantity for each item to match the approved quantity
            foreach ($approvedItems as $item) {
                // Get the total quantity approved (sum of all ordering task items for this request item)
                $totalApproved = OrderingTaskItem::where('inventory_request_item_id', $item->id)
                    ->sum('quantity_approved');
                
                $item->update([
                    'fulfilled_quantity' => $totalApproved,
                ]);
            }

            // Send notification to employee
            $inventoryRequest->user->notify(new InventoryRequestFulfilledNotification($inventoryRequest));

            // Create app notification for in-app notification bell
            AppNotification::createNotification(
                user: $inventoryRequest->user,
                type: 'inventory_requests.fulfilled',
                title: 'Request Fulfilled',
                message: 'Your inventory request "' . $inventoryRequest->request_title . '" has been fulfilled and is ready for pickup!',
                linkUrl: route('inventory-requests.show', $inventoryRequest),
                data: [
                    'request_id' => $inventoryRequest->id,
                    'request_title' => $inventoryRequest->request_title,
                ]
            );

            \Log::info("Request #{$inventoryRequest->id} auto-fulfilled with items updated and notifications sent");
        }
    }

    /**
     * Cancel ordering task item
     */
    public function cancelItem($itemId, Request $request)
    {
        try {
            $user = Auth::user();

            // Find item and verify org access
            $item = OrderingTaskItem::where('id', $itemId)
                ->where('org_id', $user->org_id)
                ->firstOrFail();

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from ordering task',
            ]);
        } catch (\Exception $e) {
            \Log::error('Cancel item error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to cancel item',
            ], 500);
        }
    }

    /**
     * Get or create open ordering task for organization
     */
    public static function getOrCreateOpenTask($orgId)
    {
        $task = OrderingTask::forOrganization($orgId)
            ->where('status', 'open')
            ->first();

        if (!$task) {
            $task = OrderingTask::create([
                'org_id' => $orgId,
                'status' => 'open',
                'opened_at' => now(),
            ]);
        }

        return $task;
    }
}
