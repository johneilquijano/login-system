<?php

namespace App\Http\Controllers;

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
        $documentCount = Document::forOrganization($orgId)->count();
        $pendingDocuments = Document::forOrganization($orgId)->where('status', 'pending_review')->count();
        $toolCheckoutCount = ToolCheckout::forOrganization($orgId)->count();
        $activeCheckouts = ToolCheckout::forOrganization($orgId)->active()->count();
        $inventoryRequestCount = InventoryRequest::forOrganization($orgId)->count();
        $pendingRequests = InventoryRequest::forOrganization($orgId)->where('status', 'submitted')->count();

        return view('dashboard.index', compact(
            'documentCount',
            'pendingDocuments',
            'toolCheckoutCount',
            'activeCheckouts',
            'inventoryRequestCount',
            'pendingRequests'
        ));
    }
}
