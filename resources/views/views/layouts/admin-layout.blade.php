<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="font-inter antialiased bg-gray-50">
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-900 text-white min-h-screen fixed left-0 top-0 overflow-y-auto">
                <div class="p-6 border-b border-gray-800">
                    <h1 class="text-2xl font-bold">Admin Panel</h1>
                    <p class="text-sm text-gray-400 mt-2">{{ auth()->user()->organization->name }}</p>
                </div>

                <nav class="p-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Users
                    </a>
                </nav>

                <div class="absolute bottom-0 left-0 w-64 p-4 border-t border-gray-800 bg-gray-900">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium">
                            Sign Out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 ml-64">
                <!-- Top Bar -->
                <div class="bg-white border-b border-gray-200 shadow-sm">
                    <div class="px-6 py-4">
                        <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->first_name }}</span>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 font-medium mb-2">There were some problems:</p>
                            <ul class="list-disc list-inside text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
