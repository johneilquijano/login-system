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
        $documents = Auth::user()->documents()->orderBy('created_at', 'desc')->paginate(10);
        return view('employee.documents.index', compact('documents'));
    }

    public function show(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }
        return Storage::download($document->file_path, $document->title);
    }
}
