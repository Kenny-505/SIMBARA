@extends('layouts.superadmin')

@section('title', 'Transaksi - SIMBARA Super Admin')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Transaksi</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Transaksi & Pembayaran</h1>
        <p class="text-gray-600">Verifikasi pembayaran sewa dan denda</p>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('superadmin.transaksi.export', request()->query()) }}" 
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
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
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
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
                <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['verified'] }}</p>
                                </div>
                            </div>
                        </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('superadmin.transaksi.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Jenis Transaksi -->
        <div>
            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
            <select name="jenis" id="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                <option value="sewa" {{ request('jenis') === 'sewa' ? 'selected' : '' }}>Pembayaran Sewa</option>
                <option value="denda" {{ request('jenis') === 'denda' ? 'selected' : '' }}>Pembayaran Denda</option>
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
            <a href="{{ route('superadmin.transaksi.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                Reset
            </a>
        </div>
    </form>
                </div>

                <!-- Transaksi Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Transaksi</h3>
    </div>
    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Transaksi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jumlah
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bukti Pembayaran
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transaksi as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($t->jenis_transaksi === 'sewa')
                                <div class="text-sm font-medium text-gray-900">{{ $t->peminjaman->kode_peminjaman }}</div>
                                <div class="text-sm text-gray-500">{{ $t->peminjaman->tujuan_peminjaman }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $t->pengembalian->peminjaman->kode_peminjaman }}</div>
                                <div class="text-sm text-gray-500">{{ $t->pengembalian->peminjaman->tujuan_peminjaman }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($t->jenis_transaksi === 'sewa')
                                <div class="text-sm text-gray-900">{{ $t->peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $t->peminjaman->user->nama_lembaga ?? 'N/A' }}</div>
                            @else
                                <div class="text-sm text-gray-900">{{ $t->pengembalian->peminjaman->user->nama_penanggung_jawab ?? $t->user->nama_penanggung_jawab ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $t->pengembalian->peminjaman->user->nama_lembaga ?? $t->user->nama_lembaga ?? 'N/A' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($t->nominal, 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($t->jenis_transaksi) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($t->status_verifikasi === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($t->status_verifikasi === 'approved') bg-green-100 text-green-800
                                @elseif($t->status_verifikasi === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($t->status_verifikasi === 'pending') Menunggu
                                @elseif($t->status_verifikasi === 'approved') Terverifikasi
                                @elseif($t->status_verifikasi === 'rejected') Ditolak
                                @else {{ ucfirst($t->status_verifikasi) }} @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($t->bukti_pembayaran)
                                @if($t->bukti_pembayaran === 'dummy_payment_proof')
                                    <div class="flex flex-col">
                                        <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded mb-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L3.98 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            Data Migrasi
                                        </span>
                                        <span class="text-xs text-gray-500">Perlu upload ulang</span>
                                    </div>
                                @else
                                    <a href="{{ route('superadmin.transaksi.payment-proof', $t->id_transaksi) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                        Lihat
                                    </a>
                                @endif
                            @else
                                <span class="text-gray-500 text-sm">Belum upload</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $t->created_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $t->created_at->format('H:i') }}</div>
                            @if($t->tanggal_verifikasi)
                                <div class="text-xs text-green-600">Verified: {{ $t->tanggal_verifikasi->format('d/m/Y H:i') }}</div>
                            @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($t->status_verifikasi === 'pending')
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('superadmin.transaksi.verify', $t->id_transaksi) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin memverifikasi pembayaran ini?')"
                                                class="text-green-600 hover:text-green-900">
                                            Verifikasi
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('superadmin.transaksi.reject', $t->id_transaksi) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menolak pembayaran ini?')"
                                                class="text-red-600 hover:text-red-900">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                                    </td>
                                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada transaksi yang ditemukan
                                    </td>
                                </tr>
                @endforelse
                            </tbody>
                        </table>
                    </div>
    
    <!-- Pagination -->
    @if($transaksi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transaksi->links() }}
        </div>
    @endif
</div>

<!-- Pending Verification Alert -->
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
                    {{ $stats['pending'] }} transaksi menunggu verifikasi
                </h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Terdapat pembayaran yang perlu diverifikasi. Segera proses untuk memperlancar alur peminjaman.
                </p>
                </div>
        </div>
    </div>
@endif
@endsection 