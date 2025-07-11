@extends('layouts.user')

@section('title', 'Konfirmasi Pengajuan - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" class="text-blue-600 hover:text-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Konfirmasi Pengajuan</h1>
            <p class="text-gray-600">Review dan konfirmasi pengajuan peminjaman Anda</p>
        </div>
    </div>
</div>

<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center">
        <div class="flex items-center text-green-600">
            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">
                ✓
            </div>
            <span class="ml-2 text-sm font-medium">Pilih Barang</span>
        </div>
        <div class="flex-1 h-px bg-green-600 mx-4"></div>
        <div class="flex items-center text-green-600">
            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">
                ✓
            </div>
            <span class="ml-2 text-sm font-medium">Form Pengajuan</span>
        </div>
        <div class="flex-1 h-px bg-blue-600 mx-4"></div>
        <div class="flex items-center text-blue-600">
            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">
                3
            </div>
            <span class="ml-2 text-sm font-medium">Konfirmasi</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Pengajuan Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Peminjaman</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $peminjaman->kode_peminjaman }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Draft
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->tujuan_peminjaman }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Periode Peminjaman -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Periode Peminjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <p class="text-sm text-gray-900">
                        @if($peminjaman->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d M Y') }}
                        @else
                            <span class="text-gray-400 italic">Belum diset</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <p class="text-sm text-gray-900">
                        @if($peminjaman->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d M Y') }}
                        @else
                            <span class="text-gray-400 italic">Belum diset</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durasi</label>
                    <p class="text-sm text-gray-900">
                        @if($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)) + 1 }} hari
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Pengambil -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Penanggung Jawab Pengambilan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->nama_pengambil }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Identitas</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->no_identitas_pengambil }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <p class="text-sm text-gray-900">{{ $peminjaman->no_hp_pengambil }}</p>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Dipinjam</h2>
            
            <div class="space-y-4">
                @foreach($peminjaman->peminjamanBarangs as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <!-- Item Image -->
                    <div class="flex-shrink-0">
                        @if($item->barang->foto_1)
                            <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_1) }}" 
                                 alt="{{ $item->barang->nama_barang }}" 
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
                        <h3 class="text-lg font-medium text-gray-900">{{ $item->barang->nama_barang }}</h3>
                        <p class="text-sm text-blue-600">{{ $item->barang->admin->asal }}</p>
                        <p class="text-sm text-gray-500">Jumlah: {{ $item->jumlah_pinjam }} unit</p>
                        @if($userType === 'non_civitas')
                        <p class="text-sm text-gray-600">Harga: Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}/hari</p>
                        @endif
                    </div>
                    
                    <!-- Item Cost -->
                    @if($userType === 'non_civitas')
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Subtotal:</p>
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Summary & Actions -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pengajuan</h2>
            
            <!-- Summary Info -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Item:</span>
                    <span class="font-medium">{{ $peminjaman->peminjamanBarangs->count() }} jenis</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Quantity:</span>
                    <span class="font-medium">{{ $peminjaman->peminjamanBarangs->sum('jumlah_pinjam') }} unit</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Durasi:</span>
                    <span class="font-medium">
                        @if($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)) + 1 }} hari
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </span>
                </div>
                
                @if($userType === 'non_civitas')
                <div class="border-t pt-3">
                    <div class="flex justify-between text-lg font-semibold text-blue-600">
                        <span>Total Biaya:</span>
                        <span>Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">*Pembayaran dilakukan setelah Anda konfirmasi pengajuan yang sudah disetujui admin</p>
                </div>
                @else
                <div class="border-t pt-3">
                    <div class="flex justify-between text-lg font-semibold text-blue-600">
                        <span>Status:</span>
                        <span>GRATIS</span>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Important Notes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
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
                                <li>Setelah submit, pengajuan akan menunggu persetujuan admin</li>
                                <li>Anda tidak dapat mengubah pengajuan setelah disubmit</li>
                                <li>Pastikan semua data sudah benar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($peminjaman->status_pengajuan === 'draft')
                    <!-- Draft Status: Show all buttons -->
                    <form method="POST" action="{{ route('user.pengajuan.submit', $peminjaman->id_peminjaman) }}">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Submit Pengajuan
                        </button>
                    </form>
                    
                    <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" 
                       class="w-full bg-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-400 transition-colors text-center block font-medium">
                        Edit Pengajuan
                    </a>
                    
                    <form method="POST" action="{{ route('user.pengajuan.cancel', $peminjaman->id_peminjaman) }}" 
                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Batalkan Pengajuan
                        </button>
                    </form>
                @else
                    <!-- After Submit: Show status info only -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Pengajuan Telah Disubmit</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    @if($peminjaman->status_pengajuan === 'pending')
                                        Pengajuan Anda sedang menunggu persetujuan admin.
                                    @elseif($peminjaman->status_pengajuan === 'approved')
                                        Pengajuan Anda telah disetujui admin.
                                    @elseif($peminjaman->status_pengajuan === 'rejected')
                                        Pengajuan Anda telah ditolak admin.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.pengajuan.index') }}" 
                       class="w-full bg-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-400 transition-colors text-center block font-medium">
                        Kembali ke Daftar Pengajuan
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 