@extends('layouts.admin')

@section('title', 'Kalender Ketersediaan')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Kalender Ketersediaan</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">Dashboard</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">Kalender</span>
                    </li>
                </ol>
            </nav>
        </div>
        <button id="refreshBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Peminjaman -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-blue-600 uppercase">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPeminjaman }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-green-600 uppercase">Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $bulanIni }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Item Aktif -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-cyan-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-cyan-600 uppercase">Item Aktif</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $itemAktif }}</p>
                </div>
                <div class="bg-cyan-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-yellow-600 uppercase">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $sedangDipinjam }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h6 class="text-lg font-semibold text-gray-800">Kalender Peminjaman</h6>
            <button id="refreshBtn2" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
        <div class="p-6">
            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="itemFilter" class="block text-sm font-medium text-gray-700 mb-1">Filter Item</label>
                    <select id="itemFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Semua Item</option>
                        @foreach($adminBarangs as $barang)
                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="approved">Disetujui</option>
                        <option value="confirmed">Dikonfirmasi</option>
                    </select>
                </div>
                <div>
                    <label for="viewMode" class="block text-sm font-medium text-gray-700 mb-1">Tampilan</label>
                    <select id="viewMode" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="dayGridMonth">Bulan</option>
                        <option value="timeGridWeek">Minggu</option>
                        <option value="timeGridDay">Hari</option>
                        <option value="listMonth">List</option>
                    </select>
                </div>
            </div>

            <!-- Calendar Legend -->
            <div class="mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <span class="font-bold">Cara Membaca Kalender:</span> Setiap blok warna menunjukkan periode peminjaman barang milik lembaga Anda dari tanggal mulai hingga tanggal selesai peminjaman.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Disetujui (Belum Dimulai)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Dikonfirmasi (Belum Dimulai)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Sedang Berlangsung</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Sudah Dikembalikan</span>
                    </div>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="relative">
                <div id="calendar-loading" class="hidden absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Memuat kalender...</p>
                    </div>
                </div>
                <div id="calendar" class="min-h-[600px]"></div>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-semibold text-gray-800" id="eventDetailModalLabel">Detail Peminjaman</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div id="eventDetailContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3 border-t pt-3">
            <button id="closeModalBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Tutup</button>
            <a id="eventDetailLink" href="#" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Lihat Detail</a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    #calendar {
        background: white;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    
    .fc-event {
        cursor: pointer;
        border: none !important;
        padding: 2px 6px;
        margin: 1px 0;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .fc-event-title {
        font-weight: 600;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
    }
    
    .fc .fc-button {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
    }
    
    .fc .fc-button-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .fc .fc-button-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    
    .fc .fc-daygrid-day-number {
        padding: 6px 8px;
        font-weight: 500;
        color: #374151;
    }
    
    .fc .fc-daygrid-day.fc-day-today {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #e5e7eb;
    }
    
    /* Custom tooltip styles */
    .tooltip-custom {
        position: absolute;
        background: rgba(0,0,0,0.9);
        color: white;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 12px;
        z-index: 1000;
        width: 200px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    let calendar;
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing calendar...');
        
        // Check if FullCalendar is loaded
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar not loaded');
            showCalendarError('FullCalendar library failed to load. Please refresh the page.');
            return;
        }
        
        const calendarEl = document.getElementById('calendar');
        
        if (!calendarEl) {
            console.error('Calendar element not found');
            return;
        }
        
        try {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                height: 'auto',
                locale: 'id',
                displayEventTime: false,
                events: {
                    url: '{{ route("admin.calendar.events") }}',
                    method: 'GET',
                    extraParams: function() {
                        return {
                            item_filter: document.getElementById('itemFilter')?.value || '',
                            status_filter: document.getElementById('statusFilter')?.value || ''
                        };
                    },
                    failure: function(error) {
                        console.error('Failed to load calendar events:', error);
                        showCalendarError('Gagal memuat data kalender. Silakan refresh halaman.');
                    }
                },
                eventClick: function(info) {
                    showEventDetail(info.event);
                },
                eventMouseEnter: function(info) {
                    showEventTooltip(info);
                },
                eventMouseLeave: function(info) {
                    hideEventTooltip(info);
                },
                loading: function(isLoading) {
                    document.getElementById('calendar-loading').style.display = isLoading ? 'flex' : 'none';
                },
                dateClick: function(info) {
                    console.log('Date clicked:', info.dateStr);
                },
                eventDidMount: function(info) {
                    // Add custom styling for ongoing events
                    if (info.event.extendedProps.is_ongoing) {
                        info.el.style.fontWeight = 'bold';
                        info.el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                    }
                }
            });
            
            console.log('Calendar created, rendering...');
            calendar.render();
            console.log('Calendar rendered successfully');
            
        } catch (error) {
            console.error('Error initializing calendar:', error);
            showCalendarError('Error initializing calendar. Please refresh the page.');
            return;
        }
        
        // Event filters
        setupEventFilters();
        
        // Setup refresh buttons
        document.getElementById('refreshBtn').addEventListener('click', refreshCalendar);
        document.getElementById('refreshBtn2').addEventListener('click', refreshCalendar);
        
        // Setup modal close buttons
        document.getElementById('closeModal').addEventListener('click', closeEventModal);
        document.getElementById('closeModalBtn').addEventListener('click', closeEventModal);
    });
    
    function setupEventFilters() {
        const itemFilter = document.getElementById('itemFilter');
        const viewMode = document.getElementById('viewMode');
        const statusFilter = document.getElementById('statusFilter');
        
        if (itemFilter) {
            itemFilter.addEventListener('change', function() {
                if (calendar) calendar.refetchEvents();
            });
        }
        
        if (viewMode) {
            viewMode.addEventListener('change', function() {
                if (calendar) calendar.changeView(this.value);
            });
        }
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                if (calendar) calendar.refetchEvents();
            });
        }
    }
    
    function showCalendarError(message) {
        document.getElementById('calendar').innerHTML = `
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-12 h-12 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">Error Loading Calendar</h5>
                <p class="text-gray-600 mb-4">${message}</p>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" onclick="location.reload()">Refresh Page</button>
            </div>
        `;
    }
    
    function showEventTooltip(info) {
        const props = info.event.extendedProps;
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip-custom';
        
        const statusText = props.is_ongoing ? 'Sedang Berlangsung' : 
                          (props.status === 'approved' ? 'Disetujui' : 'Dikonfirmasi');
        
        tooltip.innerHTML = `
            <div style="text-align: left;">
                <strong>${info.event.title}</strong><br>
                <small>Peminjam: ${props.nama_pengambil}</small><br>
                <small>Periode: ${props.tanggal_mulai} - ${props.tanggal_selesai}</small><br>
                <small>Status: ${statusText}</small>
            </div>
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = info.el.getBoundingClientRect();
        tooltip.style.top = `${rect.top - 80}px`;
        tooltip.style.left = `${rect.left + rect.width/2 - 100}px`;
        
        info.el.tooltip = tooltip;
    }
    
    function hideEventTooltip(info) {
        if (info.el.tooltip) {
            info.el.tooltip.remove();
        }
    }
    
    function showEventDetail(event) {
        const props = event.extendedProps;
        const statusText = props.is_ongoing ? 'Sedang Berlangsung' : 
                          (props.status === 'approved' ? 'Disetujui' : 'Dikonfirmasi');
        
        const statusClass = props.is_ongoing ? 'bg-yellow-500' : 
                          (props.status === 'approved' ? 'bg-green-500' : 'bg-blue-500');
        
        const content = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h6 class="text-sm font-semibold text-gray-500 mb-2">Informasi Peminjaman</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode:</span>
                            <span class="font-medium">${props.kode_peminjaman}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Peminjam:</span>
                            <span class="font-medium">${props.nama_pengambil}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">No. Identitas:</span>
                            <span class="font-medium">${props.no_identitas_pengambil}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <h6 class="text-sm font-semibold text-gray-500 mb-2">Periode Peminjaman</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mulai:</span>
                            <span class="font-medium">${props.tanggal_mulai}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Selesai:</span>
                            <span class="font-medium">${props.tanggal_selesai}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-medium">${props.durasi_hari} hari</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h6 class="text-sm font-semibold text-gray-500 mb-2">Barang yang Dipinjam</h6>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h6 class="font-semibold text-gray-800">${event.title}</h6>
                    <p class="text-sm text-gray-500">Milik: ${props.nama_admin}</p>
                </div>
            </div>
        `;
        
        document.getElementById('eventDetailContent').innerHTML = content;
        document.getElementById('eventDetailLink').href = `{{ url('/admin/peminjaman') }}/${props.id_peminjaman}`;
        
        document.getElementById('eventDetailModal').classList.remove('hidden');
    }
    
    function closeEventModal() {
        document.getElementById('eventDetailModal').classList.add('hidden');
    }
    
    function refreshCalendar() {
        if (calendar) {
            calendar.refetchEvents();
        }
    }
</script>
@endpush