<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Vehicles" 
                    subtitle="Manage and track organization vehicles" 
                />

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Coming Soon Placeholder -->
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                            <!-- Icon -->
                            <div class="flex justify-center mb-6">
                                <div class="flex items-center justify-center h-20 w-20 rounded-full bg-purple-100">
                                    <svg class="h-10 w-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Vehicle Maintenance Log Section</h2>
                            <p class="text-gray-600 mb-6 text-lg">Coming Soon</p>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                                We're preparing a comprehensive vehicle management system for administrators. This section will allow you to track, manage, and monitor all organization vehicles, maintenance schedules, and inspection records.
                            </p>

                            <!-- Return Button -->
                            <a href="{{ route('admin.dashboard') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
