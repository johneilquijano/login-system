<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="API Token Management" 
                    subtitle="Manage permanent API tokens for AI agents and automated access" 
                />

                <!-- Content -->
                <div class="p-4 md:p-8">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="max-w-3xl">
                        <!-- Main Card -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-8">
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">API Token for AI Agents</h3>
                                <p class="text-gray-600">Use this token to give AI agents (like ChatGPT) permanent access to your admin dashboard. This token never expires and is designed for automated/integration access.</p>
                            </div>

                            @if ($apiToken)
                                <!-- Token Display Section -->
                                <div class="space-y-6">
                                    <!-- Current Token -->
                                    <div class="bg-blue-50 rounded-lg p-4 md:p-6 border border-blue-200">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your API Token</label>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <input 
                                                type="text" 
                                                value="{{ $apiToken }}" 
                                                id="apiToken"
                                                readonly
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-mono text-sm focus:outline-none"
                                            />
                                            <button 
                                                onclick="copyApiToken()"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 px-4 md:px-6 rounded-lg transition whitespace-nowrap w-full sm:w-auto"
                                            >
                                                Copy Token
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">This token is permanent and never expires</p>
                                    </div>

                                    <!-- Token Metadata -->
                                    <div class="bg-gray-50 rounded-lg p-4 md:p-6 border border-gray-200">
                                        <h4 class="font-semibold text-gray-900 mb-4">Token Information</h4>
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Created</p>
                                                <p class="text-sm text-gray-900">{{ $apiTokenCreatedAt?->format('M d, Y \a\t h:i A') ?? 'Never' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Last Used</p>
                                                <p class="text-sm text-gray-900">{{ $apiTokenLastUsedAt?->format('M d, Y \a\t h:i A') ?? 'Never' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600 uppercase">Status</p>
                                                <p class="text-sm">
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Active</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Usage Instructions -->
                                    <div class="bg-indigo-50 rounded-lg p-4 md:p-6 border border-indigo-200">
                                        <h4 class="font-semibold text-gray-900 mb-4">How to Use This Token</h4>
                                        <div class="space-y-4 text-sm text-gray-700">
                                            <div>
                                                <p class="font-semibold text-gray-900 mb-1">Option 1: Authorization Header (Recommended)</p>
                                                <p class="text-xs bg-white p-2 rounded border border-indigo-300 font-mono">Authorization: Bearer {{ substr($apiToken, 0, 10) }}...{{ substr($apiToken, -10) }}</p>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 mb-1">Option 2: Query Parameter</p>
                                                <p class="text-xs bg-white p-2 rounded border border-indigo-300 font-mono">GET /admin-api?api_token={{ substr($apiToken, 0, 10) }}...{{ substr($apiToken, -10) }}</p>
                                            </div>
                                            <div class="pt-2 border-t border-indigo-300">
                                                <p class="font-semibold text-gray-900 mb-2">For ChatGPT / AI Agents:</p>
                                                <ol class="list-decimal list-inside space-y-1">
                                                    <li>Provide this entire token to your AI agent configuration</li>
                                                    <li>AI will use it to authenticate API requests</li>
                                                    <li>Token has permanent access (never expires)</li>
                                                    <li>Revoke anytime if you want to disable AI access</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security Notice -->
                                    <div class="bg-yellow-50 rounded-lg p-4 md:p-6 border border-yellow-200">
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-yellow-900">⚠️ Security Notice</p>
                                                <p class="text-sm text-yellow-800 mt-1">
                                                    This token grants full admin access to your system. Only share it with trusted AI agents. This token never expires - revoke it immediately if compromised.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                        <form method="POST" action="{{ route('admin.api-token.regenerate') }}" class="w-full sm:flex-1">
                                            @csrf
                                            <button type="submit" onclick="return confirm('This will generate a new token and invalidate the current one. Continue?')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 md:py-3 px-4 rounded-lg transition">
                                                Regenerate Token
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.api-token.revoke') }}" class="w-full sm:flex-1">
                                            @csrf
                                            <button type="submit" onclick="return confirm('This will revoke the token and any AI agents using it will lose access. Continue?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 md:py-3 px-4 rounded-lg transition">
                                                Revoke Token
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- No Token State -->
                                <div class="bg-gray-50 rounded-lg p-4 md:p-8 border-2 border-dashed border-gray-300 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <p class="text-gray-600 mb-4">No API token created yet</p>
                                    <p class="text-sm text-gray-500 mb-6">Generate an API token to allow AI agents and automated tools to access your admin dashboard.</p>
                                    <form method="POST" action="{{ route('admin.api-token.generate') }}">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 px-6 md:px-8 rounded-lg transition">
                                            Generate API Token
                                        </button>
                                    </form>
                                </div>
                            @endif
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
    </script>
</x-layout>
