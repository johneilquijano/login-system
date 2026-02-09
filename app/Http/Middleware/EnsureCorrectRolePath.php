<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCorrectRolePath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Super Admins can access any page - bypass all role path checks
        if ($user->is_super_admin) {
            return $next($request);
        }

        $path = $request->path();
        $isAdmin = $user->role === 'admin';
        $isEmployee = $user->role === 'employee';

        // Admin accessing employee routes - redirect to admin
        if ($isAdmin && !str_starts_with($path, 'admin/') && !str_starts_with($path, 'super-admin/')) {
            // Allow API routes and other non-role-specific routes
            if (!str_starts_with($path, 'api/') && $path !== '/' && !str_starts_with($path, 'notifications')) {
                return redirect('/admin/dashboard');
            }
        }

        // Employee accessing admin routes - already handled by middleware, but add extra check
        if ($isEmployee && (str_starts_with($path, 'admin/') || str_starts_with($path, 'super-admin/'))) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
