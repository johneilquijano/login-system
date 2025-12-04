<x-layouts.admin-layout>
    <div>
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Back to Users</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Info Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $user->full_name }}</h1>

                    <!-- User Details -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Role</p>
                                <p class="font-medium">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="font-medium">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Member Since</p>
                                <p class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-600">Last Updated</p>
                                <p class="font-medium text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email Verified</p>
                                <p class="font-medium text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not verified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-4 flex-wrap">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                            Edit User
                        </a>
                        
                        @if ($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.disable', $user->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm" onclick="return confirm('Are you sure you want to disable this user?')">
                                    Disable Account
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.enable', $user->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">
                                    Enable Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reset Password Card -->
            <div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Reset Password</h2>
                    <p class="text-gray-600 text-sm mb-4">
                        Set a new password for this user. They will receive a notification to change it on next login.
                    </p>

                    <form method="POST" action="{{ route('admin.users.resetPassword', $user->id) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-gray-500 text-xs mt-1">Min 8 chars, uppercase, lowercase, number</p>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium text-sm">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-layout>
