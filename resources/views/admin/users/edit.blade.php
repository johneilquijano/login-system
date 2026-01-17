<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Edit User: {{ $user->name }}" 
                    subtitle="" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <div class="bg-white rounded-lg shadow max-w-2xl">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-8">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                    required
                                >
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="mb-6">
                                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                                <select 
                                    id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                                    required
                                >
                                    <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Org ID -->
                            <div class="mb-6">
                                <label for="org_id" class="block text-sm font-semibold text-gray-700 mb-2">Organization ID (Optional)</label>
                                <input 
                                    type="number" 
                                    id="org_id" 
                                    name="org_id" 
                                    value="{{ old('org_id', $user->org_id) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('org_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- User Info -->
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
                                <p class="text-sm text-gray-600"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4">
                                <button 
                                    type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                                >
                                    Update User
                                </button>
                                <a 
                                    href="{{ route('admin.users.index') }}" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition"
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