<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->org_id;
        // Employees only see their own documents
        $documents = Document::forOrganization($orgId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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
        return Storage::download($document->file_path, $document->file_name);
    }
}
