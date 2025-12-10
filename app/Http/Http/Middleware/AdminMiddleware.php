<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is admin
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        // Check if user account is active
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been disabled.');
        }

        return $next($request);
    }
}
