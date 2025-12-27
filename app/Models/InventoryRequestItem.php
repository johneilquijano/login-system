<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_request_items';

    protected $fillable = [
        'inventory_request_id',
        'item_name',
        'category',
        'quantity',
        'notes',
        'fulfilled_quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'fulfilled_quantity' => 'integer',
    ];

    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    /**
     * Get remaining unfulfilled quantity
     */
    public function getRemainingQuantity()
    {
        return $this->quantity - ($this->fulfilled_quantity ?? 0);
    }
}
