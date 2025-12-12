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
                            <h2 class="text-2xl font-bold text-gray-900">Employee Documents</h2>
                            <p class="text-sm text-gray-600 mt-1">Access and manage your documents</p>
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
                        <h1 class="text-3xl font-bold">Your Documents</h1>
                        <p class="mt-2 text-blue-100">{{ $documents->total() }} document(s) available</p>
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

                    <!-- Documents Grid -->
                    @if($documents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($documents as $doc)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $doc->title }}</h3>
                                        <p class="text-xs text-gray-500">{{ $doc->category }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($doc->description)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($doc->description, 100) }}</p>
                            @endif

                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024, 2) }} KB</span>
                                <span class="text-xs text-gray-500">{{ $doc->created_at->format('M d, Y') }}</span>
                            </div>

                            <a href="{{ route('documents.download', $doc) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm text-center">
                                Download
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No documents available yet</p>
                        <p class="text-gray-500 text-sm mt-1">Documents will appear here once they are shared with you</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
