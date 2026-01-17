<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
        <table class="w-full min-w-max md:min-w-full">
            <thead class="bg-gray-100 border-b sticky top-0" style="border-bottom-color: #ccc;">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Name</th>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Email</th>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Role</th>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Joined</th>
                    <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-700 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50 transition" style="border-bottom-color: #ccc;">
                    <td class="px-4 md:px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                        @if($user->status === 'active')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Disabled</span>
                        @endif
                    </td>
                    <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                        <div class="relative inline-block text-left">
                            <button type="button" data-menu-button="{{ $user->id }}" onclick="toggleMenu({{ $user->id }})" aria-haspopup="true" aria-expanded="false" class="p-2 rounded-full hover:bg-gray-100" style="z-index:1;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>

                            <div id="menu-{{ $user->id }}" class="hidden origin-top-right absolute right-0 mt-2 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-10" style="background-color:#ffffff; min-width: max-content;">
                                <div class="py-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Edit</a>

                                    <a href="{{ route('admin.users.resetPassword.form', $user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Reset Password</a>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-red-50 whitespace-nowrap">Delete</button>
                                    </form>

                                    @if($user->status === 'active')
                                    <form method="POST" action="{{ route('admin.users.disable', $user) }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Disable</button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('admin.users.enable', $user) }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-green-50 whitespace-nowrap">Enable</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6 px-4 md:px-6 pagination">
        {{ $users->links() }}
    </div>
    @endif
</div>
