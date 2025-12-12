<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryRequestController extends Controller
{
    public function index()
    {
        $requests = Auth::user()->inventoryRequests()->orderBy('requested_date', 'desc')->paginate(10);
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
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->inventoryRequests()->create([
            ...$validated,
            'status' => 'pending',
            'requested_date' => now(),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory request submitted successfully');
    }

    public function show(InventoryRequest $inventoryRequest)
    {
        if ($inventoryRequest->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.inventory.show', compact('inventoryRequest'));
    }
}
