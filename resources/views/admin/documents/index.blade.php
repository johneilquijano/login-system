<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Document Management" 
                    subtitle="" 
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
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-8 shadow-lg">
                        <!-- Section Title with Buttons -->
                        <div class="mb-6 md:mb-8">
                            <div class="mb-4 md:mb-6">
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900">All Documents</h3>
                                <p class="text-sm text-gray-600 mt-2">{{ $documents->total() }} document{{ $documents->total() !== 1 ? 's' : '' }} in your organization</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                                <button onclick="openUploadModal()" class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-semibold py-2 md:py-3 px-4 md:px-6 rounded-xl transition-all duration-300 text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    <span>Upload Document</span>
                                </button>
                                <button onclick="openAssignModal()" class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-2 md:py-3 px-4 md:px-6 rounded-xl transition-all duration-300 text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Assign Document</span>
                                </button>
                            </div>
                        </div>

                        <!-- Search, Filter, and Sort Controls (Responsive) -->
                        <div class="mb-6 md:mb-8">
                            <form id="document-filter-form" method="GET" action="{{ route('admin.documents.index') }}" class="flex flex-col gap-3">
                                <!-- Search Bar -->
                                <div class="w-full">
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
                                
                                <!-- Filter Row (Wraps on smaller screens) -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <!-- Signature Filter -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Signature</label>
                                        <select id="signatureFilter" name="signature" class="w-full px-3 md:px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                            <option value="">All</option>
                                            <option value="needs_signature" {{ request('signature') === 'needs_signature' ? 'selected' : '' }}>Needs Sig</option>
                                            <option value="signed" {{ request('signature') === 'signed' ? 'selected' : '' }}>Signed</option>
                                        </select>
                                    </div>

                                    <!-- Status Filter -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                        <select id="statusFilter" name="status" class="w-full px-3 md:px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                            <option value="">All Status</option>
                                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>                                

                                    <!-- Sort -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sort</label>
                                        <select id="sortFilter" name="sort" class="w-full px-3 md:px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer filter-select">
                                            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>A-Z</option>
                                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Z-A</option>
                                        </select>
                                    </div>

                                    <!-- Reset Button -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">&nbsp;</label>
                                        <button type="button" onclick="resetFilters()" class="w-full bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-semibold py-2.5 px-3 md:px-4 rounded-xl transition-all flex items-center justify-center gap-1 md:gap-2 border border-red-200 hover:border-red-300 text-xs md:text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Reset</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Documents Table -->
                        @if($documents->count() > 0)
                        <div class="overflow-x-auto -mx-4 md:mx-0">
                            <table class="w-full min-w-max md:min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                                    <tr>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Document Name</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Assigned To</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Type</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Date</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Signature</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Status</th>
                                        <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($documents as $doc)
                                    <tr class="hover:bg-gray-50 transition-all duration-200">
                                        <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                                            <a href="{{ route('admin.documents.show', $doc) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                                {{ $doc->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            @if($doc->user)
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                                    {{ $doc->user->name }}
                                                </span>
                                            @else
                                                <button onclick="openQuickAssignModal('{{ $doc->id }}', '{{ $doc->title }}')" class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 hover:border-gray-400 transition-all cursor-pointer">
                                                    Unassigned
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                                {{ $doc->formatted_type }}
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                            <span class="text-xs">{{ $doc->created_at->format('M d, Y H:i') }}</span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                                            @if(!$doc->signed_at)
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                                            @else
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">Signed</span>
                                            @endif
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                                @if($doc->status === 'approved')
                                                    bg-emerald-100 text-emerald-700 border border-emerald-200
                                                @elseif($doc->status === 'pending_review')
                                                    bg-orange-100 text-orange-700 border border-orange-200
                                                @elseif($doc->status === 'rejected')
                                                    bg-red-100 text-red-700 border border-red-200
                                                @else
                                                    bg-gray-100 text-gray-700 border border-gray-200
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $doc->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-sm flex gap-2 whitespace-nowrap">
                                            <a href="{{ route('admin.documents.show', $doc) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-900 font-semibold py-1.5 px-3 rounded-lg transition-all text-xs border border-blue-200 hover:border-blue-300" title="Preview">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.documents.download', $doc) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 font-semibold py-1.5 px-3 rounded-lg transition-all text-xs border border-gray-200 hover:border-gray-300" title="Download">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            <button onclick="openDeleteModal('{{ $doc->id }}', '{{ $doc->title }}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-semibold py-1.5 px-3 rounded-lg transition-all text-xs border border-red-200 hover:border-red-300" title="Delete">
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
                        <div class="mt-8 flex justify-center">
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
                            <p class="text-gray-600 text-sm mt-2 max-w-sm mx-auto">Start by assigning your first document to employees in your organization.</p>
                            <button onclick="openAssignModal()" class="inline-block mt-6 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Assign First Document
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Document Modal -->
    <!-- Upload Document Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-in fade-in scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Upload Document</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="uploadForm" action="{{ route('admin.documents.uploadStore') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Document Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Document Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all" placeholder="e.g., Employee Handbook">
                </div>

                <!-- Document Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all" placeholder="Optional description"></textarea>
                </div>

                <!-- Drag & Drop Zone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File *</label>
                    <div id="uploadDropZone" class="border-2 border-dashed border-purple-300 rounded-xl p-8 text-center cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all bg-purple-50" onclick="document.getElementById('uploadFileInput').click()">
                        <svg id="uploadIcon" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-purple-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p id="uploadText" class="text-gray-700 font-medium">Drag and drop your file here, or <span class="text-purple-600">click to browse</span></p>
                        <p class="text-gray-500 text-sm mt-1">PDF, DOC, DOCX, JPG, PNG, GIF (Max 10MB)</p>
                        <input type="file" name="document" id="uploadFileInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required class="hidden">
                    </div>
                    <div id="uploadFileInfo" class="mt-3 hidden">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-sm text-gray-600">Selected: <span id="uploadFileName" class="font-semibold text-green-700"></span></p>
                            <div id="uploadProgress" class="mt-2 hidden">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="uploadProgressBar" class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeUploadModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-4 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="uploadSubmitBtn" class="flex-1 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-semibold py-2.5 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                        Upload Document
                    </button>
                </div>

                <!-- Success Message -->
                <div id="uploadSuccess" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-700">Upload complete!</p>
                            <p class="text-sm text-green-600">Your document has been uploaded successfully.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full max-h-96 overflow-y-auto animate-in fade-in scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Assign Document to Employees</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="assignForm" action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Document Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Document Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="e.g., Employee Handbook">
                </div>

                <!-- Document Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Optional description"></textarea>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Document *</label>
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-gray-600 font-medium">Drag and drop or <span class="text-blue-600">click to upload</span></p>
                        <p class="text-gray-500 text-sm mt-1">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                        <input type="file" name="document" id="documentInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required class="hidden">
                    </div>
                    <div id="fileInfo" class="mt-3 hidden">
                        <p class="text-sm text-gray-600">Selected: <span id="fileName" class="font-semibold"></span></p>
                    </div>
                </div>

                <!-- Employee Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assign To Employees *</label>
                    <div id="employeeList" class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-xl p-4 bg-gray-50">
                        @forelse($employees as $employee)
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg">
                            <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700">{{ $employee->name }}</span>
                        </label>
                        @empty
                        <p class="text-gray-500 text-sm">No employees found in your organization</p>
                        @endforelse
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-4 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-2.5 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                        Assign Document
                    </button>
                </div>
            </form>
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

    <!-- Quick Assign Modal (NEW) -->
    <div id="quickAssignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Assign Document</h3>
                <button onclick="closeQuickAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="text-sm text-gray-600 mb-4">Assign to: <span id="quickAssignDocName" class="font-semibold text-gray-900"></span></p>

            <!-- Search -->
            <input type="text" id="employeeSearch" placeholder="Search employees..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Employee List -->
            <div id="employeeListContainer" class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-2 min-h-64 flex items-center justify-center">
                <p class="text-gray-500 text-sm">Loading employees...</p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeQuickAssignModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteDocumentId = null;
        let quickAssignDocumentId = null;
        let allEmployees = [];

        function openAssignModal() {
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignForm').reset();
            document.getElementById('fileInfo').classList.add('hidden');
        }

        // ===== QUICK ASSIGN MODAL =====
        function openQuickAssignModal(docId, docTitle) {
            quickAssignDocumentId = docId;
            document.getElementById('quickAssignDocName').textContent = docTitle;
            document.getElementById('quickAssignModal').classList.remove('hidden');
            document.getElementById('employeeSearch').value = '';
            loadAndDisplayEmployees();
        }

        function closeQuickAssignModal() {
            quickAssignDocumentId = null;
            document.getElementById('quickAssignModal').classList.add('hidden');
            allEmployees = [];
        }

        function loadAndDisplayEmployees() {
            const container = document.getElementById('employeeListContainer');
            container.innerHTML = '<p class="text-gray-500 text-sm">Loading...</p>';
            
            fetch('{{ route("admin.documents.employees") }}')
                .then(r => r.json())
                .then(data => {
                    console.log('Employees loaded:', data.employees.length);
                    allEmployees = data.employees || [];
                    renderEmployeeList(allEmployees);
                })
                .catch(err => {
                    console.error('Error loading employees:', err);
                    container.innerHTML = '<p class="text-red-500 text-sm">Error loading employees</p>';
                });
        }

        function renderEmployeeList(employees) {
            const container = document.getElementById('employeeListContainer');
            
            if (!employees || employees.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-8">No employees found</p>';
                return;
            }

            let html = '<div class="divide-y divide-gray-200">';
            employees.forEach(emp => {
                const initial = emp.name.charAt(0).toUpperCase();
                const colors = ['bg-blue-500', 'bg-indigo-500', 'bg-purple-500', 'bg-pink-500', 'bg-green-500', 'bg-teal-500'];
                const colorIndex = emp.id % colors.length;
                const bgColor = colors[colorIndex];
                
                html += `
                    <button type="button" onclick="assignDocumentToEmployee(${emp.id})" class="w-full px-4 py-3 hover:bg-gray-50 transition-colors text-left flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-full ${bgColor} text-white flex items-center justify-center text-sm font-semibold flex-shrink-0">
                            ${initial}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 group-hover:text-blue-600">${emp.name}</p>
                            <p class="text-xs text-gray-500 truncate">${emp.email}</p>
                        </div>
                        <div class="text-gray-400 group-hover:text-blue-500 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                `;
            });
            html += '</div>';
            
            container.innerHTML = html;
            console.log('Rendered ' + employees.length + ' employees');
        }

        function filterEmployees() {
            const searchTerm = document.getElementById('employeeSearch').value.toLowerCase();
            if (!searchTerm) {
                renderEmployeeList(allEmployees);
                return;
            }

            const filtered = allEmployees.filter(emp => 
                emp.name.toLowerCase().includes(searchTerm) || 
                emp.email.toLowerCase().includes(searchTerm)
            );
            renderEmployeeList(filtered);
        }

        function assignDocumentToEmployee(employeeId) {
            if (!quickAssignDocumentId) {
                alert('Error: Document ID not set');
                return;
            }

            console.log('Assigning document', quickAssignDocumentId, 'to employee', employeeId);

            fetch('{{ route("admin.documents.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    document_id: quickAssignDocumentId,
                    user_id: employeeId
                })
            })
            .then(r => r.json())
            .then(data => {
                console.log('Assignment response:', data);
                if (data.success) {
                    closeQuickAssignModal();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error('Assignment error:', err);
                alert('Error assigning document: ' + err.message);
            });
        }

        // Search listener
        document.getElementById('employeeSearch')?.addEventListener('keyup', filterEmployees);

        function openUploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            resetUploadForm();
        }

        function resetUploadForm() {
            document.getElementById('uploadForm').reset();
            document.getElementById('uploadFileInfo').classList.add('hidden');
            document.getElementById('uploadSuccess').classList.add('hidden');
            document.getElementById('uploadProgress').classList.add('hidden');
            document.getElementById('uploadSubmitBtn').disabled = false;
        }

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
            form.action = '/admin/documents/' + deleteDocumentId;
            
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

        // File upload drag and drop
        const dropZone = document.getElementById('dropZone');
        const documentInput = document.getElementById('documentInput');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-blue-500', 'bg-blue-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-blue-500', 'bg-blue-50'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            documentInput.files = files;
            displayFileName();
        }, false);

        dropZone.addEventListener('click', () => documentInput.click());
        documentInput.addEventListener('change', displayFileName);

        function displayFileName() {
            if (documentInput.files.length > 0) {
                const fileName = documentInput.files[0].name;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileInfo').classList.remove('hidden');
            } else {
                document.getElementById('fileInfo').classList.add('hidden');
            }
        }

        // Real-time filtering
        let filterTimeout;

        function submitFiltersForm() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                document.getElementById('document-filter-form').submit();
            }, 300);
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

        document.getElementById('searchInput')?.addEventListener('keyup', () => {
            toggleClearButton();
            submitFiltersForm();
        });

        document.getElementById('signatureFilter')?.addEventListener('change', submitFiltersForm);
        document.getElementById('statusFilter')?.addEventListener('change', submitFiltersForm);
        document.getElementById('sortFilter')?.addEventListener('change', submitFiltersForm);

        toggleClearButton();

        // Close modals when clicking outside
        document.getElementById('assignModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Upload Modal - Close when clicking outside
        document.getElementById('uploadModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });

        // Quick Assign Modal - Close when clicking outside
        document.getElementById('quickAssignModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuickAssignModal();
            }
        });

        // =====  DRAG & DROP UPLOAD FUNCTIONALITY =====
        const uploadDropZone = document.getElementById('uploadDropZone');
        const uploadFileInput = document.getElementById('uploadFileInput');
        const uploadFileInfo = document.getElementById('uploadFileInfo');
        const uploadFileName = document.getElementById('uploadFileName');

        if (uploadDropZone) {
            // File input change
            uploadFileInput.addEventListener('change', handleFileSelect);

            // Drag over
            uploadDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadDropZone.classList.add('border-purple-500', 'bg-purple-100');
                uploadDropZone.classList.remove('border-purple-300', 'hover:border-purple-500', 'hover:bg-purple-50');
            });

            // Drag leave
            uploadDropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadDropZone.classList.remove('border-purple-500', 'bg-purple-100');
                uploadDropZone.classList.add('border-purple-300', 'hover:border-purple-500', 'hover:bg-purple-50');
            });

            // Drop
            uploadDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadDropZone.classList.remove('border-purple-500', 'bg-purple-100');
                uploadDropZone.classList.add('border-purple-300', 'hover:border-purple-500', 'hover:bg-purple-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    uploadFileInput.files = files;
                    handleFileSelect({ target: { files: files } });
                }
            });
        }

        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (10MB max)
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                alert('File size exceeds 10MB limit');
                uploadFileInput.value = '';
                uploadFileInfo.classList.add('hidden');
                return;
            }

            // Validate file type
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG, GIF');
                uploadFileInput.value = '';
                uploadFileInfo.classList.add('hidden');
                return;
            }

            // Show file info
            uploadFileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + 'MB)';
            uploadFileInfo.classList.remove('hidden');
        }

        // Handle upload form submission
        document.getElementById('uploadForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('uploadSubmitBtn');
            const progressBar = document.getElementById('uploadProgress');
            const progressBarInner = document.getElementById('uploadProgressBar');
            
            submitBtn.disabled = true;
            progressBar.classList.remove('hidden');

            const formData = new FormData(document.getElementById('uploadForm'));

            try {
                const response = await fetch(document.getElementById('uploadForm').action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Show success message
                    document.getElementById('uploadSuccess').classList.remove('hidden');
                    
                    // After 2 seconds, close modal and refresh
                    setTimeout(() => {
                        closeUploadModal();
                        location.reload();
                    }, 2000);
                } else {
                    const data = await response.json();
                    alert('Upload failed: ' + (data.message || 'Unknown error'));
                    submitBtn.disabled = false;
                    progressBar.classList.add('hidden');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
                submitBtn.disabled = false;
                progressBar.classList.add('hidden');
            }
        });
    </script>
</x-layout>
