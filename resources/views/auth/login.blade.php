<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>Login - SIMBARA</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left Side - Image -->
            <div class="hidden lg:flex lg:w-1/2 relative p-6">
                <div class="w-full h-full relative overflow-hidden rounded-3xl">
                    <img 
                        src="{{ asset('images/image 38.png') }}" 
                        alt="FMIPA Building" 
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/80 to-purple-600/80 flex flex-col justify-center items-start text-white p-12">
                        <div class="max-w-md">
                            <!-- Logo -->
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <!-- New Logo Design based on reference -->
                                    <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center mr-3 relative">
                                        <!-- Clipboard background -->
                                        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                        </svg>
                                        <!-- 3D Box overlay -->
                                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-blue-600 rounded flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-2xl font-bold">SIMBARA</span>
                                </div>
                            </div>
                            
                            <h1 class="text-3xl font-bold mb-6">Sistem Peminjaman Barang</h1>
                            <p class="text-lg mb-6 text-white/90">
                                Terintegrasi, cepat, dan andal untuk semua kebutuhan Anda di lingkungan FMIPA.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="mb-8">
                        <!-- Back to Home Link -->
                        <div class="flex justify-start mb-6">
                            <a href="{{ url('/') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>

                        <!-- Mobile Logo -->
                        <div class="lg:hidden mb-6 text-center">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-12 h-12 bg-white border-2 border-blue-600 rounded-xl flex items-center justify-center mr-3 relative">
                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                    </svg>
                                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-blue-600 rounded flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-xl font-bold text-gray-900">SIMBARA</span>
                            </div>
                        </div>

                        <!-- Top Right Actions -->
                        <div class="flex justify-end mb-6">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Belum punya akun?</p>
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Buat Akun</a>
                            </div>
                        </div>
                        
                        <!-- Welcome Text -->
                        <div class="text-left">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
                            <p class="text-gray-600">Silakan masukkan detail Anda untuk login.</p>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <input 
                                id="username" 
                                name="username" 
                                type="text" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-300 @enderror"
                                placeholder="Masukkan username Anda"
                                value="{{ old('username') }}"
                            >
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-300 @enderror"
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                    onclick="togglePassword()"
                                >
                                    <svg id="eye-open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eye-closed" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember" 
                                    name="remember" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Login gagal!
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Username atau password tidak valid.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Login
                            </button>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-xs text-gray-500">
                            © 2024 SIMBARA - FMIPA UNUD
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeOpen = document.getElementById('eye-open');
                const eyeClosed = document.getElementById('eye-closed');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            }
        </script>
    </body>
</html>
