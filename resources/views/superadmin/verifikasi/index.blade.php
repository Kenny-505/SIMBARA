@extends('layouts.superadmin')

@section('title', 'Verifikasi Pendaftaran - SIMBARA Super Admin')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Verifikasi Pendaftaran</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Title -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pendaftaran</h1>
</div>

<!-- Success Message with Credentials -->
@if(session('success') && session('credentials'))
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">{{ session('success') }}</h3>
            <div class="mt-2 text-sm text-green-700">
                <p class="font-medium">Kredensial Login yang Dibuat:</p>
                <div class="mt-2 bg-white border border-green-200 rounded p-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="font-medium">Username:</span>
                            <span class="ml-2 font-mono bg-gray-100 px-2 py-1 rounded">{{ session('credentials.username') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Password:</span>
                            <span class="ml-2 font-mono bg-gray-100 px-2 py-1 rounded">{{ session('credentials.password') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Email:</span>
                            <span class="ml-2 text-sm">{{ session('credentials.email') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Kredensial telah dikirim via email. Jika email tidak terkirim, Anda dapat memberikan informasi di atas kepada user.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Success/Error Messages -->
@if(session('success') && !session('credentials'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<!-- Main Content -->
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pengajuan Pendaftaran</h2>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('superadmin.verifikasi.index') }}" class="flex items-center space-x-2">
                    <select name="status" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="select-all" class="text-sm text-gray-700">Pilih Semua</label>
            </div>
            <button id="bulk-approve" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 disabled:opacity-50" disabled>
                Setujui Terpilih
                <span id="selected-count" class="ml-1 text-xs">(0 item dipilih)</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. WA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengajuan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="selected_items[]" value="{{ $item->id_pengajuan }}" class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_penanggung_jawab }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->no_hp }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->tujuan_peminjaman }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jenis_peminjam == 'civitas_akademik' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $item->jenis_peminjam == 'civitas_akademik' ? 'Civitas' : 'Non-Civitas' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') : '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $item->status_verifikasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $item->status_verifikasi == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->status_verifikasi == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ $item->status_verifikasi == 'pending' ? 'Menunggu Verifikasi' : '' }}
                                {{ $item->status_verifikasi == 'approved' ? 'Disetujui' : '' }}
                                {{ $item->status_verifikasi == 'rejected' ? 'Ditolak' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('superadmin.verifikasi.show', $item->id_pengajuan) }}" class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                                @if($item->status_verifikasi == 'pending')
                                    <form method="POST" action="{{ route('superadmin.verifikasi.approve', $item->id_pengajuan) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Yakin ingin membuat akun untuk pengajuan ini?')">
                                            Create Account
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada pengajuan pendaftaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($pengajuan->hasPages())
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $pengajuan->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkApproveBtn = document.getElementById('bulk-approve');
    const selectedCountSpan = document.getElementById('selected-count');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select all checkbox
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === itemCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < itemCheckboxes.length;
        });
    });

    // Bulk approve functionality
    bulkApproveBtn.addEventListener('click', function() {
        const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedItems.length === 0) {
            alert('Pilih minimal satu item untuk disetujui');
            return;
        }

        if (confirm(`Yakin ingin menyetujui ${selectedItems.length} pengajuan?`)) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("superadmin.verifikasi.bulk-approve") }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add selected items
            selectedItems.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = itemId;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    });

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        bulkApproveBtn.disabled = checkedCount === 0;
        selectedCountSpan.textContent = `(${checkedCount} item dipilih)`;
    }
});
</script>
@endpush
@endsection 