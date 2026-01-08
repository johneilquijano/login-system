<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $count = AppNotification::forUser(Auth::id())
            ->unread()
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Get recent notifications (for bell dropdown)
     */
    public function recent(Request $request)
    {
        $limit = $request->query('limit', 10);
        $notifications = AppNotification::forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'link_url' => $notif->link_url,
                    'read_at' => $notif->read_at,
                    'created_at' => $notif->created_at,
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = AppNotification::findOrFail($id);

        // Authorization check
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        AppNotification::forUser(Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $notification = AppNotification::findOrFail($id);

        // Authorization check
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show notifications page
     */
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id;
        $userId = Auth::id();

        $query = AppNotification::forOrganization($orgId)
            ->forUser($userId)
            ->orderBy('created_at', 'desc');

        // Filter by read status
        $status = $request->query('status', 'all');
        if ($status === 'unread') {
            $query->unread();
        } elseif ($status === 'read') {
            $query->read();
        }

        // Filter by type
        $type = $request->query('type');
        if ($type) {
            if ($type === 'documents') {
                $query->byTypeCategory('documents');
            } elseif ($type === 'tools') {
                $query->byTypeCategory('tools');
            } elseif ($type === 'inventory_requests') {
                $query->byTypeCategory('inventory_requests');
            }
        }

        $notifications = $query->paginate(20);
        $unreadCount = AppNotification::forUser($userId)->unread()->count();

        return view('notifications.index', compact('notifications', 'status', 'type', 'unreadCount'));
    }

    /**
     * Show a single notification detail
     */
    public function show($id)
    {
        $notification = AppNotification::findOrFail($id);

        // Authorization check
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read
        if ($notification->isUnread()) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }
}
