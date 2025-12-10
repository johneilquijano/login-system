<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('auth.register', compact('organizations'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,org_id,' . $request->org_id,
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.unique' => 'This email is already registered for this organization.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::create([
            'org_id' => $validated['org_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
            'status' => 'active',
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
