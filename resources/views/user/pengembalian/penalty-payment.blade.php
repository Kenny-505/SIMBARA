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
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <span class="font-medium">{{ $item->barang->nama_barang }}</span>
                        <span class="text-gray-500 text-sm ml-2">({{ $item->jumlah_kembali }} unit, {{ ucfirst($item->kondisi_barang ?? 'Belum ditentukan') }})</span>
                    </div>
                    <span class="font-medium text-red-600">Rp {{ number_format($item->denda_kerusakan ?? 0, 0, ',', '.') }}</span>
                </div>
                @endforeach
                
                @if($pengembalian->denda_telat > 0)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <span class="font-medium">Denda Keterlambatan</span>
                        <span class="text-gray-500 text-sm ml-2">({{ $pengembalian->hari_telat }} hari terlambat)</span>
                    </div>
                    <span class="font-medium text-red-600">Rp {{ number_format($pengembalian->denda_telat, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="flex justify-between items-center py-2 border-t border-gray-300 pt-2 font-semibold">
                    <span class="text-gray-900">Total Denda:</span>
                    <span class="text-red-600 text-lg">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</span>
                </div>
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
                <h4 class="text-sm font-medium text-blue-800">Informasi Pembayaran Denda</h4>
                <div class="mt-2 text-sm text-blue-700">
                    <p class="font-medium">Bank BCA</p>
                    <p>No. Rekening: <span class="font-mono">1234567890</span></p>
                    <p>Atas Nama: <span class="font-medium">FMIPA UNIVERSITAS</span></p>
                    <p class="mt-2 font-medium">Total denda yang harus dibayar: <span class="text-lg">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    @if($pengembalian->status_pengembalian === 'payment_required')
    
    <!-- Rejection Alert (if payment was rejected) -->
    @if($pengembalian->status_pembayaran_denda === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-red-800">Bukti Pembayaran Sebelumnya Ditolak</h3>
                <p class="text-red-700 mt-1">Bukti pembayaran yang Anda upload sebelumnya ditolak oleh Super Admin. Silakan upload ulang bukti pembayaran yang lebih jelas dan sesuai.</p>
                @if($pengembalian->catatan_pembayaran)
                <div class="mt-3 p-3 bg-red-100 rounded-lg">
                    <p class="text-red-800 text-sm"><strong>Catatan Admin:</strong> {{ $pengembalian->catatan_pembayaran }}</p>
                </div>
                @endif
                @if($pengembalian->verified_payment_at)
                <p class="text-red-600 text-sm mt-2">
                    <strong>Ditolak pada:</strong> {{ $pengembalian->verified_payment_at->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            {{ $pengembalian->status_pembayaran_denda === 'rejected' ? 'Upload Ulang Bukti Transfer Denda' : 'Upload Bukti Transfer Denda' }}
        </h2>
        
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

        <form action="{{ route('user.pengembalian.upload-penalty-payment', $pengembalian->id_pengembalian) }}" method="POST" enctype="multipart/form-data">
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
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 5MB</p>
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



            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.pengembalian.show', $pengembalian->id_pengembalian) }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
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
        

    </div>
    @endif
</div>

<!-- Custom Styles -->
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

<!-- JavaScript for Image Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const fileInput = document.getElementById('bukti_pembayaran');
    const uploadArea = document.getElementById('upload-area');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    const changeFileBtn = document.getElementById('change-file');

    console.log('Elements found:', {
        fileInput: !!fileInput,
        uploadArea: !!uploadArea,
        uploadPlaceholder: !!uploadPlaceholder,
        imagePreview: !!imagePreview,
        previewImage: !!previewImage,
        fileName: !!fileName,
        changeFileBtn: !!changeFileBtn
    });

    // File input change event
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            console.log('File input changed:', e.target.files[0]);
            handleFileSelect(e.target.files[0]);
        });
    }

    // Drag and drop events
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
    }

    // Change file button
    if (changeFileBtn) {
        changeFileBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }

    function handleFileSelect(file) {
        console.log('Handling file select:', file);
        
        if (!file) {
            resetUploadArea();
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
            resetUploadArea();
            return;
        }

        // Validate file size (5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            resetUploadArea();
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('File loaded, showing preview');
            
            if (previewImage) {
                previewImage.src = e.target.result;
            }
            if (fileName) {
                fileName.textContent = file.name;
            }
            
            if (uploadPlaceholder) {
                uploadPlaceholder.classList.add('hidden');
            }
            if (imagePreview) {
                imagePreview.classList.remove('hidden');
            }
            if (uploadArea) {
                uploadArea.classList.add('file-upload-success');
            }
        };
        reader.readAsDataURL(file);
    }

    function resetUploadArea() {
        console.log('Resetting upload area');
        
        if (uploadPlaceholder) {
            uploadPlaceholder.classList.remove('hidden');
        }
        if (imagePreview) {
            imagePreview.classList.add('hidden');
        }
        if (uploadArea) {
            uploadArea.classList.remove('file-upload-success');
        }
        if (previewImage) {
            previewImage.src = '';
        }
        if (fileName) {
            fileName.textContent = '';
        }
        if (fileInput) {
            fileInput.value = '';
        }
    }
});
</script>
@endsection 