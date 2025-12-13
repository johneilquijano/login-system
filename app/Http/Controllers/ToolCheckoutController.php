<?php

namespace App\Http\Controllers;

use App\Models\ToolCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolCheckoutController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->org_id;
        // Employees only see their own tool checkouts
        $checkouts = ToolCheckout::forOrganization($orgId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('employee.tools.index', compact('checkouts'));
    }

    public function show(ToolCheckout $toolCheckout)
    {
        // Verify checkout belongs to same organization and is user's checkout
        if ($toolCheckout->org_id !== Auth::user()->org_id || $toolCheckout->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.tools.show', compact('toolCheckout'));
    }
}
