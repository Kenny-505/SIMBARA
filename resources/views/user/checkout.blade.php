@extends('layouts.user')

@section('title', 'Form Pengajuan - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.cart.index') }}" class="text-blue-600 hover:text-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Form Pengajuan Peminjaman</h1>
            <p class="text-gray-600">Lengkapi data untuk mengajukan peminjaman</p>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Terdapat error dalam form:</h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center">
        <div class="flex items-center text-blue-600">
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">
                1
            </div>
            <span class="ml-2 text-sm font-medium">Pilih Barang</span>
        </div>
        <div class="flex-1 h-px bg-blue-600 mx-4"></div>
        <div class="flex items-center text-blue-600">
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">
                2
            </div>
            <span class="ml-2 text-sm font-medium">Form Pengajuan</span>
        </div>
        <div class="flex-1 h-px bg-gray-300 mx-4"></div>
        <div class="flex items-center text-gray-400">
            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-medium">
                3
            </div>
            <span class="ml-2 text-sm font-medium">Konfirmasi</span>
        </div>
    </div>
</div>

<form id="checkoutForm" method="POST" action="{{ route('user.pengajuan.store') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Periode Peminjaman -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Periode Peminjaman</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required
                               value="{{ old('tanggal_pinjam') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pinjam') border-red-500 @enderror"
                               min="{{ date('Y-m-d', strtotime('+3 days')) }}"
                               onchange="updateEndDateMin(); calculateDuration();">
                        <p class="text-xs text-gray-500 mt-1">Minimal H-3 dari hari ini</p>
                        @error('tanggal_pinjam')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali" required
                               value="{{ old('tanggal_kembali') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_kembali') border-red-500 @enderror"
                               onchange="calculateDuration();">
                        <p class="text-xs text-gray-500 mt-1">Tanggal pengembalian barang</p>
                        @error('tanggal_kembali')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 rounded-md">
                    <p class="text-sm text-blue-800">
                        <strong>Durasi:</strong> <span id="duration-display">Pilih tanggal untuk melihat durasi</span>
                    </p>
                </div>
            </div>
            
            <!-- Data Pengambil -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Penanggung Jawab Pengambilan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_pengambil" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_pengambil" name="nama_pengambil" required
                               value="{{ old('nama_pengambil') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_pengambil') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('nama_pengambil')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Identitas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nomor_identitas" name="nomor_identitas" required
                               value="{{ old('nomor_identitas') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nomor_identitas') border-red-500 @enderror"
                               placeholder="NIM/KTP/SIM">
                        @error('nomor_identitas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor HP <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="nomor_hp" name="nomor_hp" required
                           value="{{ old('nomor_hp') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nomor_hp') border-red-500 @enderror"
                           placeholder="08xxxxxxxxxx">
                    @error('nomor_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Tujuan Peminjaman -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tujuan Peminjaman</h2>
                
                <div>
                    <label for="tujuan_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="tujuan_peminjaman" name="tujuan_peminjaman" required
                           value="{{ old('tujuan_peminjaman') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tujuan_peminjaman') border-red-500 @enderror"
                           placeholder="Contoh: Seminar Nasional AI, Workshop Fotografi, dll">
                    @error('tujuan_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pengajuan</h2>
                
                <!-- Items List -->
                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($item['barang']->foto_1)
                                <img src="data:image/jpeg;base64,{{ base64_encode($item['barang']->foto_1) }}" 
                                     alt="{{ $item['barang']->nama_barang }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item['barang']->nama_barang }}</h4>
                            <p class="text-xs text-gray-500">{{ $item['barang']->admin->asal ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-600">Qty: {{ $item['quantity'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Cost Summary -->
                <div class="border-t pt-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Item:</span>
                            <span class="font-medium">{{ count($cartItems) }} jenis</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Quantity:</span>
                            <span class="font-medium">{{ array_sum(array_column($cartItems, 'quantity')) }} unit</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-medium" id="duration-summary">-</span>
                        </div>
                        
                        @if($userType === 'non_civitas')
                        <div class="border-t pt-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya per Hari:</span>
                                <span class="font-medium">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold text-blue-600">
                                <span>Total Biaya:</span>
                                <span id="total-cost">Rp 0</span>
                            </div>
                        </div>
                        @else
                        <div class="border-t pt-2">
                            <div class="flex justify-between text-lg font-semibold text-green-600">
                                <span>Status:</span>
                                <span>GRATIS</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button type="submit" id="submitBtn" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Lanjut ke Konfirmasi
                    </button>
                    <a href="{{ route('user.cart.index') }}" class="w-full bg-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-400 transition-colors text-center block font-medium">
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
const dailyCost = {{ $totalCost }};
const userType = '{{ $userType }}';

function updateEndDateMin() {
    const startDate = document.getElementById('tanggal_pinjam').value;
    const endDateInput = document.getElementById('tanggal_kembali');
    
    if (startDate) {
        const startDateObj = new Date(startDate);
        startDateObj.setDate(startDateObj.getDate() + 1);
        const minEndDate = startDateObj.toISOString().split('T')[0];
        endDateInput.min = minEndDate;
        
        // Reset end date if it's before the new minimum
        if (endDateInput.value && endDateInput.value < minEndDate) {
            endDateInput.value = '';
        }
    }
}

function calculateDuration() {
    const startDate = document.getElementById('tanggal_pinjam').value;
    const endDate = document.getElementById('tanggal_kembali').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const timeDiff = end.getTime() - start.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff > 0) {
            const durationText = `${daysDiff} hari`;
            document.getElementById('duration-display').textContent = durationText;
            document.getElementById('duration-summary').textContent = durationText;
            
            if (userType === 'non_civitas') {
                const totalCost = dailyCost * daysDiff;
                document.getElementById('total-cost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
            }
        } else {
            document.getElementById('duration-display').textContent = 'Tanggal tidak valid';
            document.getElementById('duration-summary').textContent = '-';
            if (userType === 'non_civitas') {
                document.getElementById('total-cost').textContent = 'Rp 0';
            }
        }
    } else {
        document.getElementById('duration-display').textContent = 'Pilih tanggal untuk melihat durasi';
        document.getElementById('duration-summary').textContent = '-';
        if (userType === 'non_civitas') {
            document.getElementById('total-cost').textContent = 'Rp 0';
        }
    }
}

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    console.log('Form submit triggered'); // Debug log
    
    const submitBtn = document.getElementById('submitBtn');
    
    // Prevent double submission
    if (submitBtn.disabled) {
        e.preventDefault();
        console.log('Form already submitting, preventing double submit');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
    
    const startDate = document.getElementById('tanggal_pinjam').value;
    const endDate = document.getElementById('tanggal_kembali').value;
    const namaPengambil = document.getElementById('nama_pengambil').value;
    const nomorIdentitas = document.getElementById('nomor_identitas').value;
    const nomorHp = document.getElementById('nomor_hp').value;
    const tujuanPeminjaman = document.getElementById('tujuan_peminjaman').value;
    
    console.log('Form data:', {
        startDate,
        endDate,
        namaPengambil,
        nomorIdentitas,
        nomorHp,
        tujuanPeminjaman
    });
    
    // Basic validation
    if (!startDate || !endDate || !namaPengambil || !nomorIdentitas || !nomorHp || !tujuanPeminjaman) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Lanjut ke Konfirmasi';
        return;
    }
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const now = new Date();
    const minStartDate = new Date();
    minStartDate.setDate(now.getDate() + 3);
    
    if (start < minStartDate) {
        e.preventDefault();
        alert('Tanggal mulai harus minimal H-3 dari hari ini');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Lanjut ke Konfirmasi';
        return;
    }
    
    if (end <= start) {
        e.preventDefault();
        alert('Tanggal selesai harus setelah tanggal mulai');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Lanjut ke Konfirmasi';
        return;
    }
    
    console.log('Form validation passed, submitting...');
    
    // Set a timeout to re-enable button if something goes wrong
    setTimeout(function() {
        if (submitBtn.disabled) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Lanjut ke Konfirmasi';
            console.log('Re-enabled submit button after timeout');
        }
    }, 10000); // 10 seconds timeout
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateEndDateMin();
    calculateDuration();
});
</script>
@endsection 