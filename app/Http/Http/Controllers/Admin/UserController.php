<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $organization = auth()->user()->organization;
        
        $query = $organization->users();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,org_id,' . auth()->user()->org_id,
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:employee,admin',
            'status' => 'required|in:active,disabled',
        ]);

        $user = User::create([
            'org_id' => auth()->user()->org_id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'role' => 'required|in:employee,admin',
            'status' => 'required|in:active,disabled',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'password' => ['required', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User password reset successfully.');
    }

    /**
     * Disable a user account.
     */
    public function disable(User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        $user->update(['status' => 'disabled']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User account disabled successfully.');
    }

    /**
     * Enable a user account.
     */
    public function enable(User $user)
    {
        // Ensure user belongs to the same organization
        if ($user->org_id !== auth()->user()->org_id) {
            abort(403);
        }

        $user->update(['status' => 'active']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User account enabled successfully.');
    }
}
