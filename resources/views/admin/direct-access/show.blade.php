<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Direct Access Link" 
                    subtitle="Get instant access to your admin dashboard" 
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
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Your Direct Access Link</h3>
                                <p class="text-gray-600">Use this link to quickly access your admin dashboard without logging in. This link is unique to you and your organization.</p>
                            </div>

                            <!-- Link Display -->
                            <div class="bg-gray-50 rounded-lg p-4 md:p-6 mb-6 border border-gray-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Direct Access URL</label>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input 
                                        type="text" 
                                        value="{{ $directAccessUrl }}" 
                                        id="directAccessUrl"
                                        readonly
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-mono text-sm focus:outline-none"
                                    />
                                    <button 
                                        onclick="copyToClipboard()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 px-4 md:px-6 rounded-lg transition whitespace-nowrap w-full sm:w-auto"
                                    >
                                        Copy Link
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Click the button above to copy the link to your clipboard</p>
                            </div>

                            <!-- Token Display -->
                            <div class="bg-blue-50 rounded-lg p-4 md:p-6 mb-6 border border-blue-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Access Token</label>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input 
                                        type="text" 
                                        value="{{ $token }}" 
                                        id="accessToken"
                                        readonly
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-mono text-sm focus:outline-none"
                                    />
                                    <button 
                                        onclick="copyToken()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 px-4 md:px-6 rounded-lg transition whitespace-nowrap w-full sm:w-auto"
                                    >
                                        Copy Token
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">This token is part of your access URL</p>
                            </div>

                            <!-- Security Info -->
                            <div class="bg-yellow-50 rounded-lg p-4 md:p-6 mb-6 border border-yellow-200">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-yellow-900 mb-1">Security Notice</h4>
                                        <p class="text-sm text-yellow-800">This link expires in 1 year and logs you in automatically. Don't share this link with others. Keep it secure!</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Regenerate Button -->
                            <div class="border-t pt-6">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Regenerate Token</h3>
                                <p class="text-sm text-gray-600 mb-4">Generate a new direct access link. The old link will no longer work.</p>
                                <form method="POST" action="{{ route('admin.direct-access.regenerate') }}" onsubmit="return confirm('Are you sure? The current link will stop working.');" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                        Regenerate Link
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- How to Use -->
                        <div class="mt-8 bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">How to Use</h3>
                            <ol class="space-y-4 text-gray-700">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</span>
                                    <span>Copy the direct access link above</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">2</span>
                                    <span>Paste it into your browser's address bar</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">3</span>
                                    <span>Press Enter and you'll be logged in automatically</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">4</span>
                                    <span>You'll be taken to your admin dashboard</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard() {
        const input = document.getElementById('directAccessUrl');
        input.select();
        document.execCommand('copy');
        
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }

    function copyToken() {
        const input = document.getElementById('accessToken');
        input.select();
        document.execCommand('copy');
        
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }
    </script>
</x-layout>
