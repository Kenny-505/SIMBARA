@extends('layouts.user')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Detail Header -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Detail Peminjaman</h2>
                <p class="text-sm text-gray-600">{{ $peminjaman->kode_peminjaman }}</p>
            </div>
            
            <div class="p-6">
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Informasi Peminjam & Pengajuan</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm text-gray-600">Nama Lembaga</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->user->nama_lembaga ?? 'N/A' }}</dd>
                            </div>
                             <div>
                                <dt class="text-sm text-gray-600">Penanggung Jawab Akun</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</dd>
                                <dd class="text-xs text-gray-500">{{ $peminjaman->user->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Nama Pengambil</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->nama_pengambil ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Nomor HP Pengambil</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->no_hp_pengambil ?? '-' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm text-gray-600">Nomor Identitas Pengambil</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->no_identitas_pengambil ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Informasi Peminjaman</h4>
                        <dl class="space-y-4">
                             <div>
                                <dt class="text-sm text-gray-600">Tujuan Peminjaman</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->tujuan_peminjaman }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Tanggal Pengajuan</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Periode Peminjaman</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Status Pengajuan</dt>
                                <dd>
                                     @if($peminjaman->status_pengajuan == 'pending' || $peminjaman->status_pengajuan == 'pending_approval')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($peminjaman->status_pengajuan == 'approved')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @elseif($peminjaman->status_pengajuan == 'rejected')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                     @elseif($peminjaman->status_pengajuan == 'partial')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Sebagian Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($peminjaman->status_pengajuan) }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Barang yang Dipinjam</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($peminjaman->peminjamanBarangs as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->barang->nama_barang }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->barang->admin->nama_lembaga ?? 'N/A' }}
                                        </div>
                                        @if($item->status_persetujuan == 'rejected' && $item->notes_admin)
                                            <div class="mt-1 text-xs text-red-600">
                                                <strong>Alasan Ditolak:</strong> {{ $item->notes_admin }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jumlah_pinjam }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($item->harga_satuan > 0)
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($item->subtotal > 0)
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status_persetujuan == 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                @elseif($item->status_persetujuan == 'rejected')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @elseif($item->status_persetujuan == 'cancelled')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($item->status_persetujuan == 'rejected' && !$item->user_action && in_array($peminjaman->status_pengajuan, ['draft', 'pending_approval', 'approved', 'partial']))
                                    <!-- Delete button for rejected items -->
                                    <form action="{{ route('user.peminjaman.delete-item', $item->id_peminjaman_barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini dari pengajuan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                @elseif($item->user_action == 'deleted')
                                    <!-- Show deleted status -->
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="text-xs">Dihapus</span>
                                    </div>
                                    @if($item->action_timestamp)
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ Carbon\Carbon::parse($item->action_timestamp)->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Actions for Partial Approval -->
        @if($peminjaman->status_pengajuan == 'partial')
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tindakan Diperlukan</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Beberapa item dalam pengajuan Anda ditolak. Anda dapat mengedit pengajuan untuk menghapus atau mengganti item yang ditolak.
                </p>
                <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Pengajuan
                </a>
            </div>
        </div>
        @endif

        <!-- Actions -->
        @if($peminjaman->status_pengajuan == 'approved' && $peminjaman->status_pembayaran == 'pending' && $peminjaman->total_biaya > 0)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Silakan lakukan pembayaran untuk melanjutkan proses peminjaman.
                </p>
                <a href="{{ route('user.peminjaman.payment', $peminjaman->id_peminjaman) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Upload Bukti Pembayaran
                </a>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($peminjaman->catatan_admin)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Catatan Admin</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-700">{{ $peminjaman->catatan_admin }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 