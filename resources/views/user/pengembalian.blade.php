@extends('layouts.user')

@section('title', 'Pengembalian - SIMBARA')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Pengembalian Barang</h1>
        <p class="text-gray-600 mt-2">Kelola pengembalian barang yang telah dipinjam</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Ongoing Loans -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $ongoingPeminjaman->count() }}</h3>
                    <p class="text-sm text-gray-600">Peminjaman Aktif</p>
                </div>
            </div>
        </div>

        <!-- Pending Returns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $returnHistory->where('status_pengembalian', 'pending')->count() }}</h3>
                    <p class="text-sm text-gray-600">Menunggu Proses</p>
                </div>
            </div>
        </div>

        <!-- Overdue Items -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $overdueItems->count() }}</h3>
                    <p class="text-sm text-gray-600">Terlambat</p>
                </div>
            </div>
        </div>

        <!-- Completed Returns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $returnHistory->where('status_pengembalian', 'completed')->count() }}</h3>
                    <p class="text-sm text-gray-600">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Alert -->
    @if($overdueItems->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-red-800">Perhatian: Ada Peminjaman Terlambat!</h3>
                <p class="text-red-700 mt-1">Anda memiliki {{ $overdueItems->count() }} peminjaman yang sudah melewati batas waktu. Segera ajukan pengembalian untuk menghindari denda tambahan.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Ongoing Loans Section -->
    @if($ongoingPeminjaman->count() > 0)
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Peminjaman Aktif</h2>
            <p class="text-sm text-gray-600 mt-1">Barang yang sedang dipinjam dan dapat dikembalikan</p>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($ongoingPeminjaman as $peminjaman)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $peminjaman->kode_peminjaman }}</h3>
                            @if(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)->isPast())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Terlambat
                                </span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Tanggal Selesai: {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y') }}
                            @if(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)->isPast())
                                <span class="text-red-600 font-medium">
                                    ({{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->diffInDays(now()) }} hari terlambat)
                                </span>
                            @endif
                        </p>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Items:</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($peminjaman->peminjamanBarangs as $item)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->barang->nama_barang }} ({{ $item->jumlah_pinjam }})
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <form action="{{ route('user.pengembalian.submit', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin mengajukan pengembalian untuk {{ $peminjaman->kode_peminjaman }}? Super Admin akan melakukan verifikasi kondisi barang.')"
                                    class="inline-flex items-center px-4 py-2 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Ajukan Pengembalian
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Return History Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Pengembalian</h2>
            <p class="text-sm text-gray-600 mt-1">Daftar permintaan pengembalian yang telah diajukan</p>
        </div>
        @if($returnHistory->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($returnHistory as $pengembalian)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $pengembalian->peminjaman->kode_peminjaman }}</h3>
                            @if($pengembalian->status_pengembalian === 'pending')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu Proses
                                </span>
                            @elseif($pengembalian->status_pengembalian === 'completed')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Tanggal Pengembalian: {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}
                        </p>
                        @if($pengembalian->status_pengembalian === 'completed' && $pengembalian->total_denda > 0)
                        <p class="text-sm text-red-600 mt-1">
                            Total Denda: Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                        </p>
                        @endif
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Items:</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($pengembalian->pengembalianBarangs as $item)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->barang->nama_barang }} ({{ $item->jumlah_kembali }})
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex space-x-2">
                        <a href="{{ route('user.pengembalian.show', $pengembalian->id_pengembalian) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        @if($pengembalian->status_pengembalian === 'pending')
                        <form action="{{ route('user.pengembalian.cancel', $pengembalian->id_pengembalian) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan permintaan pengembalian ini?')"
                                    class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $returnHistory->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Pengembalian</h3>
            <p class="text-gray-600">Riwayat pengembalian akan muncul setelah Anda mengajukan permintaan pengembalian.</p>
        </div>
        @endif
    </div>

    <!-- Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Cara Pengajuan Pengembalian</h4>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Klik "Ajukan Pengembalian"</strong> - Anda hanya perlu menekan tombol untuk mengajukan pengembalian</li>
                        <li><strong>Verifikasi Super Admin</strong> - Super Admin akan melakukan verifikasi kondisi barang dan menghitung denda jika ada</li>
                        <li><strong>Pastikan kondisi barang baik</strong> - Siapkan barang dalam kondisi yang sama seperti saat dipinjam</li>
                        <li><strong>Pembatalan</strong> - Anda dapat membatalkan permintaan selama masih dalam status "Menunggu Proses"</li>
                        <li><strong>Denda otomatis</strong> - Denda keterlambatan dan kerusakan akan dihitung oleh sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 