@extends('layouts.user')

@section('title', 'Katalog Barang - SIMBARA')

@section('content')
<!-- Page Header with Cart -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Katalog Barang</h1>
        <p class="text-gray-600">Temukan dan pinjam barang yang Anda butuhkan</p>
    </div>
    
    <!-- Cart Button -->
    <div class="relative">
        <a href="{{ route('user.cart.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H17M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
            </svg>
            <span>Keranjang</span>
            <span id="cart-count" class="bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">0</span>
        </a>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('user.gallery') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
        <!-- Search Input -->
        <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Barang</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Cari nama barang atau deskripsi...">
            </div>
        </div>

        <!-- Lembaga Filter -->
        <div class="md:w-48">
            <label for="lembaga" class="block text-sm font-medium text-gray-700 mb-2">Lembaga</label>
            <select id="lembaga" name="lembaga" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Lembaga</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id_admin }}" {{ request('lembaga') == $admin->id_admin ? 'selected' : '' }}>
                        {{ $admin->asal }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Availability Filter -->
        <div class="md:w-40">
            <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Ketersediaan</label>
            <select id="availability" name="availability" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>

        @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa')
        <!-- Price Range for Non-Civitas -->
        <div class="md:w-32">
            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Min</label>
            <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" 
                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="0">
        </div>
        <div class="md:w-32">
            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
            <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" 
                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="999999">
        </div>
        @endif

        <!-- Filter Button -->
        <div class="md:w-32">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Filter
            </button>
        </div>

        <!-- Reset Button -->
        @if(request()->hasAny(['search', 'lembaga', 'availability', 'min_price', 'max_price']))
        <div class="md:w-24">
            <a href="{{ route('user.gallery') }}" class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors text-center block">
                Reset
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Results Info -->
@if(request()->hasAny(['search', 'lembaga', 'availability', 'min_price', 'max_price']))
<div class="mb-4">
    <p class="text-sm text-gray-600">
        Menampilkan {{ $barangs->count() }} dari {{ $barangs->total() }} barang
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
    </p>
</div>
@endif

<!-- Catalog Grid -->
@if($barangs->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
    @foreach($barangs as $barang)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
        <!-- Item Image -->
        <div class="aspect-w-16 aspect-h-9 bg-gray-100">
            @if($barang->foto_1)
                <img src="{{ route('user.barang.image', ['id' => $barang->id_barang, 'image' => 1]) }}" 
                     alt="{{ $barang->nama_barang }}" 
                     class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Item Info -->
        <div class="p-4">
            <!-- Header -->
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $barang->nama_barang }}</h3>
                <p class="text-sm text-blue-600">{{ $barang->admin->asal }}</p>
            </div>

            <!-- Stock Info -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1 {{ $barang->stok_tersedia > 0 ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="{{ $barang->stok_tersedia > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $barang->stok_tersedia }}/{{ $barang->stok_total }} tersedia
                    </span>
                </div>

                @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa')
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</span>/hari
                </div>
                @else
                <div class="text-sm text-green-600 font-medium">
                    GRATIS
                </div>
                @endif
            </div>

            <!-- Description -->
            @if($barang->deskripsi)
            <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ Str::limit($barang->deskripsi, 80) }}</p>
            @endif

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('user.item.detail', $barang->id_barang) }}" 
                   class="flex-1 bg-blue-600 text-white py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-center text-sm font-medium">
                    Detail
                </a>
                @if($barang->stok_tersedia > 0)
                <button onclick="openAddToCartModal({{ $barang->id_barang }}, '{{ $barang->nama_barang }}', {{ $barang->stok_tersedia }}, {{ $barang->harga_sewa ?? 0 }})" 
                        class="flex-1 bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition-colors text-center text-sm font-medium">
                    + Keranjang
                </button>
                @else
                <button disabled 
                        class="flex-1 bg-gray-300 text-gray-500 py-2 px-3 rounded-lg cursor-not-allowed text-center text-sm font-medium">
                    Tidak Tersedia
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="flex justify-center">
    {{ $barangs->appends(request()->query())->links() }}
</div>

@else
<!-- Empty State -->
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada barang ditemukan</h3>
    <p class="mt-1 text-sm text-gray-500">
        @if(request()->hasAny(['search', 'lembaga', 'availability', 'min_price', 'max_price']))
            Coba ubah filter pencarian Anda atau
            <a href="{{ route('user.gallery') }}" class="text-blue-600 hover:text-blue-500">lihat semua barang</a>
        @else
            Belum ada barang yang tersedia saat ini.
        @endif
    </p>
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
            <h4 class="text-sm font-medium text-blue-800">Tips Peminjaman</h4>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Klik "Detail" untuk melihat informasi lengkap dan foto barang</li>
                    <li>Pastikan barang tersedia sebelum mengajukan peminjaman</li>
                    <li>Pengajuan harus dilakukan minimal H-3 sebelum tanggal peminjaman</li>
                    @if(auth()->guard('user')->user()->role->nama_role === 'user_fmipa')
                    <li>Sebagai civitas akademik, Anda tidak dikenakan biaya sewa</li>
                    @else
                    <li>Sebagai non-civitas, Anda akan dikenakan biaya sewa sesuai tarif</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<div id="addToCartModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah ke Keranjang</h3>
                    <button onclick="closeAddToCartModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <h4 id="modalItemName" class="font-medium text-gray-900 mb-2"></h4>
                    <p id="modalItemStock" class="text-sm text-gray-600 mb-2"></p>
                    <p id="modalItemPrice" class="text-sm text-gray-600"></p>
                </div>
                
                <div class="mb-6">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <div class="flex items-center space-x-3">
                        <button onclick="decreaseQuantity()" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full hover:bg-gray-300 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" id="quantity" min="1" value="1" class="w-16 text-center border border-gray-300 rounded-md py-1">
                        <button onclick="increaseQuantity()" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full hover:bg-gray-300 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="closeAddToCartModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button onclick="addToCart()" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentItem = null;
let maxStock = 0;

// Load cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

function updateCartCount() {
    fetch('{{ route("user.cart.count") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = data.count;
        })
        .catch(error => console.error('Error:', error));
}

function openAddToCartModal(itemId, itemName, stock, price) {
    currentItem = itemId;
    maxStock = stock;
    
    document.getElementById('modalItemName').textContent = itemName;
    document.getElementById('modalItemStock').textContent = `Stok tersedia: ${stock}`;
    
    @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa')
    document.getElementById('modalItemPrice').textContent = `Harga: Rp ${price.toLocaleString('id-ID')}/hari`;
    @else
    document.getElementById('modalItemPrice').textContent = 'Harga: GRATIS';
    @endif
    
    document.getElementById('quantity').value = 1;
    document.getElementById('quantity').max = stock;
    document.getElementById('addToCartModal').classList.remove('hidden');
}

function closeAddToCartModal() {
    document.getElementById('addToCartModal').classList.add('hidden');
    currentItem = null;
    maxStock = 0;
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    if (!currentItem || quantity < 1 || quantity > maxStock) {
        alert('Jumlah tidak valid');
        return;
    }
    
    const formData = new FormData();
    formData.append('barang_id', currentItem);
    formData.append('quantity', quantity);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("user.cart.add") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            closeAddToCartModal();
            updateCartCount();
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successDiv.textContent = data.message;
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Detailed error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi. Error: ' + error.message);
    });
}

// Close modal when clicking outside
document.getElementById('addToCartModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddToCartModal();
    }
});
</script>
@endsection 