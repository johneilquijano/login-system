<div class="bg-white rounded-lg shadow">
    <!-- Mobile/Tablet Cards -->
    <div class="lg:hidden p-4 space-y-3">
        @forelse($users as $user)
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $user->email }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $user->role === 'admin' ? 'border-purple-800 text-purple-800' : 'border-blue-800 text-blue-800' }} uppercase">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->status === 'active')
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border border-green-800 text-green-800 uppercase">Active</span>
                        @else
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border border-red-800 text-red-800 uppercase">Disabled</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-600">
                    <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-semibold text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                        Edit
                    </a>

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
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center text-sm text-gray-600">
                No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table -->
    <div class="hidden lg:block overflow-x-auto -mx-4 md:mx-0 px-4 md:px-0">
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
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border {{ $user->role === 'admin' ? 'border-purple-800 text-purple-800' : 'border-blue-800 text-blue-800' }} uppercase">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                        @if($user->status === 'active')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-green-800 text-green-800 uppercase">Active</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-red-800 text-red-800 uppercase">Disabled</span>
                        @endif
                    </td>
                    <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-4 md:px-6 py-4 text-sm whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Edit</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('admin.users.resetPassword.form', $user) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Reset</a>
                            <span class="text-gray-300">|</span>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline bg-transparent border-0 p-0 cursor-pointer">Delete</button>
                            </form>
                            <span class="text-gray-300">|</span>
                            @if($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.disable', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-orange-600 hover:text-orange-800 hover:underline bg-transparent border-0 p-0 cursor-pointer">Disable</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.users.enable', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 hover:underline bg-transparent border-0 p-0 cursor-pointer">Enable</button>
                            </form>
                            @endif
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
