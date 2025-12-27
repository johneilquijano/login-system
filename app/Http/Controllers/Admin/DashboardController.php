<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\ToolCheckout;
use App\Models\InventoryRequest;
use App\Models\Tool;
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

        return view('admin.dashboard', compact(
            'totalUsers',
            'adminCount',
            'employeeCount',
            'pendingDocuments',
            'pendingCheckouts',
            'pendingInventory',
            'inventoryStats',
            'toolsStats'
        ));
    }
}
