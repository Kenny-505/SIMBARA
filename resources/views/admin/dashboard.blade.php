@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Barang -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Barang</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalBarang }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Barang Tersedia -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Barang Tersedia</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $barangTersedia }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Menunggu Approval -->
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
                                <p class="text-sm font-medium text-gray-600">Menunggu Approval</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $menungguApproval }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Peminjaman Statistics -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Peminjaman</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Peminjaman</span>
                                <span class="text-sm font-medium text-gray-900">{{ $totalPeminjaman }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Peminjaman Aktif</span>
                                <span class="text-sm font-medium text-green-600">{{ $peminjamanAktif }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Barang Tidak Tersedia</span>
                                <span class="text-sm font-medium text-red-600">{{ $barangTidakTersedia }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Stok Menipis</h3>
                        @if($lowStockItems->count() > 0)
                            <div class="space-y-3">
                                @foreach($lowStockItems as $item)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</p>
                            <p class="text-xs text-gray-500">Kode: {{ $item->kode_barang ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->stok_tersedia <= ($item->stok_total * 0.1) ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $item->stok_tersedia }}/{{ $item->stok_total }}
                                        </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ round(($item->stok_tersedia / $item->stok_total) * 100) }}% tersedia
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada barang dengan stok menipis</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Peminjaman -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Peminjaman Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Peminjam
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barang
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
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
                                @forelse($recentPeminjaman as $peminjaman)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr($peminjaman->user->nama_penanggung_jawab ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                        {{ $peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                        {{ $peminjaman->kode_peminjaman }}
                                    </div>
                                </div>
                                            </div>
                                        </td>
                                                                                 <td class="px-6 py-4 whitespace-nowrap">
                                             <div class="text-sm text-gray-900">
                                                 {{ $peminjaman->peminjamanBarangs->count() }} item
                                             </div>
                            <div class="text-sm text-gray-500">
                                {{ $peminjaman->peminjamanBarangs->first()->barang->nama_barang ?? 'N/A' }}
                                @if($peminjaman->peminjamanBarangs->count() > 1)
                                    +{{ $peminjaman->peminjamanBarangs->count() - 1 }} lainnya
                                @endif
                                             </div>
                                         </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $peminjaman->tanggal_mulai ? \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') : 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                s/d {{ $peminjaman->tanggal_selesai ? \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') : 'N/A' }}
                            </div>
                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($peminjaman->status_pengajuan)
                                @case('draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                    @break
                                @case('pending_approval')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Menunggu Approval
                                                </span>
                                    @break
                                @case('approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                    @break
                                @case('rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Ditolak
                                                </span>
                                    @break
                                @case('confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Dikonfirmasi
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($peminjaman->status_pengajuan) }}
                                    </span>
                            @endswitch
                                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.peminjaman.show', $peminjaman->id_peminjaman) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Lihat Detail
                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada peminjaman terbaru
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.barang.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
        <div class="flex items-center justify-center mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
        <h3 class="font-medium">Tambah Barang</h3>
        <p class="text-sm text-blue-100 mt-1">Tambah inventaris baru</p>
    </a>

    <a href="{{ route('admin.peminjaman.index') }}" 
       class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
        <div class="flex items-center justify-center mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="font-medium">Kelola Peminjaman</h3>
        <p class="text-sm text-green-100 mt-1">Review dan approve peminjaman</p>
    </a>

    <a href="{{ route('admin.calendar.index') }}" 
       class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
        <div class="flex items-center justify-center mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v0M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
            </svg>
        </div>
        <h3 class="font-medium">Lihat Kalender</h3>
        <p class="text-sm text-purple-100 mt-1">Jadwal peminjaman</p>
    </a>
    </div>
@endsection 