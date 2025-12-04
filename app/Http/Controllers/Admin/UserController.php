<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            // group name/email conditions so they don't interact with other filters
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Active/Disabled filter
        if ($request->filled('active')) {
            $activeVal = $request->input('active');
            // allow '1' or '0' (strings) or boolean-like
            $query->where('active', $activeVal);
        }

        // preserve search/filters when paginating
        $users = $query->paginate(10)->appends($request->only(['search', 'role', 'active']));

        // If AJAX request, return only the table partial (HTML) so the frontend can replace the table
        if ($request->ajax()) {
            return view('admin.users._table', compact('users'));
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:employee,admin',
            'org_id' => 'nullable|integer',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:employee,admin',
            'org_id' => 'nullable|integer',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'Password123!';
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password reset. Temporary password: {$newPassword}");
    }

    public function disable(User $user)
    {
        $user->update(['active' => false]);

        return back()->with('success', 'User disabled successfully.');
    }

    public function enable(User $user)
    {
        $user->update(['active' => true]);

        return back()->with('success', 'User enabled successfully.');
    }
}
