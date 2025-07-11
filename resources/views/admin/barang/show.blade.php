@extends('layouts.admin')

@section('title', 'Detail Barang - Admin')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Detail Barang</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
            <p class="text-sm text-gray-600">{{ $barang->nama_barang }} - ID: {{ $barang->id_barang }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.barang.edit', $barang) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Barang</span>
            </a>
            <a href="{{ route('admin.barang.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Barang</label>
                    <p class="text-sm text-gray-900">{{ $barang->nama_barang }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">ID Barang</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $barang->id_barang }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Stok Total</label>
                    <p class="text-sm text-gray-900">{{ $barang->stok_total }} unit</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Stok Tersedia</label>
                    <p class="text-sm text-gray-900">{{ $barang->stok_tersedia }} unit</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Harga Sewa</label>
                    <p class="text-sm text-gray-900">Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}/hari</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
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
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $barang->deskripsi }}</p>
        </div>

        <!-- Penalty Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Denda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Denda Ringan</label>
                    <p class="text-sm text-gray-900">Rp {{ number_format($barang->denda_ringan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Denda Sedang</label>
                    <p class="text-sm text-gray-900">Rp {{ number_format($barang->denda_sedang ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Denda Parah</label>
                    <p class="text-sm text-gray-900">Rp {{ number_format($barang->denda_parah ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Barang</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach(['foto_1', 'foto_2', 'foto_3'] as $index => $imageField)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Gambar {{ $index + 1 }}</label>
                        @if($barang->$imageField)
                            <img class="w-full h-48 object-cover rounded-lg border cursor-pointer hover:shadow-md transition-shadow" 
                                 src="data:image/jpeg;base64,{{ base64_encode($barang->$imageField) }}" 
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
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Total Peminjaman</span>
                        <span class="text-lg font-bold text-gray-900">0</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Sedang Dipinjam</span>
                        <span class="text-lg font-bold text-gray-900">0</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Ditambahkan</span>
                        <span class="text-sm text-gray-500">{{ $barang->created_at ? $barang->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Terakhir Update</span>
                        <span class="text-sm text-gray-500">{{ $barang->updated_at ? $barang->updated_at->format('d/m/Y') : '-' }}</span>
                    </div>
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

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection 