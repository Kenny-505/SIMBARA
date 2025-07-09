@extends('layouts.user')

@section('title', 'Profile - SIMBARA')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-6">
            <!-- Avatar -->
            <div class="w-24 h-24 {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'bg-blue-500' : 'bg-green-500' }} rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-2xl">{{ substr(auth()->guard('user')->user()->nama_penanggung_jawab ?? 'U', 0, 1) }}</span>
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ auth()->guard('user')->user()->nama_penanggung_jawab }}</h1>
                <p class="text-lg text-gray-600">
                    @if(auth()->guard('user')->user()->role->nama_role === 'user_fmipa')
                        Civitas Akademik FMIPA
                    @else
                        Non-Civitas
                    @endif
                </p>
                <div class="flex items-center mt-2">
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ auth()->guard('user')->user()->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ auth()->guard('user')->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Personal</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penanggung Jawab</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->nama_penanggung_jawab }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->no_hp }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Identitas</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->no_identitas }}</p>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->username }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe User</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if(auth()->guard('user')->user()->role->nama_role === 'user_fmipa')
                            Civitas Akademik FMIPA
                        @else
                            Non-Civitas
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if(auth()->guard('user')->user()->tanggal_berakhir)
                            {{ \Carbon\Carbon::parse(auth()->guard('user')->user()->tanggal_berakhir)->format('d F Y') }}
                        @else
                            Tidak terbatas
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Akun</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if(auth()->guard('user')->user()->is_active)
                            <span class="text-green-600">Aktif</span>
                        @else
                            <span class="text-red-600">Nonaktif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kegiatan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tujuan Peminjaman</label>
                <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->tujuan_peminjaman }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                <p class="mt-1 text-sm text-gray-900">{{ auth()->guard('user')->user()->created_at->format('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Aktivitas</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold {{ auth()->guard('user')->user()->role->nama_role === 'user_fmipa' ? 'text-blue-600' : 'text-green-600' }}">
                    {{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->count() }}
                </div>
                <div class="text-sm text-gray-600">Total Pengajuan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->where('status', 'completed')->count() }}
                </div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->where('status', 'ongoing')->count() }}
                </div>
                <div class="text-sm text-gray-600">Sedang Berjalan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">
                    {{ \App\Models\Peminjaman::where('id_user', auth()->guard('user')->id())->where('status', 'cancelled')->count() }}
                </div>
                <div class="text-sm text-gray-600">Dibatalkan</div>
            </div>
        </div>
    </div>
</div>
@endsection 