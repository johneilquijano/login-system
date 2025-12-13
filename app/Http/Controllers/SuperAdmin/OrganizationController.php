<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $organizations = $query->paginate(10)->appends($request->only(['search', 'status']));

        // Return only table HTML for AJAX requests
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('super-admin.organizations._table', compact('organizations'));
        }

        return view('super-admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('super-admin.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations',
            'slug' => 'required|string|max:255|unique:organizations',
            'email' => 'required|email|unique:organizations',
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Organization::create($validated);

        return redirect()->route('super-admin.organizations.index')->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization)
    {
        return view('super-admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('organizations')->ignore($organization->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('organizations')->ignore($organization->id)],
            'email' => ['required', 'email', Rule::unique('organizations')->ignore($organization->id)],
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $organization->update($validated);

        return redirect()->route('super-admin.organizations.index')->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Organization deleted successfully.']);
        }

        return redirect()->route('super-admin.organizations.index')->with('success', 'Organization deleted successfully.');
    }

    public function disable(Organization $organization)
    {
        $organization->update(['status' => 'inactive']);

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Organization disabled successfully.']);
        }

        return redirect()->route('super-admin.organizations.index')->with('success', 'Organization disabled successfully.');
    }

    public function enable(Organization $organization)
    {
        $organization->update(['status' => 'active']);

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Organization enabled successfully.']);
        }

        return redirect()->route('super-admin.organizations.index')->with('success', 'Organization enabled successfully.');
    }

    public function show(Organization $organization)
    {
        $usersCount = User::where('org_id', $organization->id)->count();
        $activeUsersCount = User::where('org_id', $organization->id)->where('status', 'active')->count();

        return view('super-admin.organizations.show', compact('organization', 'usersCount', 'activeUsersCount'));
    }
}
