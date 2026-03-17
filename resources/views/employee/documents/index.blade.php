<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Documents" 
                    subtitle="View and manage your documents" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex justify-between items-center shadow-md animate-in fade-in slide-in-from-top">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-900 font-semibold">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700 font-bold text-xl">&times;</button>
                    </div>
                    @endif

                    <!-- Documents Section -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-lg">
                        <!-- Section Title with Upload Button -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">My Documents</h3>
                                <p class="text-sm text-gray-600 mt-2">{{ $documents->total() }} document{{ $documents->total() !== 1 ? 's' : '' }} in your collection</p>
                            </div>
                            <a href="{{ route('documents.upload') }}" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Upload</span>
                            </a>
                        </div>

                        <!-- Search, Filter, and Sort Controls -->
                        <div class="mb-8">
                            <form id="document-filter-form" method="GET" action="{{ route('documents.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                                <!-- Search Bar -->
                                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                                    <div class="relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input 
                                            type="text" 
                                            id="searchInput"
                                            name="search" 
                                            placeholder="Search documents..." 
                                            value="{{ request('search') }}"
                                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <button 
                                            type="button"
                                            id="clearSearchBtn"
                                            onclick="clearSearchInput()"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-all {{ request('search') ? '' : 'hidden' }}"
                                            title="Clear search">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Signature Filter -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Signature</label>
                                    <select id="signatureFilter" name="signature" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                        <option value="">All</option>
                                        <option value="needs_signature" {{ request('signature') === 'needs_signature' ? 'selected' : '' }}>Needs Signature</option>
                                        <option value="signed" {{ request('signature') === 'signed' ? 'selected' : '' }}>Signed</option>
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select id="statusFilter" name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                        <option value="">All Status</option>
                                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>                                

                                <!-- Sort -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort</label>
                                    <select id="sortFilter" name="sort" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                    </select>
                                </div>

                                <!-- Reset Button -->
                                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">&nbsp;</label>
                                    <button type="button" onclick="resetFilters()" class="w-full bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-semibold py-2.5 px-4 rounded-xl transition-all flex items-center justify-center gap-2 border border-red-200 hover:border-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span>Reset</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Documents Table -->
                        @if($documents->count() > 0)
                        <div class="lg:hidden space-y-3">
                            @foreach($documents as $doc)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <a href="{{ route('documents.show', $doc) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $doc->title }}
                                        </a>
                                        <div class="mt-2">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-purple-800 text-purple-800 uppercase">
                                                {{ $doc->formatted_type }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                        @if($doc->status === 'approved')
                                            border border-green-800 text-green-800 uppercase
                                        @elseif($doc->status === 'pending_review')
                                            border border-orange-800 text-orange-800 uppercase
                                        @elseif($doc->status === 'rejected')
                                            border border-red-800 text-red-800 uppercase
                                        @else
                                            border border-gray-800 text-gray-800 uppercase
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $doc->status)) }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3 text-xs text-gray-600">
                                    <div>
                                        <p class="font-semibold text-gray-700">Uploaded</p>
                                        <p>{{ $doc->created_at->format('M d, Y') }}</p>
                                        <p class="text-gray-500">{{ $doc->created_at->format('H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-700">Signature</p>
                                        @if(!$doc->signed_at)
                                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold border border-yellow-800 text-yellow-800 uppercase">Pending</span>
                                        @else
                                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold border border-green-800 text-green-800 uppercase">Signed</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('documents.show', $doc) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-900 font-semibold py-2 px-3 rounded-lg transition-all text-xs border border-blue-200 hover:border-blue-300 inline-flex items-center justify-center" title="Preview document">
                                        Preview
                                    </a>
                                    @if(!$doc->signed_at)
                                    <a href="{{ route('documents.sign', $doc) }}" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 hover:text-emerald-900 font-semibold py-2 px-3 rounded-lg transition-all text-xs border border-emerald-200 hover:border-emerald-300 inline-flex items-center justify-center" title="Sign document">
                                        Sign
                                    </a>
                                    @endif
                                    <a href="{{ route('documents.download', $doc) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-lg transition-all text-xs border border-gray-200 hover:border-gray-300 inline-flex items-center justify-center" title="Download document">
                                        Download
                                    </a>
                                    <button onclick="openDeleteModal('{{ $doc->id }}', '{{ $doc->title }}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-semibold py-2 px-3 rounded-lg transition-all text-xs border border-red-200 hover:border-red-300 inline-flex items-center justify-center" title="Delete document">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="hidden lg:block overflow-x-auto -mx-8 md:mx-0 px-8 md:px-0">
                            <table class="w-full min-w-max md:min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Document Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Type</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Uploaded On</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Signature</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($documents as $doc)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <a href="{{ route('documents.show', $doc) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline truncate block">
                                                {{ $doc->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-purple-800 text-purple-800 uppercase">
                                                {{ $doc->formatted_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            <span class="text-xs">{{ $doc->created_at->format('M d, Y') }}</span><br>
                                            <span class="text-xs text-gray-500">{{ $doc->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            @if(!$doc->signed_at)
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-yellow-800 text-yellow-800 uppercase">Pending</span>
                                            @else
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-green-800 text-green-800 uppercase">Signed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                                @if($doc->status === 'approved')
                                                    border border-green-800 text-green-800 uppercase
                                                @elseif($doc->status === 'pending_review')
                                                    border border-orange-800 text-orange-800 uppercase
                                                @elseif($doc->status === 'rejected')
                                                    border border-red-800 text-red-800 uppercase
                                                @else
                                                    border border-gray-800 text-gray-800 uppercase
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $doc->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm flex gap-2 whitespace-nowrap">
                                            <a href="{{ route('documents.show', $doc) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-900 font-semibold py-2 px-2.5 rounded-lg transition-all text-xs border border-blue-200 hover:border-blue-300 flex items-center justify-center" title="Preview document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(!$doc->signed_at)
                                            <a href="{{ route('documents.sign', $doc) }}" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 hover:text-emerald-900 font-semibold py-2 px-3 rounded-lg transition-all text-xs border border-emerald-200 hover:border-emerald-300 flex items-center justify-center gap-1.5" title="Sign document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <g transform="rotate(15 12 12)">
                                                        <path d="M12 2L8 8L8 12L10 18L12 22L14 18L16 12L16 8L12 2Z"/>
                                                        <line x1="12" y1="8" x2="12" y2="20" stroke="white" stroke-width="1.5"/>
                                                    </g>
                                                </svg>
                                                <span>Sign</span>
                                            </a>
                                            @endif
                                            <a href="{{ route('documents.download', $doc) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 font-semibold py-2 px-2.5 rounded-lg transition-all text-xs border border-gray-200 hover:border-gray-300 flex items-center justify-center" title="Download document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            <button onclick="openDeleteModal('{{ $doc->id }}', '{{ $doc->title }}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-semibold py-2 px-2.5 rounded-lg transition-all text-xs border border-red-200 hover:border-red-300 flex items-center justify-center" title="Delete document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8 flex justify-center px-4 md:px-0">
                            <div class="text-gray-700 text-sm">
                                {{ $documents->links() }}
                            </div>
                        </div>
                        @else
                        <div class="text-center py-16">
                            <div class="inline-block p-4 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl mb-4 border border-blue-200">
                                <svg class="w-16 h-16 text-blue-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-900 font-semibold text-lg">No documents yet</p>
                            <p class="text-xs text-gray-500 mt-2 lg:hidden">You're all caught up.</p>
                            <p class="text-gray-600 text-sm mt-2 max-w-sm mx-auto">Start by uploading your first document or wait for your organization to share documents with you.</p>
                            <a href="{{ route('documents.upload') }}" class="inline-block mt-6 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Upload Your First Document
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 animate-in fade-in scale-95">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Delete Document?</h3>
            <p class="text-gray-600 text-center mb-6">
                Are you sure you want to delete <span id="docTitle" class="font-semibold text-gray-900"></span>? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-xl transition-all">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="flex-1 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteDocumentId = null;

        function openDeleteModal(docId, docTitle) {
            deleteDocumentId = docId;
            document.getElementById('docTitle').textContent = docTitle;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteDocumentId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function confirmDelete() {
            if (!deleteDocumentId) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/documents/' + deleteDocumentId;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = csrfToken;
            
            form.appendChild(methodField);
            form.appendChild(tokenField);
            
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Real-time filtering
        let filterTimeout;

        function submitFiltersForm() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                document.getElementById('document-filter-form').submit();
            }, 300); // 300ms debounce for search
        }

        function toggleClearButton() {
            const searchInput = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            if (searchInput.value.trim() !== '') {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        function clearSearchInput() {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            toggleClearButton();
            submitFiltersForm();
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('signatureFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            toggleClearButton();
            document.getElementById('document-filter-form').submit();
        }

        // Search input - submit on keystroke with debounce
        document.getElementById('searchInput')?.addEventListener('keyup', () => {
            toggleClearButton();
            submitFiltersForm();
        });

        // Initialize clear button visibility on page load
        toggleClearButton();

        // Filter dropdowns - submit on change immediately
        document.getElementById('signatureFilter')?.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            document.getElementById('document-filter-form').submit();
        });

        document.getElementById('statusFilter')?.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            document.getElementById('document-filter-form').submit();
        });

        document.getElementById('sortFilter')?.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            document.getElementById('document-filter-form').submit();
        });
    </script>
</x-layout>
