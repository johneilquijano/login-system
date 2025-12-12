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
                <a href="{{ route('password.form') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition flex items-center space-x-2">
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
