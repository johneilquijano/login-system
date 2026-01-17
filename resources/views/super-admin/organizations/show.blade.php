<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen flex-col lg:flex-row">
            <x-super-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto flex flex-col">
                <!-- Mobile/Tablet Header -->
                <x-super-admin-header title="Organization Details" />

                <!-- Desktop Header -->
                <div class="hidden lg:block bg-white shadow">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $organization->name }}</h2>
                        <div class="flex gap-4">
                            <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Edit
                            </a>
                            <a href="{{ route('super-admin.organizations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-4 md:p-6 flex-1 overflow-auto">
                    <div class="max-w-4xl">
                        <!-- Organization Details -->
                        <div class="bg-white rounded-lg shadow p-6 md:p-8 mb-6">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-6">Organization Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Name</p>
                                    <p class="text-base md:text-lg font-medium text-gray-900">{{ $organization->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Email</p>
                                    <p class="text-base md:text-lg font-medium text-gray-900 truncate">{{ $organization->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Slug</p>
                                    <p class="text-base md:text-lg font-medium text-gray-900 truncate">{{ $organization->slug }}</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Status</p>
                                    <p class="text-base md:text-lg font-medium">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $organization->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($organization->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Created</p>
                                    <p class="text-base md:text-lg font-medium text-gray-900">{{ $organization->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Last Updated</p>
                                    <p class="text-base md:text-lg font-medium text-gray-900">{{ $organization->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Organization Users -->
                        <div class="bg-white rounded-lg shadow p-6 md:p-8">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-6">Organization Members</h3>
                            
                            @if($organization->users->count() > 0)
                            <div class="overflow-x-auto -mx-6 md:mx-0 px-6 md:px-0">
                            <table class="w-full min-w-max md:min-w-full">
                                <thead class="bg-gray-100 border-b sticky top-0">
                                    <tr>
                                        <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Name</th>
                                        <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Email</th>
                                        <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Role</th>
                                        <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                                        <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->users as $user)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="px-3 md:px-6 py-4 text-xs md:text-sm font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-3 md:px-6 py-4 text-xs md:text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-3 md:px-6 py-4 text-xs md:text-sm whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                            @else
                            <p class="text-center text-gray-500 py-8 text-sm">No users in this organization yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
