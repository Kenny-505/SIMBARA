<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'SIMBARA User Dashboard')</title>

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
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen w-full overflow-x-hidden">
        <div class="flex min-h-screen w-full">
            <!-- Fixed Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-gradient-to-r from-blue-600 to-purple-600' : 'bg-gradient-to-r from-blue-600 to-purple-600' }}">
                    <h1 class="text-xl font-bold text-white">SIMBARA</h1>
                </div>

                <!-- User Info -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-500' : 'bg-blue-500' }} rounded-full flex items-center justify-center">
                            <span class="text-white font-medium">{{ substr(auth()->guard('user')->user()->nama_penanggung_jawab ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->guard('user')->user()->nama_penanggung_jawab ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'Civitas Akademik' : 'Non-Civitas' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <nav class="mt-4 px-4 h-[calc(100%-8rem)] overflow-y-auto pb-20">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? route('user.civitas.dashboard') : route('user.non_civitas.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.*.dashboard') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                            </svg>
                            Dashboard
                        </a>

                        <!-- Gallery -->
                        <a href="{{ route('user.gallery') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.gallery') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Katalog Barang
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('user.cart.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.cart*') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H17M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                            </svg>
                            Keranjang
                            <span id="sidebar-cart-count" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center hidden">0</span>
                        </a>

                        <!-- Riwayat Peminjaman -->
                        <a href="{{ route('user.peminjaman.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.peminjaman*') || request()->routeIs('user.pengajuan*') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Riwayat Peminjaman
                        </a>

                        <!-- Pengembalian -->
                        <a href="{{ route('user.pengembalian.index') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.pengembalian*') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Pengembalian
                        </a>

                        <!-- Profile -->
                        <a href="{{ route('user.profile') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user.profile') ? (auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'bg-blue-50 text-blue-700 border-r-4 border-blue-700') : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>

                        <!-- Logout -->
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
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
                        <!-- Search Bar -->
                        <div class="flex-1 max-w-lg">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari barang, peminjaman, atau status...">
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Account Info -->
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->guard('user')->user()->nama_penanggung_jawab ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">
                                    Berakhir: {{ auth()->guard('user')->user()->tanggal_berakhir ? \Carbon\Carbon::parse(auth()->guard('user')->user()->tanggal_berakhir)->format('d M Y') : '-' }}
                                </p>
                            </div>

                            <!-- User Avatar -->
                            <div class="w-8 h-8 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-500' : 'bg-blue-500' }} rounded-full flex items-center justify-center">
                                <span class="text-white font-medium">{{ substr(auth()->guard('user')->user()->nama_penanggung_jawab ?? 'U', 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Scrollable Content -->
                <main class="bg-gray-50 min-h-screen w-full">
                    <div class="p-6 max-w-full">
                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                    <div class="ml-auto pl-3">
                                        <div class="-mx-1.5 -my-1.5">
                                            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                                <span class="sr-only">Dismiss</span>
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">
                                            {{ session('error') }}
                                        </p>
                                    </div>
                                    <div class="ml-auto pl-3">
                                        <div class="-mx-1.5 -my-1.5">
                                            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                                <span class="sr-only">Dismiss</span>
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-800">
                                            {{ session('warning') }}
                                        </p>
                                    </div>
                                    <div class="ml-auto pl-3">
                                        <div class="-mx-1.5 -my-1.5">
                                            <button type="button" class="inline-flex bg-yellow-50 rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                                <span class="sr-only">Dismiss</span>
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-blue-800">
                                            {{ session('info') }}
                                        </p>
                                    </div>
                                    <div class="ml-auto pl-3">
                                        <div class="-mx-1.5 -my-1.5">
                                            <button type="button" class="inline-flex bg-blue-50 rounded-md p-1.5 text-blue-500 hover:bg-blue-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                                <span class="sr-only">Dismiss</span>
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    <script>
        // Update cart count in sidebar
        function updateSidebarCartCount() {
            fetch('{{ route("user.cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const sidebarCartCount = document.getElementById('sidebar-cart-count');
                    if (data.count > 0) {
                        sidebarCartCount.textContent = data.count;
                        sidebarCartCount.classList.remove('hidden');
                    } else {
                        sidebarCartCount.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSidebarCartCount();
        });
    </script>
    </body>
</html> 