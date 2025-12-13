<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
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
            
            // Check if user is employee or admin
            if ($user->role === 'employee' || $user->role === 'admin') {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
