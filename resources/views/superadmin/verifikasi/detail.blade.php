@extends('layouts.superadmin')

@section('title', 'Detail Pengajuan Pendaftaran - SIMBARA Super Admin')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('superadmin.verifikasi.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-700">Verifikasi Pendaftaran</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500">Detail Pengajuan</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Header with Back Button -->
<div class="flex items-center justify-between mb-8">
    <div class="flex items-center space-x-4">
        <a href="{{ route('superadmin.verifikasi.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan Pendaftaran</h1>
    </div>
    
    <!-- Action Buttons in Header -->
    @if($pengajuan->status_verifikasi == 'pending')
    <div class="flex items-center space-x-3">
        <form method="POST" action="{{ route('superadmin.verifikasi.approve', $pengajuan->id_pengajuan) }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Yakin ingin membuat akun untuk pengajuan ini?')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Create Account
            </button>
        </form>
        
        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="openRejectModal()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tolak
        </button>
    </div>
    @endif
</div>

<!-- Main Content -->
<div class="space-y-6">
    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $pengajuan->nama_penanggung_jawab }}</h2>
                <p class="text-sm text-gray-500">Pengajuan #{{ $pengajuan->id_pengajuan }} • {{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') : '-' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $pengajuan->status_verifikasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $pengajuan->status_verifikasi == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $pengajuan->status_verifikasi == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ $pengajuan->status_verifikasi == 'pending' ? 'Menunggu Verifikasi' : '' }}
                    {{ $pengajuan->status_verifikasi == 'approved' ? 'Disetujui' : '' }}
                    {{ $pengajuan->status_verifikasi == 'rejected' ? 'Ditolak' : '' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengajuan->jenis_peminjam == 'civitas_akademik' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                    {{ $pengajuan->jenis_peminjam == 'civitas_akademik' ? 'Civitas Akademik' : 'Non-Civitas Akademik' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
            </div>
            <div class="px-6 py-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap:</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->nama_penanggung_jawab }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email:</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. HP:</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->no_hp }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Identitas:</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->no_identitas }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Information -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tujuan Peminjaman</h3>
            </div>
            <div class="px-6 py-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kegiatan:</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->tujuan_peminjaman }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Schedule -->
    <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Jadwal Kegiatan</h3>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai:</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->tanggal_mulai_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai_kegiatan)->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Berakhir:</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->tanggal_berakhir_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_berakhir_kegiatan)->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durasi Kegiatan:</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($pengajuan->tanggal_mulai_kegiatan && $pengajuan->tanggal_berakhir_kegiatan)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai_kegiatan)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_berakhir_kegiatan)) + 1 }} hari
                        @else
                            -
                        @endif
                    </p>
                </div>
                        <div>
                    <label class="block text-sm font-medium text-gray-700">Masa Aktif Akun:</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($pengajuan->tanggal_berakhir_kegiatan)
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_berakhir_kegiatan)->addDays(7)->format('d M Y') }}
                        @else
                            -
                        @endif
                            </p>
                        </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Kegiatan:</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($pengajuan->tanggal_mulai_kegiatan && $pengajuan->tanggal_berakhir_kegiatan)
                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($pengajuan->tanggal_mulai_kegiatan);
                                $end = \Carbon\Carbon::parse($pengajuan->tanggal_berakhir_kegiatan);
                            @endphp
                            @if($now->between($start, $end))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Sedang Berlangsung
                                </span>
                            @elseif($now->lt($start))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Belum Dimulai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Sudah Berakhir
                                </span>
                            @endif
                        @else
                            -
                        @endif
                    </p>
                </div>
                                    </div>
                                </div>
                            </div>

    <!-- Document Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Dokumen Pendukung</h3>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Surat Keterangan:</label>
                    @if($pengajuan->surat_keterangan)
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            <div>
                                    <p class="text-sm font-medium text-gray-900">surat_keterangan_{{ $pengajuan->id_pengajuan }}.pdf</p>
                                    <p class="text-xs text-gray-500">Dokumen PDF</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('superadmin.verifikasi.download-surat', $pengajuan->id_pengajuan) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Download
                                            </a>
                                <button onclick="viewPDF({{ $pengajuan->id_pengajuan }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat
                                </button>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Tidak ada dokumen yang diupload</p>
                    @endif
                                    </div>
                                        </div>
                                </div>
                            </div>

    <!-- Timeline -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                                    </div>
        <div class="px-6 py-6">
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                        <p class="text-sm text-gray-500">Pengajuan Dikirim</p>
                                                        </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('Y-m-d') : '' }}">{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') : '-' }}</time>
                                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </div>

<!-- Reject Modal -->
@if($pengajuan->status_verifikasi == 'pending')
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pengajuan</h3>
            <form method="POST" action="{{ route('superadmin.verifikasi.reject', $pengajuan->id_pengajuan) }}">
                    @csrf
                    <div class="mb-4">
                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan:</label>
                    <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- PDF Viewer Modal -->
<div id="pdfModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Surat Keterangan</h3>
            <button onclick="closePDFModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                        </button>
                    </div>
        <div class="h-96 md:h-[600px]">
            <iframe id="pdfViewer" src="" class="w-full h-full border rounded-md" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <script>
function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function viewPDF(id) {
    const pdfUrl = `/superadmin/verifikasi/${id}/download-surat`;
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfModal').classList.remove('hidden');
}

function closePDFModal() {
    document.getElementById('pdfModal').classList.add('hidden');
    document.getElementById('pdfViewer').src = '';
        }

// Close modals when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

document.getElementById('pdfModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePDFModal();
            }
        });
    </script>
@endsection 