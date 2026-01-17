<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Request Review" 
                    subtitle="Request #{{ str_pad($inventoryRequest->id, 4, '0', STR_PAD_LEFT) }} from {{ $inventoryRequest->user->name }}" 
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
                    <a href="{{ route('admin.inventory-requests.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Requests
                    </a>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Request Information Card -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                                    <h3 class="text-lg md:text-xl font-bold text-gray-900">{{ $inventoryRequest->request_title }}</h3>
                                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap
                                        @if($inventoryRequest->status === 'submitted')
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
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Employee</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900">{{ $inventoryRequest->user->name }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Priority</p>
                                        <p class="text-base md:text-lg font-semibold">
                                            <span class="inline-block px-3 py-1 rounded-full text-sm
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
                                        <p class="text-sm text-gray-600 mb-1">Submitted</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900 break-words">
                                            {{ $inventoryRequest->submitted_at ? $inventoryRequest->submitted_at->format('M d, Y H:i') : 'Not submitted' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Needed By</p>
                                        <p class="text-base md:text-lg font-semibold text-gray-900 break-words">
                                            {{ $inventoryRequest->needed_by_date ? $inventoryRequest->needed_by_date->format('M d, Y') : 'Not specified' }}
                                            @if($inventoryRequest->needed_by_date && $inventoryRequest->needed_by_date < now())
                                                <span class="ml-2 inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded font-semibold">Overdue</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Reason -->
                                @if($inventoryRequest->reason)
                                <div class="border-t border-gray-200 pt-6">
                                    <p class="text-sm text-gray-600 mb-2">Reason</p>
                                    <p class="text-gray-900 break-words">{{ $inventoryRequest->reason }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Requested Items Card -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Requested Items</h3>
                                
                                @if($inventoryRequest->items->count() > 0)
                                <div class="overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
                                    <table class="w-full min-w-max md:min-w-full">
                                        <thead class="bg-gray-100 border-b border-gray-200 sticky top-0">
                                            <tr>
                                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Item</th>
                                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Job Needed For</th>
                                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Model</th>
                                                <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-700 whitespace-nowrap">Qty</th>
                                                <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-700 whitespace-nowrap">Fulfilled</th>
                                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventoryRequest->items as $item)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-4 md:px-6 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $item->item_name }}</td>
                                                <td class="px-4 md:px-6 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $item->job_number ?? '-' }}</td>
                                                <td class="px-4 md:px-6 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $item->model_number ?? '-' }}</td>
                                                <td class="px-4 md:px-6 py-3 text-sm text-gray-900 font-semibold text-center whitespace-nowrap">{{ $item->quantity }}</td>
                                                <td class="px-4 md:px-6 py-3 text-sm text-gray-900 font-semibold text-center whitespace-nowrap">{{ $item->fulfilled_quantity ?? 0 }}</td>
                                                <td class="px-4 md:px-6 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $item->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p class="text-gray-600 text-center py-4">No items in this request</p>
                                @endif
                            </div>

                            <!-- Admin Notes Card -->
                            @if($inventoryRequest->admin_notes)
                            <div class="bg-blue-50 rounded-lg shadow-md border border-blue-200 p-4 md:p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Notes</h3>
                                <p class="text-gray-900 whitespace-pre-wrap break-words">{{ $inventoryRequest->admin_notes }}</p>
                            </div>
                            @endif

                            <!-- Denial Reason (if denied) -->
                            @if($inventoryRequest->status === 'denied' && $inventoryRequest->denied_reason)
                            <div class="bg-red-50 rounded-lg shadow-md border border-red-200 p-4 md:p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Reason for Denial</h3>
                                <p class="text-gray-900 break-words">{{ $inventoryRequest->denied_reason }}</p>
                            </div>
                            @endif

                            <!-- Timeline -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Request Timeline</h3>
                                <div class="space-y-4">
                            <!-- Denial Reason (if denied) -->
                            @if($inventoryRequest->status === 'denied' && $inventoryRequest->denied_reason)
                            <div class="bg-red-50 rounded-lg shadow-md border border-red-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Reason for Denial</h3>
                                <p class="text-gray-900">{{ $inventoryRequest->denied_reason }}</p>
                            </div>
                            @endif

                            <!-- Timeline -->
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Request Timeline</h3>
                                <div class="space-y-4">
                                    <!-- Created -->
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            @if($inventoryRequest->status !== 'draft')
                                            <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                            @endif
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Created</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>

                                    <!-- Submitted -->
                                    @if($inventoryRequest->status !== 'draft')
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            @if($inventoryRequest->status !== 'submitted')
                                            <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                            @endif
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Submitted</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->submitted_at ? $inventoryRequest->submitted_at->format('M d, Y H:i') : 'Pending' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Approved -->
                                    @if(in_array($inventoryRequest->status, ['approved', 'fulfilled']))
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            @if($inventoryRequest->status === 'fulfilled')
                                            <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                            @endif
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Approved</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->approved_at ? $inventoryRequest->approved_at->format('M d, Y H:i') : 'Pending' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Denied -->
                                    @if($inventoryRequest->status === 'denied')
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Denied</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->denied_at ? $inventoryRequest->denied_at->format('M d, Y H:i') : 'Pending' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Fulfilled -->
                                    @if($inventoryRequest->status === 'fulfilled')
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-indigo-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Fulfilled</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->fulfilled_at ? $inventoryRequest->fulfilled_at->format('M d, Y H:i') : 'Pending' }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Cancelled -->
                                    @if($inventoryRequest->status === 'cancelled')
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-gray-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <p class="text-sm font-semibold text-gray-900">Request Cancelled</p>
                                            <p class="text-xs text-gray-600">{{ $inventoryRequest->cancelled_at ? $inventoryRequest->cancelled_at->format('M d, Y H:i') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar: Actions -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6 lg:sticky lg:top-24">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Actions</h3>
                                
                                <div class="space-y-3">
                                    <!-- Approve (Submitted only) -->
                                    @if($inventoryRequest->status === 'submitted')
                                    <button 
                                        onclick="openApproveModal()"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                    >
                                        Approve Request
                                    </button>
                                    @endif

                                    <!-- Deny (Submitted only) -->
                                    @if($inventoryRequest->status === 'submitted')
                                    <button 
                                        onclick="openDenyModal()"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                    >
                                        Deny Request
                                    </button>
                                    @endif

                                    <!-- Fulfill (Approved only) -->
                                    @if($inventoryRequest->status === 'approved')
                                    <button 
                                        onclick="openFulfillModal()"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                                    >
                                        Fulfill Request
                                    </button>
                                    @endif

                                    <!-- Add Note -->
                                    <button 
                                        onclick="openAddNoteModal()"
                                        class="w-full border border-gray-300 text-gray-900 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition"
                                    >
                                        Add Note
                                    </button>

                                    <!-- Back to List -->
                                    <a href="{{ route('admin.inventory-requests.index') }}" class="block w-full text-center border border-gray-300 text-gray-900 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition">
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

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Approve Request</h3>
                    <button onclick="closeApproveModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.inventory-requests.approve', $inventoryRequest) }}" class="p-6">
                    @csrf
                    <div class="mb-6">
                        <label for="admin_notes" class="block text-sm font-semibold text-gray-900 mb-2">Admin Notes (Optional)</label>
                        <textarea 
                            id="admin_notes" 
                            name="admin_notes" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="Add any notes about this approval..."
                        ></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeApproveModal()" class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition flex-1">
                            Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deny Modal -->
    <div id="denyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Deny Request</h3>
                    <button onclick="closeDenyModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.inventory-requests.deny', $inventoryRequest) }}" class="p-6">
                    @csrf
                    <div class="mb-6">
                        <label for="denied_reason" class="block text-sm font-semibold text-gray-900 mb-2">Reason for Denial *</label>
                        <textarea 
                            id="denied_reason" 
                            name="denied_reason" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            placeholder="Please explain why this request is being denied..."
                            required
                        ></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="admin_notes_deny" class="block text-sm font-semibold text-gray-900 mb-2">Internal Notes (Optional)</label>
                        <textarea 
                            id="admin_notes_deny" 
                            name="admin_notes" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            placeholder="Internal notes for reference..."
                        ></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDenyModal()" class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex-1">
                            Deny
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fulfill Modal -->
    <div id="fulfillModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Fulfill Request</h3>
                    <button onclick="closeFulfillModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.inventory-requests.fulfill', $inventoryRequest) }}" class="p-6">
                    @csrf
                    <h4 class="font-semibold text-gray-900 mb-4">Record Issued Quantities</h4>
                    
                    @foreach($inventoryRequest->items as $item)
                    <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ $item->item_name }} (Requested: {{ $item->quantity }})
                        </label>
                        <input 
                            type="number" 
                            name="fulfilled_quantities[{{ $item->id }}]" 
                            value="{{ $item->fulfilled_quantity ?? $item->quantity }}"
                            min="0"
                            max="{{ $item->quantity }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            required
                        />
                    </div>
                    @endforeach

                    <div class="mb-6">
                        <label for="fulfillment_notes" class="block text-sm font-semibold text-gray-900 mb-2">Fulfillment Notes (Optional)</label>
                        <textarea 
                            id="fulfillment_notes" 
                            name="fulfillment_notes" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Pickup instructions, item conditions, etc..."
                        ></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeFulfillModal()" class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition flex-1">
                            Fulfill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div id="addNoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Add Admin Note</h3>
                    <button onclick="closeAddNoteModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.inventory-requests.addNote', $inventoryRequest) }}" class="p-6">
                    @csrf
                    <div class="mb-6">
                        <label for="note" class="block text-sm font-semibold text-gray-900 mb-2">Note *</label>
                        <textarea 
                            id="note" 
                            name="note" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Add a note to this request..."
                            required
                        ></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeAddNoteModal()" class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex-1">
                            Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal() {
            document.getElementById('approveModal').classList.remove('hidden');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openDenyModal() {
            document.getElementById('denyModal').classList.remove('hidden');
        }
        function closeDenyModal() {
            document.getElementById('denyModal').classList.add('hidden');
        }

        function openFulfillModal() {
            document.getElementById('fulfillModal').classList.remove('hidden');
        }
        function closeFulfillModal() {
            document.getElementById('fulfillModal').classList.add('hidden');
        }

        function openAddNoteModal() {
            document.getElementById('addNoteModal').classList.remove('hidden');
        }
        function closeAddNoteModal() {
            document.getElementById('addNoteModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.id === 'approveModal') closeApproveModal();
            if (event.target.id === 'denyModal') closeDenyModal();
            if (event.target.id === 'fulfillModal') closeFulfillModal();
            if (event.target.id === 'addNoteModal') closeAddNoteModal();
        });
    </script>
</x-layout>
