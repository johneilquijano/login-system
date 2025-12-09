<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <!-- Sidebar Navigation -->
            <div class="dashboard-sidebar flex flex-col w-64 bg-white shadow-lg h-screen sticky top-0" style="display:flex;flex-direction:column;width:16rem;height:100vh;">
                <div class="h-16 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center px-6">
                    <h1 class="text-white font-bold text-xl">Admin Panel</h1>
                </div>
                
                <nav class="dashboard-nav flex flex-col flex-1 mt-6 space-y-1 overflow-y-auto" style="display:flex;flex-direction:column;flex:1;">
                    <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center px-6 py-3 border-l-4 border-purple-600 bg-purple-50 text-purple-700 font-semibold" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="w-full flex items-center px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                        </svg>
                        Manage Users
                    </a>
                </nav>
                
                <!-- User Profile Section (Bottom) -->
                <div class="mt-auto border-t px-6 py-4">
                    <div class="relative">
                        <button id="admin-profile-menu-btn" onclick="toggleAdminProfileMenu()" class="w-full flex items-center space-x-3 hover:bg-gray-100 p-2 rounded-lg transition cursor-pointer">
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
                        <div id="admin-profile-dropdown" class="hidden fixed bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden w-48" style="display: none;">
                            <a href="{{ route('password.form') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Change Password</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
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

                <script>
                    function toggleAdminProfileMenu() {
                        const dropdown = document.getElementById('admin-profile-dropdown');
                        const btn = document.getElementById('admin-profile-menu-btn');
                        
                        if (dropdown.style.display === 'none' || dropdown.classList.contains('hidden')) {
                            const rect = btn.getBoundingClientRect();
                            dropdown.style.display = 'block';
                            dropdown.classList.remove('hidden');
                            dropdown.style.bottom = (window.innerHeight - rect.top) + 'px';
                            dropdown.style.left = rect.left + 'px';
                            dropdown.style.width = '12rem';
                        } else {
                            dropdown.style.display = 'none';
                            dropdown.classList.add('hidden');
                        }
                    }

                    document.addEventListener('click', function(e) {
                        const dropdown = document.getElementById('admin-profile-dropdown');
                        const btn = document.getElementById('admin-profile-menu-btn');
                        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                            dropdown.style.display = 'none';
                            dropdown.classList.add('hidden');
                        }
                    });
                </script>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <header class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-8 py-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                            <p class="text-sm text-gray-600 mt-1">Monitor and manage your system</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">Administrator</p>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Welcome Banner -->
                    <div class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-md p-6 text-white">
                        <h1 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}</h1>
                        <p class="mt-2 text-purple-100">Here's what's happening with your system today</p>
                    </div>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Users</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Admins</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-purple-100 text-purple-600">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Employees</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'employee')->count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-green-100 text-green-600">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Last Update</p>
                                    <p class="text-xl font-bold text-gray-900 mt-2">{{ now()->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-orange-100 text-orange-600">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">User Management</h3>
                            <p class="text-gray-600 text-sm mb-4">Create, edit, disable accounts, and reset passwords</p>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                                <span>Manage Users</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-purple-200 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 text-purple-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">System Settings</h3>
                            <p class="text-gray-600 text-sm mb-4">Configure system-wide settings and preferences</p>
                            <button disabled class="inline-flex items-center space-x-2 bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed text-sm">
                                <span>Coming Soon</span>
                            </button>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 hover:shadow-lg hover:border-green-200 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 text-green-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reports & Analytics</h3>
                            <p class="text-gray-600 text-sm mb-4">View system reports, analytics, and insights</p>
                            <button disabled class="inline-flex items-center space-x-2 bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed text-sm">
                                <span>Coming Soon</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
