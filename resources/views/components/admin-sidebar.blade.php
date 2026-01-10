<div class="dashboard-sidebar flex flex-col w-64 bg-white shadow-lg h-screen sticky top-0">
    <div class="h-16 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center px-6">
        <h1 class="text-white font-bold text-xl">Admin Panel</h1>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="dashboard-nav flex flex-col flex-1 mt-6 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.dashboard'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.dashboard')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Dashboard
        </a>
        
        <a href="{{ route('admin.users.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.users.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.users.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
            </svg>
            Manage Users
        </a>

        <a href="{{ route('admin.documents.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.documents.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.documents.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Document Management
        </a>

        <a href="{{ route('admin.tools.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.tools.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.tools.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            Tools Inventory
        </a>

        <a href="{{ route('admin.inventory-requests.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.inventory-requests.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.inventory-requests.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Inventory Requests
        </a>

        <a href="{{ route('admin.ordering-tasks.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.ordering-tasks.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.ordering-tasks.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Ordering Tasks
        </a>

        <a href="{{ route('admin.vehicles.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('admin.vehicles.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('admin.vehicles.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Vehicles
        </a>

        <a href="{{ route('notifications.index') }}" @class([
            'w-full flex items-center px-6 py-3 border-l-4 transition',
            'border-purple-600 bg-purple-50 text-purple-700 font-semibold' => request()->routeIs('notifications.*'),
            'border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50' => !request()->routeIs('notifications.*')
        ])>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            Notifications
        </a>
    </nav>
    
    <!-- User Profile Section (Bottom) -->
    <div class="mt-auto border-t border-gray-300 px-6 py-4">
        <div class="relative">
            <button class="admin-profile-menu-btn w-full flex items-center space-x-3 hover:bg-gray-100 p-2 rounded-lg transition cursor-pointer">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 text-left">
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>

            <!-- Dropdown Menu (fixed position, appears above) -->
            <div class="admin-profile-dropdown hidden fixed bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden w-48">
                <a href="{{ route('admin.direct-access.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <span>Direct Access Link</span>
                </a>
                <a href="{{ route('admin.api-token.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>API Token</span>
                </a>
                <a href="{{ route('admin.ai-setup.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>AI Setup</span>
                </a>
                <a href="{{ route('password.form') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition flex items-center space-x-2 border-t border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>Change Password</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-300">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-700 hover:bg-red-50 transition flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script type="module" defer>
        // Admin profile dropdown management with event delegation
        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.querySelector('.admin-profile-menu-btn');
            const profileDropdown = document.querySelector('.admin-profile-dropdown');
            
            if (!profileBtn || !profileDropdown) return;

            const toggleDropdown = (show) => {
                if (show) {
                    const rect = profileBtn.getBoundingClientRect();
                    profileDropdown.classList.remove('hidden');
                    profileDropdown.style.bottom = (window.innerHeight - rect.top) + 'px';
                    profileDropdown.style.left = rect.left + 'px';
                } else {
                    profileDropdown.classList.add('hidden');
                }
            };

            // Toggle on button click
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = profileDropdown.classList.contains('hidden');
                toggleDropdown(isHidden);
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!profileDropdown.classList.contains('hidden') && 
                    !profileDropdown.contains(e.target) && 
                    !profileBtn.contains(e.target)) {
                    toggleDropdown(false);
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !profileDropdown.classList.contains('hidden')) {
                    toggleDropdown(false);
                }
            });
        });
    </script>
</div>
