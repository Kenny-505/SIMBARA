<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'SIMBARA Admin Panel')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            html, body {
                width: 100%;
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }
            
            .content-container {
                width: calc(100% - 16rem);
                max-width: 100%;
                overflow-x: hidden;
            }
            
            @media (max-width: 1280px) {
                .content-container {
                    width: 100%;
                }
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen w-full overflow-x-hidden">
        <div class="flex min-h-screen w-full">
            <!-- Fixed Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-purple-600">
                    <h1 class="text-xl font-bold text-white">SIMBARA</h1>
                </div>

                <!-- Admin Info -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium">{{ substr(auth()->guard('admin')->user()->nama_lengkap ?? 'A', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->guard('admin')->user()->nama_lengkap ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Admin Panel</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <nav class="mt-4 px-4 h-[calc(100%-8rem)] overflow-y-auto pb-20">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>

                        <!-- Inventaris -->
                        <a href="{{ route('admin.barang.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.barang.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Inventaris
                        </a>

                        <!-- Peminjaman -->
                        <a href="{{ route('admin.peminjaman.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.peminjaman.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Peminjaman
                        </a>

                        <!-- Kalender -->
                        <a href="{{ route('admin.calendar.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.calendar.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Kalender
                        </a>

                        <!-- Logout -->
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('logout') }}" onsubmit="clearBrowserCache()">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="ml-64 content-container">
                <!-- Fixed Header -->
                <header class="bg-white shadow-sm border-b border-gray-200 h-16 sticky top-0 z-40 w-full">
                    <div class="flex items-center justify-between px-6 h-full">
                        <!-- Page Title -->
                        <div class="flex-1">
                            <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Admin Panel')</h1>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-2 text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5a6 6 0 0012 0z"/>
                                </svg>
                            </button>

                            <!-- Admin Info -->
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->guard('admin')->user()->nama_lengkap ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->guard('admin')->user()->username ?? 'admin' }}</p>
                            </div>

                            <!-- Admin Avatar -->
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium">{{ substr(auth()->guard('admin')->user()->nama_lengkap ?? 'A', 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Scrollable Content -->
                <main class="bg-gray-50 min-h-screen w-full">
                    <div class="p-6 max-w-full">
                        @if (session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        @yield('content')
                        {{ $slot ?? '' }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
        
        <script>
            function clearBrowserCache() {
                // Clear local storage and session storage
                localStorage.clear();
                sessionStorage.clear();
                
                // Clear any cached data
                if ('caches' in window) {
                    caches.keys().then(function(names) {
                        for (let name of names) {
                            caches.delete(name);
                        }
                    });
                }
            }
        </script>
    </body>
</html> 