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
                            <h2 class="text-2xl font-bold text-gray-900">Tool Checkout</h2>
                            <p class="text-sm text-gray-600 mt-1">View your checked-out tools and equipment</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }} Account</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Info Banner -->
                    <div class="mb-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
                        <h1 class="text-3xl font-bold">Your Tool Checkouts</h1>
                        <p class="mt-2 text-blue-100">{{ $checkouts->total() }} tool(s) in your records</p>
                    </div>

                    <!-- Tools Table -->
                    @if($checkouts->count() > 0)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b" style="border-bottom-color: #ccc;">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tool Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Serial Number</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Checked Out</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checkouts as $checkout)
                                <tr class="border-b hover:bg-gray-50 transition" style="border-bottom-color: #ccc;">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $checkout->tool_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $checkout->serial_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($checkout->status === 'checked_out')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($checkout->status === 'returned')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $checkout->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $checkout->checked_out_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($checkout->due_date)
                                            <span class="@if($checkout->due_date->isPast()) text-red-600 font-semibold @endif">
                                                {{ $checkout->due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $checkouts->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No tool checkouts yet</p>
                        <p class="text-gray-500 text-sm mt-1">Your tool checkouts will appear here</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
