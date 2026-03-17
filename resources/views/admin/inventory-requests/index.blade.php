<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Inventory Requests" 
                    subtitle="Manage and process inventory requests" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Filters Section -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Filters</h3>
                        
                        <form method="GET" action="{{ route('admin.inventory-requests.index') }}" id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 items-end">
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                                <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">All Statuses</option>
                                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="denied" {{ request('status') === 'denied' ? 'selected' : '' }}>Denied</option>
                                    <option value="fulfilled" {{ request('status') === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Priority</label>
                                <select name="priority" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">All Priorities</option>
                                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <!-- Employee Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Employee</label>
                                <select name="user_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">All Employees</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('user_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">From</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">To</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" />
                            </div>

                            <!-- Overdue Filter -->
                            <div class="flex items-center">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="show_overdue" value="1" onchange="document.getElementById('filterForm').submit()" {{ request('show_overdue') === '1' ? 'checked' : '' }} class="w-4 h-4" />
                                    <span class="text-sm font-semibold text-gray-900 whitespace-nowrap">Overdue</span>
                                </label>
                            </div>

                            <!-- Clear Filters Button -->
                            <div>
                                <a href="{{ route('admin.inventory-requests.index') }}" class="bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-300 flex items-center justify-center gap-1 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span class="text-xs">Clear</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Requests Table -->
                    @if($requests->count() > 0)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <!-- Mobile/Tablet Cards -->
                        <div class="lg:hidden p-4 space-y-3">
                            @foreach($requests as $request)
                                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $request->user->name }}</p>
                                        </div>
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if($request->status === 'submitted')
                                                border border-yellow-800 text-yellow-800 uppercase
                                            @elseif($request->status === 'approved')
                                                border border-blue-800 text-blue-800 uppercase
                                            @elseif($request->status === 'denied')
                                                border border-red-800 text-red-800 uppercase
                                            @elseif($request->status === 'fulfilled')
                                                border border-green-800 text-green-800 uppercase
                                            @else
                                                border border-gray-800 text-gray-800 uppercase
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-900 mt-2">{{ $request->request_title }}</p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                        <span class="inline-block px-2.5 py-1 rounded-full font-semibold
                                            @if($request->priority === 'urgent')
                                                border border-red-800 text-red-800 uppercase
                                            @else
                                                border border-blue-800 text-blue-800 uppercase
                                            @endif">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                        <span class="text-gray-400">•</span>
                                        <span>Submitted {{ $request->submitted_at ? $request->submitted_at->format('M d, Y') : '-' }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span>Needed {{ $request->needed_by_date ? $request->needed_by_date->format('M d, Y') : '-' }}</span>
                                        @if($request->needed_by_date && $request->needed_by_date < now() && $request->status !== 'fulfilled' && $request->status !== 'denied')
                                            <span class="inline-block px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded font-semibold">Overdue</span>
                                        @endif
                                    </div>

                                    <div class="mt-4 flex items-center justify-end">
                                        <a href="{{ route('admin.inventory-requests.show', $request) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-semibold text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="hidden lg:block overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
                            <table class="w-full min-w-max md:min-w-full">
                                <thead class="bg-gray-100 border-b border-gray-200 sticky top-0">
                                    <tr>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Request #</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Employee</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Title</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Submitted</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Needed By</th>
                                        <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-700 whitespace-nowrap">Priority</th>
                                        <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-4 md:px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $request->user->name }}</td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $request->request_title }}</td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $request->submitted_at ? $request->submitted_at->format('M d, Y') : '-' }}</td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $request->needed_by_date ? $request->needed_by_date->format('M d, Y') : '-' }}
                                            @if($request->needed_by_date && $request->needed_by_date < now() && $request->status !== 'fulfilled' && $request->status !== 'denied')
                                                <span class="ml-2 inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded font-semibold">Overdue</span>
                                            @endif
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-center text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                @if($request->priority === 'urgent')
                                                    border border-red-800 text-red-800 uppercase
                                                @else
                                                    border border-blue-800 text-blue-800 uppercase
                                                @endif">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-center text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                                @if($request->status === 'submitted')
                                                    border border-yellow-800 text-yellow-800 uppercase
                                                @elseif($request->status === 'approved')
                                                    border border-blue-800 text-blue-800 uppercase
                                                @elseif($request->status === 'denied')
                                                    border border-red-800 text-red-800 uppercase
                                                @elseif($request->status === 'fulfilled')
                                                    border border-green-800 text-green-800 uppercase
                                                @else
                                                    border border-gray-800 text-gray-800 uppercase
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm space-x-2 whitespace-nowrap">
                                            <a href="{{ route('admin.inventory-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $requests->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No inventory requests found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
