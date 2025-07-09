@extends('layouts.superadmin')

@section('title', 'Kalender Global - SIMBARA Super Admin')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
@endpush

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
                <span class="ml-1 text-sm font-medium text-gray-500">Kalender Global</span>
                        </div>
        </li>
    </ol>
</nav>

<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
                    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kalender Global</h1>
        <p class="text-gray-600">Monitor jadwal peminjaman yang sudah dikonfirmasi dari seluruh lembaga</p>
        <div class="mt-2 flex items-center text-sm text-blue-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Hanya menampilkan peminjaman dengan status "Confirmed"
                </div>
            </div>

    <div class="flex space-x-2">
        <a href="{{ route('superadmin.kalender.export', request()->query()) }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
            Export CSV
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"/>
                    </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Events</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['this_month'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Konflik</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['conflicts'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Utilization</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['utilization'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Calendar Section -->
<div class="bg-white rounded-lg shadow">
    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <h6 class="text-lg font-semibold text-gray-800">Kalender Peminjaman Global</h6>
        <button id="refreshBtn2" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>
    <div class="p-6">
        <!-- Filter Controls -->
        <form method="GET" action="{{ route('superadmin.kalender.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <input type="hidden" name="month" value="{{ request('month') }}">
            
            <div>
                <label for="adminFilter" class="block text-sm font-medium text-gray-700 mb-1">Filter Lembaga</label>
                <select name="admin_id" id="adminFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" onchange="this.form.submit()">
                    <option value="">Semua Lembaga</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id_admin }}" {{ request('admin_id') == $admin->id_admin ? 'selected' : '' }}>
                            {{ $admin->nama_lembaga }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : (request('status') == '' ? 'selected' : '') }}>Dikonfirmasi</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
                    <div>
                <label for="viewMode" class="block text-sm font-medium text-gray-700 mb-1">Tampilan</label>
                <select id="viewMode" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" disabled>
                    <option value="month" selected>Tampilan Bulan</option>
                </select>
            </div>
        </form>

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
                            <span class="font-bold">Cara Membaca Kalender:</span> Setiap blok warna menunjukkan periode peminjaman barang yang sudah dikonfirmasi dari seluruh lembaga di FMIPA dari tanggal mulai hingga tanggal selesai peminjaman.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Dikonfirmasi (Belum Dimulai)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
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
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <!-- Calendar Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <form method="GET" action="{{ route('superadmin.kalender.index') }}" class="flex items-center space-x-2">
                            <input type="hidden" name="admin_id" value="{{ request('admin_id') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            
                            @php
                                $currentMonth = request('month') ? \Carbon\Carbon::parse(request('month').'-01') : now();
                                $prevMonth = $currentMonth->copy()->subMonth();
                                $nextMonth = $currentMonth->copy()->addMonth();
                            @endphp
                            
                            <button type="submit" name="month" value="{{ $prevMonth->format('Y-m') }}" 
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                ← Prev
                            </button>
                            
                            <span class="text-lg font-semibold text-gray-800 min-w-[200px] text-center">
                                {{ $currentMonth->locale('id')->isoFormat('MMMM YYYY') }}
                            </span>
                            
                            <button type="submit" name="month" value="{{ $nextMonth->format('Y-m') }}" 
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Next →
                        </button>
                        </form>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('superadmin.kalender.index') }}" 
                           class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Hari Ini
                        </a>
                        <button onclick="window.location.reload()" 
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            🔄 Refresh
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-container">
                    <!-- Weekday Headers -->
                    <div class="grid grid-cols-7 bg-gray-100">
                        @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <div class="p-3 text-center font-semibold text-gray-700 border-r border-gray-200 last:border-r-0">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7">
                        @php
                            $currentMonth = request('month') ? \Carbon\Carbon::parse(request('month').'-01') : now();
                            $startOfMonth = $currentMonth->copy()->startOfMonth();
                            $endOfMonth = $currentMonth->copy()->endOfMonth();
                            $startDate = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endDate = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            
                            // Get events for this month with filters
                            $query = \App\Models\Peminjaman::with(['user', 'peminjamanBarangs.barang'])
                                ->where('status_pengajuan', 'confirmed')
                                ->where(function($q) use ($startDate, $endDate) {
                                    $q->whereBetween('tanggal_mulai', [$startDate, $endDate])
                                      ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                                      ->orWhere(function($q2) use ($startDate, $endDate) {
                                          $q2->where('tanggal_mulai', '<=', $startDate)
                                             ->where('tanggal_selesai', '>=', $endDate);
                                      });
                                });
                            
                            // Apply admin filter
                            if (request('admin_id')) {
                                $query->whereHas('peminjamanBarangs.barang', function($q) {
                                    $q->where('id_admin', request('admin_id'));
                                });
                            }
                            
                            // Apply status filter (use the correct status field)
                            if (request('status')) {
                                $status = request('status');
                                if ($status === 'confirmed') {
                                    $query->where('status_pengajuan', 'confirmed');
                                } elseif ($status === 'ongoing') {
                                    $query->where('status_pengajuan', 'confirmed')
                                          ->whereDate('tanggal_mulai', '<=', now())
                                          ->whereDate('tanggal_selesai', '>=', now());
                                } elseif ($status === 'returned') {
                                    $query->whereHas('pengembalian');
                                }
                            }
                            
                            $monthlyEvents = $query->get();
                        @endphp
                        
                        @for($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                            @php
                                $isToday = $date->isToday();
                                $isCurrentMonth = $date->month === $currentMonth->month;
                                $dateStr = $date->format('Y-m-d');
                                
                                // Get events that span this date (not just start on this date)
                                $dayEvents = $monthlyEvents->filter(function($event) use ($date) {
                                    $startDate = \Carbon\Carbon::parse($event->tanggal_mulai)->startOfDay();
                                    $endDate = \Carbon\Carbon::parse($event->tanggal_selesai)->endOfDay();
                                    return $date->between($startDate, $endDate);
                                });
                            @endphp
                            
                            <div class="min-h-[100px] border-r border-b border-gray-200 last:border-r-0 p-2 
                                        {{ $isToday ? 'bg-blue-50' : '' }}
                                        {{ !$isCurrentMonth ? 'bg-gray-50 text-gray-400' : 'bg-white' }}">
                                
                                <!-- Day Number -->
                                <div class="text-sm font-medium mb-1 
                                           {{ $isToday ? 'text-blue-600 font-bold' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400') }}">
                                    {{ $date->day }}
                        </div>

                                                                <!-- Events for this day -->
                                @foreach($dayEvents as $event)
                                    @php
                                        $isOngoing = $date->between($event->tanggal_mulai, $event->tanggal_selesai);
                                        $isStart = $date->isSameDay($event->tanggal_mulai);
                                        $isEnd = $date->isSameDay($event->tanggal_selesai);
                                        
                                        // Determine color based on actual status
                                        $eventColor = 'bg-blue-500'; // Default: confirmed
                                        $today = now()->startOfDay();
                                        
                                        if ($event->pengembalian()->exists()) {
                                            $eventColor = 'bg-gray-500'; // Returned
                                        } elseif ($event->tanggal_mulai <= $today && $event->tanggal_selesai >= $today) {
                                            $eventColor = 'bg-green-500'; // Ongoing
                                        }
                                    @endphp
                                    
                                    <div class="text-xs {{ $eventColor }} text-white p-1 rounded mb-1 cursor-pointer hover:opacity-80"
                                         onclick="showEventDetail('{{ $event->id_peminjaman }}')"
                                         title="{{ $event->kode_peminjaman }} - {{ $event->user->nama_penanggung_jawab ?? 'User' }}">
                                        
                                        @if($isStart)
                                            {{ $event->kode_peminjaman }}
                                        @else
                                            ●●●
                                        @endif
                                        
                                        @if($dayEvents->count() > 1)
                                            <div class="text-xs text-white mt-1">
                                                +{{ $dayEvents->count() - 1 }} lainnya
                        </div>
                                        @endif
                        </div>
                                @endforeach
                        </div>
                        @endfor
                        </div>
                        </div>
                        </div>
                        </div>

<!-- Upcoming Events -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Event Mendatang</h3>
                        </div>
        <div class="p-6">
            @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                        <div class="flex items-center justify-between border-l-4 border-blue-500 pl-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $event->tujuan_peminjaman }}</p>
                                <p class="text-sm text-gray-500">{{ $event->user->nama_penanggung_jawab ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $event->tanggal_mulai->format('d/m/Y H:i') }}</p>
                        </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($event->status_pengajuan === 'pending_approval') bg-yellow-100 text-yellow-800
                                    @elseif($event->status_pengajuan === 'approved') bg-green-100 text-green-800
                                    @elseif($event->status_pengajuan === 'confirmed') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($event->status_pengajuan) }}
                                </span>
                        </div>
                        </div>
                    @endforeach
                        </div>
            @else
                <p class="text-gray-500 text-center py-4">Tidak ada event mendatang</p>
            @endif
                        </div>
                        </div>

    <!-- Conflict Detection -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Deteksi Konflik</h3>
        </div>
        <div class="p-6">
            @if($conflicts->count() > 0)
                <div class="space-y-4">
                    @foreach($conflicts as $conflict)
                        <div class="flex items-center justify-between border-l-4 border-red-500 pl-4">
                            <div>
                                <p class="text-sm font-medium text-red-900">{{ $conflict['date'] }}</p>
                                <p class="text-sm text-gray-500">{{ $conflict['events']->count() }} event bertabrakan</p>
                                <div class="text-xs text-gray-400">
                                    @foreach($conflict['events'] as $event)
                                        <div>{{ $event->tujuan_peminjaman }} ({{ $event->user->nama_lembaga ?? 'N/A' }})</div>
                                    @endforeach
                                </div>
                        </div>
                            <div class="text-right">
                                <button onclick="showConflictDetails('{{ $conflict['date'] }}')" 
                                        class="text-red-600 hover:text-red-900 text-sm">
                                    Detail
                                </button>
                        </div>
                        </div>
                    @endforeach
                        </div>
            @else
                <p class="text-gray-500 text-center py-4">Tidak ada konflik terdeteksi</p>
            @endif
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

<!-- Conflict Alert -->
@if($stats['conflicts'] > 0)
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                        </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ $stats['conflicts'] }} konflik jadwal terdeteksi
                </h3>
                <p class="mt-1 text-sm text-red-700">
                    Terdapat peminjaman yang berpotensi konflik jadwal. Segera koordinasi untuk menghindari bentrokan.
                </p>
                        </div>
                        </div>
                        </div>
