<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="sticky top-0 z-40 bg-white shadow-sm border-b" style="border-bottom-color: #ccc;">
                    <div class="flex items-center justify-between px-8 py-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                            <p class="text-sm text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}</p>
                        </div>
                        <div class="flex items-center space-x-6">
                            <x-notification-bell />
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }} Account</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Organization Info Banner -->
                    <div class="mb-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
                        <h1 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}</h1>
                        <p class="mt-2 text-blue-100">Organization ID: {{ Auth::user()->org_id ?? 'Not assigned' }}</p>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex justify-between items-center shadow-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">×</button>
                    </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm font-medium">Your Role</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-green-100 text-green-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm font-medium">Member Since</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-green-100 text-green-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm font-medium">Account Status</p>
                                    <p class="text-2xl font-bold text-green-600">Active</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100 p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Your Workspace</h2>
                        <p class="text-gray-700 leading-relaxed">
                            This is your personal employee dashboard. Here you can access important documents, check out tools, and submit inventory requests. 
                            All sections are organized for easy access and quick management of your work-related needs.
                        </p>
                    </div>

                    <!-- Placeholder Sections -->
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Available Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Employee Documents (Phase 2) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-blue-200 transition">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Employee Documents</span>
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">Review, sign, and manage your assigned documents.</p>
                            </div>

                            <div class="px-6 py-6">
                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-5">
                                    <div class="rounded-lg bg-blue-50 border border-blue-100 px-3 py-2">
                                        <p class="text-xs text-blue-700 font-medium">Needs Signature</p>
                                        <p class="text-xl font-bold text-blue-900">{{ $docs_needs_signature ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-indigo-50 border border-indigo-100 px-3 py-2">
                                        <p class="text-xs text-indigo-700 font-medium">New Documents</p>
                                        <p class="text-xl font-bold text-indigo-900">{{ $docs_new ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-emerald-50 border border-emerald-100 px-3 py-2">
                                        <p class="text-xs text-emerald-700 font-medium">Signed</p>
                                        <p class="text-xl font-bold text-emerald-900">{{ $docs_signed ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
                                        <p class="text-xs text-gray-600 font-medium">Total</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $docs_total ?? 0 }}</p>
                                    </div>
                                </div>

                                <!-- Quick info -->
                                <div class="text-sm text-gray-600 mb-5">
                                    @if(($docs_needs_signature ?? 0) > 0)
                                        <span class="font-medium text-gray-900">Action needed:</span>
                                        You have documents waiting for your signature.
                                    @else
                                        <span class="font-medium text-gray-900">All set:</span>
                                        No documents pending signature.
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center flex-1 rounded-lg bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                                        View Documents
                                    </a>
                                    <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white border border-blue-200 text-blue-700 px-4 py-2.5 text-sm font-semibold hover:bg-blue-50 transition">
                                        Sign Now
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Tool Check-Out (Phase 3) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-green-200 transition">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-5">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    <span>Tool Check-Out</span>
                                </h3>
                                <p class="text-green-100 text-sm mt-1">Browse tools, check out items, and return on time.</p>
                            </div>

                            <div class="px-6 py-6">
                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-5">
                                    <div class="rounded-lg bg-green-50 border border-green-100 px-3 py-2">
                                        <p class="text-xs text-green-700 font-medium">My Checked-Out</p>
                                        <p class="text-xl font-bold text-green-900">{{ $tools_checked_out ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-amber-50 border border-amber-100 px-3 py-2">
                                        <p class="text-xs text-amber-700 font-medium">Due Soon</p>
                                        <p class="text-xl font-bold text-amber-900">{{ $tools_due_soon ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2 col-span-2">
                                        <p class="text-xs text-gray-600 font-medium">Available Tools</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $tools_available ?? 0 }}</p>
                                    </div>
                                </div>

                                <!-- Next due item (optional) -->
                                <div class="text-sm text-gray-600 mb-5">
                                    <span class="font-medium text-gray-900">Next due:</span>
                                    {{ $next_due_tool_name ?? '—' }}
                                    <span class="text-gray-400">•</span>
                                    {{ $next_due_tool_date ?? 'No due items' }}
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center flex-1 rounded-lg bg-green-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-green-700 transition">
                                        Browse Tools
                                    </a>
                                    <a href="{{ route('tools.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white border border-green-200 text-green-700 px-4 py-2.5 text-sm font-semibold hover:bg-green-50 transition">
                                        View My Items
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Requests (Phase 4) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-purple-200 transition">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-5">
                                <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Inventory Requests</span>
                                </h3>
                                <p class="text-purple-100 text-sm mt-1">Request supplies and track approvals and fulfillment.</p>
                            </div>

                            <div class="px-6 py-6">
                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-5">
                                    <div class="rounded-lg bg-purple-50 border border-purple-100 px-3 py-2">
                                        <p class="text-xs text-purple-700 font-medium">Pending</p>
                                        <p class="text-xl font-bold text-purple-900">{{ $inv_pending ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-pink-50 border border-pink-100 px-3 py-2">
                                        <p class="text-xs text-pink-700 font-medium">Needs Action</p>
                                        <p class="text-xl font-bold text-pink-900">{{ $inv_needs_action ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2 col-span-2">
                                        <p class="text-xs text-gray-600 font-medium">Quick Create</p>
                                        <p class="text-sm text-gray-700">Submit a new request in under a minute.</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('inventory-requests.index', ['new' => 'true']) }}" class="inline-flex items-center justify-center flex-1 rounded-lg bg-purple-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-purple-700 transition">
                                        New Request
                                    </a>
                                    <a href="{{ route('inventory-requests.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white border border-purple-200 text-purple-700 px-4 py-2.5 text-sm font-semibold hover:bg-purple-50 transition">
                                        View Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Access Section -->
                    @if(Auth::user()->role === 'admin')
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg border border-indigo-100 p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Admin Panel</h3>
                                <p class="text-gray-700">Access the administration panel to manage users and system settings.</p>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition flex items-center space-x-2">
                                <span>Go to Admin Panel</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layout>
