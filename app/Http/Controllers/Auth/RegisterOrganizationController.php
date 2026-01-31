<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterOrganizationController extends Controller
{
    /**
     * Show the organization registration form.
     */
    public function create()
    {
        return view('auth.register-organization');
    }

    /**
     * Handle organization registration.
     */
    public function store(RegisterOrganizationRequest $request)
    {
        try {
            // Create organization
            $organization = Organization::create([
                'name' => $request->organization_name,
                'slug' => $this->generateUniqueSlug($request->organization_name),
                'email' => $request->email,
            ]);

            // Create the first admin user for the organization
            $user = User::create([
                'org_id' => $organization->id,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'admin', // Organization admin
                'status' => 'active',
            ]);

            // Log the user in immediately
            Auth::login($user);

            // Log organization creation (now that user is logged in)
            AuditLogService::logAction(
                user: $user,
                action: 'create',
                entityType: 'organization',
                entityId: $organization->id,
                description: "New organization created: {$organization->name}",
                metadata: [
                    'organization_name' => $organization->name,
                    'organization_slug' => $organization->slug,
                    'organization_id' => $organization->id,
                ]
            );

            // Log admin user creation
            AuditLogService::logAction(
                user: $user,
                action: 'create',
                entityType: 'user',
                entityId: $user->id,
                description: "First admin user created during organization registration: {$user->name} ({$user->email})",
                metadata: [
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => 'admin',
                    'organization_id' => $organization->id,
                    'organization_name' => $organization->name,
                ]
            );

            return redirect()->route('admin.dashboard')->with('success', 'Organization created successfully! Welcome to your new admin dashboard.');
        } catch (\Exception $e) {
            \Log::error('Organization registration error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during registration. Please try again.')->withInput();
        }
    }

    /**
     * Generate a unique slug for the organization.
     */
    private function generateUniqueSlug(string $organizationName): string
    {
        $slug = \Str::slug($organizationName);
        $originalSlug = $slug;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
