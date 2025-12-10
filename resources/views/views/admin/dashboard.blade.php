<x-layouts.admin-layout>
    <div>
        <!-- Dashboard Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Overview of your organization</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Active Users</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active_users'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Disabled Users</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['disabled_users'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Admin Count</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['admin_count'] }}</p>
            </div>
        </div>

        <!-- Recent Users Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    + Add User
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($recentUsers as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-medium text-gray-900">{{ $user->full_name }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-sm">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
