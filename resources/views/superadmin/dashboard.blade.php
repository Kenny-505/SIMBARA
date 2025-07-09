@extends('layouts.superadmin')

@section('title', 'Dashboard - SIMBARA Super Admin')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <span class="text-sm font-medium text-gray-500">Dashboard</span>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Super Admin</h1>
        <p class="text-gray-600">Ringkasan aktivitas sistem SIMBARA</p>
    </div>
    
    <!-- Period Filter -->
    <div class="flex space-x-2">
        <select id="periodFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
        </select>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Peminjaman Period -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Peminjaman {{ ucfirst($period === 'week' ? 'Minggu' : 'Bulan') }} Ini</p>
                <p id="totalPeminjamanPeriod" class="text-2xl font-bold text-gray-900">{{ $totalPeminjamanPeriod }}</p>
                <p class="text-xs text-gray-500">Total pengajuan peminjaman</p>
            </div>
        </div>
    </div>

    <!-- Revenue Non-Civitas -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Revenue Non-Civitas</p>
                <p id="revenueNonCivitas" class="text-2xl font-bold text-gray-900">Rp {{ number_format($revenueNonCivitas, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($period === 'week' ? 'Minggu' : 'Bulan') }} ini</p>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                <p id="pendingApprovals" class="text-2xl font-bold text-gray-900">{{ $pendingApprovals }}</p>
                <p class="text-xs text-gray-500">Menunggu persetujuan admin</p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Total Pengajuan Pendaftaran -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pengajuan</p>
                <p class="text-lg font-bold text-gray-900">{{ $totalPengajuan }}</p>
            </div>
        </div>
    </div>

    <!-- Disetujui -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Disetujui</p>
                <p class="text-lg font-bold text-gray-900">{{ $disetujui }}</p>
            </div>
        </div>
    </div>

    <!-- Menunggu Verifikasi -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                <p class="text-lg font-bold text-gray-900">{{ $menungguVerifikasi }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Link -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-sm p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Quick Access</p>
                <p class="text-lg font-bold">Verifikasi</p>
            </div>
            <a href="{{ route('superadmin.verifikasi.index') }}" class="bg-white bg-opacity-20 rounded-lg p-2 hover:bg-opacity-30 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Feature Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Verifikasi Pendaftaran -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Verifikasi Pendaftaran</h3>
                <p class="text-sm text-gray-500">Kelola pengajuan pendaftaran akun</p>
            </div>
        </div>
        <a href="{{ route('superadmin.verifikasi.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
            Lihat Pengajuan
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Inventaris Global -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Inventaris Global</h3>
                <p class="text-sm text-gray-500">Monitor semua barang inventaris</p>
            </div>
        </div>
        <a href="{{ route('superadmin.inventaris.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
            Lihat Inventaris
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Peminjaman Global -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Peminjaman Global</h3>
                <p class="text-sm text-gray-500">Monitor semua peminjaman</p>
            </div>
        </div>
        <a href="{{ route('superadmin.peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
            Lihat Peminjaman
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Transaksi & Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Transaksi</h3>
                <p class="text-sm text-gray-500">Verifikasi pembayaran</p>
            </div>
        </div>
        <a href="{{ route('superadmin.transaksi.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
            Lihat Transaksi
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Pengembalian -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Pengembalian</h3>
                <p class="text-sm text-gray-500">Proses pengembalian barang</p>
            </div>
        </div>
        <a href="{{ route('superadmin.pengembalian.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
            Lihat Pengembalian
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Kalender Global -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Kalender Global</h3>
                <p class="text-sm text-gray-500">Jadwal peminjaman semua lembaga</p>
            </div>
        </div>
        <a href="{{ route('superadmin.kalender.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
            Lihat Kalender
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Peminjaman -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Peminjaman Terbaru</h3>
        </div>
        <div class="p-6">
            @if($recentPeminjaman->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPeminjaman as $peminjaman)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $peminjaman->kode_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $peminjaman->user->nama_penanggung_jawab ?? 'User' }}</p>
                                <p class="text-xs text-gray-400">{{ $peminjaman->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($peminjaman->status_pengajuan === 'pending_approval') bg-yellow-100 text-yellow-800
                                    @elseif($peminjaman->status_pengajuan === 'approved') bg-green-100 text-green-800
                                    @elseif($peminjaman->status_pengajuan === 'confirmed') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($peminjaman->status_pengajuan) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada peminjaman terbaru</p>
            @endif
        </div>
    </div>

    <!-- Recent Pengembalian -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pengembalian Terbaru</h3>
        </div>
        <div class="p-6">
            @if($recentPengembalian->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPengembalian as $pengembalian)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->kode_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $pengembalian->peminjaman->user->nama_penanggung_jawab ?? 'User' }}</p>
                                <p class="text-xs text-gray-400">{{ $pengembalian->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                @if($pengembalian->total_denda > 0)
                                    <p class="text-sm font-medium text-red-600">Denda: Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm text-green-600">Tanpa Denda</p>
                                @endif
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($pengembalian->status_pengembalian) }}
                                </span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodFilter = document.getElementById('periodFilter');
    
    periodFilter.addEventListener('change', function() {
        const period = this.value;
        
        // Update URL with period parameter
        const url = new URL(window.location);
        url.searchParams.set('period', period);
        window.location.href = url.toString();
    });
});
</script>
@endsection 