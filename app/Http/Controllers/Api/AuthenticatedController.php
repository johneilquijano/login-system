<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    /**
     * API endpoint - returns authenticated user data
     * Requires: Authorization header with Bearer token or api_token query parameter
     */
    public function user(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Successfully authenticated with API token',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'organization_id' => $user->org_id,
                'is_admin' => $user->role === 'admin',
                'is_super_admin' => $user->is_super_admin,
            ],
            'timestamp' => now(),
        ]);
    }

    /**
     * Health check endpoint - confirms API access is working
     */
    public function health(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'ok',
            'message' => 'API is accessible',
            'authenticated_as' => $user->email,
            'timestamp' => now(),
        ]);
    }

    /**
     * Dashboard data endpoint - returns admin dashboard stats
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Verify user is admin
        if ($user->role !== 'admin' && !$user->is_super_admin) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Only admins can access dashboard data'
            ], 403);
        }

        $orgId = $user->org_id;

        return response()->json([
            'success' => true,
            'user' => $user->email,
            'organization_id' => $orgId,
            'timestamp' => now(),
            'message' => 'Dashboard data available - AI agent can now crawl the system'
        ]);
    }
}
