<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-col lg:flex-row h-screen">
            @if(Auth::user()->is_super_admin)
                <x-super-admin-sidebar />
            @else
                <x-admin-sidebar />
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto flex flex-col">
                <!-- Header -->
                @if(Auth::user()->is_super_admin)
                    <x-super-admin-header title="Feedback Inbox" />
                @else
                    <x-employee-header 
                        title="Feedback Inbox" 
                        subtitle="Review and manage user feedback and bug reports" 
                    />
                @endif

                <!-- Main Content -->
                <div class="p-4 md:p-6 flex-1">
                    <!-- Statistics -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wider font-semibold mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <p class="text-xs text-yellow-700 uppercase tracking-wider font-semibold mb-1">New</p>
                            <p class="text-3xl font-bold text-yellow-900">{{ $stats['new'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <p class="text-xs text-blue-700 uppercase tracking-wider font-semibold mb-1">In Review</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $stats['in_review'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <p class="text-xs text-green-700 uppercase tracking-wider font-semibold mb-1">Fixed</p>
                            <p class="text-3xl font-bold text-green-900">{{ $stats['fixed'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <p class="text-xs text-gray-700 uppercase tracking-wider font-semibold mb-1">Ignored</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['ignored'] }}</p>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Filters</h3>
                        
                        <form method="GET" action="{{ route('super-admin.feedback.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">All</option>
                                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="fixed" {{ request('status') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="ignored" {{ request('status') === 'ignored' ? 'selected' : '' }}>Ignored</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                                <select name="category" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">All</option>
                                    <option value="bug" {{ request('category') === 'bug' ? 'selected' : '' }}>Bug</option>
                                    <option value="ux" {{ request('category') === 'ux' ? 'selected' : '' }}>UX</option>
                                    <option value="feature" {{ request('category') === 'feature' ? 'selected' : '' }}>Feature</option>
                                </select>
                            </div>

                            <!-- Reporter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Reporter</label>
                                <select name="reporter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">All</option>
                                    @foreach($reporters as $reporter)
                                        <option value="{{ $reporter->id }}" {{ request('reporter') == $reporter->id ? 'selected' : '' }}>{{ $reporter->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Page Search -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Page</label>
                                <input 
                                    type="text" 
                                    name="page_search" 
                                    id="pageSearchInput"
                                    value="{{ request('page_search') }}"
                                    placeholder="Search page..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <!-- Clear Filters -->
                            <div>
                                <a href="{{ route('super-admin.feedback.index') }}" class="block w-full px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg transition text-center text-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <script>
                        // Real-time page search filter with debouncing
                        let filterTimeout;
                        const pageSearchInput = document.getElementById('pageSearchInput');
                        const filterForm = pageSearchInput.closest('form');

                        if (pageSearchInput) {
                            pageSearchInput.addEventListener('input', () => {
                                clearTimeout(filterTimeout);
                                filterTimeout = setTimeout(() => {
                                    filterForm.submit();
                                }, 500); // Submit after 500ms of no typing
                            });
                        }
                    </script>

                    <!-- Feedback Table -->
                    @if($feedbacks->count() > 0)
                    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                        <div class="lg:hidden divide-y divide-gray-200">
                            @foreach($feedbacks as $feedback)
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $feedback->category_label }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $feedback->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($feedback->status === 'new')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($feedback->status === 'in_review')
                                            bg-blue-100 text-blue-800
                                        @elseif($feedback->status === 'fixed')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $feedback->status_label }}
                                    </span>
                                </div>

                                <div class="mt-3 text-xs text-gray-600">
                                    <p class="text-gray-900">{{ Str::limit($feedback->message, 90) }}</p>
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                        {{ $feedback->category_label }}
                                    </span>
                                    @if($feedback->severity)
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($feedback->severity === 'high')
                                            bg-red-100 text-red-800
                                        @elseif($feedback->severity === 'medium')
                                            bg-orange-100 text-orange-800
                                        @else
                                            bg-green-100 text-green-800
                                        @endif">
                                        {{ $feedback->severity_label }}
                                    </span>
                                    @endif
                                    <span class="text-gray-500">{{ $feedback->user->name }} ({{ $feedback->user_role }})</span>
                                </div>

                                <div class="mt-3 text-xs text-gray-600">
                                    <p class="font-semibold text-gray-700">Page</p>
                                    <p class="text-gray-500 truncate">{{ $feedback->url_path }}</p>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('super-admin.feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
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
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Category</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Severity</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Message</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Page</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Reporter</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Submitted</th>
                                        <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-700 whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($feedback->status === 'new')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($feedback->status === 'in_review')
                                                bg-blue-100 text-blue-800
                                            @elseif($feedback->status === 'fixed')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $feedback->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            {{ $feedback->category_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        @if($feedback->severity)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($feedback->severity === 'high')
                                                bg-red-100 text-red-800
                                            @elseif($feedback->severity === 'medium')
                                                bg-orange-100 text-orange-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif">
                                            {{ $feedback->severity_label }}
                                        </span>
                                        @else
                                        <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-900 max-w-xs truncate">{{ $feedback->message }}</p>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-600 max-w-xs truncate">{{ $feedback->url_path }}</p>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-900">{{ $feedback->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $feedback->user_role }}</p>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-600">{{ $feedback->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $feedback->created_at->format('H:i') }}</p>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('super-admin.feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
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
                    <div class="mt-8 px-4 md:px-0">
                        {{ $feedbacks->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No feedback found</p>
                        <p class="text-xs text-gray-500 mt-2 lg:hidden">You're all caught up.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
