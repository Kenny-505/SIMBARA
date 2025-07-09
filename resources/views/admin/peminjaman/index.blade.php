@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('content')
    <!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Peminjaman</h1>
        <p class="text-gray-600">Kelola persetujuan peminjaman barang untuk lembaga Anda</p>
    </div>
    </div>

    <!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Peminjaman -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Peminjaman</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</p>
            </div>
        </div>
                            </div>

    <!-- Menunggu Persetujuan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
                            </div>

    <!-- Disetujui -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $approvedCount }}</p>
            </div>
        </div>
                            </div>

    <!-- Ditolak -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Ditolak</p>
                <p class="text-2xl font-bold text-gray-900">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>
        </div>

<!-- Filter & Search -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter & Pencarian</h3>
    <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                <input type="text" name="search" id="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Cari berdasarkan kode peminjaman atau nama lembaga..."
                               value="{{ request('search') }}">
                    </div>

            <!-- Actions -->
            <div class="flex items-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                            </button>
                <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Reset
                            </a>
                    </div>
                </div>
            </form>
        </div>

<!-- Peminjaman List -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Peminjaman</h3>
    </div>

            @if($peminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                            <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode Peminjaman
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lembaga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pengajuan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                            </tr>
                        </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->kode_peminjaman }}</div>
                                <div class="text-sm text-gray-500">{{ $item->tujuan_peminjaman }}</div>
                                    </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ substr($item->user->nama_lembaga, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->nama_lembaga }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->user->nama_penanggung_jawab }}</div>
                                            </div>
                                        </div>
                                    </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</div>
                                    </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $admin_id = auth()->guard('admin')->id();

                                    $total_items = $item->peminjamanBarangs()
                                        ->whereHas('barang', fn($q) => $q->where('id_admin', $admin_id))
                                        ->count();
                                        
                                    $approved_items = $item->peminjamanBarangs()
                                        ->whereHas('barang', fn($q) => $q->where('id_admin', $admin_id))
                                        ->where('status_persetujuan', 'approved')
                                        ->count();

                                    $rejected_items = $item->peminjamanBarangs()
                                        ->whereHas('barang', fn($q) => $q->where('id_admin', $admin_id))
                                        ->where('status_persetujuan', 'rejected')
                                        ->count();
                                    
                                    $pending_items = $total_items - $approved_items - $rejected_items;

                                    if ($pending_items > 0) {
                                        $status_text = 'Menunggu';
                                        $status_class = 'bg-yellow-100 text-yellow-800';
                                        $status_icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                    } elseif ($approved_items === $total_items) {
                                        $status_text = 'Disetujui';
                                        $status_class = 'bg-green-100 text-green-800';
                                        $status_icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                    } elseif ($rejected_items === $total_items) {
                                        $status_text = 'Ditolak';
                                        $status_class = 'bg-red-100 text-red-800';
                                        $status_icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                    } else {
                                        $status_text = 'Sebagian';
                                        $status_class = 'bg-blue-100 text-blue-800';
                                        $status_icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status_class }}">
                                    {!! $status_icon !!}
                                    {{ $status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $adminItemsCount = $item->peminjamanBarangs()
                                        ->whereHas('barang', function($q) {
                                            $q->where('id_admin', auth()->guard('admin')->user()->id_admin);
                                        })->count();
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $adminItemsCount }} item
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- Detail Button -->
                                    <a href="{{ route('admin.peminjaman.show', $item) }}" 
                                       class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                            </a>

                                    @if($item->status_pengajuan == 'pending_approval')
                                        <!-- Quick Approve Button -->
                                        <button onclick="showApproveModal('{{ $item->id_peminjaman }}', '{{ $item->kode_peminjaman }}')"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Approve
                                        </button>

                                        <!-- Quick Reject Button -->
                                        <button onclick="showRejectModal('{{ $item->id_peminjaman }}', '{{ $item->kode_peminjaman }}')"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Reject
                                        </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if ($peminjaman->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                            Previous
                        </span>
                    @else
                        <a href="{{ $peminjaman->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            Previous
                        </a>
                    @endif

                    @if ($peminjaman->hasMorePages())
                        <a href="{{ $peminjaman->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            Next
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                            Next
                        </span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $peminjaman->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $peminjaman->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $peminjaman->total() }}</span>
                            results
                        </p>
                    </div>

                    <div>
                        {{ $peminjaman->links() }}
                    </div>
                </div>
                    </div>
                </div>
            @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada peminjaman</h3>
            <p class="mt-1 text-sm text-gray-500">
                        @if(request('search') || request('status'))
                            Tidak ada peminjaman yang cocok dengan filter yang dipilih.
                        @else
                            Belum ada peminjaman yang masuk untuk barang Anda.
                        @endif
                    </p>
                    @if(request('search') || request('status'))
                <div class="mt-6">
                    <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Filter
                        </a>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Approve Peminjaman</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">
                Apakah Anda yakin ingin menyetujui semua item dari lembaga Anda dalam peminjaman <span id="approveKodePeminjaman" class="font-medium"></span>?
            </p>
            <form id="approveForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="approveNotes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="approveNotes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Approve Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Reject Peminjaman</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">
                Apakah Anda yakin ingin menolak semua item dari lembaga Anda dalam peminjaman <span id="rejectKodePeminjaman" class="font-medium"></span>?
            </p>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejectNotes" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea id="rejectNotes" name="notes" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Reject Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Approve Modal Functions
function showApproveModal(peminjamanId, kodePeminjaman) {
    document.getElementById('approveKodePeminjaman').textContent = kodePeminjaman;
    document.getElementById('approveForm').action = `/admin/peminjaman/${peminjamanId}/approve-all`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveNotes').value = '';
}

// Reject Modal Functions
function showRejectModal(peminjamanId, kodePeminjaman) {
    document.getElementById('rejectKodePeminjaman').textContent = kodePeminjaman;
    document.getElementById('rejectForm').action = `/admin/peminjaman/${peminjamanId}/reject-all`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectNotes').value = '';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (event.target === approveModal) {
        closeApproveModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}

@if($pendingCount > 0)
    // Auto-refresh every 30 seconds for pending requests
        setTimeout(function() {
            location.reload();
        }, 30000);
@endif
</script>
@endsection 