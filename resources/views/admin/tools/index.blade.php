<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Tools Inventory" 
                    subtitle="" 
                />
                
                <!-- Add Tool Button (moved below header) -->
                <div class="px-8 py-4 bg-white border-b border-gray-200">
                    <button onclick="openAddToolModal()" class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        + Add New Tool
                    </button>
                </div>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Filters -->
                    <div class="mb-8 bg-white rounded-2xl border border-gray-200 p-6 shadow-lg">
                        <form method="GET" action="{{ route('admin.tools.index') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="searchInput"
                                    placeholder="Search tools..." 
                                    value="{{ $search }}"
                                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <button type="button" id="clearSearchBtn" onclick="clearSearch()" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Category Filter -->
                            <select name="category" id="categorySelect" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select name="status" id="statusSelect" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">All Statuses</option>
                                <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="checked_out" {{ $status === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>

                            <!-- Reset Button -->
                            <a href="{{ route('admin.tools.index') }}" class="bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                        </form>
                    </div>

                    <!-- Tools Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Image</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Tool Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Category</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Assigned To</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Checked Out Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Condition</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($tools as $tool)
                                    <tr class="hover:bg-gray-50 transition-all">
                                        <!-- Image -->
                                        <td class="px-6 py-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                                @if($tool->image_path)
                                                    <img src="{{ asset($tool->image_path) }}" alt="{{ $tool->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Tool Name -->
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-gray-900">{{ $tool->name }}</p>
                                            <p class="text-xs text-gray-500">S/N: {{ $tool->serial_number ?? 'N/A' }}</p>
                                        </td>

                                        <!-- Category -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $tool->category }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            @php
                                                $status = $tool->getStatus();
                                                $statusColor = $status === 'Available' ? 'bg-emerald-100 text-emerald-800' : ($status === 'Checked Out' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800');
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                {{ $status }}
                                            </span>
                                        </td>

                                        <!-- Assigned To -->
                                        <td class="px-6 py-4">
                                            @php
                                                $employee = $tool->getAssignedEmployee();
                                            @endphp
                                            <p class="text-sm font-semibold text-gray-900">{{ $employee ? $employee->full_name : '—' }}</p>
                                        </td>

                                        <!-- Checked Out Date -->
                                        <td class="px-6 py-4">
                                            @php
                                                $checkout = $tool->currentCheckout();
                                            @endphp
                                            @if($checkout && $checkout->checked_out_at)
                                                <p class="text-sm text-gray-900">{{ $checkout->checked_out_at->format('M d, Y') }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">—</p>
                                            @endif
                                        </td>

                                        <!-- Condition -->
                                        <td class="px-6 py-4">
                                            @php
                                                $conditionColor = $tool->condition === 'good' ? 'bg-green-100 text-green-800' : ($tool->condition === 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800');
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $conditionColor }}">
                                                {{ ucwords(str_replace('_', ' ', $tool->condition)) }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex gap-2 justify-center">
                                                <button onclick="viewTool('{{ $tool->id }}')" class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                <button onclick="editTool('{{ $tool->id }}')" class="text-amber-600 hover:text-amber-900 transition-colors" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button onclick="toggleMaintenance('{{ $tool->id }}', {{ $tool->is_maintenance ? 'true' : 'false' }})" class="text-orange-600 hover:text-orange-900 transition-colors" title="Maintenance">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                    </svg>
                                                </button>
                                                <button onclick="viewToolHistory('{{ $tool->id }}')" class="text-purple-600 hover:text-purple-900 transition-colors" title="History">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                                <button onclick="deleteTool('{{ $tool->id }}')" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-900 font-semibold text-lg">No tools found</p>
                                            <p class="text-gray-600 text-sm mt-2">Add a new tool to get started</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($tools->hasPages())
                    <div class="mt-8">
                        {{ $tools->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Tool Modal -->
    <div id="toolModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-900">Add New Tool</h3>
                <button onclick="closeToolModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="toolForm" onsubmit="submitToolForm(event)">
                @csrf
                <input type="hidden" id="toolId" name="tool_id">

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Tool Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tool Name *</label>
                        <input type="text" id="toolName" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <input type="text" id="toolCategory" name="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Power Tools">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Condition *</label>
                        <select id="toolCondition" name="condition" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select condition</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="needs_repair">Needs Repair</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="toolDescription" name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea id="toolNotes" name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Serial Number</label>
                        <input type="text" id="toolSerial" name="serial_number" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                        <input type="file" id="toolImage" name="image_path" accept="image/*" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 justify-end">
                    <button type="button" onclick="closeToolModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Save Tool
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Tool Details Modal -->
    <div id="viewToolModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 id="viewModalTitle" class="text-2xl font-bold text-gray-900">Tool Details</h3>
                <button onclick="closeViewToolModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="viewToolContent" class="space-y-4">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>

    <!-- Maintenance Reason Modal -->
    <div id="maintenanceReasonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Set Tool to Maintenance</h3>
            <p class="text-gray-600 text-center mb-6">Please provide a reason for maintenance</p>

            <form onsubmit="return false;">
                <textarea id="maintenanceReason" placeholder="e.g., Broken handle, needs repair, calibration needed..." rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent mb-6"></textarea>

                <div class="flex gap-4">
                    <button type="button" onclick="closeMaintenanceModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmMaintenance()" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-700 hover:to-orange-600 text-white font-semibold rounded-lg transition-all duration-300">
                        Set Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Force Return Modal -->
    <div id="forceReturnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Force Return Tool?</h3>
            <p class="text-gray-600 text-center mb-6" id="forceReturnMessage">This will mark the tool as returned immediately</p>

            <form onsubmit="submitForceReturn(event)">
                <textarea name="notes" placeholder="Optional admin notes..." rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent mb-6"></textarea>

                <div class="flex gap-4">
                    <button type="button" onclick="closeForceReturnModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-700 hover:to-orange-600 text-white font-semibold rounded-lg transition-all duration-300">
                        Force Return
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentToolId = null;

        // Add Tool Modal
        function openAddToolModal() {
            document.getElementById('modalTitle').textContent = 'Add New Tool';
            document.getElementById('toolForm').reset();
            document.getElementById('toolId').value = '';
            document.getElementById('toolModal').classList.remove('hidden');
        }

        function closeToolModal() {
            document.getElementById('toolModal').classList.add('hidden');
        }

        function submitToolForm(e) {
            e.preventDefault();
            const toolId = document.getElementById('toolId').value;
            const formData = new FormData(document.getElementById('toolForm'));
            
            // Log form data for debugging
            console.log('Form Data Being Sent:');
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }
            
            const url = toolId 
                ? `{{ route('admin.tools.update', ':id') }}`.replace(':id', toolId)
                : `{{ route('admin.tools.store') }}`;
            
            const method = toolId ? 'POST' : 'POST';
            
            if (toolId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
            .then(response => {
                return response.text().then(text => {
                    try {
                        const data = JSON.parse(text);
                        console.log('Response data:', data);
                        return { status: response.status, data };
                    } catch (e) {
                        // Response is not JSON, return raw text
                        console.log('Non-JSON response:', text);
                        return { status: response.status, text, data: null };
                    }
                });
            })
            .then(({ status, data, text }) => {
                if (status >= 400) {
                    let errorMsg = 'Unknown error';
                    if (data && data.message) {
                        errorMsg = data.message;
                    }
                    if (data && data.errors) {
                        const errors = Object.entries(data.errors).map(([field, messages]) => 
                            `${field}: ${messages.join(', ')}`
                        ).join('\n');
                        errorMsg += '\n' + errors;
                    } else if (text) {
                        errorMsg = text.substring(0, 300);
                    }
                    console.log('Full error response:', data || text);
                    alert('Error:\n' + errorMsg);
                } else if (data && data.success) {
                    window.location.reload();
                } else if (data) {
                    alert('Error: ' + (data.message || 'Unknown error'));
                } else {
                    alert('Error: Invalid response from server');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving tool:\n' + error.message);
            });
        }

        // View Tool
        function viewTool(toolId) {
            fetch(`{{ route('admin.tools.show', ':id') }}`.replace(':id', toolId))
                .then(response => response.json())
                .then(data => {
                    const tool = data.tool;
                    const baseUrl = '{{ url('/') }}';
                    const imageSrc = tool.image_path ? baseUrl + '/' + tool.image_path : null;
                    let html = `
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                    ${imageSrc ? `<img src="${imageSrc}" alt="${tool.name}" class="w-full h-full object-cover">` : '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>'}
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-gray-900">${tool.name}</h4>
                                    <p class="text-sm text-gray-600">Serial: ${tool.serial_number || 'N/A'}</p>
                                    <p class="text-sm text-gray-600">Category: ${tool.category}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    <p class="text-sm font-semibold text-gray-900">${data.status}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Condition</p>
                                    <p class="text-sm font-semibold text-gray-900">${tool.condition.charAt(0).toUpperCase() + tool.condition.slice(1)}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Assigned To</p>
                                    <p class="text-sm font-semibold text-gray-900">${data.assigned_to ? data.assigned_to.full_name : '—'}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Due Date</p>
                                    <p class="text-sm font-semibold text-gray-900">${data.due_date ? new Date(data.due_date).toLocaleDateString() : '—'}</p>
                                </div>
                            </div>
                            ${tool.description ? `<div class="pt-4 border-t border-gray-200"><p class="text-xs text-gray-500">Description</p><p class="text-sm text-gray-900">${tool.description}</p></div>` : ''}
                            ${tool.notes ? `<div><p class="text-xs text-gray-500">Notes</p><p class="text-sm text-gray-900">${tool.notes}</p></div>` : ''}
                        </div>
                    `;
                    document.getElementById('viewToolContent').innerHTML = html;
                    document.getElementById('viewModalTitle').textContent = tool.name;
                    document.getElementById('viewToolModal').classList.remove('hidden');
                });
        }

        function closeViewToolModal() {
            document.getElementById('viewToolModal').classList.add('hidden');
        }

        // Edit Tool
        function editTool(toolId) {
            fetch(`{{ route('admin.tools.show', ':id') }}`.replace(':id', toolId))
                .then(response => response.json())
                .then(data => {
                    const tool = data.tool;
                    document.getElementById('modalTitle').textContent = 'Edit Tool: ' + tool.name;
                    document.getElementById('toolId').value = toolId;
                    document.getElementById('toolName').value = tool.name;
                    document.getElementById('toolCategory').value = tool.category;
                    document.getElementById('toolCondition').value = tool.condition;
                    document.getElementById('toolDescription').value = tool.description || '';
                    document.getElementById('toolNotes').value = tool.notes || '';
                    document.getElementById('toolSerial').value = tool.serial_number || '';
                    document.getElementById('toolModal').classList.remove('hidden');
                });
        }

        // Toggle Maintenance
        let maintenanceToolId = null;

        function toggleMaintenance(toolId, isMaintenance) {
            // If setting to maintenance, show modal for reason
            if (!isMaintenance) {
                maintenanceToolId = toolId;
                document.getElementById('maintenanceReasonModal').classList.remove('hidden');
            } else {
                // If removing from maintenance, just confirm
                if (!confirm('Remove tool from maintenance?')) return;
                submitMaintenanceToggle(toolId, null);
            }
        }

        function submitMaintenanceToggle(toolId, reason) {
            const body = reason ? { reason: reason } : {};
            const url = `/admin/tools/${toolId}/toggle-maintenance`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body),
            })
            .then(response => response.text().then(text => {
                try {
                    return { status: response.status, data: JSON.parse(text) };
                } catch (e) {
                    return { status: response.status, text, data: null };
                }
            }))
            .then(({ status, data, text }) => {
                if (status >= 400) {
                    alert('Error: ' + (data?.message || text || 'Unknown error'));
                } else if (data?.success) {
                    window.location.reload();
                } else if (data?.requireReason) {
                    alert('Please provide a reason for maintenance');
                } else {
                    alert('Error: ' + (data?.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }

        function closeMaintenanceModal() {
            document.getElementById('maintenanceReasonModal').classList.add('hidden');
            maintenanceToolId = null;
        }

        function confirmMaintenance() {
            const reason = document.getElementById('maintenanceReason').value.trim();
            if (!reason) {
                alert('Please provide a reason for maintenance');
                return;
            }
            const toolId = maintenanceToolId; // Save it before closing modal
            closeMaintenanceModal();
            submitMaintenanceToggle(toolId, reason);
        }

        // View Tool History
        function viewToolHistory(toolId) {
            window.location.href = `{{ route('admin.tools.history') }}?tool=${toolId}`;
        }

        // Delete Tool
        function deleteTool(toolId) {
            if (!confirm('Are you sure you want to delete this tool?')) return;

            fetch(`{{ route('admin.tools.destroy', ':id') }}`.replace(':id', toolId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        // Force Return
        function forceReturn(toolId) {
            currentToolId = toolId;
            document.getElementById('forceReturnModal').classList.remove('hidden');
        }

        function closeForceReturnModal() {
            document.getElementById('forceReturnModal').classList.add('hidden');
            currentToolId = null;
        }

        function submitForceReturn(e) {
            e.preventDefault();
            const notes = e.target.notes.value;

            fetch(`{{ route('admin.tools.forceReturn', ':id') }}`.replace(':id', currentToolId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notes: notes }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        // Close modals on outside click
        window.addEventListener('click', (e) => {
            if (e.target.id === 'toolModal') closeToolModal();
            if (e.target.id === 'viewToolModal') closeViewToolModal();
            if (e.target.id === 'forceReturnModal') closeForceReturnModal();
        });

        // Search on typing with debounce
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        // Show/hide clear button based on input value
        function toggleClearButton() {
            if (searchInput.value.trim() === '') {
                clearSearchBtn.classList.add('hidden');
            } else {
                clearSearchBtn.classList.remove('hidden');
            }
        }

        // Clear search input and submit form
        function clearSearch() {
            searchInput.value = '';
            toggleClearButton();
            document.getElementById('filterForm').submit();
        }

        // Toggle clear button on input
        searchInput.addEventListener('input', function(e) {
            toggleClearButton();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 300);
        });

        // Initialize clear button visibility on page load
        toggleClearButton();
    </script>
</x-layout>
