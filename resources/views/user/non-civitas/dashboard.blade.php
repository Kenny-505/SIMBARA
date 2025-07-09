@extends('layouts.user')

@section('title', 'Dashboard - User Non-Civitas')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Selamat datang di Sistem Informasi Peminjaman Barang FMIPA</p>
</div>

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg text-white p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold">Selamat Datang, {{ auth()->guard('user')->user()->nama_penanggung_jawab }}!</h2>
            <p class="text-blue-100 mt-1">User Non-Civitas</p>
            <p class="text-sm text-blue-200 mt-2">
                Akun berakhir: {{ auth()->guard('user')->user()->tanggal_berakhir ? \Carbon\Carbon::parse(auth()->guard('user')->user()->tanggal_berakhir)->format('d M Y') : '-' }}
            </p>
        </div>
        <div class="hidden md:block">
            <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 616 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Peminjaman Aktif</p>
                <p class="text-2xl font-semibold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Riwayat Peminjaman</p>
                <p class="text-2xl font-semibold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pending Approval</p>
                <p class="text-2xl font-semibold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Biaya</p>
                <p class="text-2xl font-semibold text-gray-900">Rp 0</p>
            </div>
        </div>
    </div>
</div>

<!-- Payment Information -->
<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h3>
    </div>
    <div class="p-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-yellow-800">Rekening Pembayaran</h4>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p class="font-medium">Bank BCA</p>
                        <p>No. Rekening: <span class="font-mono">1234567890</span></p>
                        <p>Atas Nama: <span class="font-medium">FMIPA UNIVERSITAS</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('user.gallery') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-200">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Lihat Katalog</p>
                    <p class="text-xs text-gray-500">Browse barang & harga</p>
                </div>
            </a>

            <a href="{{ route('user.pengajuan.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-200">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Ajukan Peminjaman</p>
                    <p class="text-xs text-gray-500">Buat pengajuan baru</p>
                </div>
            </a>

            <a href="{{ route('user.peminjaman.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-200">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Status Peminjaman</p>
                    <p class="text-xs text-gray-500">Lihat status pengajuan</p>
                </div>
            </a>

            <a href="{{ route('user.pengembalian.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-200">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Pengembalian</p>
                    <p class="text-xs text-gray-500">Proses pengembalian</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
    </div>
    <div class="p-6">
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h4 class="mt-2 text-sm font-medium text-gray-900">Belum ada aktivitas</h4>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pengajuan peminjaman pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('user.pengajuan.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Pengajuan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Information Panel -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h4 class="text-sm font-medium text-blue-800">Informasi Penting untuk Non-Civitas</h4>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Sebagai non-civitas, Anda <strong>dikenakan biaya sewa</strong> sesuai tarif yang berlaku</li>
                                            <li>Pembayaran dilakukan setelah Anda konfirmasi pengajuan yang sudah disetujui admin</li>
                    <li>Upload bukti pembayaran untuk verifikasi super admin</li>
                    <li>Pengajuan peminjaman harus dilakukan minimal <strong>H-3</strong> sebelum tanggal peminjaman</li>
                    <li>Anda hanya dapat memiliki <strong>1 peminjaman aktif</strong> dalam satu waktu</li>
                    <li>Denda keterlambatan sebesar <strong>Rp 250.000</strong> per peminjaman (maksimal telat 1 hari)</li>
                    <li>Akun Anda akan berakhir pada tanggal yang telah ditentukan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 