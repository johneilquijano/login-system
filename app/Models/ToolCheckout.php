<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tool_name',
        'description',
        'serial_number',
        'status',
        'checked_out_date',
        'due_date',
        'returned_date',
        'notes',
    ];

    protected $casts = [
        'checked_out_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
