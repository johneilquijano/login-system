<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'org_id',
        'request_title',
        'reason',
        'priority',
        'needed_by_date',
        'status',
        'admin_notes',
        'submitted_at',
        'approved_at',
        'denied_at',
        'fulfilled_at',
        'cancelled_at',
        'approved_by',
        'denied_by',
        'denied_reason',
    ];

    protected $casts = [
        'needed_by_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'denied_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryRequestItem::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function denier()
    {
        return $this->belongsTo(User::class, 'denied_by');
    }

    /**
     * Scope to filter requests by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Scope to filter pending requests
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted']);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get total items count
     */
    public function getTotalItemsCount()
    {
        return $this->items()->sum('quantity');
    }
}
