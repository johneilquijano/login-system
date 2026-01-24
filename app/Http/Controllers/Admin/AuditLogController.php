<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs for the organization (admin view)
     */
    public function index(Request $request)
    {
        $orgId = auth()->user()->org_id;
        
        // Build query for organization audit logs
        $query = AuditLog::forOrganization($orgId);

        // Apply filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');
        $action = $request->input('action');
        $entityType = $request->input('entity_type');

        if ($startDate && $endDate) {
            $query->dateRange(
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            );
        } else {
            // Default to last 30 days
            $query->recentDays(30);
        }

        if ($userId) {
            $query->byUser($userId);
        }

        if ($action) {
            $query->byAction($action);
        }

        if ($entityType) {
            $query->byEntityType($entityType);
        }

        // Paginate results
        $auditLogs = $query->with(['user', 'impersonator'])
            ->ordered()
            ->paginate(50);

        // Get available filters
        $users = User::forOrganization($orgId)->orderBy('name')->get(['id', 'name', 'email']);
        $actions = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'upload' => 'Upload',
            'download' => 'Download',
            'assign' => 'Assign',
            'sign' => 'Sign',
            'status_change' => 'Status Change',
            'approve' => 'Approve',
            'deny' => 'Deny',
            'submit' => 'Submit',
            'claim' => 'Claim',
            'complete' => 'Complete',
            'receive' => 'Receive',
        ];
        $entityTypes = [
            'user' => 'User',
            'document' => 'Document',
            'inventory_request' => 'Inventory Request',
            'ordering_task_item' => 'Ordering Task Item',
            'tool' => 'Tool',
            'tool_checkout' => 'Tool Checkout',
            'vehicle' => 'Vehicle',
            'feedback' => 'Feedback',
        ];

        return view('admin.audit-logs.index', [
            'auditLogs' => $auditLogs,
            'users' => $users,
            'actions' => $actions,
            'entityTypes' => $entityTypes,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
            ],
        ]);
    }
}
