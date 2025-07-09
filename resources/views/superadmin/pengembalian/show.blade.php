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
    <div class="mb-6">
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Lembaga</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nama_lembaga ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Pengambil</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->nama_pengambil ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nomor Identitas Pengambil</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->no_identitas_pengambil ?? '-' }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor HP Pengambil</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->no_hp_pengambil ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Penanggung Jawab Akun</p>
                    <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->user->nama_penanggung_jawab ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $pengembalian->peminjaman->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tipe User</p>
                    <p class="font-medium text-gray-900">
                        @if($pengembalian->peminjaman->user->role && $pengembalian->peminjaman->user->role->nama_role === 'user_fmipa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Civitas FMIPA
                            </span>
                        @elseif($pengembalian->peminjaman->user->role && $pengembalian->peminjaman->user->role->nama_role === 'user_non_fmipa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Non-Civitas FMIPA
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Unknown
                            </span>
                        @endif
                    </p>
                </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengembalian->pengembalianBarangs as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($item->barang->foto_1)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_1) }}" 
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
                                    <p class="text-sm text-gray-500">ID: {{ $item->barang->id_barang }}</p>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->denda_kerusakan, 0, ',', '.') }}
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



    <!-- Processing Timeline -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline Proses</h3>
        
        <div class="flow-root">
            <ul class="-mb-8">
                @php
                    $events = [];
                    
                    // User request event
                    $events[] = [
                        'type' => 'created',
                        'icon' => 'arrow-right-circle',
                        'title' => 'Permintaan pengembalian diajukan oleh user',
                        'date' => $pengembalian->created_at,
                    ];
                    
                    // Processing completed event
                    if($pengembalian->status_pengembalian === 'completed') {
                        $events[] = [
                            'type' => 'completed',
                            'icon' => 'check-circle',
                            'title' => 'Pengembalian selesai diproses',
                            'date' => $pengembalian->updated_at,
                        ];
                        
                        // Stock restoration event
                        $events[] = [
                            'type' => 'restored',
                            'icon' => 'database',
                            'title' => 'Stok barang dikembalikan ke inventaris',
                            'date' => $pengembalian->updated_at,
                        ];
                    }
                @endphp

                @forelse($events as $index => $event)
                <li>
                    <div class="relative pb-8">
                        @if($index < count($events) - 1)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full 
                                    @if($event['type'] == 'created') bg-blue-500
                                    @elseif($event['type'] == 'completed') bg-green-500
                                    @elseif($event['type'] == 'restored') bg-purple-500
                                    @else bg-gray-300
                                    @endif
                                    flex items-center justify-center ring-8 ring-white">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($event['icon'] == 'arrow-right-circle')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @elseif($event['icon'] == 'check-circle')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @elseif($event['icon'] == 'database')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @endif
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $event['title'] }}</p>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <time datetime="{{ $event['date'] }}">
                                        {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                    </time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="text-center text-gray-500 py-4">
                    Belum ada aktivitas
                </li>
                @endforelse
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
        <a href="{{ route('superadmin.pengembalian.process', $pengembalian->id_pengembalian) }}" 
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