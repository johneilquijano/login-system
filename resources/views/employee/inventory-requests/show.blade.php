<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Request Details" 
                    subtitle="Request #{{ str_pad($inventoryRequest->id, 4, '0', STR_PAD_LEFT) }}" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                    @endif

                    <!-- Back Button -->
                    <a href="{{ route('inventory-requests.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold mb-4 md:mb-6 text-sm md:text-base">
                        <svg class="w-4 md:w-5 h-4 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Requests
                    </a>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                        <!-- Main Content -->
                        <div class="col-span-1 lg:col-span-2 space-y-4 md:space-y-6">
                            <!-- Request Information Card -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6 gap-3">
                                    <h3 class="text-lg md:text-xl font-bold text-gray-900">{{ $inventoryRequest->request_title }}</h3>
                                    <span class="inline-block px-3 md:px-4 py-1 md:py-2 rounded-full text-xs md:text-sm font-semibold w-fit
                                        @if($inventoryRequest->status === 'draft')
                                            bg-gray-100 text-gray-800
                                        @elseif($inventoryRequest->status === 'submitted')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($inventoryRequest->status === 'approved')
                                            bg-green-100 text-green-800
                                        @elseif($inventoryRequest->status === 'denied')
                                            bg-red-100 text-red-800
                                        @elseif($inventoryRequest->status === 'fulfilled')
                                            bg-indigo-100 text-indigo-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $inventoryRequest->status)) }}
                                    </span>
                                </div>

                                <!-- Request Details Grid -->
                                <div class="grid grid-cols-2 gap-3 md:gap-6 mb-4 md:mb-6">
                                    <div>
                                        <p class="text-xs md:text-sm text-gray-600 mb-1">Priority</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900">
                                            <span class="inline-block px-2 md:px-3 py-1 rounded-full text-xs md:text-sm
                                                @if($inventoryRequest->priority === 'urgent')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-blue-100 text-blue-800
                                                @endif">
                                                {{ ucfirst($inventoryRequest->priority) }}
                                            </span>
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs md:text-sm text-gray-600 mb-1">Needed By</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900 text-sm md:text-base">
                                            {{ $inventoryRequest->needed_by_date ? $inventoryRequest->needed_by_date->format('M d, Y') : 'Not specified' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs md:text-sm text-gray-600 mb-1">Submitted</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900 text-sm md:text-base">
                                            {{ $inventoryRequest->submitted_at ? $inventoryRequest->submitted_at->format('M d, Y') : 'Not submitted' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs md:text-sm text-gray-600 mb-1">Total Items</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900">{{ $inventoryRequest->items->count() }}</p>
                                    </div>
                                </div>

                                <!-- Reason -->
                                @if($inventoryRequest->reason)
                                <div class="border-t border-gray-200 pt-4 md:pt-6">
                                    <p class="text-xs md:text-sm text-gray-600 mb-2">Reason</p>
                                    <p class="text-gray-900 text-sm md:text-base">{{ $inventoryRequest->reason }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Requested Items Card -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-3 md:mb-4">Requested Items</h3>
                                
                                @if($inventoryRequest->items->count() > 0)
                                <div class="overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
                                    <table class="w-full min-w-max md:min-w-full">
                                        <thead class="bg-gray-100 border-b border-gray-200 sticky top-0">
                                            <tr>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Item</th>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Job Needed For</th>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Model</th>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-center text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Qty</th>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-center text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Fulfilled</th>
                                                <th class="px-3 md:px-4 py-2 md:py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventoryRequest->items as $item)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-medium text-gray-900 whitespace-nowrap">{{ $item->item_name }}</td>
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $item->job_number ?? '-' }}</td>
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $item->model_number ?? '-' }}</td>
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm text-gray-900 font-semibold text-center whitespace-nowrap">{{ $item->quantity }}</td>
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm text-gray-900 font-semibold text-center whitespace-nowrap">
                                                    {{ $item->fulfilled_quantity ?? 0 }}
                                                    @if($item->fulfilled_quantity < $item->quantity)
                                                        <span class="text-xs text-orange-600 block">({{ $item->getRemainingQuantity() }} pending)</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $item->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p class="text-gray-600 text-center py-4 text-sm md:text-base">No items in this request</p>
                                @endif
                            </div>

                            <!-- Admin Notes Card (if denied or partially fulfilled) -->
                            @if($inventoryRequest->admin_notes)
                            <div class="bg-blue-50 rounded-lg shadow-md border border-blue-200 p-4 md:p-6">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-3 md:mb-4">Admin Notes</h3>
                                <p class="text-gray-900 text-sm md:text-base">{{ $inventoryRequest->admin_notes }}</p>
                            </div>
                            @endif

                            <!-- Denial Reason (if denied) -->
                            @if($inventoryRequest->status === 'denied' && $inventoryRequest->denied_reason)
                            <div class="bg-red-50 rounded-lg shadow-md border border-red-200 p-4 md:p-6">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-3 md:mb-4">Reason for Denial</h3>
                                <p class="text-gray-900 text-sm md:text-base">{{ $inventoryRequest->denied_reason }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Sidebar: Status Timeline & Actions -->
                        <div class="col-span-1 space-y-4 md:space-y-6">
                            <!-- Status Timeline -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-4 md:mb-6">Timeline</h3>
                                
                                <div class="space-y-3 md:space-y-4">
                                    <!-- Created -->
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-blue-600 rounded-full"></div>
                                            <div class="w-0.5 h-6 md:h-8 bg-gray-300"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Created</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Submitted -->
                                    @if($inventoryRequest->submitted_at)
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-yellow-600 rounded-full"></div>
                                            <div class="w-0.5 h-6 md:h-8 bg-gray-300"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Submitted</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->submitted_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Approved -->
                                    @if($inventoryRequest->approved_at)
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-green-600 rounded-full"></div>
                                            <div class="w-0.5 h-6 md:h-8 bg-gray-300"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Approved</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->approved_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-600">By: {{ $inventoryRequest->approver?->name ?? 'Unknown' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Denied -->
                                    @if($inventoryRequest->denied_at)
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-red-600 rounded-full"></div>
                                            <div class="w-0.5 h-6 md:h-8 bg-gray-300"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Denied</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->denied_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-600">By: {{ $inventoryRequest->denier?->name ?? 'Unknown' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Fulfilled -->
                                    @if($inventoryRequest->fulfilled_at)
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-indigo-600 rounded-full"></div>
                                            <div class="w-0.5 h-6 md:h-8 bg-gray-300"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Fulfilled</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->fulfilled_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Cancelled -->
                                    @if($inventoryRequest->cancelled_at)
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 md:w-4 md:h-4 bg-gray-600 rounded-full"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-sm font-semibold text-gray-900">Cancelled</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->cancelled_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 mb-3 md:mb-4">Actions</h3>
                                
                                <div class="space-y-2 md:space-y-3">
                                    <!-- Edit (Draft only) -->
                                    @if($inventoryRequest->status === 'draft')
                                    <a href="{{ route('inventory-requests.edit', $inventoryRequest) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 md:px-4 rounded-lg transition text-xs md:text-sm">
                                        Edit Request
                                    </a>
                                    @endif

                                    <!-- Submit (Draft only) -->
                                    @if($inventoryRequest->status === 'draft')
                                    <form method="POST" action="{{ route('inventory-requests.submit', $inventoryRequest) }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 md:px-4 rounded-lg transition text-xs md:text-sm">
                                            Submit Request
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Cancel (Draft/Submitted only) -->
                                    @if(in_array($inventoryRequest->status, ['draft', 'submitted']))
                                    <form method="POST" action="{{ route('inventory-requests.cancel', $inventoryRequest) }}" onsubmit="return confirm('Are you sure you want to cancel this request?');" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-3 md:px-4 rounded-lg transition text-xs md:text-sm">
                                            Cancel Request
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Back to List -->
                                    <a href="{{ route('inventory-requests.index') }}" class="block w-full text-center border border-gray-300 text-gray-900 font-semibold py-2 px-3 md:px-4 rounded-lg hover:bg-gray-50 transition text-xs md:text-sm">
                                        Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
