<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'org_id',
        'user_id',
        'user_role',
        'impersonator_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'url_path',
        'route_name',
        'method',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function impersonator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }

    /**
     * Scopes for filtering
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByEntityType($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    public function scopeRecentDays($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Attributes
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'login' => 'User Login',
            'logout' => 'User Logout',
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'upload' => 'File Uploaded',
            'download' => 'File Downloaded',
            'assign' => 'Assigned',
            'sign' => 'Signed',
            'status_change' => 'Status Changed',
            'approve' => 'Approved',
            'deny' => 'Denied',
            'submit' => 'Submitted',
            'claim' => 'Claimed',
            'complete' => 'Completed',
            default => ucfirst($this->action),
        };
    }

    public function getEntityLabel(): string
    {
        return match ($this->entity_type) {
            'user' => 'User',
            'document' => 'Document',
            'inventory_request' => 'Inventory Request',
            'tool' => 'Tool',
            'tool_checkout' => 'Tool Checkout',
            'vehicle' => 'Vehicle',
            'feedback' => 'Feedback',
            default => ucfirst(str_replace('_', ' ', $this->entity_type)),
        };
    }
}
