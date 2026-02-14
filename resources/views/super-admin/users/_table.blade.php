<div class="lg:hidden divide-y divide-gray-200">
    @forelse($users as $user)
    <div class="p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                <p class="text-xs text-gray-500">
                    @if($user->organization)
                        {{ $user->organization->name }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                {{ ucfirst($user->role) }}
            </span>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-600">
            @if($user->status === 'active')
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
            @else
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Disabled</span>
            @endif
            <span class="text-gray-500">Joined {{ $user->created_at->format('M d, Y') }}</span>
        </div>

        <div class="mt-4 flex flex-wrap gap-3 text-xs font-semibold">
            <a href="{{ route('super-admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <a href="{{ route('super-admin.users.resetPassword.form', $user) }}" class="text-indigo-600 hover:text-indigo-800">Change Password</a>
            <button type="button" onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800">Delete</button>
            @if($user->status === 'active')
            <button type="button" onclick="disableUser({{ $user->id }})" class="text-gray-700 hover:text-gray-900">Disable</button>
            @else
            <button type="button" onclick="enableUser({{ $user->id }})" class="text-green-700 hover:text-green-900">Enable</button>
            @endif
        </div>
    </div>
    @empty
    <div class="p-6 text-center text-xs text-gray-500">
        No users found. <a href="{{ route('super-admin.users.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a>
    </div>
    @endforelse
</div>

<div class="hidden lg:block">
    <table class="w-full min-w-max md:min-w-full">
        <thead class="bg-gray-100 border-b sticky top-0" style="border-bottom-color: #ccc;">
            <tr>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Name</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Email</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Organization</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Role</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Joined</th>
                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-700 whitespace-nowrap">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50 transition" style="border-bottom-color: #ccc;">
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-600 whitespace-nowrap">
                    @if($user->organization)
                        {{ $user->organization->name }}
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm whitespace-nowrap">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm whitespace-nowrap">
                    @if($user->status === 'active')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Disabled</span>
                    @endif
                </td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm text-gray-600 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-3 md:px-6 py-4 text-xs md:text-sm whitespace-nowrap">
                    <div class="relative inline-block text-left">
                        <button type="button" data-menu-button="{{ $user->id }}" onclick="toggleMenu({{ $user->id }})" aria-haspopup="true" aria-expanded="false" class="p-1 md:p-2 rounded-full hover:bg-gray-100" style="z-index:1;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 md:w-5 h-4 md:h-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>

                        <div id="menu-{{ $user->id }}" class="hidden origin-top-right absolute right-0 mt-2 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-10" style="background-color:#ffffff; min-width: max-content;">
                            <div class="py-1">
                                <a href="{{ route('super-admin.users.edit', $user) }}" class="block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Edit</a>

                                <a href="{{ route('super-admin.users.resetPassword.form', $user) }}" class="block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Change Password</a>

                                <button type="button" onclick="deleteUser({{ $user->id }})" class="w-full text-left block px-3 md:px-4 py-2 text-xs md:text-sm text-red-700 hover:bg-red-50 whitespace-nowrap">Delete</button>

                                @if($user->status === 'active')
                                <button type="button" onclick="disableUser({{ $user->id }})" class="w-full text-left block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Disable</button>
                                @else
                                <button type="button" onclick="enableUser({{ $user->id }})" class="w-full text-left block px-3 md:px-4 py-2 text-xs md:text-sm text-green-700 hover:bg-green-50 whitespace-nowrap">Enable</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 md:px-6 py-8 text-center text-xs md:text-sm text-gray-500">
                    No users found. <a href="{{ route('super-admin.users.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="mt-6 pagination">
    {{ $users->links() }}
</div>
@endif
