<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;
        // Start query filtered by organization
        $query = User::forOrganization($orgId);

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

        // Status filter - uses 'active' parameter with values 1 (active) or 0 (disabled)
        if ($request->filled('active')) {
            $status = $request->input('active') === '1' ? 'active' : 'disabled';
            $query->where('status', $status);
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
            'email' => ['required', 'email', Rule::unique('users')->where('org_id', Auth::user()->org_id)],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:employee,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['org_id'] = Auth::user()->org_id;  // Automatically assign to current organization

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where('org_id', Auth::user()->org_id)->ignore($user->id)],
            'role' => 'required|in:employee,admin',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        // This method now expects a new password via POST from the admin form.
        // Validate and update the user's password securely.
        request()->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make(request('password'))]);

        return redirect()->route('admin.users.index')->with('success', 'Password updated successfully.');
    }

    public function showResetPasswordForm(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.users.reset-password', compact('user'));
    }

    public function disable(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        $user->update(['status' => 'disabled']);

        return back()->with('success', 'User disabled successfully.');
    }

    public function enable(User $user)
    {
        // Verify user belongs to same organization
        if ($user->org_id !== Auth::user()->org_id) {
            abort(403, 'Unauthorized');
        }

        $user->update(['status' => 'active']);

        return back()->with('success', 'User enabled successfully.');
    }
}
