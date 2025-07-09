@extends('layouts.user')

@section('title', 'Detail Pengembalian - SIMBARA')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('user.pengembalian.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
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

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Status Pengembalian</h2>
                <div class="mt-2">
                    @if($pengembalian->status_pengembalian === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Menunggu Proses
                        </span>
                    @elseif($pengembalian->status_pengembalian === 'payment_required')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Perlu Pembayaran Denda
                        </span>
                    @elseif($pengembalian->status_pengembalian === 'payment_uploaded')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Bukti Pembayaran Diupload
                        </span>
                    @elseif($pengembalian->status_pengembalian === 'completed' || $pengembalian->status_pengembalian === 'fully_completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Selesai
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                <p class="font-medium text-gray-900">{{ $pengembalian->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Return Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengembalian</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Kode Peminjaman</p>
                <p class="font-medium text-gray-900">{{ $pengembalian->peminjaman->kode_peminjaman }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Pengembalian</p>
                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-medium text-gray-900">
                    @if($pengembalian->status_pengembalian === 'pending')
                        Menunggu Proses
                    @elseif($pengembalian->status_pengembalian === 'payment_required')
                        Perlu Pembayaran Denda
                    @elseif($pengembalian->status_pengembalian === 'payment_uploaded')
                        Bukti Pembayaran Diupload
                    @elseif($pengembalian->status_pengembalian === 'completed' || $pengembalian->status_pengembalian === 'fully_completed')
                        Selesai
                    @endif
                </p>
            </div>
            @if($pengembalian->status_pengembalian === 'completed')
            <div>
                <p class="text-sm text-gray-600">Diproses Oleh</p>
                <p class="font-medium text-gray-900">{{ $pengembalian->processedBy->nama_admin ?? 'Super Admin' }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Penalty Information -->
    @if($pengembalian->total_denda > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-red-800">Total Denda</h3>
                <p class="text-red-700 mt-1 text-xl font-bold">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</p>
                @if($pengembalian->status_pengembalian === 'pending')
                    <p class="text-red-600 text-sm mt-1">*Estimasi denda, akan dikonfirmasi oleh Super Admin</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Penalty Payment Section -->
    @if($pengembalian->total_denda > 0 && in_array($pengembalian->status_pengembalian, ['payment_required', 'payment_uploaded', 'fully_completed']))
    <div class="@if($pengembalian->status_pengembalian === 'fully_completed') bg-green-50 border-green-200 @else bg-blue-50 border-blue-200 @endif border rounded-lg p-6 mb-6">
                 <h3 class="text-lg font-semibold @if($pengembalian->status_pengembalian === 'fully_completed') text-green-800 @else text-blue-800 @endif mb-4">Pembayaran Denda</h3>
        
        @if($pengembalian->status_pengembalian === 'payment_required')
            <!-- Check if payment was rejected -->
            @if($pengembalian->status_pembayaran_denda === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-red-800 font-semibold">Bukti Pembayaran Ditolak</h4>
                        <p class="text-red-700 text-sm mt-1">Silakan upload ulang bukti pembayaran yang lebih jelas dan sesuai.</p>
                        @if($pengembalian->catatan_pembayaran)
                        <p class="text-red-600 text-sm mt-2"><strong>Catatan Admin:</strong> {{ $pengembalian->catatan_pembayaran }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <div class="mb-4">
                <p class="text-blue-700 mb-2">Silakan lakukan pembayaran denda sebesar <span class="font-bold">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</span></p>
                <p class="text-blue-600 text-sm mb-4">
                    Upload bukti pembayaran setelah Anda melakukan transfer. Pembayaran dapat dilakukan melalui:
                </p>
                <ul class="text-blue-600 text-sm list-disc list-inside mb-4">
                    <li>Transfer Bank: BCA 1234567890 a.n. SIMBARA</li>
                    <li>E-Wallet: Dana/GoPay 08123456789</li>
                </ul>
            </div>
            
            <a href="{{ route('user.pengembalian.penalty-payment', $pengembalian->id_pengembalian) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                {{ $pengembalian->status_pembayaran_denda === 'rejected' ? 'Upload Ulang Bukti' : 'Upload Bukti Pembayaran' }}
            </a>
            
        @elseif($pengembalian->status_pengembalian === 'payment_uploaded')
            <div class="mb-4">
                <p class="text-blue-700 mb-2">✅ Bukti pembayaran telah diupload pada {{ $pengembalian->tanggal_upload_pembayaran->format('d/m/Y H:i') }}</p>
                <p class="text-blue-600 text-sm">Menunggu verifikasi dari Super Admin. Anda akan mendapat notifikasi jika pembayaran telah diverifikasi.</p>
            </div>
            
            @if($pengembalian->bukti_pembayaran_denda)
            <div class="mb-4">
                <p class="text-sm text-blue-700 mb-2">Bukti pembayaran yang diupload:</p>
                <img src="data:image/jpeg;base64,{{ $pengembalian->bukti_pembayaran_denda }}" 
                     alt="Bukti Pembayaran" 
                     class="max-w-xs h-auto border border-gray-300 rounded-lg shadow-sm">
            </div>
            @endif
            
            @if($pengembalian->catatan_pembayaran)
            <div class="mb-4">
                <p class="text-sm text-blue-700">Catatan Anda:</p>
                <p class="text-blue-800 italic">{{ $pengembalian->catatan_pembayaran }}</p>
            </div>
            @endif
            
            <a href="{{ route('user.pengembalian.penalty-payment', $pengembalian->id_pengembalian) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                                 Lihat Detail Pembayaran
             </a>
             
         @elseif($pengembalian->status_pengembalian === 'fully_completed')
             <div class="mb-4">
                 <div class="flex items-center mb-4">
                     <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                     </svg>
                     <div>
                         <h4 class="text-green-800 font-semibold">Pembayaran Denda Berhasil Diverifikasi</h4>
                         <p class="text-green-700 text-sm">Pengembalian telah selesai sepenuhnya. Terima kasih atas kerjasamanya.</p>
                     </div>
                 </div>
                 
                 @if($pengembalian->verified_payment_at)
                 <p class="text-green-600 text-sm mb-2">
                     <strong>Diverifikasi pada:</strong> {{ $pengembalian->verified_payment_at->format('d/m/Y H:i') }}
                 </p>
                 @endif
                 
                 @if($pengembalian->catatan_pembayaran)
                 <div class="bg-white rounded-lg p-3 border border-green-200">
                     <p class="text-sm text-green-700 font-medium">Catatan Verifikasi:</p>
                     <p class="text-green-800">{{ $pengembalian->catatan_pembayaran }}</p>
                 </div>
                 @endif
             </div>
         @endif
     </div>
     @endif

    <!-- Items Returned -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Dikembalikan</h2>
        <div class="space-y-4">
            @foreach($pengembalian->pengembalianBarangs as $item)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center">
                    @if($item->barang->foto_barang)
                        <img src="data:image/jpeg;base64,{{ base64_encode($item->barang->foto_barang) }}" 
                             alt="{{ $item->barang->nama_barang }}"
                             class="w-16 h-16 object-cover rounded-lg mr-4">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->barang->nama_barang }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                            <div>
                                <p class="text-sm text-gray-600">Jumlah Dikembalikan</p>
                                <p class="font-medium text-gray-900">{{ $item->jumlah_kembali }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kondisi</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($item->kondisi_barang === 'baik') bg-green-100 text-green-800
                                    @elseif($item->kondisi_barang === 'ringan') bg-yellow-100 text-yellow-800
                                    @elseif($item->kondisi_barang === 'sedang') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($item->kondisi_barang) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Denda Item</p>
                                <p class="font-medium text-gray-900">Rp {{ number_format($item->denda_kerusakan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @if($item->catatan_user)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">Catatan Anda:</p>
                            <p class="text-sm text-gray-900 italic">{{ $item->catatan_user }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- User Notes -->
    @if($pengembalian->notes_user)
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Anda</h2>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-900">{{ $pengembalian->notes_user }}</p>
        </div>
    </div>
    @endif

    <!-- Admin Notes -->
    @if($pengembalian->notes_admin && $pengembalian->status_pengembalian === 'completed')
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Admin</h2>
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-gray-900">{{ $pengembalian->notes_admin }}</p>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('user.pengembalian.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        
        @if($pengembalian->status_pengembalian === 'pending')
        <div class="flex space-x-2">
            <form action="{{ route('user.pengembalian.cancel', $pengembalian->id_pengembalian) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan permintaan pengembalian ini?')"
                        class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Permintaan
                </button>
            </form>
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
                <h4 class="text-sm font-medium text-blue-800">Informasi Status</h4>
                <div class="mt-2 text-sm text-blue-700">
                    @if($pengembalian->status_pengembalian === 'pending')
                        <p>Permintaan pengembalian Anda sedang menunggu diproses oleh Super Admin. Anda akan mendapat notifikasi setelah proses selesai.</p>
                    @else
                        <p>Pengembalian telah selesai diproses. Barang telah dikembalikan ke inventaris dan stok telah diperbarui.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 