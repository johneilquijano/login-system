<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryRequestController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->org_id;
        // Employees only see their own inventory requests
        $requests = InventoryRequest::forOrganization($orgId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('employee.inventory.index', compact('requests'));
    }

    public function create()
    {
        return view('employee.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:1000',
        ]);

        Auth::user()->inventoryRequests()->create([
            'org_id' => Auth::user()->org_id,
            ...$validated,
            'status' => 'draft',
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory request created successfully');
    }

    public function show(InventoryRequest $inventoryRequest)
    {
        // Verify request belongs to same organization and is user's request
        if ($inventoryRequest->org_id !== Auth::user()->org_id || $inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.inventory.show', compact('inventoryRequest'));
    }
}
