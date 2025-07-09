@extends('layouts.user')

@section('title', 'Ringkasan Pembayaran - SIMBARA')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('user.payment.status') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Status Pembayaran
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500">Ringkasan</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Ringkasan Pembayaran</h1>
        <p class="text-gray-600">Detail pembayaran untuk {{ $peminjaman->kode_peminjaman }}</p>
    </div>
    
    <div class="flex space-x-2">
        @if($peminjaman->status_pembayaran === 'pending' && $peminjaman->total_biaya > 0)
            <a href="{{ route('user.peminjaman.payment', $peminjaman->id_peminjaman) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                Upload Bukti Bayar
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Peminjaman Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Peminjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Peminjaman</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $peminjaman->kode_peminjaman }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    @if($peminjaman->status_pembayaran === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Belum Dibayar
                        </span>
                    @elseif($peminjaman->status_pembayaran === 'waiting_verification')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Menunggu Verifikasi
                        </span>
                    @elseif($peminjaman->status_pembayaran === 'verified')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Pembayaran Terverifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak Perlu Bayar
                        </span>
                    @endif
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

        <!-- Items List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Dipinjam</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($peminjaman->peminjamanBarangs as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->barang->nama_barang }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->barang->kategori_barang }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->jumlah_pinjam }} unit
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->barang->admin->nama_admin }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->barang->admin->lembaga }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status_persetujuan === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @elseif($item->status_persetujuan === 'rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @elseif($item->status_persetujuan === 'cancelled')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Timeline -->
        @if($peminjaman->transaksi)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline Pembayaran</h2>
            
            <div class="flow-root">
                <ul class="-mb-8">
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900">Transaksi dibuat</p>
                                        <p class="text-sm text-gray-500">{{ $peminjaman->transaksi->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    @if($peminjaman->tanggal_upload_bukti)
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900">Bukti pembayaran diupload</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($peminjaman->tanggal_upload_bukti)->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif
                    
                    @if($peminjaman->transaksi->tanggal_verifikasi)
                    <li>
                        <div class="relative">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900">Pembayaran diverifikasi</p>
                                        <p class="text-sm text-gray-500">{{ $peminjaman->transaksi->tanggal_verifikasi->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Payment Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Biaya</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Biaya Peminjaman</span>
                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="flex justify-between">
                    <span class="text-base font-medium text-gray-900">Total</span>
                    <span class="text-base font-bold text-gray-900">Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($peminjaman->total_biaya > 0)
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h4>
                <div class="mt-2 text-sm text-blue-700">
                    <p class="font-medium">Bank BCA</p>
                    <p>No. Rekening: <span class="font-mono">1234567890</span></p>
                    <p>Atas Nama: <span class="font-medium">FMIPA UNIVERSITAS</span></p>
                </div>
            </div>
            @else
            <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-green-800">Gratis untuk Civitas</h4>
                <div class="mt-2 text-sm text-green-700">
                    <p>Peminjaman ini tidak dikenakan biaya karena Anda adalah civitas akademik FMIPA.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
            
            <div class="space-y-3">
                @if($peminjaman->status_pembayaran === 'pending' && $peminjaman->total_biaya > 0)
                    <a href="{{ route('user.peminjaman.payment', $peminjaman->id_peminjaman) }}" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-center block">
                        Upload Bukti Bayar
                    </a>
                @endif
                
                <a href="{{ route('user.payment.status') }}" 
                   class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition text-center block">
                    Kembali ke Status
                </a>
                
                <a href="{{ route('user.peminjaman.index') }}" 
                   class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition text-center block">
                    Lihat Peminjaman
                </a>
            </div>
        </div>
    </div>
</div>

@endsection 