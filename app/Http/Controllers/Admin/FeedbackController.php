<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display list of feedbacks (inbox)
     * For Super Admin: shows all feedback across all organizations
     * For Org Admin: shows organization feedback (deprecated, use super-admin routes)
     */
    public function index(Request $request)
    {
        // Check if super admin
        $isSuperAdmin = Auth::user()->is_super_admin ?? false;

        if ($isSuperAdmin) {
            // Super Admin: Show all feedback across all organizations
            $query = Feedback::query();

            // Apply filters for super admin
            if ($request->filled('org_id')) {
                $query->where('org_id', $request->org_id);
            }

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

            // Get statistics (global)
            $stats = [
                'total' => Feedback::count(),
                'new' => Feedback::byStatus('new')->count(),
                'in_review' => Feedback::byStatus('in_review')->count(),
                'fixed' => Feedback::byStatus('fixed')->count(),
                'ignored' => Feedback::byStatus('ignored')->count(),
            ];

            // Get all organizations for filter
            $organizations = \App\Models\Organization::select('id', 'name')->orderBy('name')->get();

            // Get all active users (for reporter filter if needed)
            $reporters = \App\Models\User::where('status', 'active')
                ->select('id', 'name', 'email', 'org_id')
                ->orderBy('name')
                ->get();

            // Paginate
            $feedbacks = $query->orderByRecent()->paginate(20);

            return view('admin.feedback.index', compact('feedbacks', 'stats', 'reporters', 'organizations', 'isSuperAdmin'));
        } else {
            // Org Admin: Show organization feedback (kept for backward compatibility, though routes are now super-admin only)
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

            return view('admin.feedback.index', compact('feedbacks', 'stats', 'reporters', 'isSuperAdmin'));
        }
    }

    /**
     * Display feedback detail
     */
    public function show(Feedback $feedback)
    {
        // Allow super admins to view all feedback, else verify feedback belongs to user's organization
        if (!Auth::user()->is_super_admin && $feedback->org_id !== Auth::user()->org_id) {
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
        // Allow super admins to update all feedback, else verify feedback belongs to user's organization
        if (!Auth::user()->is_super_admin && $feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_review,fixed,ignored',
        ]);

        $oldStatus = $feedback->status;
        $newStatus = $validated['status'];

        $feedback->update(['status' => $newStatus]);

        // Send notification to user if status changed to in_review or fixed
        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'in_review') {
                AppNotification::createNotification(
                    user: $feedback->user,
                    type: 'feedback.status_changed',
                    title: 'Your Feedback is Being Reviewed',
                    message: 'Your ' . $feedback->category_label . ' feedback is now being reviewed by our team.',
                    linkUrl: route('feedback.my'),
                    data: ['feedback_id' => $feedback->id, 'status' => $newStatus]
                );
            } elseif ($newStatus === 'fixed') {
                AppNotification::createNotification(
                    user: $feedback->user,
                    type: 'feedback.status_changed',
                    title: 'Your Feedback Has Been Fixed',
                    message: 'Great news! Your ' . $feedback->category_label . ' feedback has been fixed and deployed.',
                    linkUrl: route('feedback.my'),
                    data: ['feedback_id' => $feedback->id, 'status' => $newStatus]
                );
            }
        }

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
        // Allow super admins to update all feedback, else verify feedback belongs to user's organization
        if (!Auth::user()->is_super_admin && $feedback->org_id !== Auth::user()->org_id) {
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
        // Allow super admins to delete all feedback, else verify feedback belongs to user's organization
        if (!Auth::user()->is_super_admin && $feedback->org_id !== Auth::user()->org_id) {
            abort(403);
        }

        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully',
        ]);
    }
}
