<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <x-employee-header 
                    title="Dashboard Overview" 
                    subtitle="Monitor and manage your system" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    @php
                        $actionRequiredCount = $pendingRequestsCount + $orderingTasksPendingCount + $dueTodayCount + $overdueCount;
                    @endphp

                    <!-- Mobile Action Required Summary -->
                    <div class="md:hidden mb-6 bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Action Required</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $actionRequiredCount }} items need action today</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Today
                            </span>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <a href="{{ route('admin.inventory-requests.index', ['status' => 'submitted']) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100">
                                Review
                            </a>
                            <a href="{{ route('admin.inventory-requests.index', ['status' => 'submitted']) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-green-700 bg-green-50 rounded-md hover:bg-green-100">
                                Approve
                            </a>
                            <a href="{{ route('admin.ordering-tasks.index') }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100">
                                Assign
                            </a>
                        </div>
                    </div>

                    <!-- Welcome Banner -->
                    <div class="mb-8 bg-brand-100 rounded-lg shadow-md p-6 text-gray-900">
                        <h1 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}</h1>
                        <p class="mt-2 text-gray-600">Here's what's happening with your system today</p>
                    </div>

                    <!-- ===== 1) QUICK ACTIONS ===== -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3">
                            <!-- Review Inventory Requests -->
                            <a href="{{ route('admin.inventory-requests.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Review Requests</p>
                            </a>

                            <!-- Ordering Tasks -->
                            <a href="{{ route('admin.ordering-tasks.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Ordering Tasks</p>
                            </a>

                            <!-- Add New Tool -->
                            <a href="{{ route('admin.tools.create') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Add Tool</p>
                            </a>

                            <!-- Manage Users -->
                            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-indigo-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Manage Users</p>
                            </a>

                            <!-- Tools Inventory -->
                            <a href="{{ route('admin.tools.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-orange-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Tools</p>
                            </a>

                            <!-- Documents -->
                            <a href="{{ route('admin.documents.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Documents</p>
                            </a>

                            <!-- Vehicles -->
                            <a href="{{ route('admin.vehicles.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition text-center">
                                <svg class="h-6 w-6 text-cyan-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-900">Vehicles</p>
                            </a>
                        </div>
                    </div>

                    <!-- ===== 2) NEEDS ATTENTION TODAY ===== -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Needs Attention Today</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Tile A: Inventory Requests -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Inventory Requests</h3>
                                        <p class="text-sm text-gray-600">Pending & Urgent</p>
                                    </div>
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                
                                <!-- Count Badge -->
                                <div class="mb-4">
                                    <div class="flex gap-3">
                                        <div class="bg-yellow-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-yellow-900">{{ $pendingRequestsCount }}</p>
                                            <p class="text-xs text-yellow-700">Pending</p>
                                        </div>
                                        <div class="bg-red-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-red-900">{{ $urgentRequestsCount }}</p>
                                            <p class="text-xs text-red-700">Urgent</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview List -->
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    @if($pendingRequestsPreview->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($pendingRequestsPreview as $request)
                                            <a href="{{ route('admin.inventory-requests.show', $request) }}" class="block p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">Request #{{ $request->id }}</p>
                                                        <p class="text-xs text-gray-600">{{ $request->user->name }}</p>
                                                    </div>
                                                    @if($request->priority === 'urgent')
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Urgent</span>
                                                    @else
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Normal</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                            </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 text-center py-4">No pending requests</p>
                                    @endif
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('admin.inventory-requests.index', ['status' => 'submitted']) }}" class="w-full inline-block text-center px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition text-sm">
                                    Review All Requests
                                </a>

                                <!-- Approve All CTA -->
                                <form action="{{ route('admin.inventory-requests.approveAll') }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to approve all submitted requests?');">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded font-semibold hover:bg-green-700 transition text-sm">
                                        Approve All Requests
                                    </button>
                                </form>

                                <!-- Auto Approve Request Toggle -->
                                <!-- Auto Approve Request Toggle -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Auto Approve Request</p>
                                                <p class="text-xs text-gray-600">Enable automatic approval</p>
                                            </div>
                                        </div>
                                        <!-- Toggle Switch -->
                                        <button type="button" id="auto-approve-toggle" class="relative inline-flex h-8 w-14 items-center rounded-full bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <span class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform" style="margin-left: 4px;" id="toggle-indicator"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tile B: Ordering Tasks -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Ordering Tasks</h3>
                                        <p class="text-sm text-gray-600">Needing Action</p>
                                    </div>
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4"></path>
                                    </svg>
                                </div>
                                
                                <!-- Count Badge -->
                                <div class="mb-4">
                                    <div class="flex gap-3">
                                        <div class="bg-yellow-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-yellow-900">{{ $orderingTasksPendingCount }}</p>
                                            <p class="text-xs text-yellow-700">Pending</p>
                                        </div>
                                        <div class="bg-blue-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-blue-900">{{ $orderingTasksWaitingCount }}</p>
                                            <p class="text-xs text-blue-700">Waiting</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview List -->
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    @if($orderingTasksPreview->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($orderingTasksPreview as $item)
                                            <a href="{{ route('admin.ordering-tasks.show', $item->orderingTask) }}" class="block p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $item->item_name }}</p>
                                                        <p class="text-xs text-gray-600">{{ $item->job_number }} / {{ $item->model_number }}</p>
                                                        <p class="text-xs text-gray-600 mt-1">{{ $item->quantity_received }} / {{ $item->quantity_approved }}</p>
                                                    </div>
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold 
                                                        @if($item->status === 'pending')
                                                            bg-yellow-100 text-yellow-800
                                                        @elseif($item->status === 'ordered')
                                                            bg-blue-100 text-blue-800
                                                        @elseif($item->status === 'partially_received')
                                                            bg-orange-100 text-orange-800
                                                        @else
                                                            bg-green-100 text-green-800
                                                        @endif
                                                        rounded">
                                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                    </span>
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 text-center py-4">All items ordered & received</p>
                                    @endif
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('admin.ordering-tasks.index') }}" class="w-full inline-block text-center px-4 py-2 bg-green-600 text-white rounded font-semibold hover:bg-green-700 transition text-sm">
                                    Open Ordering Tasks
                                </a>
                            </div>

                            <!-- Tile C: Broken Tools / Maintenance -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Maintenance</h3>
                                        <p class="text-sm text-gray-600">Tools Needing Repair</p>
                                    </div>
                                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Count Badge -->
                                <div class="mb-4">
                                    <div class="bg-orange-100 rounded-lg px-3 py-2">
                                        <p class="text-2xl font-bold text-orange-900">{{ $maintenanceToolsCount }}</p>
                                        <p class="text-xs text-orange-700">In Maintenance</p>
                                    </div>
                                </div>

                                <!-- Preview List -->
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    @if($maintenanceToolsPreview->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($maintenanceToolsPreview as $tool)
                                            <a href="{{ route('admin.tools.show', $tool) }}" class="block p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $tool->name }}</p>
                                                        <p class="text-xs text-gray-600">{{ $tool->category ?? '-' }}</p>
                                                    </div>
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded">Maintenance</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Updated {{ $tool->updated_at->diffForHumans() }}</p>
                                            </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 text-center py-4">No tools in maintenance</p>
                                    @endif
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('admin.tools.index', ['maintenance' => 'true']) }}" class="w-full inline-block text-center px-4 py-2 bg-orange-600 text-white rounded font-semibold hover:bg-orange-700 transition text-sm">
                                    View Maintenance Queue
                                </a>
                            </div>

                            <!-- Tile D: Due Today / Overdue Returns -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Tool Returns</h3>
                                        <p class="text-sm text-gray-600">Due Today & Overdue</p>
                                    </div>
                                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Count Badge -->
                                <div class="mb-4">
                                    <div class="flex gap-3">
                                        <div class="bg-yellow-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-yellow-900">{{ $dueTodayCount }}</p>
                                            <p class="text-xs text-yellow-700">Due Today</p>
                                        </div>
                                        <div class="bg-red-100 rounded-lg px-3 py-2">
                                            <p class="text-2xl font-bold text-red-900">{{ $overdueCount }}</p>
                                            <p class="text-xs text-red-700">Overdue</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview List -->
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    @if($dueCheckoutsPreview->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($dueCheckoutsPreview as $checkout)
                                            <a href="{{ route('admin.tools.show', $checkout->tool) }}" class="block p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $checkout->tool->name }}</p>
                                                        <p class="text-xs text-gray-600">{{ $checkout->user->name }}</p>
                                                    </div>
                                                    @if($checkout->return_due_date->isPast())
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Overdue</span>
                                                    @else
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Due Today</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs {{ $checkout->return_due_date->isPast() ? 'text-red-600' : 'text-gray-500' }} mt-1">
                                                    Due: {{ $checkout->return_due_date->format('M d, Y') }}
                                                </p>
                                            </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 text-center py-4">No tools due today or overdue</p>
                                    @endif
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('admin.tools.index', ['checkouts' => 'overdue']) }}" class="w-full inline-block text-center px-4 py-2 bg-red-600 text-white rounded font-semibold hover:bg-red-700 transition text-sm">
                                    View All Returns
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ===== 3) INVENTORY REQUESTS QUEUE ===== -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Inventory Requests Queue</h2>
                            <a href="{{ route('admin.inventory-requests.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">View All</a>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Tabs -->
                            <div class="flex border-b border-gray-200">
                                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 text-sm font-semibold text-gray-700 border-b-2 border-blue-600 text-blue-600">
                                    Pending
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 text-sm font-semibold text-gray-500 hover:text-gray-700">
                                    Approved
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 text-sm font-semibold text-gray-500 hover:text-gray-700">
                                    All
                                </a>
                            </div>

                            <!-- Mobile/Tablet Cards -->
                            <div class="md:hidden p-4 space-y-3">
                                @forelse($latestInventoryRequests as $request)
                                    @php
                                        $statusClasses = [
                                            'submitted' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'denied' => 'bg-red-100 text-red-800',
                                            'fulfilled' => 'bg-green-100 text-green-800',
                                        ];
                                    @endphp
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Request #{{ $request->id }}</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $request->user->name }}</p>
                                            </div>
                                            <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-600">
                                            <span class="font-medium text-gray-700">
                                                {{ $request->priority === 'urgent' ? 'Urgent' : 'Normal' }}
                                            </span>
                                            <span class="text-gray-400">•</span>
                                            <span>Submitted {{ $request->created_at->format('M d, Y') }}</span>
                                        </div>

                                        <div class="mt-4 flex items-center justify-end">
                                            <a href="{{ route('admin.inventory-requests.show', $request) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-semibold text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-lg border border-gray-200 bg-white p-6 text-center text-sm text-gray-600">
                                        No inventory requests found
                                    </div>
                                @endforelse
                            </div>

                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Request #</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Employee</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Submitted</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($latestInventoryRequests as $request)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $request->id }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->user->name }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($request->priority === 'urgent')
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Urgent</span>
                                                @else
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">Normal</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @php
                                                    $statusClasses = [
                                                        'submitted' => 'bg-yellow-100 text-yellow-800',
                                                        'approved' => 'bg-blue-100 text-blue-800',
                                                        'denied' => 'bg-red-100 text-red-800',
                                                        'fulfilled' => 'bg-green-100 text-green-800',
                                                    ];
                                                @endphp
                                                <span class="inline-block px-2 py-1 text-xs font-semibold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }} rounded">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <a href="{{ route('admin.inventory-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                                                No inventory requests found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ===== 4) TOOLS SUMMARY ===== -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Tools Inventory Summary</h2>
                            <a href="{{ route('admin.tools.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Manage Tools</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-sm font-medium">Available</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $toolsStats['total'] - $toolsStats['checked_out'] - $toolsStats['maintenance'] }}</p>
                                    </div>
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-green-100 text-green-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-sm font-medium">Checked Out</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $toolsStats['checked_out'] }}</p>
                                    </div>
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4m0 0l8-4m0 0l8 4m0 6l-8 4-8-4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-sm font-medium">Maintenance</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $toolsStats['maintenance'] }}</p>
                                    </div>
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-orange-100 text-orange-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== 5) MANAGE USERS OVERVIEW ===== -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Manage Users Overview</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-sm font-medium">Total Users</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                                    </div>
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-sm font-medium">Admins</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $adminCount }}</p>
                                    </div>
                                    <div class="flex items-center justify-center h-14 w-14 rounded-lg bg-purple-100 text-purple-600">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm font-medium">Employees</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $employeeCount }}</p>
                                </div>
                                <a href="{{ route('admin.users.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded font-semibold hover:bg-indigo-700 transition text-sm text-center">
                                    Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<script>
    // Auto Approve Request Toggle Functionality
    const toggleButton = document.getElementById('auto-approve-toggle');
    const toggleIndicator = document.getElementById('toggle-indicator');
    let isEnabled = {{ Auth::user()->auto_approve_requests ? 'true' : 'false' }};

    // Initialize toggle state from database
    if (isEnabled) {
        toggleButton.style.backgroundColor = '#3b82f6';
        toggleIndicator.style.marginLeft = '28px';
        toggleButton.setAttribute('data-state', 'on');
    }

    if (toggleButton && toggleIndicator) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const newState = !isEnabled;
            
            if (newState) {
                // Turn ON - Show confirmation before approving
                if (confirm('Are you sure you want to automatically approve all submitted inventory requests? Employees will receive notifications.')) {
                    // Update UI immediately (optimistic update)
                    toggleButton.style.backgroundColor = '#3b82f6';
                    toggleIndicator.style.marginLeft = '28px';
                    toggleButton.setAttribute('data-state', 'on');
                    isEnabled = true;
                    
                    // Save state to database
                    fetch('{{ route("admin.auto-approve-setting.update") }}', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            auto_approve_requests: true
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Make API call to approve all requests (async, don't wait)
                            return fetch('{{ route("admin.inventory-requests.approveAll") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({})
                            });
                        } else {
                            throw new Error('Failed to save setting');
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Show success message
                            const message = document.createElement('div');
                            message.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50';
                            message.textContent = 'All submitted requests approved successfully! Auto-approve is now enabled.';
                            document.body.appendChild(message);
                            
                            // Remove message after 4 seconds
                            setTimeout(() => message.remove(), 4000);
                        } else {
                            throw new Error('Failed to approve requests');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert toggle
                        isEnabled = false;
                        toggleButton.style.backgroundColor = '#d1d5db';
                        toggleIndicator.style.marginLeft = '4px';
                        
                        const message = document.createElement('div');
                        message.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
                        message.textContent = 'Error: ' + error.message;
                        document.body.appendChild(message);
                        
                        setTimeout(() => message.remove(), 3000);
                    });
                } else {
                    // User cancelled, don't change state
                }
            } else {
                // Turn OFF
                // Update UI immediately (optimistic update)
                toggleButton.style.backgroundColor = '#d1d5db';
                toggleIndicator.style.marginLeft = '4px';
                toggleButton.setAttribute('data-state', 'off');
                isEnabled = false;
                
                // Save state to database
                fetch('{{ route("admin.auto-approve-setting.update") }}', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        auto_approve_requests: false
                    })
                })
                .then(response => {
                    if (response.ok) {
                        // Show confirmation message
                        const message = document.createElement('div');
                        message.className = 'fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg shadow-lg z-50';
                        message.textContent = 'Auto-approve has been disabled.';
                        document.body.appendChild(message);
                        
                        setTimeout(() => message.remove(), 3000);
                    } else {
                        throw new Error('Failed to save setting');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert toggle
                    isEnabled = true;
                    toggleButton.style.backgroundColor = '#3b82f6';
                    toggleIndicator.style.marginLeft = '28px';
                    
                    const message = document.createElement('div');
                    message.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
                    message.textContent = 'Error: ' + error.message;
                    document.body.appendChild(message);
                    
                    setTimeout(() => message.remove(), 3000);
                });
            }
        });
    }
</script>
