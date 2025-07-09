<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display calendar view for admin's items
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Get admin's items for the filter dropdown
        $adminBarangs = Barang::where('id_admin', $admin->id_admin)
            ->orderBy('nama_barang')
            ->get();
        
        // Get recent activities for quick info
        $recentActivities = Peminjaman::with(['user', 'peminjamanBarangs.barang'])
            ->whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
                $q->where('id_admin', $admin->id_admin);
            })
            ->whereIn('status_pengajuan', ['approved', 'confirmed'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get statistics for calendar header
        $totalPeminjaman = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })->whereIn('status_pengajuan', ['approved', 'confirmed'])->count();
        
        $bulanIni = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
            })
        ->whereIn('status_pengajuan', ['approved', 'confirmed'])
        ->whereMonth('tanggal_mulai', Carbon::now()->month)
        ->whereYear('tanggal_mulai', Carbon::now()->year)
        ->count();
        
        $itemAktif = Barang::where('id_admin', $admin->id_admin)
            ->where('stok_tersedia', '>', 0)
            ->count();
        
        $sedangDipinjam = PeminjamanBarang::whereHas('barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
            })
            ->whereHas('peminjaman', function($q) {
            $q->whereIn('status_pengajuan', ['approved', 'confirmed'])
              ->where('tanggal_mulai', '<=', now())
              ->where('tanggal_selesai', '>=', now());
            })
            ->where('status_persetujuan', 'approved')
        ->sum('jumlah_pinjam');
        
        return view('admin.calendar.index', compact(
            'adminBarangs', 
            'recentActivities', 
            'totalPeminjaman', 
            'bulanIni', 
            'itemAktif', 
            'sedangDipinjam'
        ));
    }
    
    /**
     * Get calendar events data for admin's items
     */
    public function getEvents(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $start = $request->input('start');
        $end = $request->input('end');
        $itemFilter = $request->input('item_filter');
        $statusFilter = $request->input('status_filter');
        
        $query = Peminjaman::with(['user', 'peminjamanBarangs.barang', 'pengembalian'])
            ->whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
                $q->where('id_admin', $admin->id_admin);
            })
            ->whereIn('status_pengajuan', ['approved', 'confirmed']);
        
        // Filter by status if specified
        if ($statusFilter) {
            $query->where('status_pengajuan', $statusFilter);
        }
        
        // Filter by date range
        if ($start && $end) {
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('tanggal_mulai', '<=', $start)
                         ->where('tanggal_selesai', '>=', $end);
                  });
            });
        }
        
        $peminjaman = $query->get();
        
        $events = [];
        
        foreach ($peminjaman as $pinjam) {
            // Get admin's items only from this peminjaman
            $adminItems = $pinjam->peminjamanBarangs()
                ->whereHas('barang', function($q) use ($admin) {
                    $q->where('id_admin', $admin->id_admin);
                })
                ->where('status_persetujuan', 'approved')
                ->with('barang')
                ->get();
            
            if ($adminItems->count() > 0) {
                // Create events for each item if item filter is applied
                if ($itemFilter) {
                    $filteredItems = $adminItems->where('id_barang', $itemFilter);
                    
                    foreach ($filteredItems as $item) {
                        $events[] = $this->createCalendarEvent($pinjam, $item, 'single');
                    }
                } else {
                    // Create grouped event for all admin's items in this loan
                    $events[] = $this->createCalendarEvent($pinjam, $adminItems, 'group');
                }
            }
        }
        
        return response()->json($events);
    }
    
    /**
     * Create calendar event for loan item(s)
     */
    private function createCalendarEvent($peminjaman, $items, $type = 'group')
    {
        $admin = Auth::guard('admin')->user();
        
        // Determine event color based on status
        $color = '#28a745'; // Green for approved
        if ($peminjaman->status_pengajuan === 'confirmed') {
            $color = '#007bff'; // Blue for confirmed
        }
        
        // Check if loan is currently ongoing
        $now = now();
        $isOngoing = $now >= $peminjaman->tanggal_mulai && $now <= $peminjaman->tanggal_selesai;
        
        if ($isOngoing) {
            $color = '#ffc107'; // Yellow for currently ongoing
        }
        
        // Check if loan has been returned - this should override other colors
        if ($peminjaman->pengembalian()->exists()) {
            $color = '#6b7280'; // Gray for returned items
        }
        
        if ($type === 'single') {
            // Single item event
            $item = $items;
            $title = $item->barang->nama_barang . ' (' . $item->jumlah_pinjam . 'x)';
            $itemNames = [$item->barang->nama_barang];
            $totalItems = $item->jumlah_pinjam;
        } else {
            // Grouped event for multiple items
            $itemNames = $items->pluck('barang.nama_barang')->toArray();
            $totalItems = $items->sum('jumlah_pinjam');
            
            if (count($itemNames) === 1) {
                $title = $itemNames[0] . ' (' . $totalItems . 'x)';
            } else {
                $title = $peminjaman->nama_pengambil . ' (' . count($itemNames) . ' items)';
            }
        }
        
        return [
            'id' => $peminjaman->id_peminjaman . ($type === 'single' ? '_' . $items->id_barang : ''),
            'title' => $title,
            'start' => $peminjaman->tanggal_mulai,
            'end' => Carbon::parse($peminjaman->tanggal_selesai)->addDay()->format('Y-m-d'), // FullCalendar end is exclusive
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff',
            'allDay' => true,
            'extendedProps' => [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'nama_pengambil' => $peminjaman->nama_pengambil,
                'no_identitas_pengambil' => $peminjaman->no_identitas_pengambil,
                'items' => $itemNames,
                'total_items' => $totalItems,
                'status' => $peminjaman->status_pengajuan,
                'tujuan_peminjaman' => $peminjaman->tujuan_peminjaman,
                'tanggal_mulai' => Carbon::parse($peminjaman->tanggal_mulai)->format('d/m/Y'),
                'tanggal_selesai' => Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y'),
                'durasi_hari' => Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(Carbon::parse($peminjaman->tanggal_selesai)) + 1,
                'nama_admin' => $admin->nama_admin ?? 'Admin',
                'is_ongoing' => $isOngoing
            ]
        ];
        }
    
    /**
     * Get availability data for specific item (simplified version for sidebar)
     */
    public function getAvailability($itemId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if item belongs to admin
        $item = Barang::where('id_barang', $itemId)
            ->where('id_admin', $admin->id_admin)
            ->first();
            
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }
        
        // Get current active bookings for this item
        $now = now();
        $usedQuantity = PeminjamanBarang::where('id_barang', $itemId)
            ->whereHas('peminjaman', function($q) use ($now) {
                $q->whereIn('status_pengajuan', ['approved', 'confirmed'])
                  ->where('tanggal_mulai', '<=', $now)
                  ->where('tanggal_selesai', '>=', $now);
            })
            ->where('status_persetujuan', 'approved')
            ->sum('jumlah_pinjam');
        
        return response()->json([
            'success' => true,
            'data' => [
                'available' => max(0, $item->stok_total - $usedQuantity),
                'used' => $usedQuantity,
                'total' => $item->stok_total
            ]
        ]);
    }
    
    /**
     * Get monthly statistics for dashboard
     */
    public function getMonthlyStats(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        // Get loans for admin's items in the specified month
        $loans = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })
        ->whereIn('status_pengajuan', ['approved', 'confirmed'])
        ->whereMonth('tanggal_mulai', $month)
        ->whereYear('tanggal_mulai', $year)
            ->get();
        
        // Count by status
        $now = now();
        $approved = 0;
        $confirmed = 0;
        $ongoing = 0;
            
        foreach ($loans as $loan) {
            $isOngoing = $now >= $loan->tanggal_mulai && $now <= $loan->tanggal_selesai;
                
            if ($isOngoing) {
                $ongoing++;
            } elseif ($loan->status_pengajuan === 'confirmed') {
                $confirmed++;
            } else {
                $approved++;
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'approved' => $approved,
                'confirmed' => $confirmed,
                'ongoing' => $ongoing,
                'total' => $loans->count()
            ]
        ]);
    }
    
    /**
     * Get statistics for calendar dashboard
     */
    public function getStats(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        // Monthly bookings
        $monthlyBookings = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
            })
        ->whereIn('status_pengajuan', ['approved', 'confirmed'])
        ->whereMonth('tanggal_mulai', $month)
        ->whereYear('tanggal_mulai', $year)
        ->count();
        
        // Item usage statistics
        $itemUsage = PeminjamanBarang::whereHas('barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
            })
            ->whereHas('peminjaman', function($q) use ($month, $year) {
            $q->whereIn('status_pengajuan', ['approved', 'confirmed'])
              ->whereMonth('tanggal_mulai', $month)
              ->whereYear('tanggal_mulai', $year);
            })
            ->where('status_persetujuan', 'approved')
            ->with('barang')
        ->selectRaw('id_barang, SUM(jumlah_pinjam) as total_used')
            ->groupBy('id_barang')
        ->orderBy('total_used', 'desc')
            ->take(5)
        ->get();
        
        return response()->json([
            'monthly_bookings' => $monthlyBookings,
            'item_usage' => $itemUsage
        ]);
    }
} 