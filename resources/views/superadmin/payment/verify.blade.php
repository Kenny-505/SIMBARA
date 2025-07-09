@extends('layouts.superadmin')

@section('title', 'Verifikasi Pembayaran - SIMBARA Super Admin')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('superadmin.transaksi.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Transaksi
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500">Verifikasi Pembayaran</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pembayaran</h1>
        <p class="text-gray-600">Verifikasi manual bukti pembayaran dari user non-civitas</p>
    </div>
    
    <div class="flex space-x-2">
        <a href="{{ route('superadmin.transaksi.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Data
        </a>
    </div>
</div>

<!-- Verification Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-yellow-600" id="pending-count">0</p>
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
                <p class="text-sm font-medium text-gray-600">Terverifikasi Hari Ini</p>
                <p class="text-2xl font-bold text-green-600" id="verified-today">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Ditolak</p>
                <p class="text-2xl font-bold text-red-600" id="rejected-count">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-purple-600" id="total-revenue">Rp 0</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button onclick="verifyAllEligible()" 
                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Verifikasi Semua Yang Valid
        </button>
        
        <button onclick="refreshStats()" 
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh Data
        </button>
        
        <a href="{{ route('superadmin.transaksi.index', ['status' => 'pending']) }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Lihat Semua Pending
        </a>
    </div>
</div>

<!-- Payment Verification Queue -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Antrian Verifikasi Pembayaran</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <span id="queue-count">0</span> pending
            </span>
        </div>
    </div>
    
    <div id="verification-queue">
        <!-- Dynamic content will be loaded here -->
        <div class="px-6 py-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Memuat data...</h3>
            <p class="mt-1 text-sm text-gray-500">Sedang mengambil antrian verifikasi pembayaran</p>
        </div>
    </div>
</div>

<!-- Payment Verification Modal -->
<div id="verification-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Verifikasi Pembayaran
                        </h3>
                        <div id="modal-content">
                            <!-- Dynamic content -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="verifyPayment()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Verifikasi
                </button>
                <button type="button" onclick="rejectPayment()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tolak
                </button>
                <button type="button" onclick="closeModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentTransaksiId = null;

// Load verification queue on page load
document.addEventListener('DOMContentLoaded', function() {
    loadVerificationQueue();
    loadStats();
    
    // Auto refresh every 30 seconds
    setInterval(function() {
        loadVerificationQueue();
        loadStats();
    }, 30000);
});

// Load pending verifications
function loadVerificationQueue() {
    fetch('{{ route("superadmin.transaksi.index") }}?status=pending&format=json')
        .then(response => response.json())
        .then(data => {
            renderVerificationQueue(data.transaksi);
            document.getElementById('queue-count').textContent = data.transaksi.length;
        })
        .catch(error => {
            console.error('Error loading verification queue:', error);
            document.getElementById('verification-queue').innerHTML = `
                <div class="px-6 py-8 text-center">
                    <div class="text-red-600">Error loading data</div>
                </div>
            `;
        });
}

// Load statistics
function loadStats() {
    fetch('{{ route("superadmin.transaksi.stats") }}')
        .then(response => response.json())
        .then(stats => {
            document.getElementById('pending-count').textContent = stats.pending;
            document.getElementById('verified-today').textContent = stats.verified_today;
            document.getElementById('rejected-count').textContent = stats.rejected;
            document.getElementById('total-revenue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(stats.total_revenue);
        })
        .catch(error => console.error('Error loading stats:', error));
}

// Render verification queue
function renderVerificationQueue(transaksi) {
    const container = document.getElementById('verification-queue');
    
    if (transaksi.length === 0) {
        container.innerHTML = `
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pembayaran pending</h3>
                <p class="mt-1 text-sm text-gray-500">Semua pembayaran telah diverifikasi</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="divide-y divide-gray-200">';
    
    transaksi.forEach(t => {
        html += `
            <div class="px-6 py-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${t.peminjaman.kode_peminjaman}</div>
                                <div class="text-sm text-gray-500">${t.peminjaman.user.nama_penanggung_jawab}</div>
                                <div class="text-sm text-gray-500">${t.peminjaman.tujuan_peminjaman}</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(t.nominal)}</div>
                        <div class="text-sm text-gray-500">${new Date(t.created_at).toLocaleDateString('id-ID')}</div>
                    </div>
                    <div class="ml-6">
                        <button onclick="openVerificationModal(${t.id_transaksi})" 
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none transition">
                            Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

// Open verification modal
function openVerificationModal(transaksiId) {
    currentTransaksiId = transaksiId;
    
    fetch(`{{ route("superadmin.transaksi.show", "") }}/${transaksiId}`)
        .then(response => response.json())
        .then(transaksi => {
            renderVerificationModal(transaksi);
            document.getElementById('verification-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading transaction details:', error);
            alert('Error loading transaction details');
        });
}

// Render verification modal content
function renderVerificationModal(transaksi) {
    const content = document.getElementById('modal-content');
    
    content.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Transaction Details -->
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Transaksi</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Kode Peminjaman</dt>
                        <dd class="text-sm font-medium text-gray-900">${transaksi.peminjaman.kode_peminjaman}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">User</dt>
                        <dd class="text-sm font-medium text-gray-900">${transaksi.peminjaman.user.nama_penanggung_jawab}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Kegiatan</dt>
                        <dd class="text-sm font-medium text-gray-900">${transaksi.peminjaman.tujuan_peminjaman}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Nominal</dt>
                        <dd class="text-sm font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(transaksi.nominal)}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tanggal Upload</dt>
                        <dd class="text-sm font-medium text-gray-900">${new Date(transaksi.created_at).toLocaleDateString('id-ID')}</dd>
                    </div>
                </dl>
            </div>
            
            <!-- Payment Proof -->
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Bukti Pembayaran</h4>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ route('superadmin.transaksi.payment-proof', '') }}/${transaksi.id_transaksi}" 
                         alt="Bukti Pembayaran" 
                         class="w-full h-auto max-h-96 object-contain bg-gray-50">
                </div>
                ${transaksi.catatan_pembayaran ? `
                    <div class="mt-3">
                        <h5 class="text-sm font-medium text-gray-900">Catatan</h5>
                        <p class="text-sm text-gray-700 mt-1">${transaksi.catatan_pembayaran}</p>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

// Close modal
function closeModal() {
    document.getElementById('verification-modal').classList.add('hidden');
    currentTransaksiId = null;
}

// Verify payment
function verifyPayment() {
    if (!currentTransaksiId) return;
    
    if (!confirm('Yakin ingin memverifikasi pembayaran ini?')) return;
    
    fetch(`{{ route("superadmin.transaksi.verify", "") }}/${currentTransaksiId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadVerificationQueue();
            loadStats();
            showNotification('Pembayaran berhasil diverifikasi', 'success');
        } else {
            alert(data.message || 'Error verifying payment');
        }
    })
    .catch(error => {
        console.error('Error verifying payment:', error);
        alert('Error verifying payment');
    });
}

// Reject payment
function rejectPayment() {
    if (!currentTransaksiId) return;
    
    if (!confirm('Yakin ingin menolak pembayaran ini?')) return;
    
    fetch(`{{ route("superadmin.transaksi.reject", "") }}/${currentTransaksiId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadVerificationQueue();
            loadStats();
            showNotification('Pembayaran ditolak', 'error');
        } else {
            alert(data.message || 'Error rejecting payment');
        }
    })
    .catch(error => {
        console.error('Error rejecting payment:', error);
        alert('Error rejecting payment');
    });
}

// Verify all eligible payments
function verifyAllEligible() {
    if (!confirm('Yakin ingin memverifikasi semua pembayaran yang valid?')) return;
    
    // Implementation for bulk verification
    alert('Fitur ini akan diimplementasikan sesuai kebutuhan bisnis');
}

// Refresh stats
function refreshStats() {
    loadVerificationQueue();
    loadStats();
    showNotification('Data berhasil direfresh', 'info');
}

// Show notification
function showNotification(message, type = 'info') {
    // Simple notification implementation
    const colors = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush 