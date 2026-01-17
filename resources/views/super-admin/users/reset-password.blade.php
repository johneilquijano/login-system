<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen flex-col lg:flex-row">
            <x-super-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto flex flex-col">
                <!-- Mobile/Tablet Header -->
                <x-super-admin-header title="Reset User Password" />

                <!-- Desktop Header -->
                <div class="hidden lg:block bg-white shadow">
                    <div class="px-8 py-4">
                        <h2 class="text-2xl font-bold text-gray-900">Reset User Password</h2>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-4 md:p-6 flex-1">
                    <div class="max-w-2xl bg-white rounded-lg shadow p-6 md:p-8">
                        <p class="text-gray-600 mb-6">Reset password for <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>

                        <form method="POST" action="{{ route('super-admin.users.resetPassword', $user) }}">
                            @csrf

                            <!-- New Password -->
                            <div class="mb-6">
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                    placeholder="Enter new password"
                                >
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-8">
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Confirm new password"
                                >
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                <button 
                                    type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition text-sm"
                                >
                                    Reset Password
                                </button>
                                <a 
                                    href="{{ route('super-admin.users.index') }}" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-sm text-center"
                                >
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
