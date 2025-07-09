@extends('layouts.user')

@section('title', 'Dashboard - SIMBARA')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'from-blue-600 to-purple-600' : 'from-green-600 to-teal-600' }} rounded-lg p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">
                Selamat Datang, {{ auth()->guard('user')->user()->nama_penanggung_jawab }}!
            </h1>
            <p class="text-lg opacity-90">
                @if(auth()->guard('user')->user()->role->nama_role === 'user_fmipa')
                    Anda login sebagai <strong>Civitas Akademik FMIPA</strong>
                @else
                    Anda login sebagai <strong>Non-Civitas</strong>
                @endif
            </p>
            <p class="text-sm opacity-75 mt-2">
                Akun berakhir: {{ auth()->guard('user')->user()->tanggal_berakhir ? \Carbon\Carbon::parse(auth()->guard('user')->user()->tanggal_berakhir)->format('d F Y') : 'Tidak terbatas' }}
            </p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Barang -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Barang</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Barang::where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Barang Tersedia -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Barang Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Barang::where('is_active', true)->where('stok_tersedia', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pengajuan Aktif -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengajuan Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->whereNotIn('status_peminjaman', ['completed', 'cancelled'])->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Peminjaman Berjalan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peminjaman Berjalan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->where('status_peminjaman', 'ongoing')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Browse Catalog -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Jelajahi Katalog</h3>
            <p class="text-gray-600 mb-4">Temukan barang yang Anda butuhkan untuk kegiatan Anda</p>
            <a href="{{ route('user.gallery') }}" class="inline-flex items-center px-4 py-2 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Lihat Katalog
            </a>
        </div>

        <!-- Create Pengajuan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Pengajuan</h3>
            <p class="text-gray-600 mb-4">Ajukan peminjaman barang untuk kegiatan Anda</p>
            <a href="{{ route('user.pengajuan.form') }}" class="inline-flex items-center px-4 py-2 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Buat Pengajuan
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            @php
                $recentPeminjaman = \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if($recentPeminjaman->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPeminjaman as $peminjaman)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }} rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $peminjaman->kode_peminjaman }}</p>
                                    <p class="text-xs text-gray-500">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'ongoing' => 'bg-purple-100 text-purple-800',
                                        'returned' => 'bg-orange-100 text-orange-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$peminjaman->status_peminjaman] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status_peminjaman)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('user.peminjaman.index') }}" class="text-sm {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'text-blue-600 hover:text-blue-800' : 'text-green-600 hover:text-green-800' }} font-medium">
                        Lihat Semua Peminjaman →
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500">Belum ada aktivitas peminjaman</p>
                    <p class="text-sm text-gray-400 mt-1">Mulai dengan membuat pengajuan peminjaman pertama Anda</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 