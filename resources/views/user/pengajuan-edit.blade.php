@extends('layouts.user')

@section('title', 'Edit Pengajuan - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <nav class="text-sm text-gray-500 mb-2">
        <a href="{{ route('user.pengajuan.index') }}" class="text-blue-600 hover:text-blue-800">Pengajuan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Edit Pengajuan</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-900">Edit Pengajuan Peminjaman</h1>
    <p class="text-gray-600">{{ $peminjaman->kode_peminjaman }}</p>
    
    @if($peminjaman->status_pengajuan === 'partial')
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mt-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-orange-800 font-medium">Pengajuan Sebagian Disetujui</h3>
                    <p class="text-orange-700 text-sm">Barang yang disetujui tidak dapat diubah. Anda hanya dapat mengedit atau menghapus barang yang ditolak.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@if($peminjaman->status_pengajuan === 'partial')
    <!-- PARTIAL MODE: Edit only rejected items -->
    <form action="{{ route('user.pengajuan.update', $peminjaman->id_peminjaman) }}" method="POST" id="partialForm">
        @csrf
        @method('PUT')
        
        <!-- Approved Items (Read-only) -->
        @if($approvedItems->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-green-700 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Barang yang Disetujui ({{ $approvedItems->count() }} item)
                </h2>
                
                <div class="space-y-3">
                    @foreach($approvedItems as $item)
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $item->barang->nama_barang }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->barang->admin->nama_lembaga ?? 'Admin' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ $item->jumlah_pinjam }} unit</p>
                                <p class="text-sm text-green-600">✓ Disetujui</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Rejected Items (Editable) -->
        @if($rejectedItems->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-red-700 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Barang yang Ditolak ({{ $rejectedItems->count() }} item)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Pilih tindakan untuk setiap barang: hapus atau ganti dengan barang serupa</p>
                
                <div class="space-y-4">
                    @foreach($rejectedItems as $item)
                        <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $item->barang->nama_barang }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->barang->admin->nama_lembaga ?? 'Admin' }} • {{ $item->jumlah_pinjam }} unit</p>
                                        @if($item->notes_admin)
                                            <p class="text-sm text-red-600 mt-1">Alasan: {{ $item->notes_admin }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-sm text-red-600 font-medium">✗ Ditolak</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Delete Option -->
                                <label class="border border-gray-300 rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <input type="radio" name="rejected_actions[{{ $item->id_peminjaman_barang }}][action]" value="delete" class="mr-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Hapus Item</h4>
                                            <p class="text-sm text-gray-600">Tidak jadi meminjam barang ini</p>
                                        </div>
                                    </div>
                                </label>
                                
                                <!-- Replace Option -->
                                <label class="border border-gray-300 rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <input type="radio" name="rejected_actions[{{ $item->id_peminjaman_barang }}][action]" value="replace" class="mr-3" onchange="toggleReplaceOptions({{ $item->id_peminjaman_barang }})">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Ganti Barang</h4>
                                            <p class="text-sm text-gray-600">Pilih barang serupa</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                                                         <!-- Replace Options (Hidden by default) -->
                             <div id="replace-options-{{ $item->id_peminjaman_barang }}" class="mt-4 hidden">
                                 <!-- DEBUG INFO -->
                                 <div class="bg-gray-100 p-3 rounded mb-3 text-sm">
                                     <strong>Debug Info:</strong><br>
                                     Ditolak: "{{ $item->barang->nama_barang }}"<br>
                                     Kata Pertama: "{{ explode(' ', $item->barang->nama_barang)[0] }}"<br>
                                     Barang Serupa Ditemukan: {{ isset($similarItemsMap[$item->id_peminjaman_barang]) ? count($similarItemsMap[$item->id_peminjaman_barang]) : 0 }}
                                     @if(isset($similarItemsMap[$item->id_peminjaman_barang]) && count($similarItemsMap[$item->id_peminjaman_barang]) > 0)
                                         <br><strong>Barang serupa yang tersedia:</strong>
                                         @foreach($similarItemsMap[$item->id_peminjaman_barang] as $similarItem)
                                             <br>• {{ $similarItem->nama_barang }} - {{ $similarItem->admin->nama_lembaga ?? 'Admin' }} ({{ $similarItem->stok_tersedia }} tersedia)
                                         @endforeach
                                     @else
                                         <br><em style="color: orange;">Tidak ada barang lain yang dimulai dengan kata "{{ explode(' ', $item->barang->nama_barang)[0] }}"</em>
                                     @endif
                                 </div>
                                 <!-- END DEBUG -->
                                 
                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang Pengganti</label>
                                         <select name="rejected_actions[{{ $item->id_peminjaman_barang }}][id_barang]" class="replace-field-{{ $item->id_peminjaman_barang }} w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                             <option value="">-- Pilih Barang --</option>
                                             @if(isset($similarItemsMap[$item->id_peminjaman_barang]))
                                                 @foreach($similarItemsMap[$item->id_peminjaman_barang] as $similarItem)
                                                     <option value="{{ $similarItem->id_barang }}">
                                                         {{ $similarItem->nama_barang }} - {{ $similarItem->admin->nama_lembaga }} ({{ $similarItem->stok_tersedia }} tersedia)
                                                     </option>
                                                 @endforeach
                                             @endif
                                         </select>
                                     </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                        <input type="number" name="rejected_actions[{{ $item->id_peminjaman_barang }}][jumlah]" min="1" value="{{ $item->jumlah_pinjam }}" class="replace-field-{{ $item->id_peminjaman_barang }} w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('user.pengajuan.index') }}" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" id="partialSubmitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Update Pengajuan
            </button>
        </div>
    </form>

@else
    <!-- DRAFT MODE: Full edit (existing functionality) -->
<form action="{{ route('user.pengajuan.update', $peminjaman->id_peminjaman) }}" method="POST" id="pengajuanForm">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="nama_pengambil" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengambil</label>
                <input type="text" id="nama_pengambil" name="nama_pengambil" 
                       value="{{ $peminjaman->nama_pengambil }}" 
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
            </div>
            
            <div>
                <label for="no_identitas_pengambil" class="block text-sm font-medium text-gray-700 mb-2">Nomor Identitas Pengambil</label>
                <input type="text" id="no_identitas_pengambil" name="no_identitas_pengambil" 
                       value="{{ $peminjaman->no_identitas_pengambil }}" 
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
            </div>

            <div>
                <label for="no_hp_pengambil" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP Pengambil</label>
                <input type="text" id="no_hp_pengambil" name="no_hp_pengambil" 
                       value="{{ $peminjaman->no_hp_pengambil }}" 
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
            </div>
            
            <div class="md:col-span-3">
                <label for="tujuan_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Tujuan Peminjaman *</label>
                <input type="text" id="tujuan_peminjaman" name="tujuan_peminjaman" 
                       value="{{ old('tujuan_peminjaman', $peminjaman->tujuan_peminjaman) }}" 
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
                       value="{{ old('tanggal_pinjam', $peminjaman->tanggal_mulai->format('Y-m-d')) }}" 
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
                       value="{{ old('tanggal_kembali', $peminjaman->tanggal_selesai->format('Y-m-d')) }}" 
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
            @foreach($peminjaman->peminjamanBarangs as $index => $item)
                <div class="item-row border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                            <select name="items[{{ $index }}][id_barang]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="{{ $item->id_barang }}" selected>
                                    {{ $item->barang->nama_barang }} - {{ $item->barang->admin->nama_lembaga }} ({{ $item->barang->stok_tersedia }} tersedia)
                                </option>
                                @foreach($barangs as $barang)
                                    @if($barang->id_barang != $item->id_barang)
                                        <option value="{{ $barang->id_barang }}">
                                            {{ $barang->nama_barang }} - {{ $barang->admin->nama_lembaga }} ({{ $barang->stok_tersedia }} tersedia)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="number" name="items[{{ $index }}][jumlah]" min="1" value="{{ $item->jumlah_pinjam }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" class="remove-item-btn bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
            <a href="{{ route('user.pengajuan.index') }}" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            Batal
        </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Update Pengajuan
        </button>
    </div>
</form>
@endif

<script>
function toggleReplaceOptions(itemId) {
    const replaceDiv = document.getElementById('replace-options-' + itemId);
    const replaceRadio = document.querySelector(`input[name="rejected_actions[${itemId}][action]"][value="replace"]`);
    const replaceFields = document.querySelectorAll(`.replace-field-${itemId}`);
    
    if (replaceRadio.checked) {
        replaceDiv.classList.remove('hidden');
        // Enable replace fields
        replaceFields.forEach(field => {
            field.disabled = false;
            field.name = field.name; // Ensure name is correct
        });
    } else {
        replaceDiv.classList.add('hidden');
        // Disable replace fields so they won't be sent
        replaceFields.forEach(field => {
            field.disabled = true;
            field.value = ''; // Clear values
        });
    }
}

// Validate partial form submission
function validatePartialForm() {
    const rejectedItems = document.querySelectorAll('input[name*="[action]"]');
    const itemGroups = {};
    
    // Group radio buttons by item ID
    rejectedItems.forEach(radio => {
        const match = radio.name.match(/rejected_actions\[(\d+)\]\[action\]/);
        if (match) {
            const itemId = match[1];
            if (!itemGroups[itemId]) {
                itemGroups[itemId] = [];
            }
            itemGroups[itemId].push(radio);
        }
    });
    
    // Check each group has a selection
    for (const itemId in itemGroups) {
        const radios = itemGroups[itemId];
        const hasSelection = radios.some(radio => radio.checked);
        
        if (!hasSelection) {
            alert(`Silakan pilih tindakan untuk item yang ditolak (Hapus Item atau Ganti Barang).`);
            return false;
        }
        
        // If replace is selected, validate additional fields
        const replaceRadio = radios.find(radio => radio.value === 'replace' && radio.checked);
        if (replaceRadio) {
            const barangSelect = document.querySelector(`select[name="rejected_actions[${itemId}][id_barang]"]`);
            const jumlahInput = document.querySelector(`input[name="rejected_actions[${itemId}][jumlah]"]`);
            
            if (!barangSelect || !barangSelect.value) {
                alert('Silakan pilih barang pengganti.');
                if (barangSelect) barangSelect.focus();
                return false;
            }
            
            if (!jumlahInput || !jumlahInput.value || jumlahInput.value < 1) {
                alert('Silakan masukkan jumlah yang valid.');
                if (jumlahInput) jumlahInput.focus();
                return false;
            }
        }
    }
    
    return true;
}

// Handle radio button changes
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[action]')) {
        const match = e.target.name.match(/rejected_actions\[(\d+)\]\[action\]/);
        if (match) {
            const itemId = match[1];
            toggleReplaceOptions(itemId);
        }
    }
});

// Add form validation for partial mode
document.addEventListener('DOMContentLoaded', function() {
    const partialForm = document.getElementById('partialForm');
    const partialSubmitBtn = document.getElementById('partialSubmitBtn');
    
    if (partialForm && partialSubmitBtn) {
        // Initialize all replace fields as disabled
        const allReplaceFields = document.querySelectorAll('[class*="replace-field-"]');
        allReplaceFields.forEach(field => {
            field.disabled = true;
            field.value = '';
        });
        
        partialForm.addEventListener('submit', function(e) {
            if (!validatePartialForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>

@endsection 