<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for token in Authorization header (Bearer token)
        $token = null;
        
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            if (strpos($authHeader, 'Bearer ') === 0) {
                $token = substr($authHeader, 7);
            }
        }

        // If no header, check for token in query parameter
        if (!$token && $request->has('api_token')) {
            $token = $request->input('api_token');
        }

        // Validate token if provided
        if ($token) {
            $user = User::where('api_token', $token)->first();

            if ($user) {
                // Log in the user (stateless, no remember flag)
                Auth::login($user);
                
                // Update last used timestamp
                $user->updateApiTokenLastUsed();

                return $next($request);
            } else {
                return response()->json([
                    'error' => 'Invalid API token',
                    'message' => 'The provided API token is invalid or has been revoked.'
                ], 401);
            }
        }

        // No token provided
        return response()->json([
            'error' => 'Missing API token',
            'message' => 'API token is required. Provide it via Authorization header (Bearer token) or api_token query parameter.'
        ], 401);
    }
}
