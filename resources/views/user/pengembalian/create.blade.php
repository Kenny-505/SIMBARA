@extends('layouts.user')

@section('title', 'Ajukan Pengembalian - SIMBARA')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('user.pengembalian.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ajukan Pengembalian</h1>
                <p class="text-gray-600 mt-1">Kode Peminjaman: {{ $peminjaman->kode_peminjaman }}</p>
            </div>
        </div>
    </div>

    <!-- Late Warning -->
    @if($isLate)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-red-800">Peminjaman Terlambat!</h3>
                <p class="text-red-700 mt-1">
                    Peminjaman ini sudah terlambat {{ $daysLate }} hari. Denda keterlambatan akan dikenakan.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Loan Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjaman</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                <p class="font-medium {{ $isLate ? 'text-red-600' : 'text-gray-900' }}">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}
                    @if($isLate)
                        <span class="text-sm">({{ $daysLate }} hari terlambat)</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isLate ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $isLate ? 'Terlambat' : 'Aktif' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Return Form -->
    <form action="{{ route('user.pengembalian.store', $peminjaman->id_peminjaman) }}" method="POST" id="returnForm">
        @csrf
        
        <!-- Return Date -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tanggal Pengembalian</h2>
            <div class="max-w-md">
                <label for="tanggal_pengembalian" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Pengembalian <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="tanggal_pengembalian" 
                       name="tanggal_pengembalian" 
                       value="{{ old('tanggal_pengembalian', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('tanggal_pengembalian')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Items to Return -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Dikembalikan</h2>
            <div class="space-y-6">
                @foreach($peminjaman->peminjamanBarangs as $index => $peminjamanBarang)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                                            @if($peminjamanBarang->barang->foto_1)
                    <img src="data:image/jpeg;base64,{{ base64_encode($peminjamanBarang->barang->foto_1) }}" 
                                     alt="{{ $peminjamanBarang->barang->nama_barang }}"
                                     class="w-16 h-16 object-cover rounded-lg mr-4">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $peminjamanBarang->barang->nama_barang }}</h3>
                                <p class="text-sm text-gray-600">Jumlah Dipinjam: {{ $peminjamanBarang->jumlah_pinjam }}</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="items[{{ $index }}][id_barang]" value="{{ $peminjamanBarang->barang->id_barang }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Quantity to Return -->
                        <div>
                            <label for="items_{{ $index }}_jumlah_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Dikembalikan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="items_{{ $index }}_jumlah_kembali"
                                   name="items[{{ $index }}][jumlah_kembali]" 
                                   value="{{ old('items.'.$index.'.jumlah_kembali', $peminjamanBarang->jumlah_pinjam) }}"
                                   min="1" 
                                   max="{{ $peminjamanBarang->jumlah_pinjam }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('items.'.$index.'.jumlah_kembali')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item Condition -->
                        <div>
                            <label for="items_{{ $index }}_kondisi_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                Kondisi Barang <span class="text-red-500">*</span>
                            </label>
                            <select id="items_{{ $index }}_kondisi_barang"
                                    name="items[{{ $index }}][kondisi_barang]" 
                                    required
                                    onchange="updatePenaltyPreview({{ $index }}, this.value, {{ $peminjamanBarang->barang->id_barang }})"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Kondisi</option>
                                <option value="baik" {{ old('items.'.$index.'.kondisi_barang') == 'baik' ? 'selected' : '' }}>Baik (Tidak ada denda)</option>
                                <option value="ringan" {{ old('items.'.$index.'.kondisi_barang') == 'ringan' ? 'selected' : '' }}>Rusak Ringan (Rp {{ number_format($peminjamanBarang->barang->denda_ringan, 0, ',', '.') }})</option>
                                <option value="sedang" {{ old('items.'.$index.'.kondisi_barang') == 'sedang' ? 'selected' : '' }}>Rusak Sedang (Rp {{ number_format($peminjamanBarang->barang->denda_sedang, 0, ',', '.') }})</option>
                                <option value="parah" {{ old('items.'.$index.'.kondisi_barang') == 'parah' ? 'selected' : '' }}>Rusak Parah (Rp {{ number_format($peminjamanBarang->barang->denda_parah, 0, ',', '.') }})</option>
                            </select>
                            @error('items.'.$index.'.kondisi_barang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Penalty Preview -->
                    <div id="penalty_preview_{{ $index }}" class="mt-4 p-3 bg-gray-50 rounded-lg" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Estimasi Denda:</h4>
                        <div class="text-sm text-gray-600">
                            <div id="condition_penalty_{{ $index }}">Denda Kondisi: Rp 0</div>
                            @if($isLate)
                            <div id="late_penalty_{{ $index }}">Denda Keterlambatan: Rp 0</div>
                            @endif
                            <div id="total_penalty_{{ $index }}" class="font-medium text-gray-900 mt-1">Total: Rp 0</div>
                        </div>
                    </div>

                    <!-- User Notes for Item -->
                    <div class="mt-4">
                        <label for="items_{{ $index }}_catatan_user" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan untuk Barang Ini
                        </label>
                        <textarea id="items_{{ $index }}_catatan_user"
                                  name="items[{{ $index }}][catatan_user]" 
                                  rows="2"
                                  placeholder="Jelaskan kondisi barang atau catatan khusus..."
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('items.'.$index.'.catatan_user') }}</textarea>
                        @error('items.'.$index.'.catatan_user')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- General Notes -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Umum</h2>
            <div>
                <label for="notes_user" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Pengembalian
                </label>
                <textarea id="notes_user"
                          name="notes_user" 
                          rows="4"
                          placeholder="Tuliskan catatan umum tentang pengembalian ini..."
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes_user') }}</textarea>
                @error('notes_user')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Total Penalty Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Estimasi Denda</h2>
            <div id="total_penalty_summary" class="text-lg font-semibold text-gray-900">
                Total Estimasi Denda: Rp 0
            </div>
            <p class="text-sm text-gray-600 mt-2">
                *Ini adalah estimasi berdasarkan kondisi yang Anda pilih. Denda final akan ditentukan oleh Super Admin setelah verifikasi.
            </p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('user.pengembalian.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Ajukan Pengembalian
            </button>
        </div>
    </form>
</div>

<script>
// Penalty data from server
const penaltyData = @json($estimatedPenalties);
const isLate = @json($isLate);
const daysLate = @json($daysLate);

function updatePenaltyPreview(index, condition, barangId) {
    const previewDiv = document.getElementById(`penalty_preview_${index}`);
    const conditionPenaltyDiv = document.getElementById(`condition_penalty_${index}`);
    const latePenaltyDiv = document.getElementById(`late_penalty_${index}`);
    const totalPenaltyDiv = document.getElementById(`total_penalty_${index}`);
    
    if (condition) {
        const penalties = penaltyData[barangId];
        const conditionPenalty = penalties[condition];
        const latePenalty = isLate ? penalties[condition] : 0; // Late penalty is included in the calculation
        const totalPenalty = conditionPenalty;
        
        conditionPenaltyDiv.textContent = `Denda Kondisi: Rp ${conditionPenalty.toLocaleString('id-ID')}`;
        if (latePenaltyDiv) {
            latePenaltyDiv.textContent = `Denda Keterlambatan: Sudah termasuk dalam perhitungan`;
        }
        totalPenaltyDiv.textContent = `Total: Rp ${totalPenalty.toLocaleString('id-ID')}`;
        
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
    
    updateTotalPenaltySummary();
}

function updateTotalPenaltySummary() {
    let totalPenalty = 0;
    const selects = document.querySelectorAll('select[name*="[kondisi_barang]"]');
    
    selects.forEach((select, index) => {
        const condition = select.value;
        if (condition) {
            const barangId = document.querySelector(`input[name="items[${index}][id_barang]"]`).value;
            const quantity = document.querySelector(`input[name="items[${index}][jumlah_kembali]"]`).value;
            const penalties = penaltyData[barangId];
            totalPenalty += penalties[condition] * parseInt(quantity);
        }
    });
    
    document.getElementById('total_penalty_summary').textContent = `Total Estimasi Denda: Rp ${totalPenalty.toLocaleString('id-ID')}`;
}

// Update penalty when quantity changes
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[jumlah_kembali]')) {
        updateTotalPenaltySummary();
    }
});

// Form validation
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const conditions = document.querySelectorAll('select[name*="[kondisi_barang]"]');
    let allSelected = true;
    
    conditions.forEach(select => {
        if (!select.value) {
            allSelected = false;
        }
    });
    
    if (!allSelected) {
        e.preventDefault();
        alert('Mohon pilih kondisi untuk semua barang yang dikembalikan.');
        return false;
    }
    
    return confirm('Apakah Anda yakin ingin mengajukan pengembalian ini? Permintaan akan dikirim ke Super Admin untuk diproses.');
});
</script>
@endsection 