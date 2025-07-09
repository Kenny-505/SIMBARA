@extends('layouts.superadmin')

@section('title', 'Detail Peminjaman Global - SIMBARA Super Admin')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('superadmin.peminjaman.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Peminjaman Global
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500">Detail</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
        <p class="text-gray-600">{{ $peminjaman->kode_peminjaman }}</p>
    </div>
    <a href="{{ route('superadmin.peminjaman.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<!-- Summary Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Items</p>
                <p class="text-2xl font-bold text-gray-900">{{ $summary['total_items'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Items Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $summary['approved_items'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Durasi (Hari)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $summary['duration_days'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 {{ $summary['is_overdue'] ? 'bg-red-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 {{ $summary['is_overdue'] ? 'text-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.232 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">
                    {{ $summary['is_overdue'] ? 'Hari Terlambat' : 'Status' }}
                </p>
                <p class="text-2xl font-bold {{ $summary['is_overdue'] ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $summary['is_overdue'] ? $summary['days_overdue'] : 'On Time' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column - Peminjaman Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Peminjaman Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peminjaman</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kode Peminjaman</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $peminjaman->kode_peminjaman }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lembaga</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->nama_lembaga ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Pengambil</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->nama_pengambil ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nomor Identitas Pengambil</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->no_identitas_pengambil ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nomor HP Pengambil</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->no_hp_pengambil ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Penanggung Jawab Akun</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $peminjaman->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tipe User</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($peminjaman->user->role && $peminjaman->user->role->nama_role === 'user_fmipa')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Civitas FMIPA
                                </span>
                            @elseif($peminjaman->user->role && $peminjaman->user->role->nama_role === 'user_non_fmipa')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Non-Civitas FMIPA
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Unknown
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Mulai Peminjaman</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_mulai ? \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Selesai Peminjaman</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_selesai ? \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status Pengajuan</label>
                        <div class="mt-1">
                            @if($peminjaman->status_pengajuan == 'pending_approval')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Menunggu Persetujuan
                                </span>
                            @elseif($peminjaman->status_pengajuan == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Disetujui
                                </span>
                            @elseif($peminjaman->status_pengajuan == 'confirmed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Dikonfirmasi
                                </span>
                            @elseif($peminjaman->status_pengajuan == 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ditolak
                                </span>
                            @elseif($peminjaman->status_pengajuan == 'partial')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Sebagian Disetujui
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status Peminjaman</label>
                        <div class="mt-1">
                            @if($peminjaman->status_peminjaman == 'ongoing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Sedang Berlangsung
                                </span>
                            @elseif($peminjaman->status_peminjaman == 'returned')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Dikembalikan
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tujuan Peminjaman</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tujuan_peminjaman ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if($peminjaman->transaksi)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nominal Pembayaran</label>
                    <p class="mt-1 text-sm text-gray-900 font-medium">Rp {{ number_format($peminjaman->transaksi->nominal, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status Verifikasi</label>
                    <div class="mt-1">
                        @if($peminjaman->transaksi->status_verifikasi == 'verified')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Menunggu Verifikasi
                            </span>
                        @endif
                    </div>
                </div>
                @if($peminjaman->transaksi->tanggal_verifikasi)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Verifikasi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->transaksi->tanggal_verifikasi)->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Items List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftar Barang</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin/Lembaga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kuantitas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Disetujui Oleh
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman->peminjamanBarangs as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->barang->foto_1)
                                        <img class="h-10 w-10 rounded-lg object-cover mr-3" 
                                             src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_1) }}" 
                                             alt="{{ $item->barang->nama_barang }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->barang->nama_barang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->barang->admin->asal ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $item->barang->admin->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jumlah_pinjam }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status_persetujuan == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                @elseif($item->status_persetujuan == 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->approvedBy->nama_lengkap ?? '-' }}
                                @if($item->tanggal_persetujuan)
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada barang
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column - Timeline -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
            
            <div class="flow-root">
                <ul class="-mb-8">
                    @forelse($timeline as $index => $event)
                    <li>
                        <div class="relative pb-8">
                            @if($index < count($timeline) - 1)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full 
                                        @if($event['type'] == 'created') bg-blue-500
                                        @elseif($event['type'] == 'approved') bg-green-500
                                        @elseif($event['type'] == 'rejected') bg-red-500
                                        @elseif($event['type'] == 'payment_verified') bg-purple-500
                                        @elseif($event['type'] == 'returned') bg-gray-500
                                        @else bg-gray-300
                                        @endif
                                        flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($event['icon'] == 'plus-circle')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @elseif($event['icon'] == 'check-circle')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @elseif($event['icon'] == 'x-circle')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @elseif($event['icon'] == 'credit-card')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            @elseif($event['icon'] == 'arrow-left-circle')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $event['title'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $event['description'] }}</p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <time datetime="{{ $event['date'] }}">
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-gray-500 py-4">
                        Belum ada aktivitas
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 