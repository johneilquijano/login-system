<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            @if(Auth::user()->is_super_admin)
                <x-super-admin-sidebar />
            @else
                <x-admin-sidebar />
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Feedback Details" 
                    subtitle="Feedback #{{ $feedback->id }} from {{ $feedback->user->name }}" 
                />

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Back Button -->
                    <a href="{{ Auth::user()->is_super_admin ? route('super-admin.feedback.index') : route('admin.feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Inbox
                    </a>

                    <div class="grid grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="col-span-2 space-y-6">
                            <!-- Feedback Message Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $feedback->category_label }}</h3>
                                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold 
                                        @if($feedback->status === 'new')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($feedback->status === 'in_review')
                                            bg-blue-100 text-blue-800
                                        @elseif($feedback->status === 'fixed')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $feedback->status_label }}
                                    </span>
                                </div>

                                <!-- Feedback Content -->
                                <div class="mb-6">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->message }}</p>
                                </div>

                                <!-- Metadata -->
                                <div class="grid grid-cols-2 gap-6 border-t border-gray-200 pt-6">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Reporter</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $feedback->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $feedback->user->email }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Submitted</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $feedback->created_at->format('M d, Y H:i') }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Category</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $feedback->category_label }}</p>
                                    </div>

                                    @if($feedback->severity)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Severity</p>
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                            @if($feedback->severity === 'high')
                                                bg-red-100 text-red-800
                                            @elseif($feedback->severity === 'medium')
                                                bg-orange-100 text-orange-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif">
                                            {{ $feedback->severity_label }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Click Context Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Click Context</h3>
                                
                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Click Position -->
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Click Position</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            X: {{ $feedback->click_x }}, Y: {{ $feedback->click_y }}
                                        </p>
                                    </div>

                                    <!-- Scroll Position -->
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Scroll Position</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            X: {{ $feedback->scroll_x }}, Y: {{ $feedback->scroll_y }}
                                        </p>
                                    </div>

                                    <!-- Viewport -->
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Viewport Size</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $feedback->viewport_width }} × {{ $feedback->viewport_height }}
                                        </p>
                                    </div>

                                    <!-- Page URL -->
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Page</p>
                                        <p class="text-sm font-semibold text-gray-900 break-all">{{ $feedback->url_path }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Element Metadata Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Clicked Element Metadata</h3>
                                
                                <div class="space-y-4">
                                    @if($feedback->element_tag)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Element Tag</p>
                                        <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-900">{{ $feedback->element_tag }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_id)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Element ID</p>
                                        <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-900">#{{ $feedback->element_id }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_classes)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Element Classes</p>
                                        <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-900 break-all">{{ $feedback->element_classes }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_text)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Element Text</p>
                                        <p class="text-sm bg-gray-100 px-3 py-2 rounded text-gray-900 italic">{{ $feedback->element_text }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_aria_label)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">ARIA Label</p>
                                        <p class="text-sm bg-gray-100 px-3 py-2 rounded text-gray-900">{{ $feedback->element_aria_label }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_placeholder)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Placeholder</p>
                                        <p class="text-sm bg-gray-100 px-3 py-2 rounded text-gray-900">{{ $feedback->element_placeholder }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_selector)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">CSS Selector</p>
                                        <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-900 break-all">{{ $feedback->element_selector }}</p>
                                    </div>
                                    @endif

                                    @if($feedback->element_path)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">DOM Path</p>
                                        <p class="font-mono text-sm bg-gray-100 px-3 py-2 rounded text-gray-900 break-all">{{ $feedback->element_path }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Admin Notes Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Notes</h3>
                                
                                <form id="notesForm" onsubmit="updateNotes(event)">
                                    <textarea 
                                        id="adminNotes"
                                        rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Add internal notes about this feedback..."
                                    >{{ $feedback->admin_notes }}</textarea>
                                    
                                    <div class="mt-4 flex gap-3">
                                        <button 
                                            type="submit"
                                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition"
                                        >
                                            Save Notes
                                        </button>
                                        <span id="notesSaved" class="text-green-600 font-semibold flex items-center gap-2 hidden">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Saved
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Status Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                                
                                <div class="space-y-2">
                                    <button onclick="updateStatus('new')" class="w-full px-4 py-2 text-left rounded-lg font-semibold transition {{ $feedback->status === 'new' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }}">
                                        🆕 New
                                    </button>
                                    <button onclick="updateStatus('in_review')" class="w-full px-4 py-2 text-left rounded-lg font-semibold transition {{ $feedback->status === 'in_review' ? 'bg-blue-100 text-blue-800 border-2 border-blue-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }}">
                                        👀 In Review
                                    </button>
                                    <button onclick="updateStatus('fixed')" class="w-full px-4 py-2 text-left rounded-lg font-semibold transition {{ $feedback->status === 'fixed' ? 'bg-green-100 text-green-800 border-2 border-green-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }}">
                                        ✅ Fixed
                                    </button>
                                    <button onclick="updateStatus('ignored')" class="w-full px-4 py-2 text-left rounded-lg font-semibold transition {{ $feedback->status === 'ignored' ? 'bg-gray-300 text-gray-800 border-2 border-gray-500' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }}">
                                        🚫 Ignored
                                    </button>
                                </div>
                            </div>

                            <!-- Info Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Information</h3>
                                
                                <div class="space-y-3 text-sm">
                                    <div>
                                        <p class="text-gray-600 font-medium">User</p>
                                        <p class="text-gray-900">{{ $feedback->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Role</p>
                                        <p class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            {{ ucfirst($feedback->user_role) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Organization</p>
                                        <p class="text-gray-900">{{ $feedback->organization->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Feedback ID</p>
                                        <p class="font-mono text-gray-900">#{{ $feedback->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Submitted</p>
                                        <p class="text-gray-900">{{ $feedback->created_at->format('M d, Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Card -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                                
                                <div class="space-y-2">
                                    @if($feedback->url_path && $feedback->page_x !== null && $feedback->page_y !== null)
                                    <a href="{{ $feedback->url_path }}?feedback_id={{ $feedback->id }}&page_x={{ $feedback->page_x }}&page_y={{ $feedback->page_y }}" 
                                       class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center">
                                        👁 Open Page & Highlight
                                    </a>
                                    @else
                                    <button disabled 
                                       class="w-full px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                                        👁 Open Page & Highlight
                                    </button>
                                    @endif
                                    
                                    <button onclick="deleteFeedback()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                                        Delete Feedback
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const feedbackId = {{ $feedback->id }};
        const isSuperAdmin = {{ Auth::user()->is_super_admin ? 'true' : 'false' }};
        const baseUrl = isSuperAdmin ? '/super-admin/feedback' : '/admin/feedback';

        function updateStatus(status) {
            fetch(`${baseUrl}/${feedbackId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status }),
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                }
            })
            .catch(e => console.error(e));
        }

        function updateNotes(e) {
            e.preventDefault();
            const notes = document.getElementById('adminNotes').value;

            fetch(`${baseUrl}/${feedbackId}/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ admin_notes: notes }),
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    const saved = document.getElementById('notesSaved');
                    saved.classList.remove('hidden');
                    setTimeout(() => saved.classList.add('hidden'), 2000);
                }
            })
            .catch(e => console.error(e));
        }

        function deleteFeedback() {
            if (!confirm('Are you sure you want to delete this feedback?')) return;

            fetch(`${baseUrl}/${feedbackId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    window.location.href = '{{ Auth::user()->is_super_admin ? route("super-admin.feedback.index") : route("admin.feedback.index") }}';
                }
            })
            .catch(e => console.error(e));
        }
    </script>
</x-layout>