@endif
@endsection

@push('styles')
<style>
    /* Simple calendar styling */
    .calendar-container {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple event detail modal functionality
    function showEventDetail(peminjamanId) {
        // Create simple detail content
        const eventDetailContent = `
            <div class="p-4">
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Detail Peminjaman</h4>
                    <p class="text-gray-600">ID Peminjaman: ${peminjamanId}</p>
                        </div>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="text-blue-700">
                        <strong>Info:</strong> Untuk melihat detail lengkap peminjaman, silakan kunjungi halaman Peminjaman Global.
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('superadmin.peminjaman.index') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Lihat Semua Peminjaman
                    </a>
        </div>
    </div>
        `;
        
        // Show modal
        const modal = document.getElementById('eventDetailModal');
        if (modal) {
            document.getElementById('eventDetailContent').innerHTML = eventDetailContent;
            document.getElementById('eventDetailLink').href = "{{ route('superadmin.peminjaman.index') }}";
            modal.classList.remove('hidden');
        }
    }
    
    function closeEventModal() {
        const modal = document.getElementById('eventDetailModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    // Setup event listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Modal close buttons
        document.getElementById('closeModal')?.addEventListener('click', closeEventModal);
        document.getElementById('closeModalBtn')?.addEventListener('click', closeEventModal);
        
        // Close modal when clicking outside
        document.getElementById('eventDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEventModal();
            }
        });
    });
</script>
@endpush 