@extends('layouts.user')

@section('title', 'Form Pengajuan Peminjaman - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('user.pengajuan.index') }}" class="text-blue-600 hover:text-blue-800">Pengajuan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Form Pengajuan</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-900">Form Pengajuan Peminjaman</h1>
</div>

<form action="{{ route('user.pengajuan.store') }}" method="POST" id="pengajuanForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_peminjam" class="block text-sm font-medium text-gray-700 mb-2">Nama Peminjam</label>
                <input type="text" id="nama_peminjam" name="nama_peminjam" 
                       value="{{ auth()->guard('user')->user()->nama_penanggung_jawab }}" 
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
            </div>
            
            <div>
                <label for="nomor_id" class="block text-sm font-medium text-gray-700 mb-2">Nomor Identitas</label>
                <input type="text" id="nomor_id" name="nomor_id" 
                       value="{{ auth()->guard('user')->user()->no_identitas }}" 
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
            </div>
            
            <div class="md:col-span-2">
                <label for="tujuan_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Tujuan Peminjaman *</label>
                <input type="text" id="tujuan_peminjaman" name="tujuan_peminjaman" 
                       value="{{ old('tujuan_peminjaman', request('tujuan_peminjaman')) }}" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Masukkan tujuan/nama kegiatan peminjaman">
                @error('tujuan_peminjaman')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Periode Peminjaman</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" 
                       value="{{ old('tanggal_pinjam', request('start_date')) }}" 
                       min="{{ Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('tanggal_pinjam')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Minimal H-3 dari hari ini</p>
            </div>
            
            <div>
                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali *</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" 
                       value="{{ old('tanggal_kembali', request('end_date')) }}" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('tanggal_kembali')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Items Selection -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Pilih Barang</h2>
            <button type="button" id="addItemBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                + Tambah Barang
            </button>
        </div>

        <div id="itemsContainer">
            @if($selectedItem)
                <!-- Pre-selected item -->
                <div class="item-row border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                            <select name="items[0][id_barang]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="{{ $selectedItem->id_barang }}" selected>
                                    {{ $selectedItem->nama_barang }} - {{ $selectedItem->admin->nama_lembaga }} ({{ $selectedItem->stok_tersedia }} tersedia)
                                </option>
                                @foreach($barangs as $barang)
                                    @if($barang->id_barang != $selectedItem->id_barang)
                                        <option value="{{ $barang->id_barang }}">
                                            {{ $barang->nama_barang }} - {{ $barang->admin->nama_lembaga }} ({{ $barang->stok_tersedia }} tersedia)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="number" name="items[0][jumlah]" min="1" value="{{ request('quantity', 1) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" class="remove-item-btn bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty state -->
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada barang dipilih. Klik "Tambah Barang" untuk memilih barang.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Cost Summary (for non-civitas) -->
    @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estimasi Biaya</h3>
        <div id="costSummary" class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Biaya Sewa:</span>
                <span class="font-medium" id="totalCost">Rp 0</span>
            </div>
            <div class="text-sm text-gray-500">
                <p>* Biaya akan dihitung berdasarkan durasi peminjaman dan jumlah barang</p>
                <p>* Pembayaran dilakukan setelah Anda konfirmasi pengajuan yang sudah disetujui</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('user.pengajuan.index') }}" 
           class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            Batal
        </a>
        <button type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Simpan Pengajuan
        </button>
    </div>
</form>

<!-- Add Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Barang</h3>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
            <select id="modalBarangSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih barang...</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id_barang }}" 
                            data-name="{{ $barang->nama_barang }}"
                            data-lembaga="{{ $barang->admin->nama_lembaga }}"
                            data-stok="{{ $barang->stok_tersedia }}"
                            data-harga="{{ $barang->harga_sewa }}">
                        {{ $barang->nama_barang }} - {{ $barang->admin->nama_lembaga }} ({{ $barang->stok_tersedia }} tersedia)
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
            <input type="number" id="modalJumlahInput" min="1" value="1" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div class="flex justify-end space-x-3">
            <button type="button" id="cancelAddItem" 
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Batal
            </button>
            <button type="button" id="confirmAddItem" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Tambah
            </button>
        </div>
    </div>
</div>

