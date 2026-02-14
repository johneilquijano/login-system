<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Tool Check-In / Check-Out" 
                    subtitle="Manage your tool checkouts" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Tabs and Search Bar -->
                    <div class="mb-6 md:mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <!-- Tabs -->
                        <div class="flex gap-1 md:gap-2 border-b border-gray-200 w-full md:w-auto overflow-x-auto">
                            <a href="{{ route('tools.index', ['tab' => 'available', 'search' => $search]) }}" 
                               class="px-3 md:px-6 py-2 md:py-3 font-semibold text-xs md:text-sm transition-all whitespace-nowrap {{ $tab === 'available' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                                Available Tools
                            </a>
                            <a href="{{ route('tools.index', ['tab' => 'checked_out', 'search' => $search]) }}" 
                               class="px-3 md:px-6 py-2 md:py-3 font-semibold text-xs md:text-sm transition-all whitespace-nowrap {{ $tab === 'checked_out' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                                My Checked-Out Items
                            </a>
                            <a href="{{ route('tools.index', ['tab' => 'history', 'search' => $search]) }}" 
                               class="px-3 md:px-6 py-2 md:py-3 font-semibold text-xs md:text-sm transition-all whitespace-nowrap {{ $tab === 'history' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                                My History
                            </a>
                        </div>

                        <!-- Search Bar -->
                        <div class="flex-shrink-0 w-full md:w-80">
                            <form method="GET" action="{{ route('tools.index') }}" class="flex">
                                <input type="hidden" name="tab" value="{{ $tab }}">
                                <div class="relative w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 md:w-5 h-4 md:h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        placeholder="Search tools..." 
                                        value="{{ $search }}"
                                        class="w-full pl-10 pr-3 md:pr-4 py-2 md:py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm md:text-base">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Available Tools Tab -->
                    @if($tab === 'available')
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-lg">
                        @if($items->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            @foreach($items as $tool)
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:border-blue-300 flex flex-col">
                                <!-- Tool Image -->
                                <div class="w-full h-40 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden">
                                    @if($tool->image_path)
                                        <img src="{{ asset($tool->image_path) }}" alt="{{ $tool->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Tool Info -->
                                <div class="p-4">
                                    <!-- Category and Status Line -->
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                            {{ ucfirst($tool->category) }}
                                        </span>
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            Available
                                        </span>
                                    </div>

                                    <!-- Tool Name -->
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $tool->name }}</h3>

                                    <!-- Condition -->
                                    <div class="mb-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($tool->condition === 'good')
                                                bg-green-100 text-green-700 border border-green-200
                                            @elseif($tool->condition === 'fair')
                                                bg-yellow-100 text-yellow-700 border border-yellow-200
                                            @elseif($tool->condition === 'needs_repair')
                                                bg-orange-100 text-orange-700 border border-orange-200
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $tool->condition)) }}
                                        </span>
                                    </div>

                                    <!-- Checkout Button -->
                                    <button onclick="checkoutTool('{{ $tool->id }}', '{{ $tool->name }}')" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                        Checkout
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12 md:py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 md:w-16 h-12 md:h-16 text-gray-400 mx-auto mb-3 md:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-gray-900 font-semibold text-base md:text-lg">No tools available</p>
                            <p class="text-gray-600 text-sm mt-2">Check back later for available tools</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- My Checked-Out Items Tab -->
                    @if($tab === 'checked_out')
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg">
                        @if($items->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($items as $checkout)
                            <div class="p-3 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-3 md:gap-4 hover:bg-gray-50 transition-all">
                                <!-- Left: Item Image -->
                                <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($checkout->tool && $checkout->tool->image_path)
                                        <img src="{{ asset($checkout->tool->image_path) }}" alt="{{ $checkout->tool_name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Middle: Tool Info -->
                                <div class="flex-1 ml-0 md:ml-6">
                                    <h3 class="text-base md:text-lg font-bold text-gray-900">{{ $checkout->tool_name ?? ($checkout->tool->name ?? 'Tool') }}</h3>
                                    <p class="text-xs md:text-sm text-gray-600 mt-1">
                                        Checked out on: <span class="font-semibold text-gray-900">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('M d, Y \a\t H:i') : $checkout->created_at->format('M d, Y \a\t H:i') }}</span>
                                    </p>
                                </div>

                                <!-- Right: Return Button -->
                                <button onclick="returnTool('{{ $checkout->id }}', '{{ $checkout->tool_name ?? ($checkout->tool->name ?? 'Tool') }}')" 
                                        class="w-full md:w-auto ml-0 md:ml-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-semibold py-2 md:py-2.5 px-3 md:px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm md:text-base">
                                    Return Item
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12 md:py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 md:w-16 h-12 md:h-16 text-gray-400 mx-auto mb-3 md:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-900 font-semibold text-base md:text-lg">No checked-out items</p>
                            <p class="text-gray-600 text-sm mt-2">You haven't checked out any tools yet</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- My History Tab -->
                    @if($tab === 'history')
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg">
                        @if($items->count() > 0)
                        <div class="lg:hidden divide-y divide-gray-200">
                            @foreach($items as $checkout)
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                            @if($checkout->tool && $checkout->tool->image_path)
                                                <img src="{{ asset($checkout->tool->image_path) }}" alt="{{ $checkout->tool_name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $checkout->tool_name ?? ($checkout->tool->name ?? 'Tool') }}</p>
                                            <p class="text-xs text-gray-500">{{ $checkout->tool->category ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $checkout->returned_at ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $checkout->returned_at ? 'Returned' : 'Checked Out' }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3 text-xs text-gray-600">
                                    <div>
                                        <p class="font-semibold text-gray-700">Date</p>
                                        <p>{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('M d, Y') : $checkout->created_at->format('M d, Y') }}</p>
                                        <p class="text-gray-500">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('H:i A') : $checkout->created_at->format('H:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-700">Condition</p>
                                        @if($checkout->tool)
                                            <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-full text-xs font-medium 
                                                @if($checkout->tool->condition === 'good') bg-green-100 text-green-800
                                                @elseif($checkout->tool->condition === 'fair') bg-yellow-100 text-yellow-800
                                                @else bg-orange-100 text-orange-800 @endif">
                                                {{ ucfirst($checkout->tool->condition ?? 'Unknown') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 text-xs text-gray-600">
                                    <p class="font-semibold text-gray-700">Duration</p>
                                    @if($checkout->returned_at && $checkout->checked_out_at)
                                        <p class="text-gray-900">{{ $checkout->checked_out_at->diffInDays($checkout->returned_at) }} day(s)</p>
                                        <p class="text-gray-500">{{ $checkout->returned_at->format('M d, Y H:i A') }}</p>
                                    @else
                                        <p class="text-gray-900">Still checked out</p>
                                        <p class="text-gray-500">{{ $checkout->checked_out_at ? $checkout->checked_out_at->diffForHumans() : 'N/A' }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="hidden lg:block overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
                            <table class="w-full min-w-max md:min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                                    <tr>
                                        <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">Tool</th>
                                        <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">Action</th>
                                        <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">Date & Time</th>
                                        <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">Condition</th>
                                        <th class="px-4 md:px-6 py-3 md:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">Duration</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($items as $checkout)
                                    <tr class="hover:bg-gray-50 transition-all">
                                        <!-- Tool Name & Image -->
                                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2 md:gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                                    @if($checkout->tool && $checkout->tool->image_path)
                                                        <img src="{{ asset($checkout->tool->image_path) }}" alt="{{ $checkout->tool_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-xs md:text-sm font-semibold text-gray-900 whitespace-nowrap">{{ $checkout->tool_name ?? ($checkout->tool->name ?? 'Tool') }}</p>
                                                    <p class="text-xs text-gray-500 whitespace-nowrap hidden md:block">{{ $checkout->tool->category ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Action Badge -->
                                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                            <div class="flex gap-2">
                                                <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium {{ $checkout->returned_at ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $checkout->returned_at ? 'Returned' : 'Checked Out' }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Checkout Date & Time -->
                                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                            <p class="text-xs md:text-sm text-gray-900">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('M d, Y') : $checkout->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $checkout->checked_out_at ? $checkout->checked_out_at->format('H:i A') : $checkout->created_at->format('H:i A') }}</p>
                                        </td>

                                        <!-- Condition -->
                                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                            @if($checkout->tool)
                                                <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($checkout->tool->condition === 'good') bg-green-100 text-green-800
                                                    @elseif($checkout->tool->condition === 'fair') bg-yellow-100 text-yellow-800
                                                    @else bg-orange-100 text-orange-800 @endif">
                                                    {{ ucfirst($checkout->tool->condition ?? 'Unknown') }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Duration -->
                                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                            @if($checkout->returned_at && $checkout->checked_out_at)
                                                <p class="text-xs md:text-sm text-gray-900">{{ $checkout->checked_out_at->diffInDays($checkout->returned_at) }} day(s)</p>
                                                <p class="text-xs text-gray-500">{{ $checkout->returned_at->format('M d, Y H:i A') }}</p>
                                            @else
                                                <p class="text-xs md:text-sm text-gray-900">Still checked out</p>
                                                <p class="text-xs text-gray-500">{{ $checkout->checked_out_at ? $checkout->checked_out_at->diffForHumans() : 'N/A' }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12 md:py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 md:w-16 h-12 md:h-16 text-gray-400 mx-auto mb-3 md:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-900 font-semibold text-base md:text-lg">No history yet</p>
                            <p class="text-xs text-gray-500 mt-2 lg:hidden">You're all caught up.</p>
                            <p class="text-gray-600 text-sm mt-2">You haven't checked out any tools</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Confirmation Modal -->
    <div id="checkoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 animate-in fade-in scale-95">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Checkout Tool?</h3>
            <p class="text-gray-600 text-center mb-6">
                Are you sure you want to check out <span id="toolNameDisplay" class="font-semibold text-gray-900"></span>?
            </p>
            <div class="flex gap-3">
                <button onclick="closeCheckoutModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-xl transition-all">
                    Cancel
                </button>
                <button onclick="confirmCheckout()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                    Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Return Confirmation Modal -->
    <div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 animate-in fade-in scale-95">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Return Tool?</h3>
            <p class="text-gray-600 text-center mb-6">
                Are you sure you want to return <span id="toolReturnNameDisplay" class="font-semibold text-gray-900"></span>?
            </p>
            <div class="flex gap-3">
                <button onclick="closeReturnModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-xl transition-all">
                    Cancel
                </button>
                <button onclick="confirmReturn()" class="flex-1 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                    Return
                </button>
            </div>
        </div>
    </div>

    <script>
        let checkoutToolId = null;
        let returnCheckoutId = null;

        function checkoutTool(toolId, toolName) {
            checkoutToolId = toolId;
            document.getElementById('toolNameDisplay').textContent = toolName;
            document.getElementById('checkoutModal').classList.remove('hidden');
        }

        function closeCheckoutModal() {
            checkoutToolId = null;
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        function confirmCheckout() {
            if (!checkoutToolId) return;

            fetch(`/tools/${checkoutToolId}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCheckoutModal();
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to checkout tool'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function returnTool(checkoutId, toolName) {
            returnCheckoutId = checkoutId;
            document.getElementById('toolReturnNameDisplay').textContent = toolName;
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            returnCheckoutId = null;
            document.getElementById('returnModal').classList.add('hidden');
        }

        function confirmReturn() {
            if (!returnCheckoutId) return;

            fetch(`/tools/checkouts/${returnCheckoutId}/return`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeReturnModal();
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to return tool'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        // Close modals when clicking outside
        document.getElementById('checkoutModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCheckoutModal();
            }
        });

        document.getElementById('returnModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });

        // Auto-submit search form
        let searchTimeout;
        document.querySelectorAll('input[name="search"]').forEach(input => {
            input.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 300);
            });
        });
    </script>
</x-layout>
