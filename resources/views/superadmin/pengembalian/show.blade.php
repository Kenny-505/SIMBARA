@extends('layouts.superadmin')

@section('title', 'Detail Pengembalian - SIMBARA')

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
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengembalian</h1>
                <p class="text-gray-600 mt-1">Kode Peminjaman: {{ $pengembalian->peminjaman->kode_peminjaman }}</p>
            </div>
        </div>
    </div>

    <!-- Status and Processing Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Status Pengembalian</h2>
                @if($pengembalian->status_pengembalian === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Menunggu Proses
                    </span>
                @elseif($pengembalian->status_pengembalian === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Selesai
                    </span>
                @endif
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pengembalian</p>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}</p>
                </div>
                @if($pengembalian->status_pengembalian === 'completed')
                <div>
                    <p class="text-sm text-gray-600">Diproses Oleh</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->processedBy->nama_admin ?? 'Super Admin' }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Penalty Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Denda</h2>
            @if($pengembalian->total_denda > 0)
                <div class="p-4 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <div>
                            <p class="text-sm text-red-600">Total Denda</p>
                            <p class="text-2xl font-bold text-red-700">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-green-600">Tidak Ada Denda</p>
                            <p class="text-lg font-medium text-green-700">Barang dikembalikan dalam kondisi baik</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loan Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjaman</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Kode Peminjaman</p>
                <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->kode_peminjaman }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_mulai)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Selesai</p>
                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_selesai)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status Peminjaman</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ ucfirst($pengembalian->peminjaman->status_peminjaman) }}
                </span>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjam</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                <p class="text-sm text-gray-600">Nomor Telepon</p>
                <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nomor_telepon }}</p>
            </div>
        </div>
    </div>

    <!-- Items Returned -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Barang yang Dikembalikan</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda/Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengembalian->pengembalianBarangs as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($item->barang->foto_barang)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_barang) }}" 
                                         alt="{{ $item->barang->nama_barang }}"
                                         class="w-12 h-12 object-cover rounded-lg mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $item->barang->nama_barang }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->barang->kode_barang }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->jumlah_kembali }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($item->kondisi_barang === 'baik') bg-green-100 text-green-800
                                @elseif($item->kondisi_barang === 'ringan') bg-yellow-100 text-yellow-800
                                @elseif($item->kondisi_barang === 'sedang') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($item->kondisi_barang) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->denda_per_item, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->subtotal_denda, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                            @if($item->catatan_user)
                                <p class="text-sm text-gray-600 mb-1"><strong>User:</strong> {{ $item->catatan_user }}</p>
                            @endif
                            @if($item->notes_admin)
                                <p class="text-sm text-blue-600"><strong>Admin:</strong> {{ $item->notes_admin }}</p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Notes -->
    @if($pengembalian->notes_user)
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan User</h2>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-900">{{ $pengembalian->notes_user }}</p>
        </div>
    </div>
    @endif

    <!-- Admin Notes -->
    @if($pengembalian->notes_admin)
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Admin</h2>
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-gray-900">{{ $pengembalian->notes_admin }}</p>
        </div>
    </div>
    @endif

    <!-- Processing Timeline -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline Proses</h2>
        <div class="flow-root">
            <ul class="-mb-8">
                <!-- User Request -->
                <li>
                    <div class="relative pb-8">
                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                        <div class="relative flex space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 ring-8 ring-white">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Permintaan pengembalian diajukan oleh user</p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time>{{ $pengembalian->created_at->format('d/m/Y H:i') }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Processing Status -->
                <li>
                    <div class="relative pb-8">
                        @if($pengembalian->status_pengembalian === 'completed')
                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                        @endif
                        <div class="relative flex space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full 
                                @if($pengembalian->status_pengembalian === 'completed') bg-green-500 @else bg-yellow-500 @endif ring-8 ring-white">
                                @if($pengembalian->status_pengembalian === 'completed')
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        @if($pengembalian->status_pengembalian === 'completed')
                                            Pengembalian selesai diproses
                                        @else
                                            Menunggu proses verifikasi
                                        @endif
                                    </p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    @if($pengembalian->status_pengembalian === 'completed')
                                        <time>{{ $pengembalian->updated_at->format('d/m/Y H:i') }}</time>
                                    @else
                                        <span class="text-yellow-600">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Stock Restoration (if completed) -->
                @if($pengembalian->status_pengembalian === 'completed')
                <li>
                    <div class="relative">
                        <div class="relative flex space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-500 ring-8 ring-white">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Stok barang dikembalikan ke inventaris</p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time>{{ $pengembalian->updated_at->format('d/m/Y H:i') }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('superadmin.pengembalian.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
        
        @if($pengembalian->status_pengembalian === 'pending')
        <a href="{{ route('superadmin.pengembalian.create', $pengembalian->peminjaman->id_peminjaman) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Proses Pengembalian
        </a>
        @endif
    </div>
</div>
@endsection 