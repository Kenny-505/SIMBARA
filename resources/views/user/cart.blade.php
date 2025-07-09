@extends('layouts.user')

@section('title', 'Keranjang - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.gallery') }}" class="text-blue-600 hover:text-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keranjang Peminjaman</h1>
            <p class="text-gray-600">Review dan kelola barang yang akan dipinjam</p>
        </div>
    </div>
</div>

@if(count($cartItems) > 0)
<!-- Cart Items -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Dipilih ({{ count($cartItems) }} item)</h2>
        
        <div class="space-y-4">
            @foreach($cartItems as $item)
            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                <!-- Item Image -->
                <div class="flex-shrink-0">
                    @if($item['barang']->foto_1)
                        <img src="{{ route('user.barang.image', ['id' => $item['barang']->id_barang, 'image' => 1]) }}" 
                             alt="{{ $item['barang']->nama_barang }}" 
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Item Details -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-medium text-gray-900">{{ $item['barang']->nama_barang }}</h3>
                    <p class="text-sm text-blue-600">{{ $item['barang']->admin->asal }}</p>
                    <p class="text-sm text-gray-500">Stok tersedia: {{ $item['barang']->stok_tersedia }}</p>
                    @if($userType === 'non_civitas')
                    <p class="text-sm text-gray-600">Harga: Rp {{ number_format($item['barang']->harga_sewa, 0, ',', '.') }}/hari</p>
                    @endif
                </div>
                
                <!-- Quantity Controls -->
                <div class="flex items-center space-x-3">
                    <button onclick="updateQuantity({{ $item['barang']->id_barang }}, {{ $item['quantity'] - 1 }})" 
                            class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full hover:bg-gray-300 flex items-center justify-center {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                    <button onclick="updateQuantity({{ $item['barang']->id_barang }}, {{ $item['quantity'] + 1 }})" 
                            class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full hover:bg-gray-300 flex items-center justify-center {{ $item['quantity'] >= $item['barang']->stok_tersedia ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $item['quantity'] >= $item['barang']->stok_tersedia ? 'disabled' : '' }}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Item Cost -->
                @if($userType === 'non_civitas')
                <div class="text-right">
                    <p class="text-sm text-gray-600">Subtotal:</p>
                    <p class="font-medium text-gray-900">Rp {{ number_format($item['cost'], 0, ',', '.') }}</p>
                </div>
                @endif
                
                <!-- Remove Button -->
                <button onclick="removeFromCart({{ $item['barang']->id_barang }})" 
                        class="text-red-600 hover:text-red-800 p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Cart Summary -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h2>
        
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Item:</span>
                <span class="font-medium">{{ count($cartItems) }} jenis barang</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Quantity:</span>
                <span class="font-medium">{{ array_sum(array_column($cartItems, 'quantity')) }} unit</span>
            </div>
            @if($userType === 'non_civitas')
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total Biaya per Hari:</span>
                    <span class="text-blue-600">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">*Biaya final akan dihitung berdasarkan durasi peminjaman</p>
            </div>
            @else
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between text-lg font-semibold text-blue-600">
                    <span>Status:</span>
                    <span>GRATIS (Civitas Akademik)</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex space-x-4">
    <a href="{{ route('user.gallery') }}" 
       class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-400 transition-colors text-center font-medium">
        Lanjut Belanja
    </a>
    <button onclick="clearCart()" 
            class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors font-medium">
        Kosongkan Keranjang
    </button>
    <a href="{{ route('user.cart.checkout') }}" 
       class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
        Lanjut ke Pengajuan
    </a>
</div>

@else
<!-- Empty Cart -->
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H17M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang Kosong</h3>
    <p class="text-gray-600 mb-6">Belum ada barang yang dipilih untuk dipinjam</p>
    <a href="{{ route('user.gallery') }}" 
       class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
        Mulai Belanja
    </a>
</div>
@endif

<script>
function updateQuantity(barangId, newQuantity) {
    if (newQuantity < 1) return;
    
    const formData = new FormData();
    formData.append('barang_id', barangId);
    formData.append('quantity', newQuantity);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    
    fetch('{{ route("user.cart.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

function removeFromCart(barangId) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('barang_id', barangId);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');
    
    fetch('{{ route("user.cart.remove") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

function clearCart() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');
    
    fetch('{{ route("user.cart.clear") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}
</script>
@endsection 