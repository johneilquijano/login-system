<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <!-- Sidebar Navigation (desktop) -->
            <div class="dashboard-sidebar flex flex-col w-64 bg-white shadow-lg h-screen sticky top-0" style="display:flex;flex-direction:column;width:16rem;height:100vh;">
                <div class="h-16 bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center px-6">
                    <h1 class="text-white font-bold text-lg">Employee Portal</h1>
                </div>

                <!-- Navigation Menu -->
                <nav class="dashboard-nav flex flex-col flex-1 mt-6 space-y-1 overflow-y-auto" style="display:flex;flex-direction:column;flex:1;">
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center px-6 py-3 border-l-4 border-blue-600 bg-blue-50 text-blue-700 font-semibold" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="#" class="w-full flex items-center px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Employee Documents
                    </a>
                    <a href="#" class="w-full flex items-center px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Tool Check-Out
                    </a>
                    <a href="#" class="w-full flex items-center px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50" style="display:flex;width:100%;">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.75rem;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Inventory Requests
                    </a>
                </nav>

                <!-- User Profile Section (Bottom) -->
                <div class="mt-auto border-t px-6 py-4">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center space-x-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="flex items-center justify-between p-4 border-b bg-white">
                    <h2 class="text-lg font-semibold text-gray-900">Dashboard</h2>
                    <div class="flex items-center space-x-3">
                        <div class="text-sm text-gray-600">Hello, {{ Auth::user()->name }}</div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}</h1>
                    <p class="text-gray-600 mt-1">Organization: Your Organization (ID: {{ Auth::user()->org_id ?? 'N/A' }})</p>
                </div>

                <div class="p-8">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">×</button>
                    </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm font-medium">Your Role</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm font-medium">Member Since</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8a4 4 0 014-4h10a4 4 0 014 4v8a4 4 0 01-4 4H7a4 4 0 01-4-4V8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm font-medium">Status</p>
                                    <p class="text-2xl font-semibold text-green-600">Active</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Management -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Account Management</h2>
                        <p class="text-gray-600 mb-4">Manage your account security and settings.</p>
                        <a href="{{ route('password.form') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Change Password
                        </a>
                    </div>

                    <!-- Welcome Message -->
                    <div class="bg-white rounded-lg shadow p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Your Workspace</h2>
                        <p class="text-gray-600 leading-relaxed">
                            This is your personal employee dashboard. Here you can access important documents, check out tools, and submit inventory requests. 
                            All sections are organized for easy access and quick management of your work-related needs.
                        </p>
                    </div>

                    <!-- Placeholder Sections -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Employee Documents -->
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Employee Documents</span>
                                </h3>
                            </div>
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500 mb-4">Access your employee handbook, policies, and important documents.</p>
                                <div class="inline-block bg-gray-100 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Coming Soon
                                </div>
                            </div>
                        </div>

                        <!-- Tool Check-Out -->
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    <span>Tool Check-Out</span>
                                </h3>
                            </div>
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500 mb-4">Request and manage tool check-outs from the inventory.</p>
                                <div class="inline-block bg-gray-100 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Coming Soon
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Requests -->
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Inventory Requests</span>
                                </h3>
                            </div>
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500 mb-4">Submit and track your inventory requests.</p>
                                <div class="inline-block bg-gray-100 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Coming Soon
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Access Section -->
                    @if(Auth::user()->role === 'admin')
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Admin Panel</h3>
                        <p class="text-gray-600 mb-4">As an admin, you have access to the administration panel where you can manage users and system settings.</p>
                        <a href="{{ route('admin.dashboard') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Go to Admin Panel →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layout>
