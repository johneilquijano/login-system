<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'quantity',
        'category',
        'description',
        'status',
        'requested_date',
        'approved_date',
        'admin_notes',
    ];

    protected $casts = [
        'requested_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
