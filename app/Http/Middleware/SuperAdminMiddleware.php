<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user is disabled
            if ($user->status === 'disabled') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your account has been disabled.');
            }
            
            // Check if user is super admin
            if ($user->is_super_admin) {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Super admin access required.');
    }
}
