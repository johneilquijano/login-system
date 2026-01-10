<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderingTaskItem extends Model
{
    use HasFactory;

    protected $table = 'ordering_task_items';

    protected $fillable = [
        'org_id',
        'ordering_task_id',
        'inventory_request_id',
        'inventory_request_item_id',
        'item_name',
        'job_number',
        'model_number',
        'quantity_approved',
        'quantity_received',
        'notes',
        'status',
        'ordered_at',
        'received_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function orderingTask()
    {
        return $this->belongsTo(OrderingTask::class);
    }

    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    public function inventoryRequestItem()
    {
        return $this->belongsTo(InventoryRequestItem::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Scope to filter by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Record a receipt of items
     */
    public function receiveItems($quantity, $notes = null)
    {
        $this->quantity_received += $quantity;
        
        // Update status based on received quantity
        if ($this->quantity_received == 0) {
            // No change to status
        } elseif ($this->quantity_received < $this->quantity_approved) {
            $this->status = 'partially_received';
        } else {
            // quantity_received >= quantity_approved
            $this->status = 'received';
        }
        
        $this->received_at = now();
        $this->save();

        return $this;
    }

    /**
     * Get remaining quantity to receive
     */
    public function getRemainingQuantity()
    {
        return max(0, $this->quantity_approved - $this->quantity_received);
    }

    /**
     * Check if fully received
     */
    public function isFullyReceived()
    {
        return $this->quantity_received >= $this->quantity_approved;
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage()
    {
        if ($this->quantity_approved == 0) {
            return 0;
        }
        return min(100, round(($this->quantity_received / $this->quantity_approved) * 100));
    }
}
