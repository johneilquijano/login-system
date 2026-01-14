<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            @if(Auth::user()->role === 'admin')
                <x-admin-sidebar />
            @else
                <x-employee-sidebar />
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="My Feedback" 
                    subtitle="Track the feedback you've submitted" 
                />

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Statistics Row -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm font-medium">Total Submitted</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm font-medium">New</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['new'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm font-medium">In Review</p>
                            <p class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['in_review'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm font-medium">Fixed</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['fixed'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm font-medium">Ignored</p>
                            <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['ignored'] }}</p>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                        <form action="{{ route('feedback.my') }}" method="GET" class="flex flex-wrap gap-4">
                            <!-- Status Filter -->
                            <div class="flex-1 min-w-max">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-600 focus:ring-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="fixed" {{ request('status') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="ignored" {{ request('status') === 'ignored' ? 'selected' : '' }}>Ignored</option>
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div class="flex-1 min-w-max">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-600 focus:ring-indigo-500">
                                    <option value="">All Types</option>
                                    <option value="bug" {{ request('category') === 'bug' ? 'selected' : '' }}>Bug</option>
                                    <option value="ux" {{ request('category') === 'ux' ? 'selected' : '' }}>UX/UI</option>
                                    <option value="feature" {{ request('category') === 'feature' ? 'selected' : '' }}>Feature Request</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Feedback Table -->
                    @if($feedbacks->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Message</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Priority</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($feedbacks as $feedback)
                                        <tr class="hover:bg-gray-50 transition">
                                            <!-- Type Badge -->
                                            <td class="px-6 py-4">
                                                @php
                                                    $typeColors = [
                                                        'bug' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                                        'ux' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                        'feature' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                                    ];
                                                    $colors = $typeColors[$feedback->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                    {{ ucfirst($feedback->category) }}
                                                </span>
                                            </td>

                                            <!-- Message Preview -->
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-900 max-w-xs truncate">{{ Str::limit($feedback->message, 50) }}</p>
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColors = [
                                                        'new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                        'in_review' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800'],
                                                        'fixed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                                        'ignored' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                                    ];
                                                    $colors = $statusColors[$feedback->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                    {{ $feedback->status_label }}
                                                </span>
                                            </td>

                                            <!-- Priority -->
                                            <td class="px-6 py-4">
                                                @if($feedback->severity)
                                                    @php
                                                        $severityColors = [
                                                            'low' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                                            'medium' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800'],
                                                            'high' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                                        ];
                                                        $colors = $severityColors[$feedback->severity] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                        {{ ucfirst($feedback->severity) }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-500">—</span>
                                                @endif
                                            </td>

                                            <!-- Created Date -->
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-600">{{ $feedback->created_at->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $feedback->created_at->format('H:i') }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $feedbacks->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2.25A4.125 4.125 0 0014.25 21h7.5A4.125 4.125 0 0026 16.875v-2.25m-12-4.5V14m0-2.5a6 6 0 117.022 5.961L20 13m-6-2.5h6"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mt-4">No feedback yet</h3>
                            <p class="text-gray-600 mt-2">You haven't submitted any feedback. Use the "Report Issue" button to share your thoughts.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layout>
