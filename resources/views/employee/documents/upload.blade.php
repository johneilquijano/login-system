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
                            <h2 class="text-2xl font-bold text-gray-900">Upload Document</h2>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
                            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <!-- Document Title -->
                                <div>
                                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Document Title <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title"
                                        value="{{ old('title') }}"
                                        placeholder="e.g., Employment Contract"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                    >
                                    @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Description <span class="text-gray-500">(Optional)</span>
                                    </label>
                                    <textarea 
                                        id="description" 
                                        name="description"
                                        rows="4"
                                        placeholder="Add any relevant details about this document..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label for="document" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Upload File <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer" onclick="document.getElementById('document').click()">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-gray-900 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-gray-500 text-sm mt-1">PDF, Word, Image up to 10MB</p>
                                        <input 
                                            type="file" 
                                            id="document" 
                                            name="document"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                            class="hidden"
                                            onchange="updateFileName(this)"
                                        >
                                    </div>
                                    <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                                    @error('document')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex gap-4 pt-4">
                                    <button 
                                        type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                                    >
                                        Upload Document
                                    </button>
                                    <a 
                                        href="{{ route('documents.index') }}" 
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition"
                                    >
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = 'Selected: ' + input.files[0].name + ' (' + formatFileSize(input.files[0].size) + ')';
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Drag and drop
        const dropZone = document.querySelector('[onclick="document.getElementById(\'document\').click()"]');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.style.borderColor = '#3b82f6';
            dropZone.style.backgroundColor = '#eff6ff';
        }

        function unhighlight(e) {
            dropZone.style.borderColor = '#d1d5db';
            dropZone.style.backgroundColor = 'white';
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('document').files = files;
            updateFileName(document.getElementById('document'));
        }
    </script>
</x-layout>
