<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrganizationService
{
    /**
     * Get current organization from authenticated user
     */
    public static function current(): ?Organization
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->organization;
    }

    /**
     * Get current organization ID from authenticated user
     */
    public static function currentId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->org_id;
    }

    /**
     * Verify user belongs to organization
     */
    public static function belongs(User $user, Organization $organization): bool
    {
        return $user->org_id === $organization->id;
    }

    /**
     * Get all active organizations
     */
    public static function getAllActive()
    {
        return Organization::where('status', 'active')->get();
    }

    /**
     * Verify current user can access organization
     */
    public static function canAccess(Organization $organization): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->org_id === $organization->id;
    }
}
