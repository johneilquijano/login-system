<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Log an action to the audit log
     */
    public static function logAction(
        User $user,
        string $action,
        string $entityType,
        ?int $entityId = null,
        string $description = '',
        ?string $urlPath = null,
        ?string $routeName = null,
        ?string $method = null,
        ?array $metadata = null
    ): AuditLog {
        $request = request();

        return AuditLog::create([
            // org_id can be null for super admins who don't belong to a specific organization
            'org_id' => $user->org_id,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'impersonator_id' => null, // TODO: implement impersonation support if needed
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description ?: self::generateDescription($action, $entityType),
            'url_path' => $urlPath ?? $request->path(),
            'route_name' => $routeName ?? $request->route()?->getName(),
            'method' => $method ?? $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata ? json_encode($metadata) : null,
        ]);
    }

    /**
     * Generate a human-readable description if none provided
     */
    private static function generateDescription(string $action, string $entityType): string
    {
        return match ($action) {
            'login' => 'User logged in',
            'logout' => 'User logged out',
            'create' => "Created new " . str_replace('_', ' ', $entityType),
            'update' => "Updated " . str_replace('_', ' ', $entityType),
            'delete' => "Deleted " . str_replace('_', ' ', $entityType),
            'upload' => "Uploaded file",
            'download' => "Downloaded file",
            'assign' => "Assigned " . str_replace('_', ' ', $entityType),
            'sign' => "Signed " . str_replace('_', ' ', $entityType),
            'status_change' => "Changed status of " . str_replace('_', ' ', $entityType),
            'approve' => "Approved " . str_replace('_', ' ', $entityType),
            'deny' => "Denied " . str_replace('_', ' ', $entityType),
            'submit' => "Submitted " . str_replace('_', ' ', $entityType),
            'claim' => "Claimed " . str_replace('_', ' ', $entityType),
            'complete' => "Completed " . str_replace('_', ' ', $entityType),
            default => ucfirst($action) . " " . str_replace('_', ' ', $entityType),
        };
    }

    /**
     * Log from Request class (static access)
     */
    public static function fromRequest(
        Request $request,
        User $user,
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?string $description = null,
        ?array $metadata = null
    ): AuditLog {
        return self::logAction(
            user: $user,
            action: $action,
            entityType: $entityType,
            entityId: $entityId,
            description: $description ?? '',
            urlPath: $request->path(),
            routeName: $request->route()?->getName(),
            method: $request->method(),
            metadata: $metadata
        );
    }

    /**
     * Helper to log login event
     */
    public static function logLogin(User $user): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'login',
            entityType: 'user',
            entityId: $user->id,
            description: "{$user->name} logged in",
        );
    }

    /**
     * Helper to log logout event
     */
    public static function logLogout(User $user): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'logout',
            entityType: 'user',
            entityId: $user->id,
            description: "{$user->name} logged out",
        );
    }

    /**
     * Helper to log document upload
     */
    public static function logDocumentUpload(User $user, int $documentId, string $filename): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'upload',
            entityType: 'document',
            entityId: $documentId,
            description: "Uploaded document: {$filename}",
            metadata: ['filename' => $filename],
        );
    }

    /**
     * Helper to log document signature
     */
    public static function logDocumentSign(User $user, int $documentId, string $documentName): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'sign',
            entityType: 'document',
            entityId: $documentId,
            description: "Signed document: {$documentName}",
        );
    }

    /**
     * Helper to log inventory request status change
     */
    public static function logInventoryStatusChange(
        User $user,
        int $requestId,
        string $oldStatus,
        string $newStatus
    ): AuditLog {
        return self::logAction(
            user: $user,
            action: 'status_change',
            entityType: 'inventory_request',
            entityId: $requestId,
            description: "Changed inventory request status from {$oldStatus} to {$newStatus}",
            metadata: [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        );
    }

    /**
     * Helper to log tool checkout
     */
    public static function logToolCheckout(User $user, int $toolId, string $toolName): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'create',
            entityType: 'tool_checkout',
            entityId: $toolId,
            description: "Checked out tool: {$toolName}",
            metadata: ['tool_name' => $toolName],
        );
    }

    /**
     * Helper to log tool return
     */
    public static function logToolReturn(User $user, int $toolCheckoutId, string $toolName): AuditLog
    {
        return self::logAction(
            user: $user,
            action: 'update',
            entityType: 'tool_checkout',
            entityId: $toolCheckoutId,
            description: "Returned tool: {$toolName}",
            metadata: ['tool_name' => $toolName],
        );
    }

    /**
     * Helper to log user assignment
     */
    public static function logAssignment(
        User $user,
        string $entityType,
        int $entityId,
        int $assignedToUserId,
        string $assignedToName
    ): AuditLog {
        return self::logAction(
            user: $user,
            action: 'assign',
            entityType: $entityType,
            entityId: $entityId,
            description: "Assigned to {$assignedToName}",
            metadata: ['assigned_to_user_id' => $assignedToUserId],
        );
    }
}
