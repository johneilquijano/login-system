<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'description',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'logo_path',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get all users belonging to this organization
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'org_id');
    }

    /**
     * Get all admin users for this organization
     */
    public function admins(): HasMany
    {
        return $this->hasMany(User::class, 'org_id')
            ->where('role', 'admin');
    }

    /**
     * Get all employee users for this organization
     */
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'org_id')
            ->where('role', 'employee');
    }

    /**
     * Get active users for this organization
     */
    public function activeUsers(): HasMany
    {
        return $this->hasMany(User::class, 'org_id')
            ->where('status', 'active');
    }
}
