<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\ToolCheckout;
use App\Models\InventoryRequest;
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

        return view('admin.dashboard', compact(
            'totalUsers',
            'adminCount',
            'employeeCount',
            'pendingDocuments',
            'pendingCheckouts',
            'pendingInventory'
        ));
    }
}
