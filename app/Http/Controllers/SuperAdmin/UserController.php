<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_super_admin', false);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Organization filter
        if ($request->filled('organization')) {
            $query->where('org_id', $request->input('organization'));
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(10)->appends($request->only(['search', 'organization', 'role', 'status']));
        $organizations = Organization::where('status', 'active')->get();

        // Return only table HTML for AJAX requests
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('super-admin.users._table', compact('users', 'organizations'));
        }

        return view('super-admin.users.index', compact('users', 'organizations'));
    }

    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('super-admin.users.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'org_id' => 'nullable|exists:organizations,id',
            'role' => 'required|in:employee,admin',
            'status' => 'required|in:active,disabled',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('super-admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $organizations = Organization::where('status', 'active')->get();
        return view('super-admin.users.edit', compact('user', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'org_id' => 'nullable|exists:organizations,id',
            'role' => 'required|in:employee,admin',
            'status' => 'required|in:active,disabled',
        ]);

        $user->update($validated);

        return redirect()->route('super-admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return redirect()->route('super-admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function showResetPasswordForm(User $user)
    {
        return view('super-admin.users.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('super-admin.users.index')->with('success', 'User password reset successfully.');
    }

    public function disable(User $user)
    {
        $user->update(['status' => 'disabled']);

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'User disabled successfully.']);
        }

        return redirect()->route('super-admin.users.index')->with('success', 'User disabled successfully.');
    }

    public function enable(User $user)
    {
        $user->update(['status' => 'active']);

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'User enabled successfully.']);
        }

        return redirect()->route('super-admin.users.index')->with('success', 'User enabled successfully.');
    }
}
