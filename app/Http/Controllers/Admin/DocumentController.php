<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;

        $query = Document::with(['user', 'organization'])
            ->where('org_id', $orgId);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Signature filter
        if ($request->filled('signature')) {
            if ($request->input('signature') === 'needs_signature') {
                $query->whereNull('signed_at');
            } elseif ($request->input('signature') === 'signed') {
                $query->whereNotNull('signed_at');
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $documents = $query->paginate(10)->appends($request->only(['search', 'status', 'signature', 'sort']));

        // Get all employees in organization for assignment modal
        $employees = User::where('org_id', $orgId)
            ->where('role', 'employee')
            ->orderBy('name')
            ->get();

        return view('admin.documents.index', compact('documents', 'employees'));
    }

    public function show(Document $document)
    {
        // Verify document belongs to admin's organization
        if ($document->org_id !== Auth::user()->org_id) {
            abort(403);
        }
        return view('admin.documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        // Verify document belongs to admin's organization
        if ($document->org_id !== Auth::user()->org_id) {
            abort(403);
        }
        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    public function store(Request $request)
    {
        $orgId = Auth::user()->org_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'required|integer|exists:users,id',
        ]);

        // Verify all selected employees belong to admin's organization
        $employeeCount = User::where('org_id', $orgId)
            ->whereIn('id', $validated['employee_ids'])
            ->count();

        if ($employeeCount !== count($validated['employee_ids'])) {
            return back()->with('error', 'Invalid employee selection.');
        }

        // Store file
        $filePath = Storage::disk('private')->put('documents/' . $orgId, $request->file('document'));

        // Create document for each selected employee
        foreach ($validated['employee_ids'] as $employeeId) {
            Document::create([
                'org_id' => $orgId,
                'user_id' => $employeeId,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $filePath,
                'file_name' => $request->file('document')->getClientOriginalName(),
                'mime_type' => $request->file('document')->getClientMimeType(),
                'file_size' => $request->file('document')->getSize(),
                'status' => 'pending_review',
            ]);
        }

        return redirect()->route('admin.documents.index')->with('success', 'Document assigned to ' . count($validated['employee_ids']) . ' employee(s)!');
    }

    public function destroy(Document $document)
    {
        // Verify document belongs to admin's organization
        if ($document->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        // Delete file from storage
        if ($document->file_path) {
            Storage::disk('private')->delete($document->file_path);
        }

        // Delete document record
        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully!');
    }
}
