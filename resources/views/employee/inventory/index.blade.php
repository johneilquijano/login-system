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
                            <h2 class="text-2xl font-bold text-gray-900">Inventory Requests</h2>
                            <p class="text-sm text-gray-600 mt-1">Request and manage inventory items</p>
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
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">Your Inventory Requests</h1>
                                <p class="mt-2 text-blue-100">{{ $requests->total() }} request(s) submitted</p>
                            </div>
                            <a href="{{ route('inventory.create') }}" class="bg-white text-blue-600 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition">
                                New Request
                            </a>
                        </div>
                    </div>

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

                    <!-- Requests Table -->
                    @if($requests->count() > 0)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b" style="border-bottom-color: #ccc;">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Item Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Quantity</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                <tr class="border-b hover:bg-gray-50 transition" style="border-bottom-color: #ccc;">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $req->item_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $req->quantity_requested }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($req->status === 'approved')
                                                bg-green-100 text-green-800
                                            @elseif($req->status === 'submitted')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($req->status === 'rejected')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $req->submitted_at ? $req->submitted_at->format('M d, Y') : $req->created_at->format('M d, Y') }}</td>
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
                        <a href="{{ route('inventory.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold mt-2 inline-block">Create your first request</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
