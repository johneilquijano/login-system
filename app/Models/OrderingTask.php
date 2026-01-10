<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'status',
        'opened_at',
        'completed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function items()
    {
        return $this->hasMany(OrderingTaskItem::class);
    }

    /**
     * Get count of items by status
     */
    public function getStatusCount($status)
    {
        return $this->items()->where('status', $status)->count();
    }

    /**
     * Get count of all items
     */
    public function getTotalItemCount()
    {
        return $this->items()->count();
    }

    /**
     * Scope to filter by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }
}
