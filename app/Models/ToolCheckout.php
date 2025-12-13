<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'org_id',
        'tool_name',
        'description',
        'serial_number',
        'status',
        'requested_at',
        'approved_at',
        'checked_out_at',
        'return_due_date',
        'returned_at',
        'approved_by',
        'approval_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'return_due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Scope to filter checkouts by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Scope to filter active checkouts (checked out but not yet returned)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'checked_out')->whereNull('returned_at');
    }

    /**
     * Scope to filter pending checkouts
     */
    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }
}
