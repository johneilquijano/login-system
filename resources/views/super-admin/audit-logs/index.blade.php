<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-super-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <x-employee-header 
                    title="System Audit Logs" 
                    subtitle="View all actions performed across all organizations"
                />

                <!-- Main Content -->
                <div class="p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">System Audit Logs</h1>
            <p class="text-slate-600 mt-1">View all actions performed across all organizations</p>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-lg shadow-md border border-slate-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Filter Logs</h2>
            <form method="GET" action="{{ route('super-admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Organization Filter -->
                <div>
                    <label for="org_id" class="block text-sm font-medium text-slate-700 mb-2">Organization</label>
                    <select 
                        name="org_id" 
                        id="org_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">All Organizations</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ $filters['org_id'] == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                <div class="flex items-end gap-2 md:col-span-2 lg:col-span-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        Apply Filters
                    </button>
                    <a 
                        href="{{ route('super-admin.audit-logs.index') }}"
                        class="flex-1 px-6 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition-colors text-center"
                    >
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white rounded-lg shadow-md border border-slate-200 overflow-hidden">
            @if($auditLogs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date & Time</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Organization</th>
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
                                        <div class="font-medium text-slate-900">
                                            {{ $log->organization?->name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-slate-500">Org ID: {{ $log->org_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-medium text-slate-900">{{ $log->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user_role ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $log->action_color }}">
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
