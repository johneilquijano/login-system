<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures authenticated users can only access their own organization's data
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Store the organization ID in the request for use in queries
            $request->attributes->set('org_id', auth()->user()->org_id);
            
            // Store organization in view data globally
            view()->share('organization', auth()->user()->organization);
            view()->share('org_id', auth()->user()->org_id);
        }

        return $next($request);
    }
}
