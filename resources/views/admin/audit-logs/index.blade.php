<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <x-employee-header 
                    title="Audit Logs" 
                    subtitle="View all actions performed in your organization"
                />

                <!-- Main Content -->
                <div class="p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Audit Logs</h1>
            <p class="text-slate-600 mt-1">View all actions performed in your organization</p>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-lg shadow-md border border-slate-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Filter Logs</h2>
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Date Range -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">User</label>
                    <select 
                        name="user_id" 
                        id="user_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label for="action" class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                    <select 
                        name="action" 
                        id="action"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">All Actions</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}" {{ $filters['action'] == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Entity Type Filter -->
                <div>
                    <label for="entity_type" class="block text-sm font-medium text-slate-700 mb-2">Entity Type</label>
                    <select 
                        name="entity_type" 
                        id="entity_type"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">All Entities</option>
                        @foreach($entityTypes as $key => $label)
                            <option value="{{ $key }}" {{ $filters['entity_type'] == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end gap-2">
                    <button 
                        type="submit"
                        class="w-full px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        Apply Filters
                    </button>
                    <a 
                        href="{{ route('admin.audit-logs.index') }}"
                        class="w-full px-6 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition-colors text-center"
                    >
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white rounded-lg shadow-md border border-slate-200 overflow-hidden">
            @if($auditLogs->count() > 0)
                <!-- Mobile/Tablet Cards -->
                <div class="lg:hidden p-4 space-y-3">
                    @foreach($auditLogs as $log)
                        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs text-slate-500">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-1">{{ $log->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-slate-500">{{ $log->user_role ?? '-' }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $log->action_label }}
                                </span>
                            </div>

                            <div class="mt-3 text-sm text-slate-600">
                                <p class="font-medium text-slate-700">
                                    {{ $log->entity_type }}
                                    @if($log->entity_id)
                                        <span class="text-slate-400">#{{ $log->entity_id }}</span>
                                    @endif
                                </p>
                                <p class="text-slate-600 mt-1">{{ Str::limit($log->description ?? '-', 120) }}</p>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-600">
                                <span class="inline-flex items-center px-2 py-1 rounded font-mono bg-slate-100 text-slate-700">
                                    {{ $log->method ?? '-' }}
                                </span>
                                <span class="text-slate-400">•</span>
                                <span class="font-mono">{{ $log->ip_address ?? '-' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date & Time</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Action</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Entity</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Description</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Method</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($auditLogs as $log)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                        {{ $log->created_at->format('M d, Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-medium text-slate-900">{{ $log->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user_role ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $log->action_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <div>{{ $log->entity_type }}</div>
                                        @if($log->entity_id)
                                            <div class="text-xs text-slate-500">ID: {{ $log->entity_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="{{ $log->description }}">
                                        {{ $log->description ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-mono bg-slate-100 text-slate-700">
                                            {{ $log->method ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-mono text-xs">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $auditLogs->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-slate-900">No audit logs found</h3>
                    <p class="mt-1 text-slate-600">Try adjusting your filters or check back later.</p>
                </div>
            @endif
        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
