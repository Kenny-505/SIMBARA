@extends('layouts.user')

@section('title', $barang->nama_barang . ' - SIMBARA')

@section('content')
<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('user.gallery') }}" class="text-blue-600 hover:text-blue-800">Katalog</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $barang->nama_barang }}</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Images Section -->
    <div>
        <!-- Main Image -->
        <div class="bg-gray-100 rounded-lg p-8 mb-4 flex items-center justify-center h-80">
            @if($barang->foto_1)
                <img id="mainImage" src="data:image/jpeg;base64,{{ base64_encode($barang->foto_1) }}" 
                     alt="{{ $barang->nama_barang }}" 
                     class="max-w-full max-h-full object-contain">
            @else
                <div class="text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-gray-500">Tidak ada foto</p>
                </div>
            @endif
        </div>
        
        <!-- Thumbnail Images -->
        <div class="grid grid-cols-3 gap-4">
            @for($i = 1; $i <= 3; $i++)
                @if($barang->{'foto_' . $i})
                    <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center h-24 cursor-pointer hover:bg-gray-200 transition-colors thumbnail-image"
                                             onclick="changeMainImage('data:image/jpeg;base64,{{ base64_encode($barang->{'foto_' . $i}) }}')">
                    <img src="data:image/jpeg;base64,{{ base64_encode($barang->{'foto_' . $i}) }}" 
                             alt="{{ $barang->nama_barang }} - Foto {{ $i }}" 
                             class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center h-24 border-2 border-dashed border-gray-300">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            @endfor
        </div>
    </div>

    <!-- Product Details -->
    <div>
        <!-- Basic Info -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h2>
                            <p class="text-blue-600 font-medium">{{ $barang->admin->asal }}</p>
        </div>

        <!-- Availability & Price -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 mr-2 {{ $barang->stok_tersedia > 0 ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-sm {{ $barang->stok_tersedia > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                    {{ $barang->stok_tersedia }}/{{ $barang->stok_total }} tersedia
                </span>
            </div>
            
            @if($isCivitas)
                <div class="text-2xl font-bold text-green-600 mb-4">
                    GRATIS
                    <span class="text-sm text-gray-500 font-normal block">untuk civitas akademik</span>
                </div>
            @else
                <div class="text-2xl font-bold text-gray-900 mb-4">
                    <span>Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</span>
                    <span class="text-lg text-gray-500 font-normal">/hari</span>
                </div>
            @endif
        </div>

        <!-- Description -->
        @if($barang->deskripsi)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
            <p class="text-gray-600 leading-relaxed">
                {{ $barang->deskripsi }}
            </p>
        </div>
        @endif

        <!-- Penalty Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Denda</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Denda Rusak Ringan:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($barang->denda_ringan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Denda Rusak Sedang:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($barang->denda_sedang, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Denda Rusak Parah:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($barang->denda_parah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Denda Keterlambatan:</span>
                        <span class="font-medium text-gray-900">Rp 250.000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('user.gallery') }}" 
               class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-200 transition-colors text-lg font-medium text-center block">
                Kembali ke Katalog
            </a>
        </div>
    </div>
</div>

<!-- Similar Items Section -->
@if($similarItems->count() > 0)
<div class="mt-12">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Barang Serupa</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($similarItems as $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <!-- Item Image -->
            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                @if($item->foto_1)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($item->foto_1) }}" 
                         alt="{{ $item->nama_barang }}" 
                         class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Item Info -->
            <div class="p-4">
                <h4 class="font-semibold text-gray-900 mb-1 text-sm">{{ Str::limit($item->nama_barang, 30) }}</h4>
                <p class="text-xs text-blue-600 mb-2">{{ $item->admin->nama_lembaga }}</p>
                
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs {{ $item->stok_tersedia > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->stok_tersedia }}/{{ $item->stok_total }} tersedia
                    </span>
                    @if(!$isCivitas)
                    <span class="text-xs text-gray-600">
                        Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}/hari
                    </span>
                    @endif
                </div>
                
                <a href="{{ route('user.item.detail', $item->id_barang) }}" 
                   class="w-full bg-blue-600 text-white py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-xs font-medium text-center block">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Information Panel -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h4 class="text-sm font-medium text-blue-800">Informasi Penting</h4>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Pengajuan peminjaman harus dilakukan minimal H-3 sebelum tanggal peminjaman</li>
                    <li>Barang harus dikembalikan dalam kondisi baik sesuai tanggal yang disepakati</li>
                    <li>Keterlambatan pengembalian dikenakan denda Rp 250.000 per peminjaman</li>
                    <li>Kerusakan barang akan dikenakan denda sesuai tingkat kerusakan</li>
                    @if(!$isCivitas)
                    <li>Pembayaran sewa harus dilakukan sebelum pengambilan barang</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
}

// Real-time stock check
function checkStock() {
            fetch('{{ route('user.check-stock', $barang->id_barang) }}')
        .then(response => response.json())
        .then(data => {
            // Update stock display if needed
            console.log('Stock updated:', data);
        })
        .catch(error => {
            console.error('Error checking stock:', error);
        });
}

// Check stock every 30 seconds
setInterval(checkStock, 30000);
</script>
@endsection 