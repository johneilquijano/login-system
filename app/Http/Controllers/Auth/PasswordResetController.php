<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.reset-password', ['token' => ]);
    }

    public function resetPassword(Request )
    {
        // TODO: Implement password reset logic
        return redirect()->route('login');
    }
}
