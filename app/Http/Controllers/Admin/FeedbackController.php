<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display list of feedbacks (inbox)
     */
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;
        $query = Feedback::forOrganization($orgId);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('page_search')) {
            $query->byPage($request->page_search);
        }

        if ($request->filled('reporter')) {
            $query->byReporter($request->reporter);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange(
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            );
        }

        // Get statistics
        $stats = [
            'total' => Feedback::forOrganization($orgId)->count(),
            'new' => Feedback::forOrganization($orgId)->byStatus('new')->count(),
            'in_review' => Feedback::forOrganization($orgId)->byStatus('in_review')->count(),
            'fixed' => Feedback::forOrganization($orgId)->byStatus('fixed')->count(),
            'ignored' => Feedback::forOrganization($orgId)->byStatus('ignored')->count(),
        ];

        // Get employees for filter
        $reporters = \App\Models\User::where('org_id', $orgId)
            ->where('status', 'active')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Paginate
        $feedbacks = $query->orderByRecent()->paginate(20);

        return view('admin.feedback.index', compact('feedbacks', 'stats', 'reporters'));
    }

    /**
     * Display feedback detail
     */
    public function show(Feedback $feedback)
    {
        // Verify feedback belongs to user's organization
        if ($feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $feedback->load('user', 'organization');

        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Update feedback status
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        // Verify feedback belongs to user's organization
        if ($feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_review,fixed,ignored',
        ]);

        $feedback->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $feedback->status_label,
        ]);
    }

    /**
     * Update admin notes
     */
    public function updateNotes(Request $request, Feedback $feedback)
    {
        // Verify feedback belongs to user's organization
        if ($feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $feedback->updateAdminNotes($validated['admin_notes'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully',
        ]);
    }

    /**
     * Delete feedback
     */
    public function destroy(Feedback $feedback)
    {
        // Verify feedback belongs to user's organization
        if ($feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully',
        ]);
    }
}
