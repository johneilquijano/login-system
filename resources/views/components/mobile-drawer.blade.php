<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer" class="fixed inset-0 z-40 hidden">
    <!-- Overlay -->
    <div id="mobile-drawer-overlay" class="absolute inset-0 bg-black bg-opacity-50" onclick="closeMobileDrawer()"></div>
    
    <!-- Drawer Panel -->
    <div class="absolute left-0 top-0 h-full w-64 bg-white shadow-xl flex flex-col overflow-y-auto">
        <!-- Header with Org & User Profile -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md border-2 border-white border-opacity-30">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-100">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>
            @if(Auth::user()->organization)
            <div class="border-t border-blue-400 border-opacity-50 pt-3">
                <p class="text-xs text-blue-100 uppercase tracking-wider">Organization</p>
                <p class="font-medium text-sm mt-1">{{ Auth::user()->organization->name }}</p>
            </div>
            @endif
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            @if(Auth::user()->role === 'employee')
                <!-- Employee Navigation -->
                <a href="{{ route('dashboard') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('dashboard'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('dashboard')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('documents.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('documents.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('documents.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Documents</span>
                </a>

                <a href="{{ route('tools.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('tools.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('tools.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <span>Tools</span>
                </a>

                <a href="{{ route('inventory-requests.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('inventory-requests.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('inventory-requests.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Inventory</span>
                </a>

                <a href="{{ route('vehicles.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('vehicles.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('vehicles.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Vehicles</span>
                </a>

                <a href="{{ route('feedback.my') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('feedback.my'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('feedback.my')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>My Feedback</span>
                </a>

                <a href="{{ route('notifications.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('notifications.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('notifications.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Notifications</span>
                </a>
            @elseif(Auth::user()->role === 'admin')
                <!-- Admin Navigation -->
                <a href="{{ route('admin.dashboard') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.dashboard'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.dashboard')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.users.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.users.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                    </svg>
                    <span>Manage Users</span>
                </a>

                <a href="{{ route('admin.documents.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.documents.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.documents.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Document Management</span>
                </a>

                <a href="{{ route('admin.tools.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.tools.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.tools.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <span>Tools Inventory</span>
                </a>

                <a href="{{ route('admin.inventory-requests.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.inventory-requests.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.inventory-requests.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Inventory Requests</span>
                </a>

                <a href="{{ route('admin.ordering-tasks.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.ordering-tasks.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.ordering-tasks.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Ordering Tasks</span>
                </a>

                <a href="{{ route('admin.vehicles.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.vehicles.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.vehicles.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Vehicles</span>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('admin.audit-logs.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.audit-logs.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Audit Logs</span>
                </a>

                <!-- System Section Divider -->
                <div class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">System</div>

                <a href="{{ route('feedback.my') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('feedback.my'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('feedback.my')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>My Feedback</span>
                </a>

                <a href="{{ route('notifications.index') }}" onclick="closeMobileDrawer()" @class([
                    'mobile-nav-link flex items-center px-4 py-3 rounded-lg transition',
                    'bg-blue-50 text-blue-700 font-semibold' => request()->routeIs('notifications.*'),
                    'text-gray-700 hover:bg-gray-50' => !request()->routeIs('notifications.*')
                ])>
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Notifications</span>
                </a>
            @endif
        </nav>

        <!-- Logout Button -->
        <div class="border-t border-gray-200 p-4 space-y-2">
            @if(Auth::user()->role === 'admin')
                <!-- Admin Only Options -->
                <a href="{{ route('admin.direct-access.show') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <span>Direct Access Link</span>
                </a>
                <a href="{{ route('admin.api-token.show') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>API Token</span>
                </a>
                <a href="{{ route('admin.ai-setup.show') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>AI Setup</span>
                </a>
                <div class="border-t border-gray-200 pt-2 mt-2"></div>
            @endif
            
            <a href="{{ route('password.form') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Change Password</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-700 hover:bg-red-50 transition rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openMobileDrawer() {
        const drawer = document.getElementById('mobile-drawer');
        drawer.classList.remove('hidden');
    }

    function closeMobileDrawer() {
        const drawer = document.getElementById('mobile-drawer');
        drawer.classList.add('hidden');
    }

    // Close drawer on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMobileDrawer();
        }
    });
</script>
