<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Document Preview" 
                    subtitle="" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Document Details Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-lg mb-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 lg:gap-8">
                            <!-- Left Column - Document Info -->
                            <div>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6">{{ $document->title }}</h3>
                                
                                <div class="space-y-3 md:space-y-4">
                                    <!-- File Type -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">File Type</label>
                                        <p class="text-gray-600">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-purple-800 text-purple-800 uppercase">
                                                {{ $document->formatted_type }}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- File Size -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">File Size</label>
                                        <p class="text-gray-600">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                                    </div>

                                    <!-- Uploaded Date -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Uploaded On</label>
                                        <p class="text-gray-600">{{ $document->created_at->format('F d, Y \a\t h:i A') }}</p>
                                    </div>

                                    <!-- Description -->
                                    @if($document->description)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                                        <p class="text-gray-600">{{ $document->description }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right Column - Status & Actions -->
                            <div>
                                <div class="bg-gray-50 rounded-xl p-4 md:p-6 space-y-4 md:space-y-6">
                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold 
                                            @if($document->status === 'approved')
                                                border border-green-800 text-green-800 uppercase
                                            @elseif($document->status === 'pending_review')
                                                border border-orange-800 text-orange-800 uppercase
                                            @elseif($document->status === 'rejected')
                                                border border-red-800 text-red-800 uppercase
                                            @else
                                                border border-gray-800 text-gray-800 uppercase
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                        </span>
                                    </div>

                                    <!-- Signature Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Signature Status</label>
                                        @if(!$document->signed_at)
                                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold border border-yellow-800 text-yellow-800 uppercase">
                                            Pending Signature
                                        </span>
                                        @else
                                        <div class="space-y-3">
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold border border-green-800 text-green-800 uppercase">
                                                Signed
                                            </span>
                                            <p class="text-sm text-gray-600">{{ $document->signed_at->format('F d, Y \a\t h:i A') }}</p>
                                            
                                            @if($document->signature_data)
                                            <div class="mt-4 pt-4 border-t border-gray-300">
                                                <p class="text-sm font-semibold text-gray-700 mb-3">Your Signature:</p>
                                                @if($document->signature_type === 'draw')
                                                    <img src="{{ $document->signature_data }}" alt="Signature" class="border border-gray-300 rounded-lg max-w-full h-auto" style="max-height: 150px;">
                                                @else
                                                    <div class="font-signature text-4xl text-gray-800 p-4 border border-gray-300 rounded-lg inline-block" style="font-family: cursive;">
                                                        {{ $document->signature_data }}
                                                    </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="pt-4 space-y-2 md:space-y-3 border-t border-gray-200">
                                        @if(!$document->signed_at)
                                        <a href="{{ route('documents.sign', $document) }}" class="w-full block text-center bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-semibold py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm md:text-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Sign Document
                                        </a>
                                        @else
                                        @endif

                                        @php
                                            $isPdf = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)) === 'pdf';
                                        @endphp

                                        <a href="{{ route('documents.download', $document) }}" class="w-full block text-center bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm md:text-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download Document
                                        </a>

                                        <!-- Signing Info -->
                                        @if($document->signed_at)
                                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                                            <p><strong>📄 Document Signed:</strong></p>
                                            <p class="mt-2">Your document has been signed on {{ $document->signed_at->format('M d, Y \a\t h:i A') }}</p>
                                            <p class="mt-2">Click <strong>"Download Document"</strong> (Blue) to download your signed document.</p>
                                            </ol>
                                        </div>
                                        @endif

                                        <a href="{{ route('documents.index') }}" class="w-full block text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all duration-300 text-sm md:text-base">
                                            Back to Documents
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Preview -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-lg">
                        <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6">Document Content</h4>
                        
                        @php
                            $mimeType = $document->mime_type;
                            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <!-- Image Preview -->
                        <div class="flex justify-center overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
                            <img src="{{ route('documents.download', $document) }}" alt="{{ $document->title }}" class="max-w-full max-h-96 rounded-lg border border-gray-200">
                        </div>

                        @elseif($extension === 'pdf')
                        <!-- PDF Preview with PDF.js -->
                        <div class="space-y-3 md:space-y-4">
                            <div class="flex flex-col md:flex-row items-center justify-between bg-gray-100 p-3 md:p-4 rounded-lg gap-2 md:gap-4">
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 md:gap-4 w-full md:w-auto">
                                    <button id="prevPage" class="px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm md:text-base">← Previous</button>
                                    <span id="pageInfo" class="text-gray-700 font-semibold text-sm md:text-base">Page <span id="pageNum">1</span> of <span id="pageCount">--</span></span>
                                    <button id="nextPage" class="px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm md:text-base">Next →</button>
                                </div>
                                <a href="{{ route('documents.download', $document) }}" class="px-3 md:px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition w-full md:w-auto text-center text-sm md:text-base">Download</a>
                            </div>
                            <div class="flex justify-center bg-gray-100 p-3 md:p-4 rounded-lg overflow-x-auto" style="min-height: 400px; md: min-height: 600px;">
                                <canvas id="pdfCanvas" style="max-width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem;"></canvas>
                            </div>
                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                        <script>
                            // Set up PDF.js worker
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                            let pdfDoc = null;
                            let pageNum = 1;
                            let pageRendering = false;
                            let pageNumPending = null;

                            const canvas = document.getElementById('pdfCanvas');
                            const ctx = canvas.getContext('2d');

                            function renderPage(num) {
                                pageRendering = true;
                                pdfDoc.getPage(num).then(function(page) {
                                    const viewport = page.getViewport({scale: 1.5});
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    const renderContext = {
                                        canvasContext: ctx,
                                        viewport: viewport
                                    };
                                    const renderTask = page.render(renderContext);

                                    renderTask.promise.then(function() {
                                        pageRendering = false;
                                        if (pageNumPending !== null) {
                                            renderPage(pageNumPending);
                                            pageNumPending = null;
                                        }
                                    });
                                });

                                document.getElementById('pageNum').textContent = num;
                            }

                            function queuePage(num) {
                                if (num < 1 || num > pdfDoc.numPages) {
                                    return;
                                }
                                if (pageRendering) {
                                    pageNumPending = num;
                                } else {
                                    renderPage(num);
                                }
                            }

                            document.getElementById('prevPage').addEventListener('click', function() {
                                if (pageNum <= 1) return;
                                pageNum--;
                                queuePage(pageNum);
                            });

                            document.getElementById('nextPage').addEventListener('click', function() {
                                if (pageNum >= pdfDoc.numPages) return;
                                pageNum++;
                                queuePage(pageNum);
                            });

                            // Load the PDF
                            const url = '{{ route('documents.download', $document) }}';
                            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                                pdfDoc = pdf;
                                document.getElementById('pageCount').textContent = pdf.numPages;
                                renderPage(pageNum);
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                                document.getElementById('pdfCanvas').parentElement.innerHTML = '<div class="text-center py-12"><p class="text-gray-600">Error loading PDF. <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:underline">Download instead</a></p></div>';
                            });
                        </script>

                        @elseif(in_array($extension, ['doc', 'docx']))
                        <!-- Word Document Preview Placeholder -->
                        <div class="bg-gray-50 rounded-lg p-6 md:p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 md:w-16 h-12 md:h-16 text-blue-500 mx-auto mb-3 md:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base">Word documents cannot be previewed in the browser</p>
                            <a href="{{ route('documents.download', $document) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 md:px-6 rounded-lg transition text-sm md:text-base">
                                Download Document to View
                            </a>
                        </div>

                        @else
                        <!-- Unknown File Type -->
                        <div class="bg-gray-50 rounded-lg p-6 md:p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 md:w-16 h-12 md:h-16 text-gray-400 mx-auto mb-3 md:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base">This file type cannot be previewed in the browser</p>
                            <a href="{{ route('documents.download', $document) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 md:px-6 rounded-lg transition text-sm md:text-base">
                                Download File to View
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
