<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'org_id',
        'status',
        'is_super_admin',
        'direct_access_token',
        'direct_access_token_expires_at',
        'api_token',
        'api_token_created_at',
        'api_token_last_used_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'direct_access_token_expires_at' => 'datetime',
        'api_token_created_at' => 'datetime',
        'api_token_last_used_at' => 'datetime',
    ];

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Relationships
    /**
     * Get the organization this user belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function toolCheckouts()
    {
        return $this->hasMany(ToolCheckout::class);
    }

    public function inventoryRequests()
    {
        return $this->hasMany(InventoryRequest::class);
    }

    /**
     * Scope to filter users by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Generate a unique direct access token for admin
     */
    public function generateDirectAccessToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->update([
            'direct_access_token' => $token,
            'direct_access_token_expires_at' => now()->addYear(),
        ]);
        return $token;
    }

    /**
     * Get or generate direct access token
     */
    public function getDirectAccessToken()
    {
        if (!$this->direct_access_token || $this->isDirectAccessTokenExpired()) {
            $this->generateDirectAccessToken();
        }
        return $this->direct_access_token;
    }

    /**
     * Check if direct access token is expired
     */
    public function isDirectAccessTokenExpired()
    {
        return $this->direct_access_token_expires_at && $this->direct_access_token_expires_at->isPast();
    }

    /**
     * Regenerate the direct access token
     */
    public function regenerateDirectAccessToken()
    {
        return $this->generateDirectAccessToken();
    }

    /**
     * Generate a permanent API token for AI/automated access
     */
    public function generateApiToken()
    {
        $token = 'api_' . bin2hex(random_bytes(32));
        $this->update([
            'api_token' => $token,
            'api_token_created_at' => now(),
        ]);
        return $token;
    }

    /**
     * Get or generate API token
     */
    public function getApiToken()
    {
        if (!$this->api_token) {
            $this->generateApiToken();
        }
        return $this->api_token;
    }

    /**
     * Regenerate the API token
     */
    public function regenerateApiToken()
    {
        return $this->generateApiToken();
    }

    /**
     * Revoke the API token
     */
    public function revokeApiToken()
    {
        $this->update([
            'api_token' => null,
            'api_token_created_at' => null,
            'api_token_last_used_at' => null,
        ]);
    }

    /**
     * Update last used timestamp for API token
     */
    public function updateApiTokenLastUsed()
    {
        $this->update(['api_token_last_used_at' => now()]);
    }
}

