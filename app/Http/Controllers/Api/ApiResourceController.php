<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Tool;
use App\Models\ToolCheckout;
use App\Models\InventoryRequest;
use App\Models\User;

class ApiResourceController extends Controller
{
    /**
     * GET /api/documents - List all documents with status
     */
    public function documents()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $documents = Document::forOrganization($orgId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->file_name,
                    'uploaded_by' => $doc->user?->name ?? 'Unknown',
                    'uploaded_by_email' => $doc->user?->email ?? 'Unknown',
                    'status' => $doc->status, // pending_review, approved, rejected
                    'signature_required' => $doc->requires_signature,
                    'signature_present' => !empty($doc->signature_data),
                    'created_at' => $doc->created_at->format('Y-m-d H:i'),
                    'updated_at' => $doc->updated_at->format('Y-m-d H:i'),
                ];
            });

        $pending = $documents->filter(fn($d) => $d['status'] === 'pending_review')->count();
        $approved = $documents->filter(fn($d) => $d['status'] === 'approved')->count();
        $rejected = $documents->filter(fn($d) => $d['status'] === 'rejected')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $documents->count(),
                'pending_review' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
            ],
            'documents' => $documents->values(),
        ]);
    }

    /**
     * GET /api/tools - List all tools with status
     */
    public function tools()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $tools = Tool::forOrganization($orgId)
            ->orderBy('name')
            ->get()
            ->map(function ($tool) {
                // Count active checkouts
                $activeCheckout = ToolCheckout::where('tool_id', $tool->id)
                    ->where('status', 'checked_out')
                    ->first();

                return [
                    'id' => $tool->id,
                    'name' => $tool->name,
                    'description' => $tool->description,
                    'condition' => $tool->condition ?? 'good', // good, fair, needs_repair
                    'maintenance_status' => $tool->maintenance_status,
                    'available' => !$activeCheckout,
                    'currently_checked_out_by' => $activeCheckout?->user?->name ?? null,
                    'checkout_due_date' => $activeCheckout?->return_due_date?->format('Y-m-d H:i') ?? null,
                    'notes' => $tool->notes,
                    'created_at' => $tool->created_at->format('Y-m-d'),
                ];
            });

        $available = $tools->filter(fn($t) => $t['available'])->count();
        $checked_out = $tools->filter(fn($t) => !$t['available'])->count();
        $needs_repair = $tools->filter(fn($t) => $t['condition'] === 'needs_repair')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $tools->count(),
                'available' => $available,
                'checked_out' => $checked_out,
                'needs_repair' => $needs_repair,
            ],
            'tools' => $tools->values(),
        ]);
    }

    /**
     * GET /api/tool-checkouts - List all tool checkouts with status
     */
    public function toolCheckouts()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $checkouts = ToolCheckout::forOrganization($orgId)
            ->with('user', 'tool')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($checkout) {
                $isOverdue = $checkout->status === 'checked_out' && 
                            $checkout->return_due_date && 
                            $checkout->return_due_date->isPast();

                return [
                    'id' => $checkout->id,
                    'tool_name' => $checkout->tool?->name ?? 'Unknown',
                    'checked_out_by' => $checkout->user?->name ?? 'Unknown',
                    'checked_out_by_email' => $checkout->user?->email ?? 'Unknown',
                    'status' => $checkout->status, // checked_out, returned
                    'checkout_date' => $checkout->checkout_date->format('Y-m-d H:i'),
                    'return_due_date' => $checkout->return_due_date?->format('Y-m-d H:i'),
                    'actual_return_date' => $checkout->actual_return_date?->format('Y-m-d H:i'),
                    'is_overdue' => $isOverdue,
                    'condition_notes' => $checkout->condition_notes,
                    'maintenance_notes' => $checkout->maintenance_notes,
                    'admin_action' => $checkout->admin_action,
                ];
            });

        $active = $checkouts->filter(fn($c) => $c['status'] === 'checked_out')->count();
        $overdue = $checkouts->filter(fn($c) => $c['is_overdue'])->count();
        $returned = $checkouts->filter(fn($c) => $c['status'] === 'returned')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $checkouts->count(),
                'currently_checked_out' => $active,
                'overdue' => $overdue,
                'returned' => $returned,
            ],
            'checkouts' => $checkouts->values(),
        ]);
    }

    /**
     * GET /api/inventory-requests - List all inventory requests with status
     */
    public function inventoryRequests()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $requests = InventoryRequest::forOrganization($orgId)
            ->with('user', 'inventoryItems')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($request) {
                $items = $request->inventoryItems->map(function ($item) {
                    return [
                        'item_name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                    ];
                });

                return [
                    'id' => $request->id,
                    'requested_by' => $request->user?->name ?? 'Unknown',
                    'requested_by_email' => $request->user?->email ?? 'Unknown',
                    'status' => $request->status, // pending, approved, denied, fulfilled, cancelled
                    'item_count' => $items->count(),
                    'items' => $items,
                    'created_at' => $request->created_at->format('Y-m-d H:i'),
                    'updated_at' => $request->updated_at->format('Y-m-d H:i'),
                    'notes' => $request->notes,
                ];
            });

        $pending = $requests->filter(fn($r) => $r['status'] === 'pending')->count();
        $approved = $requests->filter(fn($r) => $r['status'] === 'approved')->count();
        $denied = $requests->filter(fn($r) => $r['status'] === 'denied')->count();
        $fulfilled = $requests->filter(fn($r) => $r['status'] === 'fulfilled')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $requests->count(),
                'pending' => $pending,
                'approved' => $approved,
                'denied' => $denied,
                'fulfilled' => $fulfilled,
            ],
            'requests' => $requests->values(),
        ]);
    }

    /**
     * GET /api/users - List all users in organization
     */
    public function users()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        $users = User::forOrganization($orgId)
            ->orderBy('name')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'status' => $u->status, // active, disabled
                    'is_super_admin' => $u->is_super_admin,
                    'created_at' => $u->created_at->format('Y-m-d'),
                ];
            });

        $admins = $users->filter(fn($u) => $u['role'] === 'admin')->count();
        $employees = $users->filter(fn($u) => $u['role'] === 'employee')->count();
        $active = $users->filter(fn($u) => $u['status'] === 'active')->count();
        $disabled = $users->filter(fn($u) => $u['status'] === 'disabled')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $users->count(),
                'admins' => $admins,
                'employees' => $employees,
                'active' => $active,
                'disabled' => $disabled,
            ],
            'users' => $users->values(),
        ]);
    }

    /**
     * GET /api/dashboard-stats - Dashboard summary statistics
     */
    public function dashboardStats()
    {
        $user = Auth::user();
        $orgId = $user->org_id;

        // Documents
        $documents = Document::forOrganization($orgId)->get();
        $pending_docs = $documents->where('status', 'pending_review')->count();
        $approved_docs = $documents->where('status', 'approved')->count();

        // Tools
        $tools = Tool::forOrganization($orgId)->get();
        $active_checkouts = ToolCheckout::forOrganization($orgId)
            ->where('status', 'checked_out')
            ->get();
        $overdue_checkouts = $active_checkouts->filter(fn($c) => 
            $c->return_due_date && $c->return_due_date->isPast()
        );

        // Inventory Requests
        $pending_requests = InventoryRequest::forOrganization($orgId)
            ->where('status', 'pending')
            ->count();
        $approved_requests = InventoryRequest::forOrganization($orgId)
            ->where('status', 'approved')
            ->count();

        // Users
        $all_users = User::forOrganization($orgId)->get();
        $active_users = $all_users->where('status', 'active')->count();
        $disabled_users = $all_users->where('status', 'disabled')->count();

        return response()->json([
            'success' => true,
            'organization_id' => $orgId,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'documents' => [
                'total' => $documents->count(),
                'pending_review' => $pending_docs,
                'approved' => $approved_docs,
                'rejected' => $documents->where('status', 'rejected')->count(),
            ],
            'tools' => [
                'total' => $tools->count(),
                'available' => $tools->count() - $active_checkouts->count(),
                'checked_out' => $active_checkouts->count(),
                'overdue_checkouts' => $overdue_checkouts->count(),
                'needs_repair' => $tools->where('condition', 'needs_repair')->count(),
            ],
            'inventory' => [
                'pending_requests' => $pending_requests,
                'approved_requests' => $approved_requests,
                'fulfilled_requests' => InventoryRequest::forOrganization($orgId)
                    ->where('status', 'fulfilled')
                    ->count(),
            ],
            'users' => [
                'total' => $all_users->count(),
                'active' => $active_users,
                'disabled' => $disabled_users,
                'admins' => $all_users->where('role', 'admin')->count(),
                'employees' => $all_users->where('role', 'employee')->count(),
            ],
            'alerts' => [
                'documents_pending' => $pending_docs > 0,
                'tools_overdue' => $overdue_checkouts->count() > 0,
                'inventory_waiting_approval' => $pending_requests > 0,
                'disabled_users' => $disabled_users > 0,
            ],
        ]);
    }
}
