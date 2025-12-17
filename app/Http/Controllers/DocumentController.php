<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;
        
        $query = Document::forOrganization($orgId)
            ->where('user_id', Auth::id());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Signature filter (need signature vs signed)
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

        return view('employee.documents.index', compact('documents'));
    }

    public function show(Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }
        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    public function sign(Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if already signed
        if ($document->signed_at) {
            return redirect()->route('documents.show', $document)->with('error', 'Document is already signed.');
        }

        return view('employee.documents.sign', compact('document'));
    }

    public function upload()
    {
        return view('employee.documents.upload');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240', // 10MB max
        ]);

        // Store file
        $filePath = Storage::disk('private')->put('documents/' . Auth::user()->org_id, $request->file('document'));
        
        // Create document record
        Document::create([
            'org_id' => Auth::user()->org_id,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'mime_type' => $request->file('document')->getClientMimeType(),
            'file_size' => $request->file('document')->getSize(),
            'status' => 'pending_review',
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully!');
    }

    public function storeSigning(Request $request, Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already signed
        if ($document->signed_at) {
            return redirect()->route('documents.show', $document)->with('error', 'Document is already signed.');
        }

        $validated = $request->validate([
            'signature_type' => 'required|in:draw,type',
            'signature_data' => 'required|string',
        ]);

        // Update document with signature
        $document->update([
            'signed_at' => now(),
            'status' => 'approved',
            'signature_type' => $validated['signature_type'],
            'signature_data' => $validated['signature_data'],
        ]);

        return redirect()->route('documents.index')->with('success', 'Document signed successfully!');
    }

    public function destroy(Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete file from storage
        if ($document->file_path) {
            Storage::disk('private')->delete($document->file_path);
        }

        // Delete document record
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }
}
