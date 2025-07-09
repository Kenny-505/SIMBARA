@extends('layouts.user')

@section('title', 'Upload Bukti Pembayaran - SIMBARA')

@push('styles')
<style>
    #upload-area {
        transition: all 0.3s ease;
    }
    
    #upload-area.dragover {
        border-color: #3B82F6;
        background-color: #EFF6FF;
    }
    
    #preview-image {
        transition: all 0.3s ease;
    }
    
    #preview-image:hover {
        transform: scale(1.05);
    }
    
    .file-upload-success {
        border-color: #10B981 !important;
        background-color: #ECFDF5 !important;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.peminjaman.index') }}" class="text-blue-600 hover:text-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Upload Bukti Pembayaran</h1>
            <p class="text-gray-600">Upload bukti transfer untuk peminjaman Anda</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Peminjaman Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Peminjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Peminjaman</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $peminjaman->kode_peminjaman }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Dikonfirmasi - Menunggu Pembayaran
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->tujuan_peminjaman }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Periode</label>
                    <p class="text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h4>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="font-medium">Bank BCA</p>
                        <p>No. Rekening: <span class="font-mono">1234567890</span></p>
                        <p>Atas Nama: <span class="font-medium">FMIPA UNIVERSITAS</span></p>
                        <p class="mt-2 font-medium">Total yang harus dibayar: <span class="text-lg">Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Transfer</h2>
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h4>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('user.peminjaman.upload-payment', $peminjaman->id_peminjaman) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran *
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors" id="upload-area">
                        <div class="space-y-1 text-center" id="upload-placeholder">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="bukti_pembayaran" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload file</span>
                                    <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" accept="image/jpeg,image/jpg,image/png" required class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                        </div>
                        <!-- Image Preview -->
                        <div class="hidden" id="image-preview">
                            <div class="flex flex-col items-center">
                                <img id="preview-image" src="" alt="Preview" class="max-h-64 max-w-full rounded-lg shadow-lg mb-4">
                                <div class="text-center">
                                    <p id="file-name" class="text-sm font-medium text-gray-900 mb-2"></p>
                                    <button type="button" id="change-file" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ganti File
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('bukti_pembayaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="catatan_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Pembayaran (Opsional)
                    </label>
                    <textarea id="catatan_pembayaran" name="catatan_pembayaran" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_pembayaran') }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('user.peminjaman.index') }}" 
                       class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Upload Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Biaya</h3>
            
            <div class="space-y-3">
                @foreach($peminjaman->peminjamanBarangs as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->barang->nama_barang }} ({{ $item->jumlah_pinjam }}x)</span>
                    <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                
                <div class="border-t pt-3">
                    <div class="flex justify-between text-lg font-semibold text-blue-600">
                        <span>Total Biaya:</span>
                        <span>Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-yellow-800">Penting!</h4>
                        <div class="mt-1 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan nominal transfer sesuai dengan total biaya</li>
                                <li>Upload bukti transfer yang jelas dan dapat dibaca</li>
                                <li>Peminjaman akan diproses setelah pembayaran diverifikasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File upload preview
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('bukti_pembayaran');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    const changeFileBtn = document.getElementById('change-file');
    const uploadArea = document.getElementById('upload-area');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        handleFile(file);
    });

    // Handle drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-500', 'bg-blue-50');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (isValidImageFile(file)) {
                fileInput.files = files;
                handleFile(file);
            } else {
                alert('File harus berupa gambar (PNG, JPG, JPEG) dan maksimal 2MB');
            }
        }
    });

    // Handle change file button
    changeFileBtn.addEventListener('click', function() {
        fileInput.click();
    });

    function handleFile(file) {
        if (!file) return;

        // Validate file
        if (!isValidImageFile(file)) {
            alert('File harus berupa gambar (PNG, JPG, JPEG) dan maksimal 2MB');
            fileInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = file.name;
            
            // Show preview, hide placeholder
            uploadPlaceholder.classList.add('hidden');
            imagePreview.classList.remove('hidden');
            
            // Update upload area styling
            uploadArea.classList.remove('border-dashed');
            uploadArea.classList.add('border-solid', 'border-green-300', 'bg-green-50');
        };
        reader.readAsDataURL(file);
    }

    function isValidImageFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        return validTypes.includes(file.type) && file.size <= maxSize;
    }

    // Reset preview when form is reset
    function resetPreview() {
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        uploadArea.classList.remove('border-solid', 'border-green-300', 'bg-green-50');
        uploadArea.classList.add('border-dashed');
        previewImage.src = '';
        fileName.textContent = '';
        fileInput.value = '';
    }

    // Add reset functionality if needed
    window.resetFileUpload = resetPreview;
});
</script>
@endsection 