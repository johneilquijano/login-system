@props([
    'title' => '',
    'subtitle' => ''
])

<header class="sticky top-0 z-40 bg-white shadow-sm border-b" style="border-bottom-color: #ccc;">
    <div class="flex items-center justify-between px-8 py-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="flex items-center space-x-6">
            <x-notification-bell />
            <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }} Account</p>
            </div>
        </div>
    </div>
</header>
