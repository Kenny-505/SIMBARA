@extends('layouts.superadmin')

@section('title', 'Audit Stok Inventaris')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Audit Stok Inventaris</h1>
                <p class="text-gray-600 mt-2">Periksa dan perbaiki inkonsistensi stok barang</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="runAudit()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Jalankan Audit
                </button>
                <a href="{{ route('superadmin.inventaris.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="status-cards">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="total-items">-</h3>
                    <p class="text-sm text-gray-600">Total Barang</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="consistent-items">-</h3>
                    <p class="text-sm text-gray-600">Stok Konsisten</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 14c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="issue-items">-</h3>
                    <p class="text-sm text-gray-600">Ada Masalah</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="audit-status">Belum Dijalankan</h3>
                    <p class="text-sm text-gray-600">Status Audit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Menjalankan audit stok...</p>
    </div>

    <!-- Issues Table -->
    <div id="issues-section" class="hidden">
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Masalah Stok Ditemukan</h2>
                <div class="flex space-x-2">
                    <button onclick="fixAllRecalculate()" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Perbaiki Semua (Hitung Ulang)
                    </button>
                    <button onclick="selectAllIssues()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Pilih Semua
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Seharusnya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masalah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="issues-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- All Items Table -->
    <div id="all-items-section" class="hidden">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Semua Barang</h2>
                <p class="text-sm text-gray-600 mt-1">Detail audit untuk semua barang dalam sistem</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dipinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dikembalikan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rusak/Hilang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="all-items-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- No Issues State -->
    <div id="no-issues-state" class="hidden bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Masalah Stok</h3>
        <p class="text-gray-600">Semua stok barang konsisten dengan transaksi yang tercatat.</p>
    </div>
</div>

<!-- Modal untuk Detail Analisis -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Detail Analisis Stok</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="modal-content">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let auditResults = [];
let selectedItems = [];

function runAudit() {
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('issues-section').classList.add('hidden');
    document.getElementById('all-items-section').classList.add('hidden');
    document.getElementById('no-issues-state').classList.add('hidden');
    document.getElementById('audit-status').textContent = 'Sedang Berjalan...';

    fetch('{{ route("superadmin.inventaris.audit") }}?ajax=1')
        .then(response => response.json())
        .then(data => {
            auditResults = data.results;
            displayAuditResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menjalankan audit');
        })
        .finally(() => {
            document.getElementById('loading-state').classList.add('hidden');
        });
}

function displayAuditResults(data) {
    // Update status cards
    document.getElementById('total-items').textContent = data.total_items;
    document.getElementById('consistent-items').textContent = data.total_items - data.issues_found;
    document.getElementById('issue-items').textContent = data.issues_found;
    document.getElementById('audit-status').textContent = 'Selesai';

    if (data.issues_found > 0) {
        // Show issues table
        document.getElementById('issues-section').classList.remove('hidden');
        displayIssuesTable(data.issues);
    } else {
        // Show no issues state
        document.getElementById('no-issues-state').classList.remove('hidden');
    }

    // Show all items table
    document.getElementById('all-items-section').classList.remove('hidden');
    displayAllItemsTable(data.results);
}

