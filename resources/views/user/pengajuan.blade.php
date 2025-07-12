@extends('layouts.user')

@section('title', 'Daftar Pengajuan - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pengajuan</h1>
            <p class="text-gray-600">Kelola pengajuan peminjaman Anda</p>
        </div>
        <a href="{{ route('user.pengajuan.form') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            + Buat Pengajuan Baru
        </a>
    </div>
</div>

@if($peminjamans->count() > 0)
<!-- Pengajuan List -->
<div class="space-y-6">
    @foreach($peminjamans as $peminjaman)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $peminjaman->kode_peminjaman }}</h3>
                <p class="text-sm text-gray-600">{{ $peminjaman->tujuan_peminjaman }}</p>
                <p class="text-xs text-gray-500">
                    Dibuat: {{ $peminjaman->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="text-right">
                @php
                    // Determine the primary status to display
                    $primaryStatus = $peminjaman->status_pengajuan;
                    
                    // Only show status_peminjaman if the loan is actually confirmed and active
                    // This prevents showing "ongoing" for items that are still pending approval
                    if ($peminjaman->status_pengajuan === 'confirmed' && 
                        in_array($peminjaman->status_peminjaman, ['ongoing', 'returned', 'completed'])) {
                        $primaryStatus = $peminjaman->status_peminjaman;
                    }
                    
                    $statusConfig = [
                        'draft' => ['bg-gray-100', 'text-gray-800', 'Draft'],
                        'pending_approval' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu Persetujuan'],
                        'approved' => ['bg-green-100', 'text-green-800', 'Disetujui'],
                        'confirmed' => ['bg-blue-100', 'text-blue-800', 'Dikonfirmasi'],
                        'partial' => ['bg-orange-100', 'text-orange-800', 'Sebagian Disetujui'],
                        'waiting_verification' => ['bg-purple-100', 'text-purple-800', 'Menunggu Verifikasi'],
                        'verified' => ['bg-indigo-100', 'text-indigo-800', 'Terverifikasi'],
                        'ongoing' => ['bg-cyan-100', 'text-cyan-800', 'Sedang Dipinjam'],
                        'returned' => ['bg-orange-100', 'text-orange-800', 'Dikembalikan'],
                        'completed' => ['bg-green-100', 'text-green-800', 'Selesai'],
                        'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                        'rejected' => ['bg-red-100', 'text-red-800', 'Ditolak']
                    ];
                    $config = $statusConfig[$primaryStatus] ?? ['bg-gray-100', 'text-gray-800', ucfirst($primaryStatus)];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                    {{ $config[2] }}
                </span>
                
                @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' && $peminjaman->total_biaya > 0)
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-900">
                        Total: Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Date Range -->
        <div class="mb-4">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @if($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai)
                    <span>{{ Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ Carbon\Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(Carbon\Carbon::parse($peminjaman->tanggal_selesai)) + 1 }} hari</span>
                @else
                    <span class="text-gray-400 italic">Tanggal belum diset</span>
                @endif
            </div>
        </div>

        <!-- Items List -->
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Barang yang Dipinjam:</h4>
            <div class="space-y-2">
                @foreach($peminjaman->peminjamanBarangs as $item)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $item->barang->nama_barang }}</p>
                        <p class="text-xs text-gray-600">{{ $item->barang->admin->asal }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-900">{{ $item->jumlah_pinjam }} unit</p>
                        @if($item->harga_satuan > 0)
                        <p class="text-xs text-gray-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div class="ml-4">
                        @php
                            $itemStatusConfig = [
                                'pending' => ['bg-yellow-100', 'text-yellow-800', 'Pending'],
                                'approved' => ['bg-green-100', 'text-green-800', 'Disetujui'],
                                'rejected' => ['bg-red-100', 'text-red-800', 'Ditolak']
                            ];
                            $itemConfig = $itemStatusConfig[$item->status_persetujuan] ?? ['bg-gray-100', 'text-gray-800', ucfirst($item->status_persetujuan)];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $itemConfig[0] }} {{ $itemConfig[1] }}">
                            {{ $itemConfig[2] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Notes (if any) -->
        @if($peminjaman->notes_admin)
        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="text-sm font-medium text-yellow-800 mb-1">Catatan Admin:</h5>
            <p class="text-sm text-yellow-700">{{ $peminjaman->notes_admin }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            @if($peminjaman->status_pengajuan === 'draft')
                <!-- Draft actions -->
                <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Edit
                </a>
                <form action="{{ route('user.pengajuan.submit', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm"
                            onclick="return confirm('Yakin ingin submit pengajuan ini?')">
                        Submit
                    </button>
                </form>
                <form action="{{ route('user.pengajuan.cancel', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                            onclick="return confirm('Yakin ingin batalkan pengajuan ini?')">
                        Batalkan
                    </button>
                </form>

            @elseif($peminjaman->status_pengajuan === 'pending_approval')
                <!-- Pending approval -->
                <span class="text-sm text-gray-500">Menunggu review admin</span>

            @elseif($peminjaman->status_pengajuan === 'partial')
                <!-- PARTIAL: User must edit -->
                <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Edit Pengajuan
                </a>
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                    Lihat Detail
                </a>

            @elseif($peminjaman->status_pengajuan === 'approved')
                <!-- Approved - can confirm -->
                @php
                    $allApproved = $peminjaman->peminjamanBarangs->every(function($item) {
                        return $item->status_persetujuan === 'approved';
                    });
                @endphp
                @if($allApproved)
                    <form action="{{ route('user.pengajuan.confirm', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm"
                                onclick="return confirm('Yakin ingin konfirmasi peminjaman ini?')">
                            Konfirmasi Peminjaman
                        </button>
                    </form>
                @else
                    <span class="text-sm text-gray-500">Menunggu persetujuan semua barang</span>
                @endif

            @elseif($peminjaman->status_pengajuan === 'confirmed')
                @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' && $peminjaman->status_pembayaran === 'pending')
                    <!-- Non-civitas need to upload payment proof -->
                    <a href="{{ route('user.peminjaman.payment', $peminjaman->id_peminjaman) }}" 
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                        Upload Bukti Bayar
                    </a>
                @elseif(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' && $peminjaman->status_pembayaran === 'waiting_verification')
                    <!-- Payment uploaded, waiting for verification -->
                    <span class="text-sm text-yellow-600">Bukti bayar telah diupload, menunggu verifikasi</span>
                @else
                    <!-- Civitas or payment verified -->
                    <span class="text-sm text-green-600">Peminjaman dikonfirmasi, siap diambil</span>
                @endif

            @elseif($peminjaman->status_peminjaman === 'ongoing' && $peminjaman->status_pengajuan !== 'rejected')
                <!-- Ongoing loan (only if not rejected) - check if return request exists -->
                @if($peminjaman->pengembalian)
                    @if($peminjaman->pengembalian->status_pengembalian === 'pending')
                        <span class="text-sm text-orange-600">Menunggu Verifikasi Pengembalian</span>
                    @elseif($peminjaman->pengembalian->status_pengembalian === 'approved')
                        <span class="text-sm text-green-600">Pengembalian Disetujui</span>
                    @elseif($peminjaman->pengembalian->status_pengembalian === 'completed')
                        <span class="text-sm text-green-600">Pengembalian Selesai</span>
                    @elseif($peminjaman->pengembalian->status_pengembalian === 'payment_required')
                        @if($peminjaman->pengembalian->status_pembayaran_denda === 'rejected')
                        <a href="{{ route('user.pengembalian.penalty-payment', $peminjaman->pengembalian->id_pengembalian) }}" 
                           class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition-colors text-sm">
                            Upload Ulang Bukti
                        </a>
                        <span class="text-sm text-red-700">Pembayaran Ditolak - Perlu Upload Ulang</span>
                        @else
                        <a href="{{ route('user.pengembalian.penalty-payment', $peminjaman->pengembalian->id_pengembalian) }}" 
                           class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                            Bayar Denda
                        </a>
                        <span class="text-sm text-red-600">Denda Perlu Dibayar</span>
                        @endif
                    @else
                        <span class="text-sm text-orange-600">{{ ucfirst(str_replace('_', ' ', $peminjaman->pengembalian->status_pengembalian)) }}</span>
                    @endif
                @else
                    <!-- No return request yet, show button -->
                    <form action="{{ route('user.pengembalian.submit', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm"
                                onclick="return confirm('Yakin ingin mengajukan pengembalian untuk semua barang?')">
                            Ajukan Pengembalian
                        </button>
                    </form>
                @endif

            @elseif($peminjaman->status_pengajuan === 'rejected')
                <!-- Rejected/Cancelled applications -->
                <span class="text-sm text-red-600">Pengajuan dibatalkan</span>

            @elseif($peminjaman->status_peminjaman === 'returned')
                <!-- Returned items -->
                <span class="text-sm text-orange-600">Barang telah dikembalikan</span>

            @elseif($peminjaman->status_peminjaman === 'completed')
                <!-- Completed loans -->
                <span class="text-sm text-green-600">Peminjaman selesai</span>

            @else
                <!-- View details for other statuses -->
                <a href="{{ route('user.peminjaman.detail', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6 flex justify-center">
    {{ $peminjamans->links() }}
</div>

@else
<!-- Empty State -->
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengajuan</h3>
    <p class="mt-1 text-sm text-gray-500">
        Mulai dengan membuat pengajuan peminjaman pertama Anda.
    </p>
    <div class="mt-6">
        <a href="{{ route('user.pengajuan.form') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Buat Pengajuan Baru
        </a>
    </div>
</div>
@endif

<!-- Information Panel -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h4 class="text-sm font-medium text-blue-800">Status Pengajuan</h4>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Draft:</strong> Pengajuan masih bisa diedit dan belum disubmit</li>
                    <li><strong>Menunggu Persetujuan:</strong> Pengajuan sedang direview oleh admin</li>
                    <li><strong>Disetujui:</strong> Semua barang telah disetujui, bisa dikonfirmasi</li>
                    <li><strong>Dikonfirmasi:</strong> Peminjaman telah dikonfirmasi
                        @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa')
                        , upload bukti pembayaran
                        @endif
                    </li>
                    <li><strong>Sedang Dipinjam:</strong> Barang sedang dalam masa peminjaman</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 