<script>
let itemIndex = {{ $selectedItem ? 1 : 0 }};
const isNonCivitas = {{ auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' ? 'true' : 'false' }};

// Add item functionality
document.getElementById('addItemBtn').addEventListener('click', function() {
    document.getElementById('addItemModal').classList.remove('hidden');
    document.getElementById('addItemModal').classList.add('flex');
});

document.getElementById('cancelAddItem').addEventListener('click', function() {
    document.getElementById('addItemModal').classList.add('hidden');
    document.getElementById('addItemModal').classList.remove('flex');
});

document.getElementById('confirmAddItem').addEventListener('click', function() {
    const select = document.getElementById('modalBarangSelect');
    const jumlah = document.getElementById('modalJumlahInput').value;
    
    if (!select.value || !jumlah) {
        alert('Mohon pilih barang dan masukkan jumlah');
        return;
    }
    
    const option = select.options[select.selectedIndex];
    const stok = parseInt(option.dataset.stok);
    
    if (parseInt(jumlah) > stok) {
        alert('Jumlah melebihi stok yang tersedia');
        return;
    }
    
    addItemRow(select.value, option.text, jumlah);
    
    // Reset modal
    select.value = '';
    document.getElementById('modalJumlahInput').value = 1;
    document.getElementById('addItemModal').classList.add('hidden');
    document.getElementById('addItemModal').classList.remove('flex');
});

function addItemRow(barangId, barangText, jumlah) {
    const container = document.getElementById('itemsContainer');
    
    // Remove empty state if exists
    const emptyState = container.querySelector('.text-center');
    if (emptyState) {
        emptyState.remove();
    }
    
    const itemRow = document.createElement('div');
    itemRow.className = 'item-row border border-gray-200 rounded-lg p-4 mb-4';
    itemRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                <select name="items[${itemIndex}][id_barang]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="${barangId}" selected>${barangText}</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id_barang }}">
                            {{ $barang->nama_barang }} - {{ $barang->admin->nama_lembaga }} ({{ $barang->stok_tersedia }} tersedia)
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="items[${itemIndex}][jumlah]" min="1" value="${jumlah}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="button" class="remove-item-btn bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(itemRow);
    itemIndex++;
    
    // Add remove functionality
    itemRow.querySelector('.remove-item-btn').addEventListener('click', function() {
        itemRow.remove();
        checkEmptyState();
    });
    
    updateCostSummary();
}

// Remove item functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item-btn')) {
        e.target.closest('.item-row').remove();
        checkEmptyState();
        updateCostSummary();
    }
});

function checkEmptyState() {
    const container = document.getElementById('itemsContainer');
    const itemRows = container.querySelectorAll('.item-row');
    
    if (itemRows.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada barang dipilih. Klik "Tambah Barang" untuk memilih barang.</p>
            </div>
        `;
    }
}

// Update cost summary
function updateCostSummary() {
    if (!isNonCivitas) return;
    
    const startDate = document.getElementById('tanggal_pinjam').value;
    const endDate = document.getElementById('tanggal_kembali').value;
    
    if (!startDate || !endDate) return;
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
    
    let totalCost = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('select[name*="[id_barang]"]');
        const jumlahInput = row.querySelector('input[name*="[jumlah]"]');
        
        if (select && jumlahInput && select.value) {
            const option = select.options[select.selectedIndex];
            const barangId = select.value;
            
            // Find barang data
            @foreach($barangs as $barang)
                if (barangId == '{{ $barang->id_barang }}') {
                    const harga = {{ $barang->harga_sewa }};
                    const jumlah = parseInt(jumlahInput.value) || 0;
                    totalCost += harga * jumlah * duration;
                }
            @endforeach
        }
    });
    
    document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
}

// Update cost when dates change
document.getElementById('tanggal_pinjam').addEventListener('change', updateCostSummary);
document.getElementById('tanggal_kembali').addEventListener('change', updateCostSummary);

// Update cost when quantity changes
document.addEventListener('input', function(e) {
    if (e.target.name && e.target.name.includes('[jumlah]')) {
        updateCostSummary();
    }
});

// Initial cost calculation
document.addEventListener('DOMContentLoaded', function() {
    updateCostSummary();
});

// Form validation
document.getElementById('pengajuanForm').addEventListener('submit', function(e) {
    const itemRows = document.querySelectorAll('.item-row');
    
    if (itemRows.length === 0) {
        e.preventDefault();
        alert('Mohon pilih minimal satu barang');
        return;
    }
    
    // Validate each item
    let isValid = true;
    itemRows.forEach(row => {
        const select = row.querySelector('select[name*="[id_barang]"]');
        const jumlahInput = row.querySelector('input[name*="[jumlah]"]');
        
        if (!select.value || !jumlahInput.value || parseInt(jumlahInput.value) < 1) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua data barang');
    }
});
</script>
@endsection 