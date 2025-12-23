<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tool;
use App\Models\ToolCheckout;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ToolController extends Controller
{
    /**
     * Display tools inventory for admin
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $search = $request->query('search', '');
        $category = $request->query('category', '');
        $status = $request->query('status', '');

        $query = Tool::forOrganization($orgId)->with(['checkouts' => function($q) {
            $q->whereNull('returned_at')->with('user');
        }]);

        // Search by name, category, or assigned employee
        if ($search) {
            $searchLower = strtolower($search);
            $query = $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%'])
                  ->orWhereRaw('LOWER(category) LIKE ?', ['%' . $searchLower . '%']);
            });
        }

        // Filter by category
        if ($category) {
            $query->where('category', $category);
        }

        // Filter by status
        if ($status) {
            if ($status === 'available') {
                $query->where('is_maintenance', false)
                      ->whereDoesntHave('checkouts', function ($q) {
                          $q->whereNull('returned_at');
                      });
            } elseif ($status === 'checked_out') {
                $query->whereHas('checkouts', function ($q) {
                    $q->whereNull('returned_at');
                });
            } elseif ($status === 'maintenance') {
                $query->where('is_maintenance', true);
            }
        }

        $tools = $query->orderBy('name')->paginate(15);

        // Get unique categories for filter dropdown
        $categories = Tool::forOrganization($orgId)
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('admin.tools.index', [
            'tools' => $tools,
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a new tool
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $orgId = $user->org_id;

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'condition' => 'required|in:good,fair,needs_repair',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
                'serial_number' => 'nullable|unique:tools,serial_number',
                'image_path' => 'nullable|image|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image_path')) {
                // Ensure directory exists
                $uploadDir = public_path('storage/tools');
                if (!File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true, true);
                }

                $file = $request->file('image_path');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $validated['image_path'] = 'storage/tools/' . $filename;
            }

            $validated['org_id'] = $orgId;
            $validated['is_active'] = true;
            $validated['is_maintenance'] = false;

            $tool = Tool::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tool created successfully',
                'tool' => $tool,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show tool details
     */
    public function show(Tool $tool)
    {
        $user = Auth::user();

        if ($tool->org_id !== $user->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tool->load(['checkouts' => function($q) {
            $q->whereNull('returned_at')->with('user');
        }]);

        return response()->json([
            'tool' => $tool,
            'status' => $tool->getStatus(),
            'assigned_to' => $tool->getAssignedEmployee(),
            'due_date' => $tool->getDueDate(),
        ]);
    }

    /**
     * Update a tool
     */
    public function update(Tool $tool, Request $request)
    {
        try {
            $user = Auth::user();

            if ($tool->org_id !== $user->org_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'condition' => 'required|in:good,fair,needs_repair',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
                'serial_number' => 'nullable|unique:tools,serial_number,' . $tool->id,
                'image_path' => 'nullable|image|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image_path')) {
                // Ensure directory exists
                $uploadDir = public_path('storage/tools');
                if (!File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true, true);
                }

                $file = $request->file('image_path');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $validated['image_path'] = 'storage/tools/' . $filename;
            }

            $tool->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tool updated successfully',
                'tool' => $tool,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a tool
     */
    public function destroy(Tool $tool)
    {
        $user = Auth::user();

        if ($tool->org_id !== $user->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tool->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tool deleted successfully',
        ]);
    }

    /**
     * Toggle tool maintenance status
     */
    public function toggleMaintenance(Tool $tool, Request $request)
    {
        try {
            $user = Auth::user();

            if ($tool->org_id !== $user->org_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // If setting to maintenance, require a reason
            if (!$tool->is_maintenance && !$request->has('reason')) {
                return response()->json([
                    'requireReason' => true,
                    'message' => 'Please provide a reason for maintenance',
                ], 422);
            }

            $newMaintenanceStatus = !$tool->is_maintenance;
            $maintenanceReason = $newMaintenanceStatus ? $request->input('reason') : null;

            $tool->update([
                'is_maintenance' => $newMaintenanceStatus,
            ]);

            // Record admin action in history
            ToolCheckout::create([
                'org_id' => $user->org_id,
                'user_id' => $user->id, // Track which admin performed the action
                'tool_id' => $tool->id,
                'tool_name' => $tool->name,
                'action_type' => $newMaintenanceStatus ? 'maintenance_on' : 'maintenance_off',
                'status' => $newMaintenanceStatus ? 'maintenance' : 'available',
                'maintenance_reason' => $maintenanceReason,
                'checked_out_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $newMaintenanceStatus ? 'Tool marked for maintenance' : 'Tool available again',
                'status' => $tool->getStatus(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Maintenance toggle error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Force return a tool (admin override)
     */
    public function forceReturn(Tool $tool, Request $request)
    {
        $user = Auth::user();

        if ($tool->org_id !== $user->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $checkout = $tool->currentCheckout();

        if (!$checkout) {
            return response()->json(['error' => 'Tool is not checked out'], 422);
        }

        // Record admin notes
        if (!empty($validated['notes'])) {
            $checkout->notes = ($checkout->notes ? $checkout->notes . "\n" : '') . 
                             "[Admin Force Return] " . $validated['notes'];
        } else {
            $checkout->notes = ($checkout->notes ? $checkout->notes . "\n" : '') . 
                             "[Admin Force Return] Tool returned by admin override";
        }

        $checkout->update([
            'returned_at' => now(),
            'status' => 'returned',
            'notes' => $checkout->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tool force-returned successfully',
            'status' => $tool->getStatus(),
        ]);
    }

    /**
     * Display tool history/transactions
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $search = $request->query('search', '');
        $toolFilter = $request->query('tool', '');
        $employeeFilter = $request->query('employee', '');
        $statusFilter = $request->query('status', '');
        $categoryFilter = $request->query('category', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        $query = ToolCheckout::where('org_id', $orgId)
            ->with(['tool', 'user'])
            ->orderBy('checked_out_at', 'desc');

        // Search by tool name or employee name
        if ($search) {
            $searchLower = strtolower($search);
            $query = $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(tool_name) LIKE ?', ['%' . $searchLower . '%'])
                  ->orWhereHas('user', function ($uq) use ($searchLower) {
                      $uq->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%']);
                  });
            });
        }

        // Filter by tool
        if ($toolFilter) {
            $query->where('tool_id', $toolFilter);
        }

        // Filter by employee
        if ($employeeFilter) {
            $query->where('user_id', $employeeFilter);
        }

        // Filter by status
        if ($statusFilter) {
            if ($statusFilter === 'open') {
                $query->whereNull('returned_at');
            } elseif ($statusFilter === 'returned') {
                $query->whereNotNull('returned_at');
            } elseif ($statusFilter === 'overdue') {
                $query->whereNull('returned_at')
                      ->where('return_due_date', '<', now());
            }
        }

        // Filter by category
        if ($categoryFilter) {
            $query->whereHas('tool', function ($q) use ($categoryFilter) {
                $q->where('category', $categoryFilter);
            });
        }

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('checked_out_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('checked_out_at', '<=', $dateTo);
        }

        $checkouts = $query->paginate(20);

        // Get filter options
        $tools = Tool::forOrganization($orgId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $employees = User::where('org_id', $orgId)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $categories = Tool::forOrganization($orgId)
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('admin.tools.history', [
            'checkouts' => $checkouts,
            'search' => $search,
            'toolFilter' => $toolFilter,
            'employeeFilter' => $employeeFilter,
            'statusFilter' => $statusFilter,
            'categoryFilter' => $categoryFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'tools' => $tools,
            'employees' => $employees,
            'categories' => $categories,
        ]);
    }
}
