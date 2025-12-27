<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-8 py-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Request</h2>
                            <p class="text-sm text-gray-600 mt-1">Update request #{{ str_pad($inventoryRequest->id, 4, '0', STR_PAD_LEFT) }}</p>
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
                    <!-- Back Button -->
                    <a href="{{ route('inventory-requests.show', $inventoryRequest) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Request
                    </a>

                    <!-- Form Card -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 max-w-4xl">
                        <form method="POST" action="{{ route('inventory-requests.update', $inventoryRequest) }}" id="editRequestForm">
                            @csrf
                            @method('PUT')

                            <!-- Request Details Section -->
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Request Details</h3>
                                
                                <div class="grid grid-cols-2 gap-6 mb-6">
                                    <!-- Request Title -->
                                    <div class="col-span-2">
                                        <label for="request_title" class="block text-sm font-semibold text-gray-900 mb-2">Request Title *</label>
                                        <input 
                                            type="text" 
                                            id="request_title" 
                                            name="request_title" 
                                            value="{{ old('request_title', $inventoryRequest->request_title) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('request_title') border-red-500 @enderror"
                                            placeholder="e.g., Office Equipment Request"
                                            required
                                        />
                                        @error('request_title')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Priority -->
                                    <div>
                                        <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">Priority *</label>
                                        <select 
                                            id="priority" 
                                            name="priority"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('priority') border-red-500 @enderror"
                                            required
                                        >
                                            <option value="normal" {{ old('priority', $inventoryRequest->priority) === 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="urgent" {{ old('priority', $inventoryRequest->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                        @error('priority')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Needed By Date -->
                                    <div>
                                        <label for="needed_by_date" class="block text-sm font-semibold text-gray-900 mb-2">Needed By Date</label>
                                        <input 
                                            type="date" 
                                            id="needed_by_date" 
                                            name="needed_by_date"
                                            value="{{ old('needed_by_date', $inventoryRequest->needed_by_date?->format('Y-m-d')) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('needed_by_date') border-red-500 @enderror"
                                        />
                                        @error('needed_by_date')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Reason -->
                                    <div class="col-span-2">
                                        <label for="reason" class="block text-sm font-semibold text-gray-900 mb-2">Reason</label>
                                        <textarea 
                                            id="reason" 
                                            name="reason" 
                                            rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('reason') border-red-500 @enderror"
                                            placeholder="Why do you need these items?"
                                        >{{ old('reason', $inventoryRequest->reason) }}</textarea>
                                        @error('reason')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-bold text-gray-900">Request Items</h3>
                                    <button 
                                        type="button" 
                                        onclick="addEditItem()"
                                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm"
                                    >
                                        + Add Item
                                    </button>
                                </div>

                                <div id="itemsContainer" class="space-y-4">
                                    <!-- Item rows will be populated here -->
                                </div>

                                @error('items')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center gap-3 border-t border-gray-200 pt-6">
                                <a href="{{ route('inventory-requests.show', $inventoryRequest) }}" class="px-6 py-3 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition flex-1 text-center">
                                    Cancel
                                </a>
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex-1"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemCount = 0;

        function addEditItem(itemId = null, itemName = '', category = '', quantity = 1, notes = '') {
            const container = document.getElementById('itemsContainer');
            const itemRow = document.createElement('div');
            itemRow.className = 'p-4 border border-gray-200 rounded-lg bg-gray-50 item-row';
            itemRow.id = `item-${itemCount}`;
            itemRow.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="font-semibold text-gray-900">Item ${itemCount + 1}</h5>
                    <button 
                        type="button" 
                        onclick="removeEditItem('item-${itemCount}')"
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
                            value="${itemName}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., Office Chair, Monitor, etc."
                            required
                        />
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                        <input 
                            type="text" 
                            name="items[${itemCount}][category]" 
                            value="${category}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., Equipment"
                        />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Quantity *</label>
                        <input 
                            type="number" 
                            name="items[${itemCount}][quantity]" 
                            value="${quantity}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            min="1"
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
                        >${notes}</textarea>
                    </div>
                </div>
            `;
            container.appendChild(itemRow);
            itemCount++;
        }

        function removeEditItem(itemId) {
            const itemRow = document.getElementById(itemId);
            if (itemRow) {
                itemRow.remove();
            }
        }

        // Initialize with existing items
        document.addEventListener('DOMContentLoaded', function() {
            const existingItems = {!! json_encode($inventoryRequest->items) !!};
            existingItems.forEach((item, index) => {
                addEditItem(item.id, item.item_name, item.category, item.quantity, item.notes);
            });
        });
    </script>
</x-layout>
