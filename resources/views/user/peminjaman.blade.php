@extends('layouts.user')

@section('title', 'Riwayat Peminjaman - SIMBARA')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
            <p class="text-gray-600">Kelola semua pengajuan dan peminjaman Anda</p>
        </div>
        <a href="{{ route('user.pengajuan.form') }}" 
           class="{{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg transition-colors">
            + Buat Pengajuan Baru
        </a>
    </div>
</div>

@if($peminjamans->count() > 0)
<!-- Peminjaman List -->
<div class="space-y-6" id="peminjaman-list">
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
                    // Determine status based on actual workflow logic
                    $statusDisplay = '';
                    $statusClass = '';
                    
                    if ($peminjaman->status_pengajuan === 'draft') {
                        $statusDisplay = 'Draft';
                        $statusClass = 'bg-gray-100 text-gray-800';
                    } elseif ($peminjaman->status_pengajuan === 'pending_approval') {
                        $statusDisplay = 'Menunggu Persetujuan';
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                    } elseif ($peminjaman->status_pengajuan === 'approved') {
                        $statusDisplay = 'Disetujui';
                        $statusClass = 'bg-green-100 text-green-800';
                    } elseif ($peminjaman->status_pengajuan === 'confirmed') {
                        if ($peminjaman->status_pembayaran === 'pending') {
                            $statusDisplay = 'Dikonfirmasi - Menunggu Bayar';
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                        } elseif ($peminjaman->status_pembayaran === 'waiting_verification') {
                            $statusDisplay = 'Menunggu Verifikasi Bayar';
                            $statusClass = 'bg-orange-100 text-orange-800';
                        } else {
                            $statusDisplay = 'Dikonfirmasi';
                            $statusClass = 'bg-blue-100 text-blue-800';
                        }
                    } elseif ($peminjaman->status_pengajuan === 'rejected') {
                        $statusDisplay = 'Ditolak';
                        $statusClass = 'bg-red-100 text-red-800';
                    } elseif ($peminjaman->status_pengajuan === 'partial') {
                        $statusDisplay = 'Sebagian Disetujui';
                        $statusClass = 'bg-orange-100 text-orange-800';
                    } elseif ($peminjaman->status_peminjaman === 'ongoing') {
                        $statusDisplay = 'Sedang Dipinjam';
                        $statusClass = 'bg-purple-100 text-purple-800';
                    } elseif ($peminjaman->status_peminjaman === 'returned') {
                        $statusDisplay = 'Dikembalikan';
                        $statusClass = 'bg-orange-100 text-orange-800';
                    } elseif ($peminjaman->status_peminjaman === 'completed') {
                        $statusDisplay = 'Selesai';
                        $statusClass = 'bg-green-100 text-green-800';
                    } else {
                        $statusDisplay = ucfirst($peminjaman->status_pengajuan);
                        $statusClass = 'bg-gray-100 text-gray-800';
                    }
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                    {{ $statusDisplay }}
                </span>
            </div>
        </div>

        <!-- Periode -->
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            @if($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai)
                {{ $peminjaman->tanggal_mulai->format('d/m/Y') }} - {{ $peminjaman->tanggal_selesai->format('d/m/Y') }}
                <span class="ml-2 text-xs">
                    ({{ $peminjaman->tanggal_mulai->diffInDays($peminjaman->tanggal_selesai) + 1 }} hari)
                </span>
            @else
                <span class="text-gray-400 italic">Tanggal belum diset</span>
            @endif
        </div>

        <!-- Items -->
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Barang yang Dipinjam:</h4>
            <div class="space-y-2">
                @foreach($peminjaman->peminjamanBarangs as $item)
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div class="flex items-center">
                        @if($item->barang->foto_1)
                            <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_1) }}" 
                                 alt="{{ $item->barang->nama_barang }}"
                                 class="w-8 h-8 object-cover rounded-lg mr-3">
                        @else
                            <div class="w-8 h-8 bg-gray-300 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->barang->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $item->barang->admin->nama_lengkap ?? 'Admin' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $item->jumlah_pinjam }} unit</p>
                        @php
                            $itemStatusConfig = [
                                'pending' => ['bg-yellow-100', 'text-yellow-800', 'Pending'],
                                'approved' => ['bg-green-100', 'text-green-800', 'Approved'],
                                'rejected' => ['bg-red-100', 'text-red-800', 'Rejected']
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

        <!-- Total Cost (for non-civitas) -->
        @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' && $peminjaman->total_biaya > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                <span class="text-sm font-medium text-yellow-800">
                    Total Biaya: Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}
                </span>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
            @if($peminjaman->status_pengajuan === 'draft')
                <!-- Draft actions -->
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Lihat Detail
                </a>
                <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                    Edit
                </a>
                <form action="{{ route('user.pengajuan.cancel', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm"
                            onclick="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                        Batalkan
                    </button>
                </form>

            @elseif($peminjaman->status_pengajuan === 'pending_approval')
                <!-- Pending approval -->
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Lihat Detail
                </a>
                <span class="text-sm text-gray-500">Menunggu review admin</span>

            @elseif($peminjaman->status_pengajuan === 'approved' && ($peminjaman->status_peminjaman === 'ongoing' && $peminjaman->status_pembayaran === 'pending'))
                <!-- Approved but ongoing with pending payment - need confirmation -->
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

            @elseif($peminjaman->status_pengajuan === 'approved' && in_array($peminjaman->status_peminjaman, ['pending', 'approved']))
                <!-- Approved - can confirm (normal flow) -->
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

            @elseif($peminjaman->status_pengajuan === 'approved' && $peminjaman->status_peminjaman === 'ongoing')
                <!-- Already confirmed and ongoing - check if return request exists -->
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

            @elseif($peminjaman->status_pengajuan === 'confirmed')
                @php
                    $allTransaksi = $peminjaman->transaksis ?? [];
                    $transaksiApproved = collect($allTransaksi)->first(fn($t) => $t->status_verifikasi === 'approved');
                    $transaksiPending = collect($allTransaksi)->first(fn($t) => in_array($t->status_verifikasi, ['pending', 'waiting_verification']));
                    $allRejected = count($allTransaksi) > 0 && collect($allTransaksi)->every(fn($t) => $t->status_verifikasi === 'rejected');
                @endphp
                @php
                    $allTransaksi = $peminjaman->transaksis ?? [];
                    $adaRejected = collect($allTransaksi)->contains(fn($t) => $t->status_verifikasi === 'rejected');
                    $adaPendingOrApproved = collect($allTransaksi)->contains(fn($t) => in_array($t->status_verifikasi, ['pending', 'waiting_verification', 'approved']));
                @endphp
                @if(auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa' && $peminjaman->status_pengajuan === 'confirmed')
                    @if(!$adaPendingOrApproved && $adaRejected)
                        <div class="flex flex-col space-y-2">
                            <span class="text-sm text-red-600 font-semibold">Pembayaran Anda sebelumnya <b>ditolak</b>. Silakan upload ulang bukti pembayaran sewa.</span>
                            <a href="{{ route('user.peminjaman.payment', $peminjaman->id_peminjaman) }}" 
                               class="w-auto bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                                Upload Ulang Bukti Pembayaran
                            </a>
                        </div>
                    @endif
                @endif

            @elseif($peminjaman->status_pengajuan === 'partial')
                <!-- Partial approval - some items approved, some rejected -->
                <a href="{{ route('user.pengajuan.edit', $peminjaman->id_peminjaman) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Edit Pengajuan
                </a>
                @php
                    $hasApprovedItems = $peminjaman->peminjamanBarangs->where('status_persetujuan', 'approved')->count() > 0;
                @endphp
                @if($hasApprovedItems)
                    <form action="{{ route('user.pengajuan.confirm-partial', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm"
                                onclick="return confirm('Lanjutkan dengan barang yang disetujui saja?')">
                            Lanjutkan yang Disetujui
                        </button>
                    </form>
                @endif
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>

            @elseif($peminjaman->status_peminjaman === 'ongoing' && $peminjaman->status_pengajuan !== 'rejected' && $peminjaman->status_pengajuan !== 'partial')
                <!-- Ongoing loan (only if not rejected and not partial) - check if return request exists -->
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
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>
                <span class="text-sm text-red-600">Pengajuan dibatalkan</span>

            @elseif($peminjaman->status_peminjaman === 'returned')
                <!-- Returned items -->
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>
                <span class="text-sm text-orange-600">Barang telah dikembalikan</span>

            @elseif($peminjaman->status_peminjaman === 'completed')
                <!-- Completed loans -->
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>
                <span class="text-sm text-green-600">Peminjaman selesai</span>

            @else
                <!-- View details for other statuses -->
                <a href="{{ route('user.pengajuan.show', $peminjaman->id_peminjaman) }}" 
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
    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
    <p class="mt-1 text-sm text-gray-500">
        Mulai dengan membuat pengajuan peminjaman pertama Anda.
    </p>
    <div class="mt-6">
        <a href="{{ route('user.pengajuan.form') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }}">
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
            <h4 class="text-sm font-medium text-blue-800">Alur Peminjaman</h4>
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
                    <li><strong>Selesai:</strong> Peminjaman telah selesai dan barang dikembalikan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection 