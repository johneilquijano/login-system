<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', ['token' => $request->query('token')]);
    }

    public function resetPassword(Request $request)
    {
        // TODO: Implement password reset logic
        return redirect()->route('login');
    }
}
