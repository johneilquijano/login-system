<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use App\Models\User;
use App\Models\AppNotification;
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

    /**
     * Upload a document (without assignment - admin only)
     * The document will be stored and can be assigned later
     */
    public function uploadStore(Request $request)
    {
        $orgId = Auth::user()->org_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
        ]);

        // Store file
        $filePath = Storage::disk('private')->put('documents/' . $orgId, $request->file('document'));

        // Create document record without assignment (no user_id)
        $document = Document::create([
            'org_id' => $orgId,
            'user_id' => null,  // No assignment yet
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'mime_type' => $request->file('document')->getClientMimeType(),
            'file_size' => $request->file('document')->getSize(),
            'status' => 'draft',  // Draft status until assigned
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully!',
                'document' => $document,
            ]);
        }

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully! You can now assign it to employees.');
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

    /**
     * Get all employees for the current organization (for assignment modal)
     */
    public function getEmployees()
    {
        $orgId = Auth::user()->org_id;

        $employees = User::where('org_id', $orgId)
            ->where('role', 'employee')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'employees' => $employees,
        ]);
    }

    /**
     * Assign an unassigned document to an employee
     */
    public function assignDocument(Request $request)
    {
        $orgId = Auth::user()->org_id;

        $validated = $request->validate([
            'document_id' => 'required|integer|exists:documents,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Get the document
        $document = Document::find($validated['document_id']);

        // Verify document belongs to admin's organization
        if ($document->org_id !== $orgId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Verify document is unassigned
        if ($document->user_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Document is already assigned',
            ]);
        }

        // Verify employee belongs to admin's organization
        $employee = User::find($validated['user_id']);
        if ($employee->org_id !== $orgId || $employee->role !== 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid employee',
            ]);
        }

        // Assign the document
        $document->update([
            'user_id' => $validated['user_id'],
            'status' => 'pending_review',
        ]);

        // Create notification for the employee
        try {
            AppNotification::createNotification(
                user: $employee,
                type: 'documents.assigned',
                title: 'Document Assigned',
                message: 'The document "' . $document->title . '" has been assigned to you',
                linkUrl: route('documents.show', $document->id),
                data: [
                    'document_id' => $document->id,
                    'document_title' => $document->title,
                ]
            );
            \Log::info('Notification created for employee ' . $employee->id . ' for document ' . $document->id);
        } catch (\Exception $e) {
            \Log::error('Error creating notification: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Document assigned successfully!',
            'document' => $document,
        ]);
    }
}
