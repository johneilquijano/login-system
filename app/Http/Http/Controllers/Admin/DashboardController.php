<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $organization = $user->organization;

        $stats = [
            'total_users' => $organization->users()->count(),
            'active_users' => $organization->users()->where('status', 'active')->count(),
            'disabled_users' => $organization->users()->where('status', 'disabled')->count(),
            'admin_count' => $organization->users()->where('role', 'admin')->count(),
        ];

        $recentUsers = $organization->users()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('organization', 'stats', 'recentUsers'));
    }
}
