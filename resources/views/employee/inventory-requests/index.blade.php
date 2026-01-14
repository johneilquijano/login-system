<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Inventory Requests" 
                    subtitle="Manage your inventory requests" 
                />

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex justify-between items-center shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 font-bold text-xl">&times;</button>
                    </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex justify-between items-center shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-800 font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-800 font-bold text-xl">&times;</button>
                    </div>
                    @endif

                    <!-- Top Bar: Search, Filters, New Request Button -->
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <div class="flex-1 flex gap-4">
                            <!-- Search -->
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Search by request title..." 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            
                            <!-- Status Filter -->
                            <select 
                                id="statusFilter"
                                class="px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                                <option value="denied">Denied</option>
                                <option value="fulfilled">Fulfilled</option>
                                <option value="cancelled">Cancelled</option>
                            </select>

                            <!-- Date Filter -->
                            <input 
                                type="date" 
                                id="dateFilter"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <!-- New Request Button -->
                        <button 
                            onclick="openNewRequestModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                        >
                            <span class="mr-2">+</span> New Request
                        </button>
                    </div>

                    <!-- Requests Table/List -->
                    @if($requests->count() > 0)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Request #</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Items</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Priority</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Submitted</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition searchable-row" data-search="{{ strtolower($request->request_title) }}" data-status="{{ $request->status }}" data-date="{{ $request->created_at->format('Y-m-d') }}">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $request->request_title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <button onclick="viewRequestItems({{ $request->id }})" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            {{ $request->items->count() }} item(s)
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($request->priority === 'urgent')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($request->status === 'draft')
                                                bg-gray-100 text-gray-800
                                            @elseif($request->status === 'submitted')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'approved')
                                                bg-green-100 text-green-800
                                            @elseif($request->status === 'denied')
                                                bg-red-100 text-red-800
                                            @elseif($request->status === 'fulfilled')
                                                bg-indigo-100 text-indigo-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $request->submitted_at ? $request->submitted_at->format('M d, Y') : 'Not submitted' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <a href="{{ route('inventory-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View</a>
                                        
                                        @if($request->status === 'draft')
                                            <a href="{{ route('inventory-requests.edit', $request) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Edit</a>
                                        @endif

                                        @if(in_array($request->status, ['draft', 'submitted']))
                                            <form method="POST" action="{{ route('inventory-requests.cancel', $request) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        <p class="text-gray-600 font-medium">No inventory requests yet</p>
                        <button onclick="openNewRequestModal()" class="text-blue-600 hover:text-blue-800 font-semibold mt-2 inline-block">Create your first request</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- New Request Modal -->
    <div id="newRequestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">New Inventory Request</h3>
                    <button onclick="closeNewRequestModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>

                <!-- Modal Content -->
                <form id="newRequestForm" method="POST" action="{{ route('inventory-requests.store') }}" class="p-6">
                    @csrf

                    <!-- Request Details Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h4>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Request Title -->
                            <div class="col-span-2">
                                <label for="request_title" class="block text-sm font-semibold text-gray-900 mb-2">Request Title *</label>
                                <input 
                                    type="text" 
                                    id="request_title" 
                                    name="request_title" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="e.g., Office Equipment Request"
                                    required
                                />
                                <small class="text-gray-600">Briefly describe what you're requesting</small>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">Priority *</label>
                                <select 
                                    id="priority" 
                                    name="priority"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    required
                                >
                                    <option value="">Select priority</option>
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>

                            <!-- Needed By Date -->
                            <div>
                                <label for="needed_by_date" class="block text-sm font-semibold text-gray-900 mb-2">Needed By Date</label>
                                <input 
                                    type="date" 
                                    id="needed_by_date" 
                                    name="needed_by_date"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                />
                            </div>

                            <!-- Reason -->
                            <div class="col-span-2">
                                <label for="reason" class="block text-sm font-semibold text-gray-900 mb-2">Reason</label>
                                <textarea 
                                    id="reason" 
                                    name="reason" 
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Why do you need these items?"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">Items to Request</h4>
                            <button 
                                type="button" 
                                onclick="addRequestItem()"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm"
                            >
                                + Add Item
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            <!-- Item rows will be added here -->
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="radio" 
                                id="status_draft" 
                                name="status" 
                                value="draft" 
                                checked
                                class="w-4 h-4 text-blue-600"
                            />
                            <div>
                                <p class="font-semibold text-gray-900">Save as Draft</p>
                                <p class="text-sm text-gray-600">Continue editing later before submitting</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer mt-3">
                            <input 
                                type="radio" 
                                id="status_submitted" 
                                name="status" 
                                value="submitted"
                                class="w-4 h-4 text-blue-600"
                            />
                            <div>
                                <p class="font-semibold text-gray-900">Submit Request</p>
                                <p class="text-sm text-gray-600">Send to admin for review and approval</p>
                            </div>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 border-t border-gray-200 pt-6">
                        <button 
                            type="button" 
                            onclick="closeNewRequestModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex-1"
                        >
                            Create Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Items Modal -->
    <div id="viewItemsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <!-- Modal Header -->
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Request Items</h3>
                    <button onclick="closeViewItemsModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>

                <!-- Modal Content -->
                <div id="viewItemsContent" class="p-6">
                    <!-- Items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemCount = 0;
        let viewItemsData = null;

        function openNewRequestModal() {
            document.getElementById('newRequestModal').classList.remove('hidden');
            itemCount = 0;
            document.getElementById('itemsContainer').innerHTML = '';
            addRequestItem(); // Add one empty item row
        }

        function closeNewRequestModal() {
            document.getElementById('newRequestModal').classList.add('hidden');
        }

        function addRequestItem() {
            const container = document.getElementById('itemsContainer');
            const itemRow = document.createElement('div');
            itemRow.className = 'p-4 border border-gray-200 rounded-lg bg-gray-50 item-row';
            itemRow.id = `item-${itemCount}`;
            itemRow.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="font-semibold text-gray-900">Item ${itemCount + 1}</h5>
                    <button 
                        type="button" 
                        onclick="removeRequestItem('item-${itemCount}')"
                        class="text-red-600 hover:text-red-800 font-semibold text-sm"
                    >
                        Remove
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Item Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Item Name *</label>
                        <input 
                            type="text" 
                            name="items[${itemCount}][item_name]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., Office Chair, Monitor, etc."
                            required
                        />
                    </div>

                    <!-- Job Needed For -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Job Needed For *</label>
                        <input 
                            type="text" 
                            name="items[${itemCount}][job_number]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., JOB-001 or reference"
                            required
                        />
                    </div>

                    <!-- Model Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Model Number *</label>
                        <input 
                            type="text" 
                            name="items[${itemCount}][model_number]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., MODEL-2024 or model reference"
                            required
                        />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Quantity *</label>
                        <input 
                            type="number" 
                            name="items[${itemCount}][quantity]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            min="1"
                            value="1"
                            required
                        />
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Notes</label>
                        <textarea 
                            name="items[${itemCount}][notes]" 
                            rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Any specifications or preferences..."
                        ></textarea>
                    </div>
                </div>
            `;
            container.appendChild(itemRow);
            itemCount++;
        }

        function removeRequestItem(itemId) {
            const itemRow = document.getElementById(itemId);
            if (itemRow) {
                itemRow.remove();
            }
        }

        function viewRequestItems(requestId) {
            const request = {!! json_encode($requests) !!}.find(r => r.id === requestId);
            if (request && request.items.length > 0) {
                let html = '<table class="w-full"><thead class="bg-gray-100 border-b border-gray-200"><tr><th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Item</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Job</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Model</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Qty</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Notes</th></tr></thead><tbody>';
                
                request.items.forEach(item => {
                    html += `<tr class="border-b border-gray-200 hover:bg-gray-50"><td class="px-4 py-3 text-sm text-gray-900">${item.item_name}</td><td class="px-4 py-3 text-sm text-gray-600">${item.job_number || '-'}</td><td class="px-4 py-3 text-sm text-gray-600">${item.model_number || '-'}</td><td class="px-4 py-3 text-sm text-gray-900 font-semibold">${item.quantity}</td><td class="px-4 py-3 text-sm text-gray-600">${item.notes || '-'}</td></tr>`;
                });
                
                html += '</tbody></table>';
                document.getElementById('viewItemsContent').innerHTML = html;
                document.getElementById('viewItemsModal').classList.remove('hidden');
            }
        }

        function closeViewItemsModal() {
            document.getElementById('viewItemsModal').classList.add('hidden');
        }

        // Search and Filter Functionality
        function filterRequests() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            document.querySelectorAll('.searchable-row').forEach(row => {
                let show = true;

                // Search filter
                if (searchTerm && !row.getAttribute('data-search').includes(searchTerm)) {
                    show = false;
                }

                // Status filter
                if (statusFilter && row.getAttribute('data-status') !== statusFilter) {
                    show = false;
                }

                // Date filter
                if (dateFilter && row.getAttribute('data-date') !== dateFilter) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        document.getElementById('searchInput')?.addEventListener('input', filterRequests);
        document.getElementById('statusFilter')?.addEventListener('change', filterRequests);
        document.getElementById('dateFilter')?.addEventListener('change', filterRequests);

        // Auto-open modal if 'new' parameter is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('new') === 'true') {
            openNewRequestModal();
            // Remove the query parameter from the URL without reloading
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</x-layout>
