@extends('layouts.admin')

@section('title', 'Tambah Barang - Admin')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
                    <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang</h1>
            <p class="text-sm text-gray-600">Tambah barang baru ke inventaris {{ auth()->guard('admin')->user()->nama_lembaga }}</p>
        </div>
        <a href="{{ route('admin.barang.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
            <span>Kembali</span>
        </a>
                    </div>
                </div>

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
                                <span class="ml-1 text-sm font-medium text-gray-500">Tambah Barang</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Form -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form method="POST" action="{{ route('admin.barang.store') }}" enctype="multipart/form-data">
                        @csrf
                        
        <!-- Basic Information -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Barang <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_barang') border-red-500 @enderror"
                                       required>
                                @error('nama_barang')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                <!-- Stok Total -->
                            <div>
                    <label for="stok_total" class="block text-sm font-medium text-gray-700 mb-2">
                        Stok Total <span class="text-red-500">*</span>
                                </label>
                    <input type="number" id="stok_total" name="stok_total" value="{{ old('stok_total') }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stok_total') border-red-500 @enderror"
                                       required>
                    @error('stok_total')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                <!-- Stok Tersedia -->
                <div>
                    <label for="stok_tersedia" class="block text-sm font-medium text-gray-700 mb-2">
                        Stok Tersedia <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stok_tersedia" name="stok_tersedia" value="{{ old('stok_tersedia') }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stok_tersedia') border-red-500 @enderror"
                           required>
                    @error('stok_tersedia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Stok tersedia tidak boleh melebihi stok total</p>
                </div>

                            <!-- Harga Sewa -->
                            <div>
                                <label for="harga_sewa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Sewa (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa') }}" min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('harga_sewa') border-red-500 @enderror"
                                       required>
                                @error('harga_sewa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea id="deskripsi" name="deskripsi" rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                          required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                </div>
            </div>
                            </div>

        <!-- Foto Barang -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Barang</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Foto 1 -->
                <div>
                    <label for="gambar_1" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto 1 (Utama)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar_1" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload foto</span>
                                    <input id="gambar_1" name="gambar_1" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    @error('gambar_1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto 2 -->
                                        <div>
                    <label for="gambar_2" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto 2 (Opsional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar_2" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload foto</span>
                                    <input id="gambar_2" name="gambar_2" type="file" class="sr-only" accept="image/*">
                                            </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                                </div>
                                            </div>
                    @error('gambar_2')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                <!-- Foto 3 -->
                <div>
                    <label for="gambar_3" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto 3 (Opsional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar_3" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload foto</span>
                                    <input id="gambar_3" name="gambar_3" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    @error('gambar_3')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Denda -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Denda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Denda Ringan -->
                <div>
                    <label for="denda_ringan" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Ringan (Rp)
                    </label>
                    <input type="number" id="denda_ringan" name="denda_ringan" value="{{ old('denda_ringan', 0) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_ringan') border-red-500 @enderror">
                    @error('denda_ringan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Untuk kerusakan kecil</p>
                </div>

                <!-- Denda Sedang -->
                <div>
                    <label for="denda_sedang" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Sedang (Rp)
                    </label>
                    <input type="number" id="denda_sedang" name="denda_sedang" value="{{ old('denda_sedang', 0) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_sedang') border-red-500 @enderror">
                    @error('denda_sedang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Untuk kerusakan sedang</p>
                </div>

                <!-- Denda Parah -->
                <div>
                    <label for="denda_parah" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Parah (Rp)
                    </label>
                    <input type="number" id="denda_parah" name="denda_parah" value="{{ old('denda_parah', 0) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_parah') border-red-500 @enderror">
                    @error('denda_parah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Untuk kerusakan berat/hilang</p>
                </div>
                            </div>
                        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.barang.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors duration-200">
                                Batal
                            </a>
                            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Simpan Barang</span>
                            </button>
                        </div>
                    </form>
    </div>

    <script>
// Validate stok_tersedia <= stok_total
document.getElementById('stok_total').addEventListener('input', validateStock);
document.getElementById('stok_tersedia').addEventListener('input', validateStock);

function validateStock() {
    const stokTotal = parseInt(document.getElementById('stok_total').value) || 0;
    const stokTersedia = parseInt(document.getElementById('stok_tersedia').value) || 0;
    
    if (stokTersedia > stokTotal) {
        document.getElementById('stok_tersedia').value = stokTotal;
            }
        }
    </script>
@endsection 