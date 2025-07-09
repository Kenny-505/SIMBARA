@extends('layouts.user')

@section('title', 'Status Pembayaran - SIMBARA')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500">Status Pembayaran</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Status Pembayaran</h1>
        <p class="text-gray-600">Pantau status pembayaran dan upload bukti transfer</p>
    </div>
</div>

<!-- Payment Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pembayaran</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_payments'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Pembayaran</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_payments'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['waiting_verification'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['verified_payments'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('user.payment.status') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Status Filter -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="waiting_payment" {{ request('status') === 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('user.payment.status') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Payment List -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Pembayaran</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Peminjaman
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Biaya
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status Pembayaran
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peminjaman as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $p->kode_peminjaman }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($p->tujuan_peminjaman, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</div>
                            @if($p->total_biaya > 0)
                                <div class="text-sm text-gray-500">Non-Civitas</div>
                            @else
                                <div class="text-sm text-green-600">Gratis (Civitas)</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->status_pembayaran === 'pending' && $p->total_biaya > 0)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Dibayar
                                </span>
                            @elseif($p->status_pembayaran === 'waiting_verification')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu Verifikasi
                                </span>
                            @elseif($p->status_pembayaran === 'verified')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Terverifikasi
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Tidak Perlu Bayar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $p->created_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $p->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($p->status_pembayaran === 'pending' && $p->total_biaya > 0)
                                <a href="{{ route('user.peminjaman.payment', $p->id_peminjaman) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Upload Bukti
                                </a>
                            @elseif($p->status_pembayaran === 'waiting_verification')
                                <a href="{{ route('user.payment.summary', $p->id_peminjaman) }}" 
                                   class="text-orange-600 hover:text-orange-900">
                                    Lihat Detail
                                </a>
                            @else
                                <a href="{{ route('user.payment.summary', $p->id_peminjaman) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    Lihat Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pembayaran yang ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($peminjaman->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $peminjaman->links() }}
        </div>
    @endif
</div>

@if($stats['pending_payments'] > 0)
<!-- Alert for Pending Payments -->
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h4 class="text-sm font-medium text-yellow-800">Perhatian!</h4>
            <div class="mt-1 text-sm text-yellow-700">
                <p>Anda memiliki {{ $stats['pending_payments'] }} pembayaran yang belum diselesaikan. Silakan upload bukti pembayaran untuk melanjutkan proses peminjaman.</p>
            </div>
        </div>
    </div>
</div>
@endif

@endsection 