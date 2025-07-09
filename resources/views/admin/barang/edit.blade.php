<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMBARA') }} - Edit Barang</title>

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
                        <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
                        <p class="text-sm text-gray-500">Edit informasi barang {{ $barang->nama_barang }}</p>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                            </svg>
                        </button>

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
                                <span class="ml-1 text-sm font-medium text-gray-500">Edit Barang</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form method="POST" action="{{ route('admin.barang.update', $barang) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Barang <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_barang') border-red-500 @enderror"
                                       required>
                                @error('nama_barang')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stok -->
                            <div>
                                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stok" name="stok" value="{{ old('stok', $barang->stok) }}" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stok') border-red-500 @enderror"
                                       required>
                                @error('stok')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harga Sewa -->
                            <div>
                                <label for="harga_sewa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Sewa (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa', $barang->harga_sewa) }}" min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('harga_sewa') border-red-500 @enderror"
                                       required>
                                @error('harga_sewa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Barang (Read-only) -->
                            <div>
                                <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Barang
                                </label>
                                <input type="text" id="kode_barang" value="{{ $barang->kode_barang }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                       readonly>
                                <p class="mt-1 text-xs text-gray-500">Kode barang tidak dapat diubah</p>
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea id="deskripsi" name="deskripsi" rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                          required>{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gambar Upload -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Barang
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @for($i = 1; $i <= 3; $i++)
                                        <div>
                                            <label for="gambar_{{ $i }}" class="block text-sm text-gray-600 mb-2">
                                                Gambar {{ $i }}
                                            </label>
                                            
                                            <!-- Current Image -->
                                            @php $currentImage = 'gambar_' . $i @endphp
                                            @if($barang->$currentImage)
                                                <div class="mb-2">
                                                    <img class="w-full h-32 object-cover rounded-lg border" 
                                                         src="{{ route('admin.barang.image', ['id' => $barang->id_barang, 'image' => $currentImage]) }}" 
                                                         alt="Current {{ $currentImage }}">
                                                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                                                </div>
                                            @endif
                                            
                                            <div class="relative">
                                                <input type="file" id="gambar_{{ $i }}" name="gambar_{{ $i }}" 
                                                       accept="image/jpeg,image/png,image/jpg"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gambar_' . $i) border-red-500 @enderror"
                                                       onchange="previewImage(this, 'preview_{{ $i }}')">
                                                <div id="preview_{{ $i }}" class="mt-2 hidden">
                                                    <img class="w-full h-32 object-cover rounded-lg border" alt="Preview">
                                                    <p class="text-xs text-gray-500 mt-1">Preview gambar baru</p>
                                                </div>
                                            </div>
                                            @error('gambar_' . $i)
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-xs text-gray-500">
                                                @if($barang->$currentImage)
                                                    Kosongkan untuk mempertahankan gambar lama. Max 2MB, format: JPEG, PNG, JPG
                                                @else
                                                    Max 2MB, format: JPEG, PNG, JPG
                                                @endif
                                            </p>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.barang.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Update Barang
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</body>
</html> 