function displayIssuesTable(issues) {
    const tbody = document.getElementById('issues-table-body');
    tbody.innerHTML = '';

    issues.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="item-checkbox rounded border-gray-300" 
                       value="${item.id_barang}" onchange="updateSelectedItems()">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${item.nama_barang}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    Total: ${item.current_stock_total}<br>
                    Tersedia: ${item.current_stock_available}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    Total: ${item.calculated_stock_total}<br>
                    Tersedia: ${item.calculated_stock_available}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-red-600">
                    ${item.issues.map(issue => `<div>• ${issue}</div>`).join('')}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="showItemDetail(${item.id_barang})" 
                        class="text-blue-600 hover:text-blue-900 mr-3">Detail</button>
                <button onclick="fixSingleItem(${item.id_barang}, 'recalculate')" 
                        class="text-green-600 hover:text-green-900 mr-3">Hitung Ulang</button>
                <button onclick="showManualFixModal(${item.id_barang})" 
                        class="text-yellow-600 hover:text-yellow-900">Manual</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function displayAllItemsTable(results) {
    const tbody = document.getElementById('all-items-table-body');
    tbody.innerHTML = '';

    results.forEach(item => {
        const row = document.createElement('tr');
        const statusClass = item.is_consistent ? 'text-green-600' : 'text-red-600';
        const statusText = item.is_consistent ? 'Konsisten' : 'Masalah';

        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${item.nama_barang}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.current_stock_total}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.current_stock_available}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.stock_borrowed}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.stock_returned}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.stock_damaged_lost}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-medium ${statusClass}">${statusText}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="showItemDetail(${item.id_barang})" 
                        class="text-blue-600 hover:text-blue-900">Detail</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function showItemDetail(itemId) {
    fetch(`{{ url('superadmin/inventaris') }}/${itemId}/analysis`)
        .then(response => response.json())
        .then(data => {
            displayItemDetailModal(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil detail');
        });
}

function displayItemDetailModal(item) {
    document.getElementById('modal-title').textContent = `Detail Analisis: ${item.nama_barang}`;
    
    const content = `
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Stok Saat Ini</h4>
                <p>Total: ${item.current_stock_total}</p>
                <p>Tersedia: ${item.current_stock_available}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Stok Seharusnya</h4>
                <p>Total: ${item.calculated_stock_total}</p>
                <p>Tersedia: ${item.calculated_stock_available}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Ringkasan Transaksi</h4>
                <p>Dipinjam: ${item.stock_borrowed}</p>
                <p>Dikembalikan: ${item.stock_returned}</p>
                <p>Rusak/Hilang: ${item.stock_damaged_lost}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Status</h4>
                <p class="${item.is_consistent ? 'text-green-600' : 'text-red-600'} font-medium">
                    ${item.is_consistent ? 'Konsisten' : 'Ada Masalah'}
                </p>
                ${!item.is_consistent ? `
                <div class="mt-2">
                    <h5 class="font-medium text-red-600">Masalah:</h5>
                    ${item.issues.map(issue => `<p class="text-sm text-red-600">• ${issue}</p>`).join('')}
                </div>
                ` : ''}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Riwayat Peminjaman</h4>
                <div class="max-h-32 overflow-y-auto">
                    ${item.peminjaman_data.length > 0 ? 
                        item.peminjaman_data.map(p => `
                            <div class="text-sm text-gray-600">
                                ${p.kode_peminjaman}: ${p.jumlah} item (${p.tanggal})
                            </div>
                        `).join('') :
                        '<p class="text-sm text-gray-500">Tidak ada data</p>'
                    }
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Riwayat Pengembalian</h4>
                <div class="max-h-32 overflow-y-auto">
                    ${item.pengembalian_data.length > 0 ? 
                        item.pengembalian_data.map(p => `
                            <div class="text-sm text-gray-600">
                                ${p.kode_peminjaman}: ${p.jumlah} item (${p.kondisi}, ${p.tanggal})
                            </div>
                        `).join('') :
                        '<p class="text-sm text-gray-500">Tidak ada data</p>'
                    }
                </div>
            </div>
        </div>

        ${!item.is_consistent ? `
        <div class="mt-6 flex space-x-3">
            <button onclick="fixSingleItem(${item.id_barang}, 'recalculate'); closeDetailModal();" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Hitung Ulang Stok
            </button>
            <button onclick="showManualFixModal(${item.id_barang}); closeDetailModal();" 
                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                Perbaiki Manual
            </button>
        </div>
        ` : ''}
    `;
    
    document.getElementById('modal-content').innerHTML = content;
    document.getElementById('detail-modal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

function fixSingleItem(itemId, action) {
    if (!confirm('Yakin ingin memperbaiki stok barang ini?')) return;

    const data = {
        items: [{
            id_barang: itemId,
            action: action
        }]
    };

    fetch('{{ route("superadmin.inventaris.fix") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stok berhasil diperbaiki');
            runAudit(); // Refresh audit results
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbaiki stok');
    });
}

function fixAllRecalculate() {
    if (!confirm('Yakin ingin memperbaiki semua stok dengan perhitungan ulang?')) return;

    const issues = auditResults.filter(item => !item.is_consistent);
    const data = {
        items: issues.map(item => ({
            id_barang: item.id_barang,
            action: 'recalculate'
        }))
    };

    fetch('{{ route("superadmin.inventaris.fix") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${data.fixed_items.length} stok berhasil diperbaiki`);
            runAudit(); // Refresh audit results
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbaiki stok');
    });
}

function selectAllIssues() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll = document.getElementById('select-all');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedItems();
}

function updateSelectedItems() {
    selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'))
        .map(checkbox => parseInt(checkbox.value));
}

// Auto-run audit on page load
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($issues) && count($issues) > 0)
        // If server-side data is available, use it
        const serverData = {
            total_items: {{ count($results) }},
            issues_found: {{ count($issues) }},
            results: @json($results),
            issues: @json($issues)
        };
        auditResults = serverData.results;
        displayAuditResults(serverData);
    @endif
});
</script>
@endsection 