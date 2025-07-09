<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SIMBARA - Sistem Inventaris dan Peminjaman Barang</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <div class="w-10 h-10 bg-white border-2 border-blue-600 rounded-lg flex items-center justify-center mr-3 relative">
                                <!-- Clipboard background -->
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                                <!-- 3D Box overlay -->
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-blue-600 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-xl font-bold text-gray-900">SIMBARA</span>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Peminjaman</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 text-sm font-medium">Tentang Kami</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 text-sm font-medium">Kontak</a>
                        </div>
                    </div>
                    
                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Daftar Akun</a>
                    </div>
                </div>
            </div>
                </nav>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                            Find, book and borrow <span class="text-blue-600">Easily</span>
                        </h1>
                        <p class="text-lg text-gray-600 mb-8">
                            Sistem peminjaman barang yang terintegrasi untuk memudahkan civitas akademik FMIPA dalam mengelola inventaris dan peminjaman barang.
                        </p>
                        
                        <!-- Search Bar -->
                        <div class="flex items-center bg-white rounded-lg shadow-lg p-2 mb-8">
                            <div class="flex items-center px-4 py-2 border-r border-gray-200">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a1 1 0 00-.707-.293H11.5a6.5 6.5 0 10-1.414 1.414v1.207a1 1 0 00.293.707l4.243 4.243a1 1 0 001.414-1.414z"></path>
                                </svg>
                                <input type="text" placeholder="Location" class="outline-none text-sm text-gray-700">
                            </div>
                            <div class="flex-1 px-4 py-2">
                                <input type="text" placeholder="Search for items..." class="w-full outline-none text-sm text-gray-700">
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                                Mulai Peminjaman
                            </button>
                        </div>
                    </div>
                    
                    <!-- Right Content - Equipment Images -->
                    <div class="relative">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Mikroskop</h3>
                                    <p class="text-sm text-gray-600">Tersedia untuk penelitian</p>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Proyektor</h3>
                                    <p class="text-sm text-gray-600">Untuk presentasi</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4 mt-8">
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Printer</h3>
                                    <p class="text-sm text-gray-600">Untuk dokumen</p>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Speaker</h3>
                                    <p class="text-sm text-gray-600">Audio system</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-blue-600 font-semibold text-sm uppercase tracking-wide mb-2">HOW IT WORK</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Mulai peminjaman dengan 4 langkah berikut ini</h2>
                </div>
                
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a1 1 0 00-.707-.293H11.5a6.5 6.5 0 10-1.414 1.414v1.207a1 1 0 00.293.707l4.243 4.243a1 1 0 001.414-1.414z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Validasi</h3>
                        <p class="text-sm text-gray-600">Validasi identitas dan kelengkapan berkas peminjam</p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengajuan</h3>
                        <p class="text-sm text-gray-600">Ajukan permohonan peminjaman barang dengan lengkap</p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Persetujuan</h3>
                        <p class="text-sm text-gray-600">Menunggu persetujuan dari admin dan disetujui</p>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengambilan</h3>
                        <p class="text-sm text-gray-600">Ambil barang sesuai jadwal dan gunakan dengan baik</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <p class="text-blue-600 font-semibold text-sm uppercase tracking-wide mb-2">WHY CHOOSE US</p>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">We offer the best experience with our rental deals</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Peminjaman mudah</h3>
                                    <p class="text-gray-600">Proses peminjaman yang cepat dan tanpa ribet melalui website</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Informasi yang terstruktur</h3>
                                    <p class="text-gray-600">Mendapatkan daftar barang yang bisa dipinjam dengan informasi lengkap</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Sistem terintegrasi</h3>
                                    <p class="text-gray-600">Pengelolaan peminjaman yang jelas dan mudah</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Keuntungan milik FMIPA</h3>
                                    <p class="text-gray-600">Tidak dipungut biaya untuk mahasiswa dan dosen FMIPA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Content - Illustration -->
                    <div class="relative">
                        <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Sistem Terpercaya</h3>
                                <p class="text-gray-600">Dikelola oleh FMIPA UNUD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Items Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-blue-600 font-semibold text-sm uppercase tracking-wide mb-2">POPULAR ITEM</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Item dengan peminjaman terbanyak</h2>
                </div>
                
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Item 1 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Microphone (Wired)</h3>
                            <p class="text-sm text-gray-600 mb-4">Audio equipment</p>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-semibold">15 items</span>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Projector</h3>
                            <p class="text-sm text-gray-600 mb-4">Display device</p>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-semibold">12 items</span>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Item 3 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Printer</h3>
                            <p class="text-sm text-gray-600 mb-4">Document printer</p>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-semibold">8 items</span>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Item 4 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Speaker</h3>
                            <p class="text-sm text-gray-600 mb-4">Audio system</p>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-semibold">10 items</span>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Login Section -->
        <section class="py-20 bg-blue-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Mulai Peminjaman Sekarang</h2>
                <p class="text-blue-100 mb-8">Pilih jenis akses sesuai dengan status Anda</p>
                
                <div class="grid md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                    <!-- Register -->
                    <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 group">
                        <div class="text-center">
                            <div class="flex justify-center mb-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Daftar Baru</h3>
                            <p class="text-gray-600 mb-6 text-sm">
                                Untuk pendaftar baru yang ingin meminjam barang
                            </p>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Daftar Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 group">
                        <div class="text-center">
                            <div class="flex justify-center mb-6">
                                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Login</h3>
                            <p class="text-gray-600 mb-6 text-sm">
                                Untuk pengguna yang sudah memiliki akun
                            </p>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                Login Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-white border-2 border-blue-600 rounded-lg flex items-center justify-center mr-3 relative">
                                <!-- Clipboard background -->
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                                <!-- 3D Box overlay -->
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-blue-600 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                                </div>
                            </div>
                            <span class="text-xl font-bold">SIMBARA</span>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Fakultas Matematika dan Ilmu Pengetahuan Alam
                        </p>
                        <p class="text-gray-400">
                            Jl. Raya Bandung-Sumedang Km. 21<br>
                            Jatinangor, Sumedang 45363
                        </p>
                        <div class="flex space-x-4 mt-4">
                            <p class="text-gray-400">📞 +62 8123 4567 890</p>
                        </div>
                        <div class="flex space-x-4 mt-2">
                            <p class="text-gray-400">✉️ simbara@gmail.com</p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Our Product</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Career</a></li>
                            <li><a href="#" class="hover:text-white">Features</a></li>
                            <li><a href="#" class="hover:text-white">Pricing</a></li>
                            <li><a href="#" class="hover:text-white">Download</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Download</a></li>
                            <li><a href="#" class="hover:text-white">Help Centre</a></li>
                            <li><a href="#" class="hover:text-white">Guides</a></li>
                            <li><a href="#" class="hover:text-white">Partner Network</a></li>
                            <li><a href="#" class="hover:text-white">Cruises</a></li>
                            <li><a href="#" class="hover:text-white">Developer</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-400">
                        Copyright 2024 - SIMBARA. All Rights Reserved
                    </p>
                </div>
        </div>
        </footer>
    </body>
</html>
