@extends('layouts.superadmin')

@section('title', 'Verifikasi Pembayaran Denda - SIMBARA')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('superadmin.pengembalian.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pembayaran Denda</h1>
                <p class="text-gray-600 mt-1">Kode Peminjaman: {{ $pengembalian->peminjaman->kode_peminjaman }}</p>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Info Verifikasi</h3>
                <p class="text-blue-700 mt-1">User telah mengupload bukti pembayaran denda. Silakan verifikasi keaslian dan kebenaran bukti pembayaran.</p>
                <p class="text-blue-600 text-sm mt-1">Setelah diverifikasi, stok barang akan dikembalikan ke inventaris dan peminjaman akan ditandai selesai.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Left Column: Payment Information -->
        <div class="space-y-6">
            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                        <span class="text-red-800 font-semibold">Total Denda</span>
                        <span class="text-red-800 font-bold text-xl">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-3">Rincian Denda:</h3>
                        <div class="space-y-2">
                            @foreach($pengembalian->pengembalianBarangs as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                <div>
                                    <span class="font-medium">{{ $item->barang->nama_barang }}</span>
                                    <span class="text-gray-500 text-sm ml-2">({{ $item->jumlah_kembali }} unit)</span>
                                    <div class="text-xs text-gray-600">{{ $item->keterangan_kerusakan }}</div>
                                </div>
                                <span class="font-medium text-red-600">Rp {{ number_format($item->denda_kerusakan ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            
                            @if($pengembalian->denda_telat > 0)
                            <div class="flex justify-between items-center py-2 border-t border-gray-300 pt-2">
                                <div>
                                    <span class="font-medium">Denda Keterlambatan</span>
                                    <span class="text-gray-500 text-sm ml-2">({{ $pengembalian->hari_telat }} hari)</span>
                                </div>
                                <span class="font-medium text-red-600">Rp {{ number_format($pengembalian->denda_telat, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi User</h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Penanggung Jawab</p>
                        <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nama_penanggung_jawab }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Organisasi</p>
                        <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nama_organisasi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nomor_telepon }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Upload Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Info Upload</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Upload</p>
                        <p class="font-medium text-gray-900">{{ $pengembalian->tanggal_upload_pembayaran->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Menunggu Verifikasi
                        </span>
                    </div>
                    @if($pengembalian->catatan_pembayaran)
                    <div>
                        <p class="text-sm text-gray-600">Catatan User</p>
                        <p class="font-medium text-gray-900 italic">{{ $pengembalian->catatan_pembayaran }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Proof & Verification -->
        <div class="space-y-6">
            <!-- Payment Proof -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bukti Pembayaran</h2>
                
                @if($pengembalian->bukti_pembayaran_denda)
                <div class="space-y-4">
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <img src="data:image/jpeg;base64,{{ $pengembalian->bukti_pembayaran_denda }}" 
                             alt="Bukti Pembayaran" 
                             class="w-full h-auto max-h-96 object-contain"
                             onclick="openImageModal(this.src)">
                    </div>
                    <p class="text-sm text-gray-600 text-center">Klik gambar untuk memperbesar</p>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500">Bukti pembayaran tidak tersedia</p>
                </div>
                @endif
            </div>

            <!-- Verification Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Pembayaran</h2>
                
                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Approve Button -->
                    <form action="{{ route('superadmin.transaksi.verify', $transaksi->id_transaksi) }}" method="POST" id="approveForm" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?\n\nSetelah disetujui:\n- Pengembalian akan ditandai selesai\n- Stok barang akan dikembalikan ke inventaris\n- User akan mendapat notifikasi approval')"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Setujui Pembayaran
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <form action="{{ route('superadmin.transaksi.reject', $transaksi->id_transaksi) }}" method="POST" id="rejectForm" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?\n\nSetelah ditolak:\n- User akan diminta upload ulang bukti pembayaran\n- Stok barang tetap tidak dikembalikan\n- User akan mendapat notifikasi penolakan')"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-75">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// JavaScript functions are no longer needed as we're using direct form submissions
// The confirmation dialogs are now handled inline in the onclick attributes

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection 