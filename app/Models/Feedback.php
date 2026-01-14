<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'org_id',
        'user_role',
        'route',
        'route_name',
        'url_path',
        'viewport_width',
        'viewport_height',
        'user_agent',
        'click_x',
        'click_y',
        'page_x',
        'page_y',
        'scroll_x',
        'scroll_y',
        'element_tag',
        'element_id',
        'element_name',
        'element_classes',
        'element_text',
        'element_aria_label',
        'element_placeholder',
        'element_selector',
        'element_path',
        'category',
        'message',
        'severity',
        'status',
        'admin_notes',
        'screenshot_url',
        'screenshot_captured',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'screenshot_captured' => 'boolean',
        'click_x' => 'integer',
        'click_y' => 'integer',
        'page_x' => 'integer',
        'page_y' => 'integer',
        'scroll_x' => 'integer',
        'scroll_y' => 'integer',
        'viewport_width' => 'integer',
        'viewport_height' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Scopes
     */
    public function scopeForOrganization(Builder $query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory(Builder $query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPage(Builder $query, $urlPath)
    {
        return $query->where('url_path', 'like', "%{$urlPath}%");
    }

    public function scopeByReporter(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDateRange(Builder $query, $fromDate, $toDate)
    {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    public function scopeRecent(Builder $query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeOrderByRecent(Builder $query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessors
     */
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'bug' => 'Bug Report',
            'ux' => 'UX Feedback',
            'feature' => 'Feature Request',
            default => ucfirst($this->category),
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'new' => 'New',
            'in_review' => 'In Review',
            'fixed' => 'Fixed',
            'ignored' => 'Ignored',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getSeverityLabelAttribute()
    {
        return $this->severity ? match($this->severity) {
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            default => ucfirst($this->severity),
        } : null;
    }

    /**
     * Methods
     */
    public function markAsReview()
    {
        $this->update(['status' => 'in_review']);
        return $this;
    }

    public function markAsFixed()
    {
        $this->update(['status' => 'fixed']);
        return $this;
    }

    public function markAsIgnored()
    {
        $this->update(['status' => 'ignored']);
        return $this;
    }

    public function updateAdminNotes($notes)
    {
        $this->update(['admin_notes' => $notes]);
        return $this;
    }
}
