<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Vehicles" 
                    subtitle="Manage and track vehicles" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <!-- Coming Soon Placeholder -->
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                            <!-- Icon -->
                            <div class="flex justify-center mb-6">
                                <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-100">
                                    <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Vehicle Maintenance Log</h2>
                            <p class="text-gray-600 mb-6 text-lg">Coming Soon</p>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                                We're preparing a comprehensive vehicle maintenance tracking system. This section will allow you to view, manage, and track vehicle maintenance logs and schedules.
                            </p>

                            <!-- Return Button -->
                            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
