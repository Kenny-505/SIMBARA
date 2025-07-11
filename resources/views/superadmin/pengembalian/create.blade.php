@extends('layouts.superadmin')

@section('title', 'Proses Pengembalian - SIMBARA')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('superadmin.pengembalian.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Proses Pengembalian</h1>
                <p class="text-gray-600 mt-1">Kode Peminjaman: {{ $peminjaman->kode_peminjaman }}</p>
            </div>
        </div>
    </div>

    <!-- Information Notice -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Verifikasi Pengembalian</h3>
                <p class="text-blue-700 mt-1">
                    Tentukan kondisi barang dan status keterlambatan berdasarkan verifikasi fisik.
                </p>
            </div>
        </div>
    </div>

    <!-- Loan and User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Loan Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjaman</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Kode Peminjaman</p>
                    <p class="font-medium text-gray-900">{{ $peminjaman->kode_peminjaman }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Mulai</p>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Selesai</p>
                    <p class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Perlu Verifikasi
                    </span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjam</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama Penanggung Jawab</p>
                    <p class="font-medium text-gray-900">{{ $peminjaman->user->nama_penanggung_jawab }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Organisasi</p>
                    <p class="font-medium text-gray-900">{{ $peminjaman->user->nama_organisasi }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kontak</p>
                    <p class="font-medium text-gray-900">{{ $peminjaman->user->nomor_telepon }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-900">{{ $peminjaman->user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Processing Form -->
    @if(isset($pengembalian))
        <form action="{{ route('superadmin.pengembalian.process.submit', $pengembalian->id_pengembalian) }}" method="POST" id="returnForm">
            @csrf
    @else
        <form action="{{ route('superadmin.pengembalian.store', $peminjaman->id_peminjaman) }}" method="POST" id="returnForm">
            @csrf
    @endif
        
        <!-- Late Status -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Keterlambatan</h2>
            <div class="max-w-md">
                <label for="is_late" class="block text-sm font-medium text-gray-700 mb-2">
                    Apakah pengembalian ini terlambat? <span class="text-red-500">*</span>
                </label>
                <select id="is_late" 
                        name="is_late" 
                        required
                        onchange="updateLateStatus()"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Status</option>
                    <option value="0" {{ old('is_late', isset($pengembalian) && isset($pengembalian->hari_telat) && $pengembalian->hari_telat == 0 ? '0' : '') == '0' ? 'selected' : '' }}>Tidak Terlambat</option>
                    <option value="1" {{ old('is_late', isset($pengembalian) && isset($pengembalian->hari_telat) && $pengembalian->hari_telat > 0 ? '1' : '') == '1' ? 'selected' : '' }}>Terlambat (Denda Rp 250.000)</option>
                </select>
                @error('is_late')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <div id="late_info" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md" style="display: none;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>Denda Keterlambatan: Rp 250.000</strong><br>
                                Denda ini akan ditambahkan ke total denda kerusakan barang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items to Return -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Barang yang Dikembalikan</h2>
            <div class="space-y-6">
                @foreach($peminjaman->peminjamanBarangs as $index => $peminjamanBarang)
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                                            @if($peminjamanBarang->barang->foto_1)
                    <img src="data:image/jpeg;base64,{{ base64_encode($peminjamanBarang->barang->foto_1) }}" 
                                     alt="{{ $peminjamanBarang->barang->nama_barang }}"
                                     class="w-20 h-20 object-cover rounded-lg mr-4">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $peminjamanBarang->barang->nama_barang }}</h3>
                                <p class="text-sm text-gray-600">Jumlah Dipinjam: {{ $peminjamanBarang->jumlah_pinjam }}</p>
                                                                    <p class="text-sm text-gray-600">ID Barang: {{ $peminjamanBarang->barang->id_barang }}</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="items[{{ $index }}][id_barang]" value="{{ $peminjamanBarang->barang->id_barang }}">
                    <input type="hidden" name="items[{{ $index }}][jumlah_kembali]" value="{{ $peminjamanBarang->jumlah_pinjam }}">
                    
                    <!-- Summary Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600 font-medium">Total Dikembalikan</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $peminjamanBarang->jumlah_pinjam }} Unit</p>
                            <p class="text-xs text-blue-600 mt-1">Seluruh unit harus dikembalikan</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Harga Sewa per Unit</p>
                            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($peminjamanBarang->barang->harga_sewa, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-600 mt-1">Denda keterlambatan terpisah</p>
                        </div>
                    </div>

                    <!-- Individual Unit Conditions -->
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Verifikasi Kondisi per Unit</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="mb-3 text-sm text-gray-600">
                                <strong>Info Denda:</strong> 
                                Ringan: Rp {{ number_format($peminjamanBarang->barang->denda_ringan, 0, ',', '.') }} • 
                                Sedang: Rp {{ number_format($peminjamanBarang->barang->denda_sedang, 0, ',', '.') }} • 
                                Parah: Rp {{ number_format($peminjamanBarang->barang->denda_parah, 0, ',', '.') }}
                            </div>
                            
                            <div class="space-y-3">
                                @for($unit = 1; $unit <= $peminjamanBarang->jumlah_pinjam; $unit++)
                                    <div class="flex items-center space-x-4 p-3 bg-white rounded border">
                                        <div class="w-16 text-sm font-medium text-gray-900">
                                            Unit {{ $unit }}
                                        </div>
                                        <div class="w-32 text-xs text-gray-500">
                                            {{ $peminjamanBarang->barang->id_barang }}-{{ $unit }}
                                        </div>
                                        <div class="flex-1">
                                            <select id="items_{{ $index }}_unit_{{ $unit }}_kondisi"
                                                    name="items[{{ $index }}][units][{{ $unit }}][kondisi_barang]" 
                                                    required
                                                    onchange="updatePenaltyCalculation({{ $index }})"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <option value="">Pilih Kondisi</option>
                                                @php
                                                    $defaultCondition = old('items.'.$index.'.units.'.$unit.'.kondisi_barang');
                                                    if (!$defaultCondition && isset($pengembalian)) {
                                                        $pengembalianBarang = $pengembalian->pengembalianBarangs->where('id_barang', $peminjamanBarang->barang->id_barang)->first();
                                                        // For existing data, we'll need to handle unit-specific conditions differently
                                                        $defaultCondition = '';
                                                    }
                                                @endphp
                                                <option value="baik" {{ $defaultCondition == 'baik' ? 'selected' : '' }}>Baik (Rp 0)</option>
                                                <option value="ringan" {{ $defaultCondition == 'ringan' ? 'selected' : '' }}>Rusak Ringan (Rp {{ number_format($peminjamanBarang->barang->denda_ringan, 0, ',', '.') }})</option>
                                                <option value="sedang" {{ $defaultCondition == 'sedang' ? 'selected' : '' }}>Rusak Sedang (Rp {{ number_format($peminjamanBarang->barang->denda_sedang, 0, ',', '.') }})</option>
                                                <option value="parah" {{ $defaultCondition == 'parah' ? 'selected' : '' }}>Rusak Parah (Rp {{ number_format($peminjamanBarang->barang->denda_parah, 0, ',', '.') }})</option>
                                            </select>
                                            @error('items.'.$index.'.units.'.$unit.'.kondisi_barang')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Penalty Calculation Display -->
                    <div id="penalty_calculation_{{ $index }}" class="p-4 bg-gray-50 rounded-lg mb-4" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Perhitungan Denda untuk {{ $peminjamanBarang->barang->nama_barang }}:</h4>
                        <div id="unit_breakdown_{{ $index }}" class="mb-3">
                            <!-- Unit breakdown will be populated by JavaScript -->
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-600">Total Denda Kondisi:</p>
                                <p id="subtotal_penalty_{{ $index }}" class="font-bold text-red-600 text-lg">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Penalty Details (Hidden inputs for calculation) -->
                    <input type="hidden" id="denda_ringan_{{ $index }}" value="{{ $peminjamanBarang->barang->denda_ringan }}">
                    <input type="hidden" id="denda_sedang_{{ $index }}" value="{{ $peminjamanBarang->barang->denda_sedang }}">
                    <input type="hidden" id="denda_parah_{{ $index }}" value="{{ $peminjamanBarang->barang->denda_parah }}">
                </div>
                @endforeach
            </div>
        </div>



        <!-- Total Penalty Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Total Denda</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-600">Denda Kondisi</p>
                    <p id="total_condition_penalty" class="text-xl font-bold text-red-700">Rp 0</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm text-orange-600">Denda Keterlambatan</p>
                    <p id="total_late_penalty" class="text-xl font-bold text-orange-700">Rp 0</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Total Keseluruhan</p>
                    <p id="grand_total_penalty" class="text-2xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('superadmin.pengembalian.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ isset($pengembalian) ? 'Update Verifikasi Pengembalian' : 'Proses Pengembalian' }}
            </button>
        </div>
    </form>
</div>

<script>
const totalItems = {{ $peminjaman->peminjamanBarangs->count() }};
const LATE_PENALTY_AMOUNT = 250000; // Fixed late penalty amount

function updateLateStatus() {
    const isLateSelect = document.getElementById('is_late');
    const lateInfo = document.getElementById('late_info');
    
    if (isLateSelect.value === '1') {
        lateInfo.style.display = 'block';
    } else {
        lateInfo.style.display = 'none';
    }
    
    // Update all penalty calculations
    for (let i = 0; i < totalItems; i++) {
        updatePenaltyCalculation(i);
    }
}

function updatePenaltyCalculation(index) {
    const calculationDiv = document.getElementById(`penalty_calculation_${index}`);
    const unitBreakdownDiv = document.getElementById(`unit_breakdown_${index}`);
    
    // Get penalty values
    const dendaRingan = parseInt(document.getElementById(`denda_ringan_${index}`).value) || 0;
    const dendaSedang = parseInt(document.getElementById(`denda_sedang_${index}`).value) || 0;
    const dendaParah = parseInt(document.getElementById(`denda_parah_${index}`).value) || 0;
    
    // Calculate total penalty for this item based on all units
    let totalConditionPenalty = 0;
    let unitCount = 0;
    let conditionsCount = { 'baik': 0, 'ringan': 0, 'sedang': 0, 'parah': 0 };
    
    // Count how many unit dropdowns exist for this item
    const unitSelects = document.querySelectorAll(`select[name^="items[${index}][units]"]`);
    
    unitSelects.forEach((select, unitIndex) => {
        const condition = select.value;
        if (condition) {
            unitCount++;
            conditionsCount[condition]++;
            
            let conditionPenalty = 0;
            switch(condition) {
                case 'ringan':
                    conditionPenalty = dendaRingan;
                    break;
                case 'sedang':
                    conditionPenalty = dendaSedang;
                    break;
                case 'parah':
                    conditionPenalty = dendaParah;
                    break;
                default:
                    conditionPenalty = 0;
            }
            totalConditionPenalty += conditionPenalty;
        }
    });
    
    if (unitCount > 0) {
        // Create unit breakdown display
        let breakdownHTML = '<div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">';
        
        if (conditionsCount.baik > 0) {
            breakdownHTML += `<div class="bg-green-100 text-green-800 px-2 py-1 rounded">${conditionsCount.baik} unit baik (Rp 0)</div>`;
        }
        if (conditionsCount.ringan > 0) {
            breakdownHTML += `<div class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">${conditionsCount.ringan} unit ringan (Rp ${(dendaRingan * conditionsCount.ringan).toLocaleString('id-ID')})</div>`;
        }
        if (conditionsCount.sedang > 0) {
            breakdownHTML += `<div class="bg-orange-100 text-orange-800 px-2 py-1 rounded">${conditionsCount.sedang} unit sedang (Rp ${(dendaSedang * conditionsCount.sedang).toLocaleString('id-ID')})</div>`;
        }
        if (conditionsCount.parah > 0) {
            breakdownHTML += `<div class="bg-red-100 text-red-800 px-2 py-1 rounded">${conditionsCount.parah} unit parah (Rp ${(dendaParah * conditionsCount.parah).toLocaleString('id-ID')})</div>`;
        }
        
        breakdownHTML += '</div>';
        unitBreakdownDiv.innerHTML = breakdownHTML;
        
        // Update total display
        document.getElementById(`subtotal_penalty_${index}`).textContent = `Rp ${totalConditionPenalty.toLocaleString('id-ID')}`;
        
        calculationDiv.style.display = 'block';
    } else {
        calculationDiv.style.display = 'none';
    }
    
    updateGrandTotal();
}

function updateGrandTotal() {
    let totalConditionPenalty = 0;
    
    for (let i = 0; i < totalItems; i++) {
        const dendaRingan = parseInt(document.getElementById(`denda_ringan_${i}`).value) || 0;
        const dendaSedang = parseInt(document.getElementById(`denda_sedang_${i}`).value) || 0;
        const dendaParah = parseInt(document.getElementById(`denda_parah_${i}`).value) || 0;
        
        // Get all unit dropdowns for this item
        const unitSelects = document.querySelectorAll(`select[name^="items[${i}][units]"]`);
        
        unitSelects.forEach((select) => {
            const condition = select.value;
            if (condition) {
                let conditionPenalty = 0;
                switch(condition) {
                    case 'ringan':
                        conditionPenalty = dendaRingan;
                        break;
                    case 'sedang':
                        conditionPenalty = dendaSedang;
                        break;
                    case 'parah':
                        conditionPenalty = dendaParah;
                        break;
                }
                totalConditionPenalty += conditionPenalty;
            }
        });
    }
    
    // Check if late is selected
    const isLateSelect = document.getElementById('is_late');
    const totalLatePenalty = (isLateSelect && isLateSelect.value === '1') ? LATE_PENALTY_AMOUNT : 0;
    
    const grandTotal = totalConditionPenalty + totalLatePenalty;
    
    document.getElementById('total_condition_penalty').textContent = `Rp ${totalConditionPenalty.toLocaleString('id-ID')}`;
    document.getElementById('total_late_penalty').textContent = `Rp ${totalLatePenalty.toLocaleString('id-ID')}`;
    document.getElementById('grand_total_penalty').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
}

// Form validation
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const isLateSelect = document.getElementById('is_late');
    const unitConditions = document.querySelectorAll('select[name*="[units]"][name*="[kondisi_barang]"]');
    let allSelected = true;
    let unselectedCount = 0;
    
    // Check if late status is selected
    if (!isLateSelect.value) {
        e.preventDefault();
        alert('Mohon pilih status keterlambatan terlebih dahulu.');
        return false;
    }
    
    // Check if all unit conditions are selected
    unitConditions.forEach(select => {
        if (!select.value) {
            allSelected = false;
            unselectedCount++;
        }
    });
    
    if (!allSelected) {
        e.preventDefault();
        alert(`Mohon pilih kondisi untuk semua unit barang. Masih ada ${unselectedCount} unit yang belum dipilih kondisinya.`);
        return false;
    }
    
    return confirm('Apakah Anda yakin ingin memproses pengembalian ini? Stok barang akan dikembalikan ke inventaris dan peminjaman akan ditandai sebagai selesai.');
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateLateStatus();
});
</script>
@endsection 