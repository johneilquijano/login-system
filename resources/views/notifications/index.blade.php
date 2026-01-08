<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            @if(Auth::user()->role === 'admin')
                <x-admin-sidebar />
            @else
                <x-employee-sidebar />
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="sticky top-0 z-40 bg-white shadow-sm border-b" style="border-bottom-color: #ccc;">
                    <div class="flex items-center justify-between px-8 py-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($status === 'unread')
                                    Unread messages
                                @elseif($status === 'read')
                                    Read messages
                                @else
                                    All notifications
                                @endif
                            </p>
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <div class="p-8">
                    <!-- Filters -->
                    <div class="mb-6 flex flex-wrap gap-3">
                        <div class="flex gap-2">
                            <a href="{{ route('notifications.index') }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $status === 'all' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                All
                            </a>
                            <a href="{{ route('notifications.index', ['status' => 'unread']) }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $status === 'unread' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                Unread ({{ $unreadCount }})
                            </a>
                            <a href="{{ route('notifications.index', ['status' => 'read']) }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $status === 'read' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                Read
                            </a>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('notifications.index') }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ !$type ? 'bg-gray-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                All Types
                            </a>
                            <a href="{{ route('notifications.index', array_merge(request()->query(), ['type' => 'documents'])) }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $type === 'documents' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                Documents
                            </a>
                            <a href="{{ route('notifications.index', array_merge(request()->query(), ['type' => 'tools'])) }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $type === 'tools' ? 'bg-green-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                Tools
                            </a>
                            <a href="{{ route('notifications.index', array_merge(request()->query(), ['type' => 'inventory_requests'])) }}" 
                               class="px-4 py-2 rounded-lg font-medium transition {{ $type === 'inventory_requests' ? 'bg-purple-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                                Inventory
                            </a>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    @if($notifications->count() > 0)
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div class="bg-white rounded-lg border {{ $notification->isUnread() ? 'border-blue-200 bg-blue-50' : 'border-gray-200' }} p-4 hover:shadow-md transition cursor-pointer" onclick="window.location.href = '{{ $notification->link_url ?? '#' }}'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                                                @if($notification->isUnread())
                                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                                @endif
                                            </div>
                                            <p class="text-gray-700 mt-1">{{ $notification->message }}</p>
                                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-600">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex gap-2 ml-4">
                                            @if($notification->isUnread())
                                                <button class="text-blue-600 hover:text-blue-700 text-sm font-medium" onclick="markAsRead(event, {{ $notification->id }})">
                                                    Mark as read
                                                </button>
                                            @endif
                                            <button class="text-red-600 hover:text-red-700 text-sm font-medium" onclick="deleteNotification(event, {{ $notification->id }})">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No notifications</h3>
                            <p class="text-gray-600">You're all caught up! Check back later for updates.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function markAsRead(e, id) {
        e.preventDefault();
        e.stopPropagation();
        fetch(`/notifications/${id}/mark-as-read`, { 
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } 
        })
        .then(() => location.reload());
    }

    function deleteNotification(e, id) {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Delete this notification?')) {
            fetch(`/notifications/${id}`, { 
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } 
            })
            .then(() => location.reload());
        }
    }
    </script>
</x-layout>
