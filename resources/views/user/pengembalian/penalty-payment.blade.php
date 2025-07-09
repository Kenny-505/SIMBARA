@extends('layouts.user')

@section('title', 'Pembayaran Denda - SIMBARA')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('user.pengembalian.show', $pengembalian->id_pengembalian) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pembayaran Denda</h1>
                <p class="text-gray-600 mt-1">Kode Peminjaman: {{ $pengembalian->peminjaman->kode_peminjaman }}</p>
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
            <div>
                <h2 class="text-xl font-semibold text-red-800">Total Denda yang Harus Dibayar</h2>
                <p class="text-red-700 mt-1 text-3xl font-bold">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="bg-white rounded-lg p-4 mb-4">
            <h3 class="font-semibold text-gray-900 mb-3">Rincian Denda:</h3>
            <div class="space-y-2">
                @foreach($pengembalian->pengembalianBarangs as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                    <div>
                        <span class="font-medium">{{ $item->barang->nama_barang }}</span>
                        <span class="text-gray-500 text-sm ml-2">({{ $item->jumlah_kembali }} unit, {{ ucfirst($item->kondisi_barang ?? 'Belum ditentukan') }})</span>
                    </div>
                    <span class="font-medium text-red-600">Rp {{ number_format($item->subtotal_denda ?? 0, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">📋 Cara Pembayaran</h3>
        <div class="space-y-4">
            <div>
                <h4 class="font-semibold text-blue-700 mb-2">1. Transfer Bank</h4>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-900"><strong>Bank BCA</strong></p>
                    <p class="text-gray-700">No. Rekening: <span class="font-mono font-bold">1234567890</span></p>
                    <p class="text-gray-700">Atas Nama: <span class="font-bold">SIMBARA</span></p>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-blue-700 mb-2">2. E-Wallet</h4>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-900"><strong>Dana / GoPay</strong></p>
                    <p class="text-gray-700">No. HP: <span class="font-mono font-bold">08123456789</span></p>
                    <p class="text-gray-700">Atas Nama: <span class="font-bold">SIMBARA</span></p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 p-4 bg-yellow-100 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm">
                <strong>⚠️ Penting:</strong> Setelah melakukan transfer, segera upload bukti pembayaran menggunakan form di bawah ini.
            </p>
        </div>
    </div>

    @if($pengembalian->status_pengembalian === 'payment_required')
    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📸 Upload Bukti Pembayaran</h3>
        
        <form action="{{ route('user.pengembalian.upload-penalty-payment', $pengembalian->id_pengembalian) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- File Upload -->
            <div class="mb-6">
                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                    Foto Bukti Transfer / Pembayaran <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       id="bukti_pembayaran" 
                       name="bukti_pembayaran" 
                       accept="image/jpeg,image/png,image/jpg" 
                       required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 5MB.</p>
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                    <img id="previewImg" src="" alt="Preview" class="max-w-xs h-auto border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="catatan_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea id="catatan_pembayaran" 
                         name="catatan_pembayaran" 
                         rows="3" 
                         placeholder="Contoh: Transfer via ATM BCA, jam 14:30"
                         class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan_pembayaran') }}</textarea>
                @error('catatan_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('user.pengembalian.show', $pengembalian->id_pengembalian) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Bukti Pembayaran
                </button>
            </div>
        </form>
    </div>
    @elseif($pengembalian->status_pengembalian === 'payment_uploaded')
    <!-- Payment Uploaded Info -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-800">Bukti Pembayaran Berhasil Diupload</h3>
                <p class="text-green-700 mt-1">Diupload pada: {{ $pengembalian->tanggal_upload_pembayaran->format('d/m/Y H:i') }}</p>
                <p class="text-green-600 text-sm mt-2">Super Admin akan memverifikasi pembayaran Anda. Anda akan mendapat notifikasi setelah verifikasi selesai.</p>
            </div>
        </div>
        
        @if($pengembalian->bukti_pembayaran_denda)
        <div class="mt-4">
            <p class="text-sm text-green-700 mb-2">Bukti pembayaran yang diupload:</p>
            <img src="data:image/jpeg;base64,{{ $pengembalian->bukti_pembayaran_denda }}" 
                 alt="Bukti Pembayaran" 
                 class="max-w-md h-auto border border-gray-300 rounded-lg shadow-sm">
        </div>
        @endif
        
        @if($pengembalian->catatan_pembayaran)
        <div class="mt-4 p-3 bg-white rounded-lg">
            <p class="text-sm text-green-700 font-medium">Catatan Anda:</p>
            <p class="text-green-800">{{ $pengembalian->catatan_pembayaran }}</p>
        </div>
        @endif
    </div>
    @endif
</div>

<!-- JavaScript for Image Preview -->
<script>
document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection 