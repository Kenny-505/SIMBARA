<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>Register - SIMBARA</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 overflow-hidden">
        <div class="h-screen flex">
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
                            
                            <h1 class="text-3xl font-bold mb-6">Satu Langkah Mudah</h1>
                            <p class="text-lg mb-6 text-white/90">
                                Untuk semua kebutuhan peminjaman barang Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
                <div class="w-full max-w-lg">
                    <!-- Header -->
                    <div class="mb-6">
                        <!-- Back to Home Link -->
                        <div class="flex justify-start mb-4">
                            <a href="{{ url('/') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>

                        <!-- Mobile Logo -->
                        <div class="lg:hidden mb-4 text-center">
                            <div class="flex items-center justify-center mb-3">
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
                        <div class="flex justify-end mb-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Sudah punya akun?</p>
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Login</a>
                            </div>
                        </div>
                        
                        <!-- Welcome Text -->
                        <div class="text-left">
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat Akun Baru</h2>
                            <p class="text-gray-600 text-sm">Isi formulir di bawah untuk mendaftar.</p>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form class="space-y-4" action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- General Error Display -->
                        @if ($errors->any())
                            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                <span class="font-bold">Terjadi kesalahan!</span> Silakan periksa kembali data yang Anda masukkan.
                            </div>
                        @endif

                        <!-- Row 1: Name and ID -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="nama" class="block text-xs font-medium text-gray-700 mb-1">
                                    Nama Penanggung Jawab
                                </label>
                                <input 
                                    id="nama" 
                                    name="nama_penanggung_jawab" 
                                    type="text" 
                                    required 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_penanggung_jawab') border-red-500 @enderror"
                                    placeholder="Nama lengkap"
                                    value="{{ old('nama_penanggung_jawab') }}"
                                >
                                @error('nama_penanggung_jawab')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="identitas" class="block text-xs font-medium text-gray-700 mb-1">
                                    No Identitas (KTM/KTP)
                                </label>
                                <input 
                                    id="identitas" 
                                    name="no_identitas" 
                                    type="text" 
                                    required 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_identitas') border-red-500 @enderror"
                                    placeholder="Nomor identitas"
                                    value="{{ old('no_identitas') }}"
                                >
                                @error('no_identitas')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2: Email and Phone -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                                    Email Penanggung Jawab
                                </label>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    required 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                    placeholder="email@example.com"
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">
                                    Nomor WA Penanggung Jawab
                                </label>
                                <input 
                                    id="phone" 
                                    name="no_hp" 
                                    type="tel" 
                                    required 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_hp') border-red-500 @enderror"
                                    placeholder="08xxxxxxxxxx"
                                    value="{{ old('no_hp') }}"
                                >
                                @error('no_hp')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Activity Name -->
                        <div>
                            <label for="nama_kegiatan" class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Kegiatan
                            </label>
                            <input 
                                id="nama_kegiatan" 
                                name="nama_kegiatan" 
                                type="text" 
                                required 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_kegiatan') border-red-500 @enderror"
                                placeholder="Nama kegiatan atau acara"
                                value="{{ old('nama_kegiatan') }}"
                            >
                            @error('nama_kegiatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Borrowing Purpose -->
                        <div>
                            <label for="tujuan" class="block text-xs font-medium text-gray-700 mb-1">
                                Tujuan Peminjaman
                            </label>
                            <textarea 
                                id="tujuan" 
                                name="tujuan_peminjaman" 
                                rows="2"
                                required 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('tujuan_peminjaman') border-red-500 @enderror"
                                placeholder="Jelaskan tujuan peminjaman..."
                            >{{ old('tujuan_peminjaman') }}</textarea>
                            @error('tujuan_peminjaman')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Row 3: Start and End Date -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="tanggal_mulai_kegiatan" class="block text-xs font-medium text-gray-700 mb-1">
                                    Tgl. Mulai Kegiatan
                                </label>
                                <input 
                                    id="tanggal_mulai_kegiatan" 
                                    name="tanggal_mulai_kegiatan" 
                                    type="date" 
                                    required 
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_mulai_kegiatan') border-red-500 @enderror"
                                    value="{{ old('tanggal_mulai_kegiatan') }}"
                                >
                                @error('tanggal_mulai_kegiatan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_berakhir_kegiatan" class="block text-xs font-medium text-gray-700 mb-1">
                                    Tgl. Berakhir Kegiatan
                                </label>
                                <input 
                                    id="tanggal_berakhir_kegiatan" 
                                    name="tanggal_berakhir_kegiatan" 
                                    type="date" 
                                    required 
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_berakhir_kegiatan') border-red-500 @enderror"
                                    value="{{ old('tanggal_berakhir_kegiatan') }}"
                                >
                                @error('tanggal_berakhir_kegiatan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 4: Borrowing Type and Document -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="jenis_peminjam" class="block text-xs font-medium text-gray-700 mb-1">
                                    Jenis Peminjam
                                </label>
                                <select 
                                    id="jenis_peminjam" 
                                    name="jenis_peminjam" 
                                    required 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_peminjam') border-red-500 @enderror"
                                >
                                    <option value="" disabled {{ old('jenis_peminjam') ? '' : 'selected' }}>-- Pilih Jenis --</option>
                                    <option value="civitas_akademik" {{ old('jenis_peminjam') == 'civitas_akademik' ? 'selected' : '' }}>Civitas FMIPA</option>
                                    <option value="non_civitas_akademik" {{ old('jenis_peminjam') == 'non_civitas_akademik' ? 'selected' : '' }}>Non-Civitas</option>
                                </select>
                                @error('jenis_peminjam')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="surat_keterangan" class="block text-xs font-medium text-gray-700 mb-1">
                                    Surat Keterangan (PDF, JPG, PNG)
                                </label>
                                <input 
                                    id="surat_keterangan" 
                                    name="surat_keterangan" 
                                    type="file" 
                                    required 
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('surat_keterangan') border-red-500 @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >
                                @error('surat_keterangan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Agreement Checkbox -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-600">
                                    Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Daftar & Kirim Pengajuan
                            </button>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            © 2024 SIMBARA - FMIPA UNUD
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Date validation
            document.addEventListener('DOMContentLoaded', function() {
                const startDateInput = document.getElementById('tanggal_mulai_kegiatan');
                const endDateInput = document.getElementById('tanggal_berakhir_kegiatan');
                
                // Update end date minimum when start date changes
                startDateInput.addEventListener('change', function() {
                    const startDate = this.value;
                    if (startDate) {
                        endDateInput.min = startDate;
                        
                        // Clear end date if it's before start date
                        if (endDateInput.value && endDateInput.value < startDate) {
                            endDateInput.value = '';
                        }
                    }
                });
                
                // Validate end date is not before start date
                endDateInput.addEventListener('change', function() {
                    const startDate = startDateInput.value;
                    const endDate = this.value;
                    
                    if (startDate && endDate && endDate < startDate) {
                        alert('Tanggal berakhir kegiatan tidak boleh lebih awal dari tanggal mulai kegiatan.');
                        this.value = '';
                        this.focus();
                    }
                });
            });

            function togglePassword(inputId) {
                const passwordInput = document.getElementById(inputId);
                const eyeOpenId = inputId === 'password' ? 'eye-open-1' : 'eye-open-2';
                const eyeClosedId = inputId === 'password' ? 'eye-closed-1' : 'eye-closed-2';
                const eyeOpen = document.getElementById(eyeOpenId);
                const eyeClosed = document.getElementById(eyeClosedId);
                
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
