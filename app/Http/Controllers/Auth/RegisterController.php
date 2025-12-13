<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Note: Registration creates users without an organization
        // Organization assignment should be done by an admin
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'employee'; // Default role
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');
    }
}
