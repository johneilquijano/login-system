<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $totalUsers = User::where('is_super_admin', false)->count();
        $activeUsers = User::where('is_super_admin', false)->where('status', 'active')->count();
        
        // Recent signups (last 7 days)
        $recentOrganizations = Organization::orderBy('created_at', 'desc')->limit(5)->get();
        $recentUsers = User::where('is_super_admin', false)->orderBy('created_at', 'desc')->limit(10)->get();

        return view('super-admin.dashboard', compact(
            'totalOrganizations',
            'activeOrganizations',
            'totalUsers',
            'activeUsers',
            'recentOrganizations',
            'recentUsers'
        ));
    }
}
