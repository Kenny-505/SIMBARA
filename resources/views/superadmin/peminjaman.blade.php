@extends('layouts.superadmin')

@section('title', 'Peminjaman Global - SIMBARA Super Admin')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Peminjaman Global</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Peminjaman Global</h1>
        <p class="text-gray-600">Monitor semua peminjaman dari seluruh lembaga</p>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('superadmin.peminjaman.export', request()->query()) }}" 
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                            </svg>
                        </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
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
                <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_approval'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Sedang Berlangsung</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['ongoing'] }}</p>
                                </div>
                            </div>
                        </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.232 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Terlambat</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('superadmin.peminjaman.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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

        <!-- Status Pengajuan -->
        <div>
            <label for="status_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">Status Pengajuan</label>
            <select name="status_pengajuan" id="status_pengajuan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending_approval" {{ request('status_pengajuan') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                <option value="approved" {{ request('status_pengajuan') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="confirmed" {{ request('status_pengajuan') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="rejected" {{ request('status_pengajuan') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <!-- Status Peminjaman -->
        <div>
            <label for="status_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Status Peminjaman</label>
            <select name="status_peminjaman" id="status_peminjaman" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="ongoing" {{ request('status_peminjaman') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="returned" {{ request('status_peminjaman') === 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
        </div>

        <!-- Admin/Lembaga -->
        <div>
            <label for="admin_id" class="block text-sm font-medium text-gray-700 mb-2">Lembaga</label>
            <select name="admin_id" id="admin_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Lembaga</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->asal }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('superadmin.peminjaman.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                Reset
            </a>
        </div>
    </form>
                </div>

                <!-- Peminjaman Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Peminjaman</h3>
    </div>
    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kode Peminjaman
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Periode
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status Pengajuan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status Peminjaman
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Biaya
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Items
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peminjaman as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $p->kode_peminjaman }}</div>
                            <div class="text-sm text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $p->user->nama_penanggung_jawab ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $p->user->nama_lembaga ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400">
                                @if($p->user->role->nama_role === 'user_fmipa')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Civitas</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full">Non-Civitas</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $p->tanggal_mulai ? $p->tanggal_mulai->format('d/m/Y') : 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d/m/Y') : 'N/A' }}</div>
                            @if($p->status_peminjaman === 'ongoing' && $p->tanggal_selesai && $p->tanggal_selesai->isPast())
                                <div class="text-xs text-red-600">Terlambat {{ $p->tanggal_selesai->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($p->status_pengajuan === 'pending_approval') bg-yellow-100 text-yellow-800
                                @elseif($p->status_pengajuan === 'approved') bg-green-100 text-green-800
                                @elseif($p->status_pengajuan === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($p->status_pengajuan === 'rejected') bg-red-100 text-red-800
                                @elseif($p->status_pengajuan === 'partial') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($p->status_pengajuan === 'partial')
                                    Sebagian Disetujui
                                @else
                                    {{ ucfirst($p->status_pengajuan) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->status_peminjaman)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($p->status_peminjaman === 'ongoing') bg-blue-100 text-blue-800
                                    @elseif($p->status_peminjaman === 'returned') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($p->status_peminjaman) }}
                                </span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</div>
                                    </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $p->peminjamanBarangs->count() }} items</div>
                            <div class="text-sm text-gray-500">
                                {{ $p->peminjamanBarangs->where('status_persetujuan', 'approved')->count() }} approved
                            </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('superadmin.peminjaman.show', $p->id_peminjaman) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                                    </td>
                                </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada peminjaman yang ditemukan
                                    </td>
                                </tr>
                @endforelse
                            </tbody>
                        </table>
    </div>
    
    <!-- Pagination -->
    @if($peminjaman->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $peminjaman->links() }}
        </div>
    @endif
</div>

<!-- Recent Activities -->
<div class="mt-8 bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Aktivitas Terbaru</h3>
    </div>
    <div class="p-6">
        @if($recentActivities->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $activity->kode_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $activity->user->nama_penanggung_jawab ?? 'User' }}</p>
                                <p class="text-xs text-gray-400">{{ $activity->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($activity->status_pengajuan === 'pending_approval') bg-yellow-100 text-yellow-800
                                @elseif($activity->status_pengajuan === 'approved') bg-green-100 text-green-800
                                @elseif($activity->status_pengajuan === 'confirmed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($activity->status_pengajuan) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Belum ada aktivitas terbaru</p>
        @endif
    </div>
</div>

<!-- Overdue Alert -->
@if($stats['overdue'] > 0)
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Perhatian: {{ $stats['overdue'] }} peminjaman terlambat
                </h3>
                <p class="mt-1 text-sm text-red-700">
                    Beberapa peminjaman melewati batas waktu pengembalian. Segera lakukan tindakan follow-up.
                </p>
                </div>
        </div>
    </div>
@endif
@endsection 