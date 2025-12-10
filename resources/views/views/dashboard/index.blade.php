<x-layouts.app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Welcome, {{ auth()->user()->first_name }}!
            </h1>
            <p class="text-gray-600 mt-2">
                Organization: <span class="font-semibold">{{ $organization->name }}</span>
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Status</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Active</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Role</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">Employee</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Department</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">—</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Member Since</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">{{ auth()->user()->created_at->format('M Y') }}</p>
            </div>
        </div>

        <!-- Main Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employee Documents -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Employee Documents</h2>
                    <span class="text-2xl">📄</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Access your employment documents, contracts, and important files.</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-blue-700 font-medium text-sm">Coming Soon</p>
                </div>
            </div>

            <!-- Tool Check-Out -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Tool Check-Out</h2>
                    <span class="text-2xl">🔧</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Check out and manage tools and equipment assigned to you.</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-green-700 font-medium text-sm">Coming Soon</p>
                </div>
            </div>

            <!-- Inventory Requests -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Inventory Requests</h2>
                    <span class="text-2xl">📦</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Submit and track requests for office supplies and equipment.</p>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                    <p class="text-purple-700 font-medium text-sm">Coming Soon</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section (Placeholder) -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <div class="text-center py-8">
                <p class="text-gray-500">No recent activity yet.</p>
            </div>
        </div>
    </div>
</x-layouts.app-layout>
