@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('content')
    <!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.peminjaman.index') }}" class="ml-1 text-gray-700 hover:text-blue-600">Peminjaman</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">Detail</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
    </div>

    <!-- Peminjaman Information -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
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
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Mulai Peminjaman</label>
                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_mulai ? $peminjaman->tanggal_mulai->format('d/m/Y') : '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Selesai Peminjaman</label>
                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_selesai ? $peminjaman->tanggal_selesai->format('d/m/Y') : '-' }}</p>
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
                    @elseif($peminjaman->status_pengajuan == 'rejected')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ditolak
                                    </span>
                    @elseif($peminjaman->status_pengajuan == 'partial')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Sebagian Disetujui
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

    <!-- Quick Actions -->
    @if($adminItems->where('status_persetujuan', 'pending')->count() > 0 && in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial']))
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Approve All -->
            <div class="border border-green-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-green-800 mb-3">Setujui Semua Item</h4>
                                 <form method="POST" action="{{ route('admin.peminjaman.approve-all', $peminjaman) }}">
                            @csrf
                    <div class="mb-4">
                        <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Persetujuan (Opsional)</label>
                        <textarea name="notes" id="approve_notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Masukkan catatan persetujuan jika diperlukan"></textarea>
                            </div>
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menyetujui semua item Anda dalam peminjaman ini?')"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Setujui Semua Item
                            </button>
                        </form>
                    </div>

            <!-- Reject All -->
            <div class="border border-red-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-red-800 mb-3">Tolak Semua Item</h4>
                                 <form method="POST" action="{{ route('admin.peminjaman.reject-all', $peminjaman) }}">
                            @csrf
                    <div class="mb-4">
                        <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="notes" id="reject_notes" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Masukkan alasan penolakan"></textarea>
                            </div>
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menolak semua item Anda dalam peminjaman ini?')"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tolak Semua Item
                            </button>
                        </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Items Summary -->
    @php
        $deletedItems = $adminItems->where('user_action', 'deleted');
        $activeItems = $adminItems->whereNull('user_action');
    @endphp
    
    @if($deletedItems->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <h4 class="text-sm font-medium text-red-800">Items yang Dihapus User</h4>
        </div>
        <p class="text-sm text-red-700 mt-1">
            {{ $deletedItems->count() }} item telah dihapus oleh user dari pengajuan ini. 
            Items tersebut masih ditampilkan di bawah untuk keperluan history admin.
        </p>
    </div>
    @endif

    <!-- Items List -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Item yang Diminta (Milik Anda)</h3>
        <p class="text-sm text-gray-500">
            Daftar barang milik lembaga Anda yang diminta dalam peminjaman ini
            @if($deletedItems->count() > 0)
                <br><span class="text-red-600 font-medium">{{ $activeItems->count() }} aktif, {{ $deletedItems->count() }} dihapus user</span>
            @endif
        </p>
        </div>

            @if($adminItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                                <tr>
                        <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="w-48 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        @if($adminItems->where('status_persetujuan', 'pending')->count() > 0 && in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial']))
                            <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                                </tr>
                            </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($adminItems as $item)
                        <tr class="hover:bg-gray-50 {{ $item->user_action === 'deleted' ? 'bg-red-50 opacity-75' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->barang->foto_1)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_1) }}"
                                         alt="{{ $item->barang->nama_barang }}"
                                         class="w-16 h-16 object-cover rounded-lg {{ $item->user_action === 'deleted' ? 'grayscale' : '' }}">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if($item->user_action === 'deleted')
                                    <div class="mt-1 flex items-center">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="text-xs text-red-500 ml-1">Dihapus User</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium {{ $item->user_action === 'deleted' ? 'text-gray-500 line-through' : 'text-gray-900' }} truncate" title="{{ $item->barang->nama_barang }}">{{ $item->barang->nama_barang }}</div>
                                <div class="text-sm text-gray-500 truncate" title="{{ $item->barang->deskripsi }}">{{ $item->barang->deskripsi ?? '-' }}</div>
                                @if($item->user_action === 'deleted' && $item->action_timestamp)
                                    <div class="text-xs text-red-500 mt-1">
                                        Dihapus: {{ $item->action_timestamp ? \Carbon\Carbon::parse($item->action_timestamp)->format('d/m/Y H:i') : '-' }}
                                    </div>
                                @endif
                                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $item->jumlah_pinjam }}
                                </span>
                                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->barang->stok_tersedia >= $item->jumlah_pinjam ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->barang->stok_tersedia }} tersedia
                                            </span>
                                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                            @if($item->user_action === 'deleted')
                                    <div class="space-y-1">
                                        @if($item->status_persetujuan == 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Menunggu
                                            </span>
                                        @elseif($item->status_persetujuan == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($item->status_persetujuan == 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @elseif($item->status_persetujuan == 'cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Dibatalkan
                                            </span>
                                        @endif
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Dihapus User
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    @if($item->status_persetujuan == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                    @elseif($item->status_persetujuan == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @elseif($item->status_persetujuan == 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @elseif($item->status_persetujuan == 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Dibatalkan
                                        </span>
                                    @endif
                                @endif
                                        </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 truncate" title="{{ $item->notes_admin }}">{{ $item->notes_admin ?? '-' }}</div>
                                @if($item->tanggal_persetujuan)
                                    <div class="text-xs text-gray-500">{{ $item->tanggal_persetujuan->format('d/m/Y H:i') }}</div>
                                            @endif
                                        </td>
                            @if($adminItems->where('status_persetujuan', 'pending')->count() > 0 && in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial']))
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($item->status_persetujuan == 'pending')
                                        <div class="flex items-center space-x-2">
                                            <!-- Individual Approve -->
                                             <form method="POST" action="{{ route('admin.peminjaman.approve', $peminjaman) }}" class="m-0">
                                                 @csrf
                                                 <input type="hidden" name="item_ids[]" value="{{ $item->id_peminjaman_barang }}">
                                                 <button type="submit"
                                                         onclick="return confirm('Setujui item {{ $item->barang->nama_barang }}?')"
                                                         class="text-green-600 hover:text-green-900">
                                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                     </svg>
                                                    </button>
                                             </form>
                                            <!-- Individual Reject -->
                                            <button onclick="showIndividualRejectModal({{ $item->id_peminjaman_barang }}, '{{ $item->barang->nama_barang }}')"
                                                    class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada item</h3>
            <p class="mt-1 text-sm text-gray-500">Tidak ada barang milik Anda dalam peminjaman ini.</p>
        </div>
    @endif
</div>

<!-- Individual Reject Modal -->
<div id="individualRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tolak Item</h3>
                <button onclick="closeIndividualRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">
                Apakah Anda yakin ingin menolak item <span id="individualItemName" class="font-medium"></span>?
            </p>
            <form id="individualRejectForm" method="POST">
                @csrf
                <input type="hidden" id="individualItemId" name="item_ids[]">
                <div class="mb-4">
                    <label for="individualRejectNotes" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea id="individualRejectNotes" name="notes" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeIndividualRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Tolak Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Individual Reject Modal Functions
function showIndividualRejectModal(itemId, itemName) {
    document.getElementById('individualItemName').textContent = itemName;
    document.getElementById('individualItemId').value = itemId;
         document.getElementById('individualRejectForm').action = `{{ route('admin.peminjaman.reject', $peminjaman) }}`;
    document.getElementById('individualRejectModal').classList.remove('hidden');
}

function closeIndividualRejectModal() {
    document.getElementById('individualRejectModal').classList.add('hidden');
    document.getElementById('individualRejectNotes').value = '';
        }
        
// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('individualRejectModal');
    if (event.target === modal) {
        closeIndividualRejectModal();
        }
    }
</script>
@endsection 