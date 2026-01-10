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
        'quantity',
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
     * Check if tool is available (has quantity remaining for checkout)
     */
    public function isAvailable()
    {
        $activeCheckouts = $this->getActiveCheckoutCount();
        return $activeCheckouts < $this->quantity;
    }

    /**
     * Get count of active checkouts (checked out but not returned)
     */
    public function getActiveCheckoutCount()
    {
        // If checkouts are already loaded (eager loaded), use them
        if ($this->relationLoaded('checkouts')) {
            return $this->checkouts
                ->where('returned_at', null)
                ->where(function ($item) {
                    // Only count actual employee checkouts, not admin maintenance actions
                    return $item['action_type'] === 'checkout' || is_null($item['action_type']);
                })
                ->count();
        }

        // Otherwise query for it
        return $this->checkouts()
            ->whereNull('returned_at')
            ->where(function ($q) {
                $q->where('action_type', 'checkout')
                  ->orWhereNull('action_type');
            })
            ->count();
    }

    /**
     * Get remaining quantity available for checkout
     */
    public function getAvailableQuantity()
    {
        return max(0, $this->quantity - $this->getActiveCheckoutCount());
    }

    /**
     * Get all active checkouts (for multiple quantity display)
     */
    public function getActiveCheckouts()
    {
        // If checkouts are already loaded (eager loaded), use them
        if ($this->relationLoaded('checkouts')) {
            return $this->checkouts
                ->where('returned_at', null)
                ->where(function ($item) {
                    // Only return actual employee checkouts, not admin maintenance actions
                    return $item['action_type'] === 'checkout' || is_null($item['action_type']);
                })
                ->values();
        }

        // Otherwise query for it
        return $this->checkouts()
            ->whereNull('returned_at')
            ->where(function ($q) {
                $q->where('action_type', 'checkout')
                  ->orWhereNull('action_type');
            })
            ->get();
    }

    /**
     * Get the current checkout if exists (only actual employee checkouts, not admin actions)
     */
    public function currentCheckout()
    {
        // Get the first active checkout
        return $this->getActiveCheckouts()->first();
    }

    /**
     * Get tool status: Available, Checked Out, or Maintenance
     */
    public function getStatus()
    {
        if ($this->is_maintenance) {
            return 'Maintenance';
        }

        // Check if there's remaining quantity available
        if ($this->isAvailable()) {
            return 'Available';
        }

        return 'Checked Out';
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
     * Get earliest due date among all active checkouts
     */
    public function getEarliestDueDate()
    {
        $activeCheckouts = $this->getActiveCheckouts();
        if ($activeCheckouts->isEmpty()) {
            return null;
        }

        $dueDates = $activeCheckouts
            ->map(function ($checkout) {
                return $checkout->return_due_date instanceof \Carbon\Carbon
                    ? $checkout->return_due_date
                    : (\Carbon\Carbon::parse($checkout->return_due_date) ?? null);
            })
            ->filter(function ($date) {
                return $date !== null;
            });

        return $dueDates->isNotEmpty() ? $dueDates->min() : null;
    }

    /**
     * Get due date if tool is checked out (deprecated - use getEarliestDueDate)
     */
    public function getDueDate()
    {
        return $this->getEarliestDueDate();
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
