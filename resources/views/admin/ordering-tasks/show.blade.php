<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Ordering Task #{{ $task->id }}" 
                    subtitle="Track and manage inventory orders"
                />

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('admin.ordering-tasks.index') }}"
                           class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Tasks
                        </a>
                    </div>

                    <!-- Status Counts -->
                    <div class="grid grid-cols-4 gap-4 mb-8">
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Items</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $items->count() }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <p class="text-sm text-yellow-700 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $items->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <p class="text-sm text-blue-700 mb-1">Ordered</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $items->where('status', 'ordered')->count() }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <p class="text-sm text-green-700 mb-1">Received</p>
                            <p class="text-2xl font-bold text-green-900">{{ $items->where('status', 'received')->count() }}</p>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="mb-6 bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex gap-4">
                            <a href="{{ route('admin.ordering-tasks.show', $task, ['status' => '']) }}"
                               class="px-4 py-2 rounded-lg font-semibold transition-all {{ $statusFilter === '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                All Items
                            </a>
                            <a href="{{ route('admin.ordering-tasks.show', $task, ['status' => 'pending']) }}"
                               class="px-4 py-2 rounded-lg font-semibold transition-all {{ $statusFilter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Pending
                            </a>
                            <a href="{{ route('admin.ordering-tasks.show', $task, ['status' => 'ordered']) }}"
                               class="px-4 py-2 rounded-lg font-semibold transition-all {{ $statusFilter === 'ordered' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Ordered
                            </a>
                            <a href="{{ route('admin.ordering-tasks.show', $task, ['status' => 'received']) }}"
                               class="px-4 py-2 rounded-lg font-semibold transition-all {{ $statusFilter === 'received' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Received
                            </a>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Item Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Job Number</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Model Number</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Qty</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Request</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <!-- DEBUG: Total items: {{ count($items) }} -->
                                    @forelse($items as $item)
                                    <tr class="hover:bg-gray-50 transition-all">
                                        <!-- Item Name -->
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-gray-900">{{ $item->item_name }}</p>
                                            @if($item->notes)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($item->notes, 50) }}</p>
                                            @endif
                                        </td>

                                        <!-- Job Number -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                                {{ $item->job_number }}
                                            </span>
                                        </td>

                                        <!-- Model Number -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                                {{ $item->model_number }}
                                            </span>
                                        </td>

                                        <!-- Quantity -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $item->quantity_received }} / {{ $item->quantity_approved }}
                                                </p>
                                                <!-- Progress bar -->
                                                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    @php
                                                        $percentage = $item->getProgressPercentage();
                                                    @endphp
                                                    <div class="h-full {{ $percentage >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                                         style="width: {{ min(100, $percentage) }}%"></div>
                                                </div>
                                                @if($item->quantity_received > $item->quantity_approved)
                                                    <p class="text-xs text-orange-600 font-semibold">
                                                        +{{ $item->quantity_received - $item->quantity_approved }} over
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColor = match($item->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'ordered' => 'bg-blue-100 text-blue-800',
                                                    'partially_received' => 'bg-orange-100 text-orange-800',
                                                    'received' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                {{ match($item->status) {
                                                    'pending' => 'Pending',
                                                    'ordered' => 'Ordered',
                                                    'partially_received' => 'Partially Received',
                                                    'received' => 'Received',
                                                    default => ucfirst($item->status)
                                                } }}
                                            </span>
                                        </td>

                                        <!-- Request Link -->
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.inventory-requests.show', $item->inventoryRequest) }}"
                                               class="text-blue-600 hover:text-blue-900 font-semibold text-sm">
                                                Request #{{ $item->inventory_request_id }}
                                            </a>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex gap-2 justify-center flex-wrap">
                                                @if($item->status === 'pending')
                                                    <button onclick="markOrdered({{ $item->id }})"
                                                            class="text-blue-600 hover:text-blue-900 font-semibold text-sm transition-colors"
                                                            title="Mark as Ordered">
                                                        Mark Ordered
                                                    </button>
                                                @endif
                                                @if(in_array($item->status, ['ordered', 'partially_received']))
                                                    <button onclick="openReceiveModal({{ $item->id }}, {{ $item->quantity_approved }}, {{ $item->quantity_received }})"
                                                            class="text-green-600 hover:text-green-900 font-semibold text-sm transition-colors"
                                                            title="Receive Items">
                                                        Receive
                                                    </button>
                                                @elseif($item->status === 'received')
                                                    <button onclick="openReceiveModal({{ $item->id }}, {{ $item->quantity_approved }}, {{ $item->quantity_received }})"
                                                            class="text-green-600 hover:text-green-900 font-semibold text-sm transition-colors"
                                                            title="Receive More">
                                                        Receive More
                                                    </button>
                                                @endif
                                                <button onclick="cancelItem({{ $item->id }})"
                                                        class="text-red-600 hover:text-red-900 font-semibold text-sm transition-colors"
                                                        title="Cancel Item">
                                                    Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-900 font-semibold text-lg">No items found</p>
                                            <p class="text-gray-600 text-sm mt-2">{{ $statusFilter ? "No items with status: $statusFilter" : "No items in this task" }}</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Modal -->
    <div id="receiveModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Receive Items</h3>
                <button onclick="closeReceiveModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>

            <form onsubmit="submitReceive(event)">
                <input type="hidden" id="receiveItemId" value="">

                <!-- Status Info -->
                <div class="mb-4 p-3 bg-blue-50 rounded border border-blue-200">
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Approved:</span>
                            <p id="receiveApprovedQty" class="font-semibold text-lg text-gray-800">-</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Already Received:</span>
                            <p id="receiveReceivedQty" class="font-semibold text-lg text-gray-800">-</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Still Needed:</span>
                            <p id="receiveRemainingQty" class="font-semibold text-lg text-blue-600">-</p>
                        </div>
                    </div>
                </div>

                <!-- Quantity to Receive -->
                <div class="mb-4">
                    <label for="receiveQuantityInput" class="block text-sm font-medium text-gray-700 mb-1">
                        Quantity to Receive <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="receiveQuantityInput"
                        name="receive_quantity"
                        min="1"
                        step="1"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter quantity"
                    >
                    <p class="text-xs text-gray-500 mt-1">You can receive more than the remaining amount</p>
                </div>

                <!-- Receive Date -->
                <div class="mb-4">
                    <label for="receiveDate" class="block text-sm font-medium text-gray-700 mb-1">
                        Receive Date
                    </label>
                    <input 
                        type="date" 
                        id="receiveDate"
                        name="receive_date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="receiveNotes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                    </label>
                    <textarea 
                        id="receiveNotes"
                        name="receive_notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Add any notes about the receipt..."
                    ></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button 
                        type="button"
                        onclick="closeReceiveModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                    >
                        Receive Items
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Receive Modal -->

    <script>
        let currentItemId = null;

        function markOrdered(itemId) {
            if (!confirm('Mark this item as ordered?')) return;

            const url = `{{ route('admin.ordering-task-items.markOrdered', ':id') }}`.replace(':id', itemId);
            console.log('Marking ordered - URL:', url);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                console.log('Mark ordered response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Mark ordered response data:', data);
                if (data.success) {
                    console.log('Success! Redirecting to ordered items...');
                    // Redirect to show ordered items
                    window.location.href = `{{ route('admin.ordering-tasks.show', $task) }}?status=ordered`;
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Mark ordered error:', error);
                alert('Network error: ' + error.message);
            });
        }

        function openReceiveModal(itemId, approved, received) {
            console.log('Opening receive modal for item:', itemId, 'Approved:', approved, 'Received:', received);
            currentItemId = itemId;
            document.getElementById('receiveItemId').value = itemId;
            document.getElementById('receiveApprovedQty').textContent = approved;
            document.getElementById('receiveReceivedQty').textContent = received;
            document.getElementById('receiveRemainingQty').textContent = Math.max(0, approved - received);
            document.getElementById('receiveQuantityInput').value = '';
            document.getElementById('receiveDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('receiveNotes').value = '';
            const modal = document.getElementById('receiveModal');
            console.log('Modal element:', modal);
            if (modal) {
                modal.classList.remove('hidden');
                console.log('Modal opened');
            } else {
                console.error('Modal element not found!');
            }
        }

        function closeReceiveModal() {
            console.log('Closing receive modal');
            const modal = document.getElementById('receiveModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            currentItemId = null;
        }

        function submitReceive(e) {
            e.preventDefault();
            const itemId = document.getElementById('receiveItemId').value;
            const quantity = parseInt(document.getElementById('receiveQuantityInput').value);
            const date = document.getElementById('receiveDate').value;
            const notes = document.getElementById('receiveNotes').value;

            if (!quantity || quantity <= 0) {
                alert('Please enter a quantity greater than 0');
                return;
            }

            const url = `{{ route('admin.ordering-task-items.receive', ':id') }}`.replace(':id', itemId);
            console.log('Submitting receive request to:', url);
            console.log('Data:', { receive_quantity: quantity, receive_date: date, receive_notes: notes });

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receive_quantity: quantity,
                    receive_date: date,
                    receive_notes: notes,
                }),
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().then(data => ({ status: response.status, data }));
            })
            .then(({ status, data }) => {
                console.log('Response data:', data);
                if (data.success) {
                    closeReceiveModal();
                    window.location.reload();
                } else {
                    const errorMsg = data.error || 'Unknown error occurred';
                    console.error('Error from server:', errorMsg);
                    alert('Error: ' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error: ' + error.message);
            });
        }

        function cancelItem(itemId) {
            if (!confirm('Remove this item from the ordering task?')) return;

            fetch(`{{ route('admin.ordering-task-items.cancel', ':id') }}`.replace(':id', itemId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }

        // Close modal on outside click
        document.getElementById('receiveModal')?.addEventListener('click', function(e) {
            if (e.target.id === 'receiveModal') {
                closeReceiveModal();
            }
        });
    </script>
</x-layout>
