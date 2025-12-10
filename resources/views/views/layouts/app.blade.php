<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-inter antialiased bg-gray-50">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600">Portal</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </body>
</html>
