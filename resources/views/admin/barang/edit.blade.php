@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
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

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
    <p class="text-sm text-gray-500">Edit informasi barang {{ $barang->nama_barang }}</p>
</div>

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
                <label for="stok_total" class="block text-sm font-medium text-gray-700 mb-2">
                    Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" id="stok_total" name="stok_total" value="{{ old('stok_total', $barang->stok_total) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stok_total') border-red-500 @enderror"
                       required>
                @error('stok_total')
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
                <label for="id_barang" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Barang
                </label>
                <input type="text" id="id_barang" value="{{ $barang->id_barang }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                       readonly>
                <p class="mt-1 text-xs text-gray-500">Kode barang tidak dapat diubah</p>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-6">
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

        <!-- Gambar Barang -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Barang</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Gambar 1 -->
                <div>
                    <label for="foto_1" class="block text-sm font-medium text-gray-700 mb-2">Gambar 1</label>
                    @if($barang->foto_1)
                        <div class="mb-3">
                            <img src="data:image/jpeg;base64,{{ base64_encode($barang->foto_1) }}" 
                                 alt="Foto 1" class="w-full h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    <input type="file" id="foto_1" name="foto_1" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('foto_1') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Max 2MB, format: JPEG, PNG, JPG</p>
                    @error('foto_1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar 2 -->
                <div>
                    <label for="foto_2" class="block text-sm font-medium text-gray-700 mb-2">Gambar 2</label>
                    @if($barang->foto_2)
                        <div class="mb-3">
                            <img src="data:image/jpeg;base64,{{ base64_encode($barang->foto_2) }}" 
                                 alt="Foto 2" class="w-full h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    <input type="file" id="foto_2" name="foto_2" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('foto_2') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Max 2MB, format: JPEG, PNG, JPG</p>
                    @error('foto_2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar 3 -->
                <div>
                    <label for="foto_3" class="block text-sm font-medium text-gray-700 mb-2">Gambar 3</label>
                    @if($barang->foto_3)
                        <div class="mb-3">
                            <img src="data:image/jpeg;base64,{{ base64_encode($barang->foto_3) }}" 
                                 alt="Foto 3" class="w-full h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    <input type="file" id="foto_3" name="foto_3" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('foto_3') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Max 2MB, format: JPEG, PNG, JPG</p>
                    @error('foto_3')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Denda -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Denda Keterlambatan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Denda Ringan -->
                <div>
                    <label for="denda_ringan" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Ringan (1-3 hari) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="denda_ringan" name="denda_ringan" value="{{ old('denda_ringan', $barang->denda_ringan) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_ringan') border-red-500 @enderror"
                           required>
                    @error('denda_ringan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Denda Sedang -->
                <div>
                    <label for="denda_sedang" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Sedang (4-7 hari) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="denda_sedang" name="denda_sedang" value="{{ old('denda_sedang', $barang->denda_sedang) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_sedang') border-red-500 @enderror"
                           required>
                    @error('denda_sedang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Denda Parah -->
                <div>
                    <label for="denda_parah" class="block text-sm font-medium text-gray-700 mb-2">
                        Denda Parah (>7 hari) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="denda_parah" name="denda_parah" value="{{ old('denda_parah', $barang->denda_parah) }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('denda_parah') border-red-500 @enderror"
                           required>
                    @error('denda_parah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex items-center justify-end space-x-4">
            <a href="{{ route('admin.barang.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Update Barang
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview image uploads
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
    
    // Auto-format number inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters except decimal point
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    });
</script>
@endpush 