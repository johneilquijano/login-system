<?php

namespace App\Http\Controllers;

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
        $userId = Auth::user()->id;

        // Document Statistics
        $docs_needs_signature = Document::forOrganization($orgId)->where('user_id', $userId)->where('status', 'pending_review')->count();
        $docs_new = Document::forOrganization($orgId)->where('user_id', $userId)->whereNull('signed_at')->where('status', 'pending_review')->count();
        $docs_signed = Document::forOrganization($orgId)->where('user_id', $userId)->whereNotNull('signed_at')->count();
        $docs_total = Document::forOrganization($orgId)->where('user_id', $userId)->count();

        // Tool Statistics
        $tools_checked_out = ToolCheckout::forOrganization($orgId)->where('user_id', $userId)->where('status', 'checked_out')->count();
        $tools_due_soon = ToolCheckout::forOrganization($orgId)->where('user_id', $userId)->where('status', 'checked_out')->whereDate('return_due_date', '<=', now()->addDays(3)->toDateString())->count();
        $tools_available = Tool::forOrganization($orgId)->where('is_active', true)->count();
        
        // Next due tool
        $nextDueCheckout = ToolCheckout::forOrganization($orgId)->where('user_id', $userId)->where('status', 'checked_out')->orderBy('return_due_date')->first();
        $next_due_tool_name = $nextDueCheckout ? $nextDueCheckout->tool_name : null;
        $next_due_tool_date = $nextDueCheckout ? $nextDueCheckout->return_due_date->format('M d, Y') : null;

        // Inventory Request Statistics
        $inv_pending = InventoryRequest::forOrganization($orgId)->where('user_id', $userId)->whereIn('status', ['submitted', 'approved'])->count();
        $inv_needs_action = InventoryRequest::forOrganization($orgId)->where('user_id', $userId)->whereIn('status', ['denied', 'fulfilled'])->count();

        return view('dashboard.index', compact(
            'docs_needs_signature',
            'docs_new',
            'docs_signed',
            'docs_total',
            'tools_checked_out',
            'tools_due_soon',
            'tools_available',
            'next_due_tool_name',
            'next_due_tool_date',
            'inv_pending',
            'inv_needs_action'
        ));
    }
}
