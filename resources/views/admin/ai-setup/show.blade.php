<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="AI System Monitoring Setup" 
                    subtitle="Configure ChatGPT or AI agents to monitor your system continuously" 
                />

                <!-- Content -->
                <div class="p-8">
                    <div class="max-w-4xl">
                        <!-- Info Banner -->
                        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-blue-900">Setup AI Monitoring in 3 Steps</p>
                                    <p class="text-sm text-blue-800 mt-1">Copy your API credentials and system prompt, then paste into ChatGPT to enable continuous system monitoring.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: API Token -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 mb-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Copy Your API Token</h3>
                                    <p class="text-sm text-gray-600 mt-1">This token grants AI access to your admin system data</p>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">API Token</label>
                                <div class="flex gap-3">
                                    <input 
                                        type="text" 
                                        value="{{ $apiToken }}" 
                                        id="apiToken"
                                        readonly
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-mono text-sm focus:outline-none"
                                    />
                                    <button 
                                        onclick="copyApiToken()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition whitespace-nowrap"
                                    >
                                        Copy Token
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">Keep this token secure. Never share it publicly.</p>
                            </div>
                        </div>

                        <!-- Step 2: System Prompt -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 mb-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">2</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Copy Your System Prompt</h3>
                                    <p class="text-sm text-gray-600 mt-1">Pre-configured instructions for your AI agent with your API token already included</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">System Prompt (Ready to Paste into ChatGPT)</label>
                                <textarea 
                                    id="systemPrompt"
                                    readonly
                                    rows="20"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-mono text-xs focus:outline-none"
                                >{{ $systemPrompt }}</textarea>
                                <button 
                                    onclick="copySystemPrompt()"
                                    class="mt-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition"
                                >
                                    Copy Full Prompt
                                </button>
                                <p class="text-xs text-gray-600 mt-2">This prompt includes your API token and all configuration needed.</p>
                            </div>
                        </div>

                        <!-- Step 3: Setup in ChatGPT -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 mb-6">
                            <div class="flex items-start gap-4 mb-6">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">3</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Paste into ChatGPT</h3>
                                    <p class="text-sm text-gray-600 mt-1">Setup AI monitoring in ChatGPT</p>
                                </div>
                            </div>

                            <ol class="space-y-3 text-sm text-gray-700 bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <li class="flex gap-3">
                                    <span class="font-semibold text-blue-600">1.</span>
                                    <span>Open <a href="https://chatgpt.com" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold">ChatGPT</a> in a new tab</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="font-semibold text-blue-600">2.</span>
                                    <span>Start a new conversation</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="font-semibold text-blue-600">3.</span>
                                    <span>Click the "Copy Full Prompt" button above</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="font-semibold text-blue-600">4.</span>
                                    <span>Paste the entire prompt into the ChatGPT conversation</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="font-semibold text-blue-600">5.</span>
                                    <span>Start chatting! Ask: "Give me a system health check" or "Show me any overdue items"</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Available Endpoints -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Available API Endpoints</h3>
                            <p class="text-sm text-gray-600 mb-4">Your AI agent has access to these endpoints for comprehensive monitoring:</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/documents</p>
                                    <p class="text-xs text-gray-600 mt-2">All documents with status, signatures, reviews</p>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/tools</p>
                                    <p class="text-xs text-gray-600 mt-2">Tool inventory with availability & condition</p>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/tool-checkouts</p>
                                    <p class="text-xs text-gray-600 mt-2">Tool loans/returns with due dates</p>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/inventory-requests</p>
                                    <p class="text-xs text-gray-600 mt-2">Requests with approval status</p>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/users</p>
                                    <p class="text-xs text-gray-600 mt-2">Users list with roles & status</p>
                                </div>
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                                    <p class="font-mono text-xs text-indigo-600 font-semibold">GET /api/dashboard-stats</p>
                                    <p class="text-xs text-gray-600 mt-2">System summary with alerts</p>
                                </div>
                            </div>
                        </div>

                        <!-- Example Requests -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Example Questions to Ask Your AI Agent</h3>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"What's the current system status?"</span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"Are there any overdue tool checkouts?"</span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"Show me documents pending review"</span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"List pending inventory requests"</span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"Give me a daily summary of issues and recommendations"</span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span>"Identify any process bottlenecks in the system"</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyApiToken() {
            const tokenInput = document.getElementById('apiToken');
            tokenInput.select();
            document.execCommand('copy');
            
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '✓ Copied!';
            button.classList.add('bg-green-600');
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        }

        function copySystemPrompt() {
            const promptInput = document.getElementById('systemPrompt');
            promptInput.select();
            document.execCommand('copy');
            
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '✓ Copied!';
            button.classList.add('bg-green-600');
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        }
    </script>
</x-layout>
