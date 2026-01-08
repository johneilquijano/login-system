<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateDirectAccessToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');

        if ($token) {
            $user = User::where('direct_access_token', $token)->first();

            if ($user && !$user->isDirectAccessTokenExpired()) {
                // Log the user in
                Auth::login($user, remember: true);
                
                // Redirect to remove token from URL
                return redirect()->route('admin.dashboard');
            }
        }

        // Token invalid or expired
        if ($token) {
            return redirect()->route('login')->with('error', 'Invalid or expired direct access link');
        }

        return $next($request);
    }
}
