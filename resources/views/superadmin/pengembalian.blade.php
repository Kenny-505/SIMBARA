@extends('layouts.superadmin')

@section('title', 'Pengembalian - SIMBARA Super Admin')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Pengembalian</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengembalian Barang</h1>
        <p class="text-gray-600">Proses pengembalian dan kelola denda</p>
    </div>
    
    <div class="flex space-x-2">
        <a href="{{ route('superadmin.peminjaman.index', ['status_peminjaman' => 'ongoing']) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
            Lihat Peminjaman Aktif
                </a>
        <a href="{{ route('superadmin.pengembalian.export', request()->query()) }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
            Export CSV
        </a>
    </div>
</div>
                
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pengembalian</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Menunggu Proses</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Denda</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Additional Statistics for Penalty Payment -->
@if(isset($stats['payment_required']) || isset($stats['payment_uploaded']))
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Perlu Pembayaran</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['payment_required'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['payment_uploaded'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Payment Verification Queue -->
@if(isset($paymentVerificationQueue) && $paymentVerificationQueue->count() > 0)
<div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-purple-800">Verifikasi Pembayaran Denda</h3>
        </div>
        <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
            {{ $paymentVerificationQueue->count() }} menunggu
        </span>
    </div>
    
    <p class="text-purple-700 mb-4">User berikut telah mengupload bukti pembayaran denda dan menunggu verifikasi Anda.</p>
    
    <div class="space-y-3">
        @foreach($paymentVerificationQueue as $payment)
        <div class="bg-white rounded-lg p-4 border border-purple-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h4 class="font-semibold text-gray-900 mr-3">{{ $payment->peminjaman->kode_peminjaman }}</h4>
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">
                            Rp {{ number_format($payment->total_denda, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p><strong>User:</strong> {{ $payment->peminjaman->user->nama_penanggung_jawab }}</p>
                        <p><strong>Upload:</strong> {{ $payment->tanggal_upload_pembayaran->format('d/m/Y H:i') }}</p>
                        @if($payment->catatan_pembayaran)
                        <p><strong>Catatan:</strong> {{ Str::limit($payment->catatan_pembayaran, 50) }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('superadmin.pengembalian.penalty-verification', $payment->id_pengembalian) }}" 
                       class="inline-flex items-center px-3 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verifikasi
                    </a>
                    <a href="{{ route('superadmin.pengembalian.show', $payment->id_pengembalian) }}" 
                       class="text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('superadmin.pengembalian.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
            <input type="text" 
                   name="search" 
                   id="search"
                   value="{{ request('search') }}"
                   placeholder="Kode peminjaman, nama user..."
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Proses</option>
                <option value="payment_required" {{ request('status') === 'payment_required' ? 'selected' : '' }}>Perlu Pembayaran</option>
                <option value="payment_uploaded" {{ request('status') === 'payment_uploaded' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="fully_completed" {{ request('status') === 'fully_completed' ? 'selected' : '' }}>Selesai Penuh</option>
            </select>
                </div>

        <!-- Kondisi -->
        <div>
            <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
            <select name="condition" id="condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Kondisi</option>
                <option value="baik" {{ request('condition') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ request('condition') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat" {{ request('condition') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                <option value="hilang" {{ request('condition') === 'hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
                        </div>

        <!-- Date Range -->
        <div>
            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" 
                   name="date_from" 
                   id="date_from"
                   value="{{ request('date_from') }}"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('superadmin.pengembalian.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                Reset
            </a>
                        </div>
    </form>
                        </div>

<!-- Pengembalian Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Pengembalian</h3>
                    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pengembalian
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jumlah Barang
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keterlambatan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Denda
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengembalian as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $p->peminjaman->kode_peminjaman }}</div>
                            <div class="text-sm text-gray-500">{{ $p->tanggal_pengembalian_aktual->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $p->peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $p->peminjaman->user->nama_organisasi ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $p->pengembalianBarangs->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->status_pengembalian === 'pending')
                                <span class="text-sm text-gray-500">Belum diverifikasi</span>
                            @elseif($p->hari_telat > 0)
                                <div class="text-sm text-red-600 font-medium">{{ $p->hari_telat }} hari</div>
                                <div class="text-xs text-gray-500">Rp {{ number_format($p->denda_telat, 0, ',', '.') }}</div>
                            @else
                                <span class="text-sm text-green-600">Tepat waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($p->status_pengembalian === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($p->status_pengembalian === 'payment_required') bg-orange-100 text-orange-800
                                @elseif($p->status_pengembalian === 'payment_uploaded') bg-purple-100 text-purple-800
                                @elseif($p->status_pengembalian === 'completed' || $p->status_pengembalian === 'fully_completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($p->status_pengembalian === 'pending') Menunggu
                                @elseif($p->status_pengembalian === 'payment_required') Perlu Bayar
                                @elseif($p->status_pengembalian === 'payment_uploaded') Verifikasi
                                @elseif($p->status_pengembalian === 'completed' || $p->status_pengembalian === 'fully_completed') Selesai
                                @else {{ ucfirst($p->status_pengembalian) }} @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('superadmin.pengembalian.show', $p->id_pengembalian) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                                @if($p->status_pengembalian === 'pending')
                                    <a href="{{ route('superadmin.pengembalian.process', $p->id_pengembalian) }}" 
                                       onclick="return confirm('Yakin ingin memproses pengembalian ini?')"
                                       class="text-green-600 hover:text-green-900">
                                        Proses
                                    </a>
                                @elseif($p->status_pengembalian === 'payment_uploaded')
                                    <a href="{{ route('superadmin.pengembalian.penalty-verification', $p->id_pengembalian) }}" 
                                       class="text-purple-600 hover:text-purple-900">
                                        Verifikasi
                                    </a>
                                @endif
                        </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada pengembalian yang ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
                        </div>
    
    <!-- Pagination -->
    @if($pengembalian->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengembalian->links() }}
                        </div>
    @endif
                    </div>

<!-- Penalty Summary -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Overdue Items -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Barang Terlambat</h3>
        </div>
        <div class="p-6">
            @if($overdueItems->count() > 0)
                <div class="space-y-3">
                    @foreach($overdueItems->take(5) as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->kode_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $item->user->nama_penanggung_jawab ?? 'N/A' }}</p>
                        </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-red-600">
                                    {{ $item->tanggal_selesai->diffInDays(now()) }} hari
                                </p>
                                <p class="text-xs text-gray-500">Sejak {{ $item->tanggal_selesai->format('d/m/Y') }}</p>
                        </div>
                        </div>
                    @endforeach
                </div>
                @if($overdueItems->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('superadmin.peminjaman.index', ['status_peminjaman' => 'ongoing']) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            Lihat {{ $overdueItems->count() - 5 }} lainnya
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-center py-4">Tidak ada barang terlambat</p>
            @endif
                        </div>
                    </div>

    <!-- Recent Returns -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pengembalian Terbaru</h3>
        </div>
        <div class="p-6">
            @if($recentReturns->count() > 0)
                <div class="space-y-3">
                    @foreach($recentReturns as $return)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $return->peminjaman->kode_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $return->peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($return->total_denda, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $return->tanggal_pengembalian_aktual->format('d/m/Y') }}</p>
                        </div>
                        </div>
                    @endforeach
                        </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada pengembalian terbaru</p>
            @endif
                        </div>
                    </div>
                </div>

<!-- Pending Processing Alert -->
@if($stats['pending'] > 0)
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    {{ $stats['pending'] }} pengembalian menunggu proses
                </h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Terdapat pengembalian yang perlu diproses. Segera lakukan untuk menyelesaikan siklus peminjaman.
                </p>
            </div>
        </div>
    </div>
@endif
@endsection 