<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Ordering Tasks" 
                    subtitle="Manage inventory ordering tasks"
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Tabs -->
                    <div class="mb-8 border-b border-gray-200">
                        <div class="flex gap-8">
                            <a href="{{ route('admin.ordering-tasks.index', ['status' => 'open']) }}"
                               class="px-4 py-3 font-semibold transition-all {{ request('status', 'open') === 'open' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                                Open Tasks
                            </a>
                            <a href="{{ route('admin.ordering-tasks.index', ['status' => 'completed']) }}"
                               class="px-4 py-3 font-semibold transition-all {{ request('status') === 'completed' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                                Completed
                            </a>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="space-y-4">
                        @forelse($tasks as $task)
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6 hover:shadow-xl transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Ordering Task #{{ $task->id }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Created {{ $task->opened_at->format('M d, Y \a\t H:i') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.ordering-tasks.show', $task) }}"
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all">
                                        View Details
                                    </a>
                                </div>

                                <!-- Status Counts -->
                                <div class="grid grid-cols-4 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 mb-1">Total Items</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $taskCounts[$task->id]['total'] ?? 0 }}</p>
                                    </div>
                                    <div class="bg-yellow-50 rounded-lg p-4">
                                        <p class="text-sm text-yellow-700 mb-1">Pending</p>
                                        <p class="text-2xl font-bold text-yellow-900">{{ $taskCounts[$task->id]['pending'] ?? 0 }}</p>
                                    </div>
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <p class="text-sm text-blue-700 mb-1">Ordered</p>
                                        <p class="text-2xl font-bold text-blue-900">{{ $taskCounts[$task->id]['ordered'] ?? 0 }}</p>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <p class="text-sm text-green-700 mb-1">Received</p>
                                        <p class="text-2xl font-bold text-green-900">{{ $taskCounts[$task->id]['received'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-16 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-900 font-semibold text-lg">No ordering tasks</p>
                                <p class="text-gray-600 text-sm mt-2">{{ request('status') === 'completed' ? 'No completed tasks yet' : 'Approved inventory requests will appear here' }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($tasks->hasPages())
                    <div class="mt-8">
                        {{ $tasks->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
