<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\ToolCheckout;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\Tool;
use App\Models\OrderingTaskItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->org_id;

        // Get organization-specific statistics
        $totalUsers = User::forOrganization($orgId)->count();
        $adminCount = User::forOrganization($orgId)->where('role', 'admin')->count();
        $employeeCount = User::forOrganization($orgId)->where('role', 'employee')->count();
        $pendingDocuments = Document::forOrganization($orgId)->pending()->count();
        $pendingCheckouts = ToolCheckout::forOrganization($orgId)->pending()->count();
        $pendingInventory = InventoryRequest::forOrganization($orgId)->pending()->count();

        // Inventory Request statistics
        $inventoryStats = [
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

        // Tools Inventory statistics
        $toolsStats = [
            'total' => Tool::forOrganization($orgId)->count(),
            'checked_out' => ToolCheckout::forOrganization($orgId)->where('status', 'checked_out')->count(),
            'overdue' => ToolCheckout::forOrganization($orgId)
                ->where('status', 'checked_out')
                ->whereDate('return_due_date', '<', now()->toDateString())
                ->count(),
            'maintenance' => Tool::forOrganization($orgId)->where('is_maintenance', true)->count(),
            'due_today' => ToolCheckout::forOrganization($orgId)
                ->where('status', 'checked_out')
                ->whereDate('return_due_date', now()->toDateString())
                ->count(),
            'due_this_week' => ToolCheckout::forOrganization($orgId)
                ->where('status', 'checked_out')
                ->whereDate('return_due_date', '>=', now()->toDateString())
                ->whereDate('return_due_date', '<=', now()->addDays(7)->toDateString())
                ->count(),
            'recent_activity' => ToolCheckout::forOrganization($orgId)
                ->where('updated_at', '>=', now()->subDay())
                ->count(),
        ];

        // ===== NEEDS ATTENTION TODAY =====
        
        // Tile A: Inventory Requests (Pending + Urgent)
        $pendingRequestsCount = InventoryRequest::forOrganization($orgId)
            ->where('status', 'submitted')
            ->count();
        
        $urgentRequestsCount = InventoryRequest::forOrganization($orgId)
            ->where('status', 'submitted')
            ->where('priority', 'urgent')
            ->count();
        
        $pendingRequestsPreview = InventoryRequest::forOrganization($orgId)
            ->where('status', 'submitted')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Tile B: Ordering Tasks Needing Action
        $orderingTasksPendingCount = OrderingTaskItem::forOrganization($orgId)
            ->where('status', 'pending')
            ->count();
        
        $orderingTasksWaitingCount = OrderingTaskItem::forOrganization($orgId)
            ->where('status', 'ordered')
            ->whereColumn('quantity_received', '<', 'quantity_approved')
            ->count();
        
        $orderingTasksActionCount = $orderingTasksPendingCount + $orderingTasksWaitingCount;
        
        $orderingTasksPreview = OrderingTaskItem::forOrganization($orgId)
            ->whereIn('status', ['pending', 'ordered'])
            ->where(function($q) {
                $q->where('status', 'pending')
                  ->orWhereColumn('quantity_received', '<', 'quantity_approved');
            })
            ->with('orderingTask')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Tile C: Broken Tools / Maintenance
        $maintenanceToolsCount = Tool::forOrganization($orgId)
            ->where('is_maintenance', true)
            ->count();
        
        $maintenanceToolsPreview = Tool::forOrganization($orgId)
            ->where('is_maintenance', true)
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        
        // Tile D: Due Today / Overdue Returns
        $dueTodayCount = ToolCheckout::forOrganization($orgId)
            ->where('status', 'checked_out')
            ->whereDate('return_due_date', now()->toDateString())
            ->count();
        
        $overdueCount = ToolCheckout::forOrganization($orgId)
            ->where('status', 'checked_out')
            ->whereDate('return_due_date', '<', now()->toDateString())
            ->count();
        
        $dueCheckoutsCount = $dueTodayCount + $overdueCount;
        
        $dueCheckoutsPreview = ToolCheckout::forOrganization($orgId)
            ->where('status', 'checked_out')
            ->where(function($q) {
                $q->whereDate('return_due_date', '<=', now()->toDateString());
            })
            ->with('user', 'tool')
            ->orderBy('return_due_date', 'asc')
            ->limit(3)
            ->get();
        
        // Latest Inventory Requests (for main queue widget)
        $latestInventoryRequests = InventoryRequest::forOrganization($orgId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'adminCount',
            'employeeCount',
            'pendingDocuments',
            'pendingCheckouts',
            'pendingInventory',
            'inventoryStats',
            'toolsStats',
            // Needs Attention Today
            'pendingRequestsCount',
            'urgentRequestsCount',
            'pendingRequestsPreview',
            'orderingTasksActionCount',
            'orderingTasksPendingCount',
            'orderingTasksWaitingCount',
            'orderingTasksPreview',
            'maintenanceToolsCount',
            'maintenanceToolsPreview',
            'dueTodayCount',
            'overdueCount',
            'dueCheckoutsCount',
            'dueCheckoutsPreview',
            'latestInventoryRequests'
        ));
    }
}
