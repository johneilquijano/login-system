<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <!-- Sidebar Navigation -->
            <div class="w-64 bg-white shadow-lg">
                <div class="h-16 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center px-6">
                    <h1 class="text-white font-bold text-xl">Admin Panel</h1>
                </div>
                
                <nav class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border-l-4 border-purple-600 bg-purple-50 text-purple-700 font-semibold flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                        </svg>
                        <span>Manage Users</span>
                    </a>
                    
                    <!-- <a href="{{ route('dashboard') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                        <span>Back to Dashboard</span>
                    </a> -->
                </nav>
                
                <!-- Logout Button -->
                <div class="absolute bottom-0 w-64 border-t px-6 py-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <div class="bg-white shadow">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                        <div class="text-sm text-gray-600">
                            Welcome, <span class="font-semibold">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Stats Row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Admins</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Employees</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'employee')->count() }}</p>
                                </div>
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Last Update</p>
                                    <p class="text-lg font-bold text-gray-900 mt-2">{{ now()->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-orange-100 text-orange-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">User Management</h3>
                            <p class="text-gray-600 text-sm mb-4">Create, edit, or disable employee accounts</p>
                            <a href="{{ route('admin.users.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                                Manage Users →
                            </a>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">System Settings</h3>
                            <p class="text-gray-600 text-sm mb-4">Configure system-wide settings</p>
                            <button disabled class="inline-block bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed text-sm">
                                Coming Soon
                            </button>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reports</h3>
                            <p class="text-gray-600 text-sm mb-4">View system reports and analytics</p>
                            <button disabled class="inline-block bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed text-sm">
                                Coming Soon
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
