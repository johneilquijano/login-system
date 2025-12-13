<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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
            
            // Check if user is admin
            if ($user->role === 'admin') {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Admin access required.');
    }
}
