@props([
    'title' => '',
    'subtitle' => ''
])

<header class="sticky top-0 z-40 bg-white shadow-sm border-b" style="border-bottom-color: #ccc;">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
        <div class="flex items-center space-x-3 md:space-x-0">
            <!-- Burger Icon (Mobile Only) -->
            <button onclick="openMobileDrawer()" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Title -->
            <div class="flex-1">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-xs md:text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        
        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4 md:space-x-6">
            <x-notification-bell />
            <div class="hidden md:block text-right">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }} Account</p>
            </div>
        </div>
    </div>
</header>
