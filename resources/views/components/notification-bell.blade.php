<div class="relative group">
    <!-- Bell Icon Button -->
    <button type="button" id="notification-bell" class="relative p-2 text-gray-600 hover:text-gray-900 transition rounded-lg hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Badge -->
        <span id="notification-badge" class="absolute top-1 right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center hidden">
            0
        </span>
    </button>

    <!-- Dropdown Menu -->
    <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50 max-h-96 flex flex-col">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Notifications</h3>
            <button id="mark-all-read-btn" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                Mark all as read
            </button>
        </div>

        <!-- Notifications List -->
        <div id="notification-list" class="overflow-y-auto flex-1">
            <div class="px-4 py-8 text-center text-gray-500">
                <p class="text-sm">Loading notifications...</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-100">
            <a href="{{ route('notifications.index') }}" class="block w-full text-center text-sm text-blue-600 hover:text-blue-700 font-medium py-2">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bell = document.getElementById('notification-bell');
    const dropdown = document.getElementById('notification-dropdown');
    const badge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');
    const markAllReadBtn = document.getElementById('mark-all-read-btn');

    // Toggle dropdown
    bell.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        if (!dropdown.classList.contains('hidden')) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Load notifications
    function loadNotifications() {
        fetch('{{ route("notifications.recent") }}?limit=10')
            .then(response => {
                if (!response.ok) {
                    console.error('Error loading notifications:', response.status);
                    notificationList.innerHTML = '<div class="px-4 py-8 text-center text-red-500"><p class="text-sm">Error loading notifications</p></div>';
                    return null;
                }
                return response.json();
            })
            .then(notifications => {
                if (!notifications) return;
                
                if (notifications.length === 0) {
                    notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500"><p class="text-sm">No notifications</p></div>';
                } else {
                    notificationList.innerHTML = notifications.map(notif => `
                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition cursor-pointer group" onclick="markAndRedirect('${notif.id}', '${notif.link_url || '#'}')">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">${notif.title}</p>
                                    <p class="text-gray-600 text-xs mt-1">${notif.message}</p>
                                    <p class="text-gray-400 text-xs mt-1">${formatTime(notif.created_at)}</p>
                                </div>
                                ${!notif.read_at ? '<span class="w-2 h-2 bg-blue-500 rounded-full mt-1 flex-shrink-0 ml-2"></span>' : ''}
                            </div>
                        </div>
                    `).join('');
                }
                updateBadge();
            })
            .catch(err => {
                console.error('Error loading notifications:', err);
                notificationList.innerHTML = '<div class="px-4 py-8 text-center text-red-500"><p class="text-sm">Error loading notifications</p></div>';
            });
    }

    // Update badge count
    function updateBadge() {
        fetch('{{ route("notifications.unreadCount") }}')
            .then(response => {
                if (!response.ok) {
                    console.error('Error fetching unread count:', response.status);
                    return { unread_count: 0 };
                }
                return response.json();
            })
            .then(data => {
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error('Error updating notification badge:', err);
            });
    }

    // Mark all as read
    markAllReadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fetch('{{ route("notifications.markAllAsRead") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(() => {
                loadNotifications();
            });
    });

    // Mark notification as read and redirect
    window.markAndRedirect = function(notificationId, url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        console.log('Marking notification as read:', notificationId, 'URL:', url);
        
        // Use FormData for sendBeacon (application/x-www-form-urlencoded)
        const formData = new FormData();
        formData.append('_token', csrfToken);
        
        // Send beacon (guarantees request is sent even on page unload)
        const sent = navigator.sendBeacon('/notifications/' + notificationId + '/mark-as-read', formData);
        console.log('Beacon sent successfully:', sent);
        
        // Delay to ensure request is processed server-side
        setTimeout(function() {
            console.log('Redirecting to:', url);
            window.location.href = url;
        }, 200);
    };

    // Format time
    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';

        return date.toLocaleDateString();
    }

    // Initial load
    updateBadge();
    loadNotifications();

    // Refresh every 30 seconds
    setInterval(updateBadge, 30000);
});
</script>
