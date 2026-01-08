<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'user_id',
        'org_id',
        'type',
        'title',
        'message',
        'data',
        'link_url',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    // Scopes
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByTypeCategory($query, $category)
    {
        // category can be 'documents', 'tools', 'inventory_requests'
        return $query->where('type', 'like', $category . '%');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function markAsRead()
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    public function markAsUnread()
    {
        if ($this->isRead()) {
            $this->update(['read_at' => null]);
        }
        return $this;
    }

    /**
     * Create a new notification (simplified alternative to Laravel's notification system for DB storage)
     */
    public static function createNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $linkUrl = null,
        array $data = []
    ) {
        return self::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'org_id' => $user->org_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link_url' => $linkUrl,
            'data' => $data,
        ]);
    }
}
