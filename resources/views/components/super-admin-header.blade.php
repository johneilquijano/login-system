<div class="bg-white shadow lg:hidden sticky top-0 z-40">
    <div class="px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
        <!-- Burger Menu Icon -->
        <button onclick="openSuperAdminDrawer()" 
                class="lg:hidden text-gray-900 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Title -->
        <h2 class="text-lg md:text-xl font-bold text-gray-900 flex-1 text-center md:text-left md:ml-4">
            {{ $title ?? 'System Admin' }}
        </h2>

        <!-- User Menu Button -->
        <button onclick="document.getElementById('mobileUserMenu').classList.toggle('hidden')" 
                class="text-gray-900 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
        </button>

        <!-- Mobile User Menu Dropdown -->
        <div id="mobileUserMenu" class="hidden absolute right-4 top-16 bg-white shadow-lg rounded-lg p-4 w-48 z-50">
            <div class="text-sm text-gray-900 mb-4 pb-4 border-b border-gray-200">
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded text-sm font-medium transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function closeSuperAdminDrawer() {
    const drawer = document.getElementById('superAdminDrawer');
    if (drawer) {
        drawer.classList.add('-translate-x-full');
    }
}

function openSuperAdminDrawer() {
    const drawer = document.getElementById('superAdminDrawer');
    if (drawer) {
        drawer.classList.remove('-translate-x-full');
    }
}
</script>

<!-- Mobile Drawer -->
<div id="superAdminDrawer" class="fixed inset-0 z-50 lg:hidden transition-transform transform -translate-x-full duration-300">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 lg:hidden" onclick="closeSuperAdminDrawer()" style="cursor: pointer;"></div>

    <!-- Drawer Content -->
    <div class="fixed left-0 top-0 bottom-0 w-64 bg-gray-900 text-white shadow-lg flex flex-col">
        <!-- Drawer Header -->
        <div class="p-4 md:p-6 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h1 class="text-lg md:text-xl font-bold">System Admin</h1>
                <p class="text-xs text-gray-400 mt-1">Super Admin Panel</p>
            </div>
            <button onclick="closeSuperAdminDrawer()" 
                    class="text-gray-400 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Drawer Navigation -->
        <nav class="flex-1 px-3 md:px-4 py-4 md:py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('super-admin.dashboard') }}" 
                onclick="closeSuperAdminDrawer()"
                class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition text-sm md:text-base">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4"></path>
                </svg>
                Dashboard
            </a>

            <div class="pt-2 md:pt-4">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</p>
            </div>

            <a href="{{ route('super-admin.organizations.index') }}" 
                onclick="closeSuperAdminDrawer()"
                class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.organizations.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition text-sm md:text-base">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                </svg>
                Organizations
            </a>

            <a href="{{ route('super-admin.users.index') }}" 
                onclick="document.getElementById('superAdminDrawer').classList.add('-translate-x-full')"
                class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition text-sm md:text-base">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                Users
            </a>

            <div class="pt-2 md:pt-4">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">System</p>
            </div>

            <a href="{{ route('super-admin.feedback.index') }}" 
                onclick="document.getElementById('superAdminDrawer').classList.add('-translate-x-full')"
                class="block px-4 py-2 rounded-lg {{ request()->routeIs('super-admin.feedback.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition text-sm md:text-base">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Feedback Inbox
            </a>
        </nav>

        <!-- Drawer Footer -->
        <div class="p-3 md:p-4 border-t border-gray-800">
            <div class="text-xs md:text-sm text-gray-300 mb-3 md:mb-4">
                <p class="font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-600/20 rounded-lg transition text-xs md:text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
