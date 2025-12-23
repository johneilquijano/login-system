<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'name',
        'category',
        'condition',
        'image_path',
        'is_active',
        'is_maintenance',
        'serial_number',
        'description',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_maintenance' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function checkouts()
    {
        return $this->hasMany(ToolCheckout::class);
    }

    /**
     * Check if tool is available (no active checkout)
     */
    public function isAvailable()
    {
        return !$this->checkouts()
            ->whereNull('returned_at')
            ->exists();
    }

    /**
     * Get the current checkout if exists (only actual employee checkouts, not admin actions)
     */
    public function currentCheckout()
    {
        // If checkouts are already loaded (eager loaded), use them
        if ($this->relationLoaded('checkouts')) {
            return $this->checkouts
                ->where('returned_at', null)
                ->where(function ($item) {
                    // Only return actual checkouts, not admin maintenance actions
                    return $item['action_type'] === 'checkout' || is_null($item['action_type']);
                })
                ->first();
        }
        
        // Otherwise query for it - only get actual employee checkouts
        return $this->checkouts()
            ->whereNull('returned_at')
            ->where(function ($q) {
                $q->where('action_type', 'checkout')
                  ->orWhereNull('action_type');
            })
            ->first();
    }

    /**
     * Get tool status: Available, Checked Out, or Maintenance
     */
    public function getStatus()
    {
        if ($this->is_maintenance) {
            return 'Maintenance';
        }

        if ($this->currentCheckout()) {
            return 'Checked Out';
        }

        return 'Available';
    }

    /**
     * Get assigned employee if tool is checked out
     */
    public function getAssignedEmployee()
    {
        $checkout = $this->currentCheckout();
        return $checkout ? $checkout->user : null;
    }

    /**
     * Get due date if tool is checked out
     */
    public function getDueDate()
    {
        $checkout = $this->currentCheckout();
        if (!$checkout) {
            return null;
        }
        
        // Ensure return_due_date is a Carbon instance
        if ($checkout->return_due_date instanceof \Carbon\Carbon) {
            return $checkout->return_due_date;
        }
        
        return $checkout->return_due_date ? \Carbon\Carbon::parse($checkout->return_due_date) : null;
    }

    /**
     * Scope to filter tools by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Scope to filter active tools
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
