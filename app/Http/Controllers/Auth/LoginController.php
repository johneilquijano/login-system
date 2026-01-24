<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user exists but is disabled, prevent login
        $maybeUser = User::where('email', $request->input('email'))->first();
        if ($maybeUser && $maybeUser->status === 'disabled') {
            return back()->withErrors([
                'email' => 'This account has been disabled. Please contact an administrator.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log login event
            $user = Auth::user();
            AuditLogService::logLogin($user);

            // Redirect based on role
            if ($user && $user->is_super_admin) {
                return redirect()->route('super-admin.dashboard');
            }
            
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log logout event before actually logging out
        $user = Auth::user();
        if ($user) {
            AuditLogService::logLogout($user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
