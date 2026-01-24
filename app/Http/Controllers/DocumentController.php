<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\AuditLogService;
use App\Services\DocumentSigningService;
use App\Services\PdfSigningService;
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

        // Log document download
        AuditLogService::logAction(
            Auth::user(),
            'download',
            'document',
            $document->id,
            "Downloaded document: " . $document->title,
            metadata: [
                'document_title' => $document->title,
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
                'mime_type' => $document->mime_type
            ]
        );

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
        $document = Document::create([
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

        // Log document upload
        AuditLogService::logDocumentUpload(
            Auth::user(),
            $document->id,
            $document->file_name
        );

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

        // Create signed document certificate
        $signingService = new DocumentSigningService();
        $certificatePath = $signingService->createSignedDocument(
            $document->file_path,
            $validated['signature_data'],
            $validated['signature_type'],
            Auth::user()->name,
            $document->id
        );

        if (!$certificatePath) {
            \Log::error("Failed to create certificate for document {$document->id}, type: {$validated['signature_type']}");
            return redirect()->route('documents.show', $document)->with('error', 'Failed to create signature certificate.');
        }

        // If PDF file, create a signed PDF with signature overlay (for both drawn and typed signatures)
        $signedPdfPath = null;
        $fileExtension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
        
        if ($fileExtension === 'pdf') {
            $pdfSigningService = new PdfSigningService();
            $signedPdfPath = $pdfSigningService->signPdf(
                $document->file_path,
                $validated['signature_data'],
                $validated['signature_type'],
                Auth::user()->name,
                $document->id
            );
        }

        // Prepare update data
        $updateData = [
            'signed_at' => now(),
            'status' => 'approved',
            'signature_type' => $validated['signature_type'],
            'signature_data' => $validated['signature_data'],
        ];

        // Add certificate path if available
        if ($certificatePath) {
            $updateData['signature_certificate_path'] = $certificatePath;
        }

        // Update file path to signed PDF if available (only if signature overlay succeeded)
        if ($signedPdfPath) {
            $updateData['file_path'] = $signedPdfPath;
        }

        $document->update($updateData);

        // Log document signing
        AuditLogService::logDocumentSign(
            Auth::user(),
            $document->id,
            $document->title
        );

        return redirect()->route('documents.index')->with('success', 'Document signed successfully! Your certificate is ready for download.');
    }

    /**
     * Download signed document certificate
     */
    public function downloadSignedCertificate(Document $document)
    {
        // Verify document belongs to same organization and is user's document
        if ($document->org_id !== Auth::user()->org_id || $document->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if document is signed and has certificate
        if (!$document->signed_at || !$document->signature_certificate_path) {
            return redirect()->route('documents.show', $document)->with('error', 'No signed certificate available for this document.');
        }

        // Check if certificate file exists
        if (!Storage::disk('private')->exists($document->signature_certificate_path)) {
            return redirect()->route('documents.show', $document)->with('error', 'Signed certificate file not found.');
        }

        // Download the certificate
        $filename = "DOC-{$document->id}_SIGNED_CERTIFICATE.html";
        return Storage::disk('private')->download($document->signature_certificate_path, $filename);
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
