<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMBARA') }} - Detail Barang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-white border-2 border-blue-500 rounded-lg flex items-center justify-center">
                            <!-- Clipboard icon -->
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <!-- 3D Box overlay -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-blue-500 rounded-sm transform rotate-12 opacity-80"></div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-blue-600">SIMBARA</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.barang.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Inventaris
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Peminjaman
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
                        </svg>
                        Pengajuan
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
                        </svg>
                        Kalender
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Denda
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Page Title -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
                        <p class="text-sm text-gray-500">{{ $barang->nama_barang }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.barang.edit', $barang) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Barang
                        </a>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::guard('admin')->user()->username, 0, 2) }}</span>
                                </div>
                                <span class="text-sm font-medium">{{ Auth::guard('admin')->user()->username }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.barang.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                Inventaris
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500">Detail Barang</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Info -->
                    <div class="lg:col-span-2">
                        <!-- Basic Information -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nama Barang</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $barang->nama_barang }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kode Barang</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $barang->kode_barang }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Stok Tersedia</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $barang->stok_tersedia }} unit</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Harga Sewa</label>
                                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <div class="mt-1">
                                        @if($barang->stok_tersedia > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Tidak Tersedia
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $barang->deskripsi }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Barang</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach(['gambar_1', 'gambar_2', 'gambar_3'] as $index => $imageField)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Gambar {{ $index + 1 }}</label>
                                        @if($barang->$imageField)
                                            <img class="w-full h-48 object-cover rounded-lg border cursor-pointer" 
                                                 src="{{ route('admin.barang.image', ['id' => $barang->id_barang, 'image' => $imageField]) }}" 
                                                 alt="{{ $barang->nama_barang }} - Gambar {{ $index + 1 }}"
                                                 onclick="openImageModal(this.src)">
                                        @else
                                            <div class="w-full h-48 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <div class="text-center">
                                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <p class="text-sm text-gray-500">Tidak ada gambar</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Total Peminjaman</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $barang->peminjamanBarangs->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Sedang Dipinjam</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $barang->peminjamanBarangs()->whereHas('peminjaman', function($query) {
                                            $query->where('status_peminjaman', 'approved');
                                        })->sum('jumlah_pinjam') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Ditambahkan</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $barang->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Terakhir Update</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $barang->updated_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.barang.edit', $barang) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Barang
                                </a>
                                
                                <a href="{{ route('admin.barang.index') }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Kembali ke Inventaris
                                </a>
                                
                                <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus Barang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-4xl p-4">
            <img id="modalImage" class="w-full h-full object-contain rounded-lg" alt="Full size image">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</body>
</html> 