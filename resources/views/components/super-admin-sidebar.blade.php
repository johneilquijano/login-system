<div class="w-64 bg-gray-900 text-white flex flex-col">
    <!-- Sidebar Header -->
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-xl font-bold">System Admin</h1>
        <p class="text-xs text-gray-400 mt-1">Super Admin Panel</p>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('super-admin.dashboard') }}" 
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4"></path>
            </svg>
            Dashboard
        </a>

        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</p>
        </div>

        <a href="{{ route('super-admin.organizations.index') }}" 
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.organizations.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
            </svg>
            Organizations
        </a>

        <a href="{{ route('super-admin.users.index') }}" 
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
            </svg>
            Users
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-800">
        <div class="text-sm text-gray-300 mb-4">
            <p class="font-medium">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-600/20 rounded-lg transition text-sm">
                Logout
            </button>
        </form>
    </div>
</div>
