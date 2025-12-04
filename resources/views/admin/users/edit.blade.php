<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <!-- Sidebar Navigation -->
            <div class="w-64 bg-white shadow-lg">
                <div class="h-16 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center px-6">
                    <h1 class="text-white font-bold text-xl">Admin Panel</h1>
                </div>
                
                <nav class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border-l-4 border-purple-600 bg-purple-50 text-purple-700 font-semibold flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                        </svg>
                        <span>Manage Users</span>
                    </a>
                    
                    <!-- <a href="{{ route('dashboard') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                        <span>Back to Dashboard</span>
                    </a> -->
                </nav>
                
                <!-- Logout Button -->
                <div class="absolute bottom-0 w-64 border-t px-6 py-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <div class="bg-white shadow">
                    <div class="px-8 py-4">
                        <h2 class="text-2xl font-bold text-gray-900">Edit User: {{ $user->name }}</h2>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-8">
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