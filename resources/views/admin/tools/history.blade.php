<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between px-8 py-5">
                        <div>
                            <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">Tools History</h2>
                            <p class="text-sm text-gray-600 mt-1">All check-in/check-out transactions</p>
                        </div>
                        <a href="{{ route('admin.tools.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                            ← Back to Inventory
                        </a>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Advanced Filters -->
                    <div class="mb-8 bg-white rounded-2xl border border-gray-200 p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                        <form method="GET" action="{{ route('admin.tools.history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Search tool or employee..." 
                                    value="{{ $search }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                            <!-- Tool Filter -->
                            <select name="tool" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Tools</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->id }}" {{ $toolFilter == $tool->id ? 'selected' : '' }}>{{ $tool->name }}</option>
                                @endforeach
                            </select>

                            <!-- Employee Filter -->
                            <select name="employee" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $employeeFilter == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                                @endforeach
                            </select>

                            <!-- Category Filter -->
                            <select name="category" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select name="status" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Statuses</option>
                                <option value="open" {{ $statusFilter === 'open' ? 'selected' : '' }}>Open (Not Returned)</option>
                                <option value="returned" {{ $statusFilter === 'returned' ? 'selected' : '' }}>Returned</option>
                                <option value="overdue" {{ $statusFilter === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>

                            <!-- Date From -->
                            <input 
                                type="date" 
                                name="date_from" 
                                value="{{ $dateFrom }}"
                                class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">

                            <!-- Date To -->
                            <input 
                                type="date" 
                                name="date_to" 
                                value="{{ $dateTo }}"
                                class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300">
                                    Filter
                                </button>
                                <a href="{{ route('admin.tools.history') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- History Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Tool</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Employee</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Action</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Checked Out</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Returned</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Due Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Duration</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($checkouts as $checkout)
                                    <tr class="hover:bg-gray-50 transition-all">
                                        <!-- Tool -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                                    @if($checkout->tool && $checkout->tool->image_path)
                                                        <img src="{{ asset($checkout->tool->image_path) }}" alt="{{ $checkout->tool_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $checkout->tool_name ?? ($checkout->tool->name ?? 'Unknown') }}</p>
                                                    <p class="text-xs text-gray-500">{{ $checkout->tool->category ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Employee -->
                                        <td class="px-6 py-4">
                                            @if($checkout->user_id)
                                                <p class="text-sm font-semibold text-gray-900">{{ $checkout->user->full_name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500">{{ $checkout->user->email ?? 'N/A' }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">N/A</p>
                                            @endif
                                        </td>

                                        <!-- Action -->
                                        <td class="px-6 py-4">
                                            @php
                                                $actionType = $checkout->action_type;
                                                // Debug log
                                                if ($actionType === 'maintenance_off') {
                                                    \Log::info('Found maintenance_off action', ['tool_id' => $checkout->tool_id]);
                                                }
                                            @endphp
                                            @if($actionType === 'maintenance_on')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Set to Maintenance
                                                </span>
                                                @if($checkout->maintenance_reason)
                                                    <p class="text-xs text-gray-600 mt-2">Reason: {{ $checkout->maintenance_reason }}</p>
                                                @endif
                                            @elseif($actionType === 'maintenance_off')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    Set Available
                                                </span>
                                            @elseif($checkout->notes && str_contains($checkout->notes, 'Admin Force Return'))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Forced Return
                                                </span>
                                            @elseif($checkout->returned_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Returned
                                                </span>
                                            @elseif($actionType === 'checkout' || !$actionType)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Checked Out
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst(str_replace('_', ' ', $actionType ?? 'unknown')) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Checked Out -->
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('M d, Y') : $checkout->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('H:i A') : $checkout->created_at->format('H:i A') }}</p>
                                        </td>

                                        <!-- Returned -->
                                        <td class="px-6 py-4">
                                            @if($checkout->returned_at)
                                                <p class="text-sm text-gray-900">{{ $checkout->returned_at->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $checkout->returned_at->format('H:i A') }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">Not yet returned</p>
                                            @endif
                                        </td>

                                        <!-- Due Date -->
                                        <td class="px-6 py-4">
                                            @php
                                                $dueDate = $checkout->return_due_date;
                                                // Ensure it's a Carbon instance
                                                if ($dueDate && !($dueDate instanceof \Carbon\Carbon)) {
                                                    $dueDate = \Carbon\Carbon::parse($dueDate);
                                                }
                                                $isOverdue = $dueDate && $dueDate->isPast() && !$checkout->returned_at;
                                            @endphp
                                            @if($dueDate)
                                                <p class="text-sm {{ $isOverdue ? 'text-red-900 font-semibold' : 'text-gray-900' }}">{{ $dueDate->format('M d, Y') }}</p>
                                                @if($isOverdue)
                                                    <p class="text-xs text-red-600">OVERDUE</p>
                                                @endif
                                            @else
                                                <p class="text-sm text-gray-500">—</p>
                                            @endif
                                        </td>

                                        <!-- Duration -->
                                        <td class="px-6 py-4">
                                            @if($checkout->returned_at && $checkout->checked_out_at)
                                                @php
                                                    $days = $checkout->checked_out_at->diffInDays($checkout->returned_at);
                                                    $hours = $checkout->checked_out_at->diffInHours($checkout->returned_at) % 24;
                                                @endphp
                                                <p class="text-sm text-gray-900">{{ $days }}d {{ $hours }}h</p>
                                            @else
                                                @php
                                                    $days = $checkout->checked_out_at ? $checkout->checked_out_at->diffInDays(now()) : 0;
                                                    $hours = $checkout->checked_out_at ? $checkout->checked_out_at->diffInHours(now()) % 24 : 0;
                                                @endphp
                                                <p class="text-sm text-gray-900">{{ $days }}d {{ $hours }}h</p>
                                                <p class="text-xs text-gray-500">Ongoing</p>
                                            @endif
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            @if($checkout->returned_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    Completed
                                                </span>
                                            @elseif($checkout->return_due_date && $checkout->return_due_date < now())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Overdue
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Open
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-900 font-semibold text-lg">No transactions found</p>
                                            <p class="text-gray-600 text-sm mt-2">Adjust filters to view transaction history</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($checkouts->hasPages())
                    <div class="mt-8">
                        {{ $checkouts->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
