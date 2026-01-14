<!-- Feedback Button (Floating) -->
<button 
    id="feedbackButton"
    class="fixed bottom-6 right-6 z-40 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 flex items-center justify-center"
    title="Report issue or send feedback"
    aria-label="Feedback button"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
    </svg>
</button>

<!-- Capture Mode Overlay -->
<div 
    id="feedbackCaptureOverlay"
    class="hidden fixed inset-0 z-50"
    style="display: none; cursor: crosshair; pointer-events: none;"
>
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl px-6 py-4 max-w-sm" style="pointer-events: auto;">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Click to Report Issue</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Click on the area you want to report, or press <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">ESC</kbd> to cancel
            </p>
            <button 
                id="feedbackCancelButton"
                class="w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition"
            >
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div 
    id="feedbackModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <!-- Modal Header -->
            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Report Issue / Send Feedback</h2>
                <button 
                    id="feedbackModalClose"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
                    aria-label="Close modal"
                >
                    &times;
                </button>
            </div>

            <!-- Modal Content -->
            <form id="feedbackForm" class="p-6 space-y-4">
                <!-- Category -->
                <div>
                    <label for="feedbackCategory" class="block text-sm font-semibold text-gray-900 mb-2">
                        Category *
                    </label>
                    <select 
                        id="feedbackCategory"
                        name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">Select a category</option>
                        <option value="bug">🐛 Bug Report</option>
                        <option value="ux">✨ UX Feedback</option>
                        <option value="feature">💡 Feature Request</option>
                    </select>
                </div>

                <!-- Message -->
                <div>
                    <label for="feedbackMessage" class="block text-sm font-semibold text-gray-900 mb-2">
                        Description *
                    </label>
                    <textarea 
                        id="feedbackMessage"
                        name="message"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Please describe the issue or feedback in detail..."
                        required
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Max 1000 characters</p>
                </div>

                <!-- Severity (Optional) -->
                <div>
                    <label for="feedbackSeverity" class="block text-sm font-semibold text-gray-900 mb-2">
                        Severity (optional)
                    </label>
                    <select 
                        id="feedbackSeverity"
                        name="severity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Not specified</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <!-- Context Summary (Read-Only) -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Context</h3>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span class="font-medium">Page:</span>
                            <span id="feedbackContextPage" class="text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Clicked Element:</span>
                            <span id="feedbackContextElement" class="text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Position:</span>
                            <span id="feedbackContextPosition" class="text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Screen Size:</span>
                            <span id="feedbackContextScreen" class="text-gray-900">-</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields for Context -->
                <input type="hidden" id="feedbackRoute" name="route" />
                <input type="hidden" id="feedbackRouteName" name="route_name" />
                <input type="hidden" id="feedbackUrlPath" name="url_path" />
                <input type="hidden" id="feedbackViewportWidth" name="viewport_width" />
                <input type="hidden" id="feedbackViewportHeight" name="viewport_height" />
                <input type="hidden" id="feedbackUserAgent" name="user_agent" />
                <input type="hidden" id="feedbackClickX" name="click_x" />
                <input type="hidden" id="feedbackClickY" name="click_y" />
                <input type="hidden" id="feedbackPageX" name="page_x" />
                <input type="hidden" id="feedbackPageY" name="page_y" />
                <input type="hidden" id="feedbackScrollX" name="scroll_x" />
                <input type="hidden" id="feedbackScrollY" name="scroll_y" />
                <input type="hidden" id="feedbackElementTag" name="element_tag" />
                <input type="hidden" id="feedbackElementId" name="element_id" />
                <input type="hidden" id="feedbackElementName" name="element_name" />
                <input type="hidden" id="feedbackElementClasses" name="element_classes" />
                <input type="hidden" id="feedbackElementText" name="element_text" />
                <input type="hidden" id="feedbackElementAriaLabel" name="element_aria_label" />
                <input type="hidden" id="feedbackElementPlaceholder" name="element_placeholder" />
                <input type="hidden" id="feedbackElementSelector" name="element_selector" />
                <input type="hidden" id="feedbackElementPath" name="element_path" />

                <!-- Form Actions -->
                <div class="flex gap-3 border-t border-gray-200 pt-4">
                    <button 
                        type="button"
                        id="feedbackFormCancel"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition"
                    >
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Feedback system globals
    let feedbackCaptureMode = false;
    let feedbackClickData = null;

    const feedbackButton = document.getElementById('feedbackButton');
    const feedbackModal = document.getElementById('feedbackModal');
    const feedbackOverlay = document.getElementById('feedbackCaptureOverlay');
    const feedbackForm = document.getElementById('feedbackForm');
    const feedbackCancelButton = document.getElementById('feedbackCancelButton');
    const feedbackModalClose = document.getElementById('feedbackModalClose');
    const feedbackFormCancel = document.getElementById('feedbackFormCancel');

    // Open feedback modal and enter capture mode
    feedbackButton.addEventListener('click', (e) => {
        e.stopPropagation();
        feedbackCaptureMode = true;
        feedbackClickData = null;
        feedbackOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    // Exit capture mode
    function exitCaptureMode() {
        feedbackCaptureMode = false;
        feedbackOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Cancel capture
    feedbackCancelButton.addEventListener('click', exitCaptureMode);

    // ESC key to cancel
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && feedbackCaptureMode) {
            exitCaptureMode();
        }
    });

    // Capture click on page
    document.addEventListener('click', (e) => {
        if (!feedbackCaptureMode) return;

        e.preventDefault();
        e.stopPropagation();

        const element = e.target;
        const scrollX = window.scrollX || window.pageXOffset;
        const scrollY = window.scrollY || window.pageYOffset;
        
        // Calculate page-relative coordinates
        const pageX = e.clientX + scrollX;
        const pageY = e.clientY + scrollY;
        
        // Collect element metadata
        feedbackClickData = {
            clickX: e.clientX,
            clickY: e.clientY,
            pageX: pageX,
            pageY: pageY,
            scrollX: scrollX,
            scrollY: scrollY,
            elementTag: element.tagName.toLowerCase(),
            elementId: element.id || null,
            elementName: element.name || null,
            elementClasses: element.className || null,
            elementText: (element.textContent || '').trim().substring(0, 255),
            elementAriaLabel: element.getAttribute('aria-label'),
            elementPlaceholder: element.getAttribute('placeholder'),
            elementSelector: getElementSelector(element),
            elementPath: getElementPath(element),
        };

        // Populate display values
        document.getElementById('feedbackContextPage').textContent = window.location.pathname;
        document.getElementById('feedbackContextElement').textContent = 
            element.id ? `#${element.id}` : 
            (element.className ? `.${element.className.split(' ')[0]}` : element.tagName.toLowerCase());
        document.getElementById('feedbackContextPosition').textContent = 
            `x: ${e.clientX}, y: ${e.clientY} (page: ${pageX}, ${pageY})`;
        document.getElementById('feedbackContextScreen').textContent = 
            `${window.innerWidth} × ${window.innerHeight}`;

        // Populate hidden fields
        document.getElementById('feedbackRoute').value = window.location.pathname;
        document.getElementById('feedbackRouteName').value = document.querySelector('meta[name="route-name"]')?.content || '';
        document.getElementById('feedbackUrlPath').value = window.location.pathname;
        document.getElementById('feedbackViewportWidth').value = window.innerWidth;
        document.getElementById('feedbackViewportHeight').value = window.innerHeight;
        document.getElementById('feedbackUserAgent').value = navigator.userAgent;
        document.getElementById('feedbackClickX').value = feedbackClickData.clickX;
        document.getElementById('feedbackClickY').value = feedbackClickData.clickY;
        document.getElementById('feedbackPageX').value = feedbackClickData.pageX;
        document.getElementById('feedbackPageY').value = feedbackClickData.pageY;
        document.getElementById('feedbackScrollX').value = feedbackClickData.scrollX;
        document.getElementById('feedbackScrollY').value = feedbackClickData.scrollY;
        document.getElementById('feedbackElementTag').value = feedbackClickData.elementTag;
        document.getElementById('feedbackElementId').value = feedbackClickData.elementId;
        document.getElementById('feedbackElementName').value = feedbackClickData.elementName;
        document.getElementById('feedbackElementClasses').value = feedbackClickData.elementClasses;
        document.getElementById('feedbackElementText').value = feedbackClickData.elementText;
        document.getElementById('feedbackElementAriaLabel').value = feedbackClickData.elementAriaLabel;
        document.getElementById('feedbackElementPlaceholder').value = feedbackClickData.elementPlaceholder;
        document.getElementById('feedbackElementSelector').value = feedbackClickData.elementSelector;
        document.getElementById('feedbackElementPath').value = feedbackClickData.elementPath;

        // Exit capture mode and show modal
        exitCaptureMode();
        feedbackModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }, true);

    // Helper: Get CSS selector for element
    function getElementSelector(element) {
        if (!element || element === document.body || element === document.documentElement) {
            return '';
        }

        let selector = '';
        
        if (element.id) {
            selector = `#${element.id}`;
        } else if (element.className) {
            selector = `.${element.className.split(' ').join('.')}`;
        } else {
            selector = element.tagName.toLowerCase();
        }

        return selector;
    }

    // Helper: Get simplified DOM path
    function getElementPath(element) {
        const path = [];
        while (element && element !== document.body) {
            path.unshift(element.tagName.toLowerCase());
            element = element.parentElement;
        }
        return path.join(' > ');
    }

    // Close modal
    function closeFeedbackModal() {
        feedbackModal.style.display = 'none';
        feedbackForm.reset();
        feedbackClickData = null;
        document.body.style.overflow = 'auto';
    }

    feedbackModalClose.addEventListener('click', closeFeedbackModal);
    feedbackFormCancel.addEventListener('click', closeFeedbackModal);

    // Submit feedback
    feedbackForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(feedbackForm);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch('{{ route("feedback.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
                // Show success toast
                showToast('Feedback submitted successfully! Thank you for your input.', 'success');
                closeFeedbackModal();
            } else {
                showToast('Failed to submit feedback. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Feedback error:', error);
            showToast('An error occurred. Please try again.', 'error');
        }
    });

    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-semibold z-50 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Close modal on outside click
    feedbackModal.addEventListener('click', (e) => {
        if (e.target === feedbackModal) {
            closeFeedbackModal();
        }
    });
</script>
