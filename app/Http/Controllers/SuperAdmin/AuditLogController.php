<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs across all organizations (super admin view)
     */
    public function index(Request $request)
    {
        // Build query for all organizations
        $query = AuditLog::query();

        // Apply filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $orgId = $request->input('org_id');
        $userId = $request->input('user_id');
        $action = $request->input('action');
        $entityType = $request->input('entity_type');

        // Default to last 30 days for performance
        if ($startDate && $endDate) {
            $query->dateRange(
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            );
        } else {
            $query->recentDays(30);
        }

        if ($orgId) {
            $query->forOrganization($orgId);
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
        $auditLogs = $query->with(['user', 'organization', 'impersonator'])
            ->ordered()
            ->paginate(50);

        // Get available filters
        $organizations = Organization::orderBy('name')->get(['id', 'name']);
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

        return view('super-admin.audit-logs.index', [
            'auditLogs' => $auditLogs,
            'organizations' => $organizations,
            'actions' => $actions,
            'entityTypes' => $entityTypes,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'org_id' => $orgId,
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
            ],
        ]);
    }
}
