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
                            <h2 class="text-2xl font-bold text-gray-900">Sign Document</h2>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <div class="max-w-4xl mx-auto">
                        <!-- Document Preview Section -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Document Name</p>
                                    <p class="text-gray-900 font-medium">{{ $document->title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Uploaded On</p>
                                    <p class="text-gray-900 font-medium">{{ $document->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">File Type</p>
                                    <p class="text-gray-900 font-medium">{{ $document->mime_type ? str_replace('application/', '', $document->mime_type) : 'Document' }}</p>
                                </div>
                            </div>
                            @if($document->description)
                            <div class="mt-4 pt-4 border-t" style="border-color: #ccc;">
                                <p class="text-sm text-gray-600">Description</p>
                                <p class="text-gray-900 mt-1">{{ $document->description }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Signature Section -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sign Document</h3>

                            <form method="POST" action="{{ route('documents.storeSign', $document) }}" id="signatureForm">
                                @csrf

                                <!-- Signature Type Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Signature Method</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" onclick="setSignatureType('draw')">
                                            <input type="radio" name="signature_type" value="draw" checked class="mr-2">
                                            <span class="font-medium text-gray-900">Draw Signature</span>
                                            <p class="text-xs text-gray-600 mt-1">Sign by drawing with your mouse or touch device</p>
                                        </label>
                                        <label class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" onclick="setSignatureType('type')">
                                            <input type="radio" name="signature_type" value="type" class="mr-2">
                                            <span class="font-medium text-gray-900">Type Signature</span>
                                            <p class="text-xs text-gray-600 mt-1">Sign using a stylized text signature</p>
                                        </label>
                                    </div>
                                </div>

                                <!-- Draw Signature Canvas -->
                                <div id="drawSection" class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Draw Your Signature</label>
                                    <div class="border border-gray-300 rounded-lg p-2 bg-white" style="max-width: 100%;">
                                        <canvas id="signatureCanvas" style="border: 1px solid #e5e7eb; border-radius: 0.375rem; display: block; cursor: crosshair; touch-action: none; max-width: 100%; height: auto;"></canvas>
                                    </div>
                                    <button type="button" class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium" onclick="clearCanvas()">
                                        Clear Signature
                                    </button>
                                </div>

                                <!-- Type Signature Input -->
                                <div id="typeSection" class="mb-6" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Signature</label>
                                    <input type="text" id="typeSignatureInput" placeholder="Type your name for signature" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                        style="font-family: 'Brush Script MT', cursive; font-size: 32px; letter-spacing: 2px;">
                                    <p class="text-xs text-gray-600 mt-2">This will be displayed as your typed signature</p>
                                </div>

                                <!-- Hidden input for signature data -->
                                <input type="hidden" id="signature_data" name="signature_data" value="">

                                <!-- Signature Acknowledgment -->
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-900">
                                        <strong>Note:</strong> By signing this document, you acknowledge that you have read and understood its contents. 
                                        Your signature will be recorded with a timestamp and cannot be undone.
                                    </p>
                                </div>

                                <!-- Agreement Checkbox -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="agreeCheckbox" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">I agree to sign this document electronically</span>
                                    </label>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition" onclick="captureSignature(event)">
                                        Sign Document
                                    </button>
                                    <a href="{{ route('documents.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
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

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.js"></script>
    <script>
        // Canvas Signature Drawing
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 3,
        });

        // Properly calibrate canvas on load and resize
        function calibrateCanvas() {
            const container = canvas.parentElement;
            const rect = canvas.getBoundingClientRect();
            
            // Set canvas resolution to match display size (device pixel ratio aware)
            const dpr = window.devicePixelRatio || 1;
            canvas.width = container.offsetWidth - 4;
            canvas.height = 200 * dpr;
            
            // Scale context for high-DPI displays
            ctx.scale(dpr, dpr);
            ctx.canvas.style.width = (container.offsetWidth - 4) + 'px';
            ctx.canvas.style.height = '200px';
            
            // Redraw signature if it exists
            signaturePad.resizeCanvas();
        }

        // Calibrate on page load
        window.addEventListener('load', calibrateCanvas);
        window.addEventListener('resize', calibrateCanvas);
        
        // Initial calibration
        calibrateCanvas();

        function setSignatureType(type) {
            const drawSection = document.getElementById('drawSection');
            const typeSection = document.getElementById('typeSection');
            
            if (type === 'draw') {
                drawSection.style.display = 'block';
                typeSection.style.display = 'none';
                document.querySelector('input[name="signature_type"][value="draw"]').checked = true;
                // Recalibrate when switching to draw
                setTimeout(calibrateCanvas, 100);
            } else {
                drawSection.style.display = 'none';
                typeSection.style.display = 'block';
                document.querySelector('input[name="signature_type"][value="type"]').checked = true;
            }
        }

        function clearCanvas() {
            signaturePad.clear();
        }

        function captureSignature(event) {
            const signatureType = document.querySelector('input[name="signature_type"]:checked').value;
            const signatureDataInput = document.getElementById('signature_data');
            
            if (signatureType === 'draw') {
                if (signaturePad.isEmpty()) {
                    event.preventDefault();
                    alert('Please draw your signature');
                    return false;
                }
                // Get signature as data URL (base64)
                signatureDataInput.value = signaturePad.toDataURL('image/png');
            } else {
                const typeSignature = document.getElementById('typeSignatureInput').value.trim();
                if (!typeSignature) {
                    event.preventDefault();
                    alert('Please enter your signature');
                    return false;
                }
                signatureDataInput.value = typeSignature;
            }

            // Check agreement
            if (!document.getElementById('agreeCheckbox').checked) {
                event.preventDefault();
                alert('Please agree to sign this document');
                return false;
            }
        }
    </script>
</x-layout>
