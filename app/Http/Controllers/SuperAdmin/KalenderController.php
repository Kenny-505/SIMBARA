<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\Admin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KalenderController extends Controller
{
    /**
     * Display global calendar view
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $adminId = $request->get('admin_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Get all admins for filter
        $admins = Admin::where('id_role', 2)
            ->orderBy('asal', 'asc')
            ->get();
        
        // Get calendar statistics
        $stats = $this->getCalendarStats($adminId, $month, $year);
        
        return view('superadmin.kalender', compact('admins', 'stats', 'month', 'year'));
    }
    
    /**
     * Get calendar events for FullCalendar
     */
    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $adminId = $request->get('admin_id');
        
        $query = Peminjaman::with(['user', 'peminjamanBarangs.barang.admin'])
            ->whereIn('status_pengajuan', ['approved', 'confirmed']) // Tampilkan yang sudah approved dan confirmed
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('tanggal_mulai', '<=', $start)
                         ->where('tanggal_selesai', '>=', $end);
                  });
            });
        
        // Filter by admin if specified
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $peminjaman = $query->get();
        
        $events = [];
        
        foreach ($peminjaman as $p) {
            // Get color based on status
            $color = $this->getEventColor($p->status_pengajuan, $p->status_peminjaman);
            
            // Get items for this peminjaman (filtered by admin if needed)
            $items = $p->peminjamanBarangs;
            if ($adminId) {
                $items = $items->filter(function($item) use ($adminId) {
                    return $item->barang->id_admin == $adminId;
                });
            }
            
            $itemNames = $items->pluck('barang.nama_barang')->take(3)->implode(', ');
            if ($items->count() > 3) {
                $itemNames .= ' (+' . ($items->count() - 3) . ' lainnya)';
            }
            
            $events[] = [
                'id' => $p->id_peminjaman,
                'title' => $p->kode_peminjaman . ' - ' . ($p->user->nama_penanggung_jawab ?? 'User'),
                'start' => $p->tanggal_mulai->format('Y-m-d'),
                'end' => $p->tanggal_selesai->addDay()->format('Y-m-d'), // FullCalendar end is exclusive
                'color' => $color,
                'extendedProps' => [
                    'kode_peminjaman' => $p->kode_peminjaman,
                    'user' => $p->user->nama_penanggung_jawab ?? 'User',
                    'lembaga' => $p->user->nama_lembaga ?? '',
                    'tujuan' => $p->tujuan_peminjaman,
                    'items' => $itemNames,
                    'total_items' => $items->count(),
                    'status_pengajuan' => ucfirst($p->status_pengajuan),
                    'status_peminjaman' => ucfirst($p->status_peminjaman ?? 'Ongoing'),
                    'tanggal_mulai' => $p->tanggal_mulai->format('d/m/Y'),
                    'tanggal_selesai' => $p->tanggal_selesai->format('d/m/Y'),
                    'total_biaya' => 'Rp ' . number_format($p->total_biaya, 0, ',', '.'),
                    'duration' => $p->tanggal_mulai->diffInDays($p->tanggal_selesai) + 1 . ' hari'
                ]
            ];
        }
        
        return response()->json($events);
    }
    
    /**
     * Store a new calendar event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string'
        ]);
        
        // For now, we'll just redirect back with success
        // In the future, this could create custom events or calendar entries
        return redirect()->route('superadmin.kalender.index')
            ->with('success', 'Event berhasil ditambahkan ke kalender.');
    }
    
    /**
     * Get availability calendar for specific item
     */
    public function getItemAvailability(Request $request, $itemId)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $barang = Barang::findOrFail($itemId);
        
        // Get all bookings for this item in the date range
        $bookings = PeminjamanBarang::with(['peminjaman'])
            ->where('id_barang', $itemId)
            ->whereHas('peminjaman', function($query) use ($start, $end) {
                $query->whereIn('status_pengajuan', ['approved', 'confirmed'])
                      ->where(function($q) use ($start, $end) {
                          $q->whereBetween('tanggal_mulai', [$start, $end])
                            ->orWhereBetween('tanggal_selesai', [$start, $end])
                            ->orWhere(function($q2) use ($start, $end) {
                                $q2->where('tanggal_mulai', '<=', $start)
                                   ->where('tanggal_selesai', '>=', $end);
                            });
                      });
            })
            ->get();
        
        $events = [];
        
        foreach ($bookings as $booking) {
            $p = $booking->peminjaman;
            
            $events[] = [
                'id' => 'booking_' . $booking->id_peminjaman_barang,
                'title' => "Dipinjam: {$booking->jumlah_pinjam} unit",
                'start' => $p->tanggal_mulai->format('Y-m-d'),
                'end' => $p->tanggal_selesai->addDay()->format('Y-m-d'),
                'color' => '#dc3545',
                'extendedProps' => [
                    'type' => 'booking',
                    'kode_peminjaman' => $p->kode_peminjaman,
                    'user' => $p->user->nama_penanggung_jawab ?? 'User',
                    'jumlah_pinjam' => $booking->jumlah_pinjam,
                    'available_stock' => $barang->stok_tersedia
                ]
            ];
        }
        
        return response()->json($events);
    }
    
    /**
     * Get calendar statistics
     */
    private function getCalendarStats($adminId, $month, $year)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        
        $query = Peminjaman::whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth]);
        
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $baseQuery = clone $query;
        
        $totalEvents = $baseQuery->count();
        
        // Calculate conflicts (simplified - count double bookings on same dates)
        $conflicts = $this->calculateConflicts($adminId, $startOfMonth, $endOfMonth);
        
        // Calculate utilization (percentage of days with confirmed bookings)
        $daysWithBookings = Peminjaman::selectRaw('DATE(tanggal_mulai) as booking_date')
            ->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
            ->whereIn('status_pengajuan', ['approved', 'confirmed'])
            ->when($adminId, function($q) use ($adminId) {
                $q->whereHas('peminjamanBarangs.barang', function($q2) use ($adminId) {
                    $q2->where('id_admin', $adminId);
                });
            })
            ->groupBy('booking_date')
            ->get()
            ->count();
        
        $totalDaysInMonth = $startOfMonth->daysInMonth;
        $utilization = $totalDaysInMonth > 0 ? round(($daysWithBookings / $totalDaysInMonth) * 100) : 0;
        
        return [
            'total_events' => $totalEvents,
            'total_bookings' => $totalEvents,
            'this_month' => $totalEvents,
            'active_bookings' => (clone $baseQuery)->whereIn('status_pengajuan', ['approved', 'confirmed'])
                ->where('status_peminjaman', 'ongoing')->count(),
            'pending_bookings' => (clone $baseQuery)->where('status_pengajuan', 'pending_approval')->count(),
            'completed_bookings' => (clone $baseQuery)->where('status_peminjaman', 'returned')->count(),
            'conflicts' => $conflicts,
            'utilization' => $utilization,
            'busiest_day' => $this->getBusiestDay($adminId, $startOfMonth, $endOfMonth),
            'peak_hours' => $this->getPeakBookingHours($adminId, $startOfMonth, $endOfMonth)
        ];
    }
    
    /**
     * Get busiest day of the month
     */
    private function getBusiestDay($adminId, $startOfMonth, $endOfMonth)
    {
        $query = Peminjaman::selectRaw('DATE(tanggal_mulai) as booking_date, COUNT(*) as booking_count')
            ->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
            ->groupBy('booking_date')
            ->orderBy('booking_count', 'desc');
        
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $result = $query->first();
        
        return $result ? [
            'date' => Carbon::parse($result->booking_date)->format('d M Y'),
            'count' => $result->booking_count
        ] : null;
    }
    
    /**
     * Get peak booking hours (most common booking creation times)
     */
    private function getPeakBookingHours($adminId, $startOfMonth, $endOfMonth)
    {
        $query = Peminjaman::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('hour')
            ->orderBy('count', 'desc');
        
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $result = $query->first();
        
        return $result ? [
            'hour' => $result->hour . ':00 - ' . ($result->hour + 1) . ':00',
            'count' => $result->count
        ] : null;
    }
    
    /**
     * Calculate conflicts (overlapping bookings for same items)
     */
    private function calculateConflicts($adminId, $startOfMonth, $endOfMonth)
    {
        $query = Peminjaman::with(['peminjamanBarangs'])
            ->whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth])
            ->where('status_pengajuan', 'confirmed');
            
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $peminjaman = $query->get();
        $conflicts = 0;
        
        // Simple conflict detection: count dates with multiple bookings
        $dateGroups = $peminjaman->groupBy(function($p) {
            return $p->tanggal_mulai->format('Y-m-d');
        });
        
        foreach ($dateGroups as $date => $bookings) {
            if ($bookings->count() > 1) {
                $conflicts++;
            }
        }
        
        return $conflicts;
    }
    
    /**
     * Get upcoming events for the sidebar
     */
    private function getUpcomingEvents($adminId = null)
    {
        $query = Peminjaman::with(['user', 'peminjamanBarangs.barang'])
            ->where('tanggal_mulai', '>=', now())
            ->whereIn('status_pengajuan', ['approved', 'confirmed'])
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(5);

        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }

        return $query->get();
    }

    /**
     * Get conflicts for the current month
     */
    private function getConflicts($adminId = null, $month = null, $year = null)
    {
        $startOfMonth = Carbon::create($year ?? now()->year, $month ?? now()->month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year ?? now()->year, $month ?? now()->month, 1)->endOfMonth();

        // Get all dates in the month and check for conflicts
        $conflicts = [];
        $period = new \DatePeriod(
            $startOfMonth,
            new \DateInterval('P1D'),
            $endOfMonth->addDay()
        );

        foreach ($period as $date) {
            $query = Peminjaman::with(['user', 'peminjamanBarangs.barang'])
                ->where('tanggal_mulai', '<=', $date->format('Y-m-d'))
                ->where('tanggal_selesai', '>=', $date->format('Y-m-d'))
                ->whereIn('status_pengajuan', ['approved', 'confirmed']);

            if ($adminId) {
                $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                    $q->where('id_admin', $adminId);
                });
            }

            $eventsOnDate = $query->get();

            // Check for item conflicts (multiple bookings of same item)
            if ($eventsOnDate->count() > 1) {
                $conflicts[] = [
                    'date' => $date->format('d/m/Y'),
                    'events' => $eventsOnDate
                ];
                
                if (count($conflicts) >= 5) break; // Limit to 5 conflicts
            }
        }

        return collect($conflicts);
    }
    
    /**
     * Get event color based on status
     */
    private function getEventColor($statusPengajuan, $statusPeminjaman)
    {
        if ($statusPengajuan === 'pending_approval') {
            return '#ffc107'; // Yellow for pending
        } elseif ($statusPengajuan === 'approved' && $statusPeminjaman === 'ongoing') {
            return '#28a745'; // Green for active
        } elseif ($statusPeminjaman === 'returned') {
            return '#6c757d'; // Gray for completed
        } elseif ($statusPengajuan === 'confirmed') {
            return '#007bff'; // Blue for confirmed
        } else {
            return '#dc3545'; // Red for rejected/other
        }
    }
    
    /**
     * Get monthly booking trends
     */
    public function getMonthlyTrends(Request $request)
    {
        $adminId = $request->get('admin_id');
        $year = $request->get('year', now()->year);
        
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            
            $query = Peminjaman::whereBetween('tanggal_mulai', [$startOfMonth, $endOfMonth]);
            
            if ($adminId) {
                $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                    $q->where('id_admin', $adminId);
                });
            }
            
            $monthlyData[] = [
                'month' => Carbon::create($year, $month, 1)->format('M'),
                'bookings' => $query->count(),
                'active' => (clone $query)->where('status_peminjaman', 'ongoing')->count(),
                'completed' => (clone $query)->where('status_peminjaman', 'returned')->count()
            ];
        }
        
        return response()->json($monthlyData);
    }
    
    /**
     * Check for booking conflicts
     */
    public function checkConflicts(Request $request)
    {
        $barangId = $request->get('barang_id');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $jumlahPinjam = $request->get('jumlah_pinjam', 1);
        $excludePeminjamanId = $request->get('exclude_peminjaman_id');

        $barang = Barang::findOrFail($barangId);

        // Get existing bookings in the same period
        $conflictingBookings = PeminjamanBarang::with(['peminjaman'])
            ->where('id_barang', $barangId)
            ->whereHas('peminjaman', function($query) use ($tanggalMulai, $tanggalSelesai, $excludePeminjamanId) {
                $query->whereIn('status_pengajuan', ['approved', 'confirmed'])
                      ->where(function($q) use ($tanggalMulai, $tanggalSelesai) {
                          $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                            ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                            ->orWhere(function($q2) use ($tanggalMulai, $tanggalSelesai) {
                                $q2->where('tanggal_mulai', '<=', $tanggalMulai)
                                   ->where('tanggal_selesai', '>=', $tanggalSelesai);
                            });
                      });
                
                if ($excludePeminjamanId) {
                    $query->where('id_peminjaman', '!=', $excludePeminjamanId);
                }
            })
            ->get();

        $totalBooked = $conflictingBookings->sum('jumlah_pinjam');
        $availableStock = $barang->stok_total - $totalBooked;
        $canBook = $availableStock >= $jumlahPinjam;

        return response()->json([
            'can_book' => $canBook,
            'available_stock' => $availableStock,
            'total_stock' => $barang->stok_total,
            'total_booked' => $totalBooked,
            'conflicting_bookings' => $conflictingBookings->map(function($booking) {
                return [
                    'kode_peminjaman' => $booking->peminjaman->kode_peminjaman,
                    'user' => $booking->peminjaman->user->nama_penanggung_jawab,
                    'tanggal_mulai' => $booking->peminjaman->tanggal_mulai->format('d/m/Y'),
                    'tanggal_selesai' => $booking->peminjaman->tanggal_selesai->format('d/m/Y'),
                    'jumlah_pinjam' => $booking->jumlah_pinjam
                ];
            })
        ]);
    }
}
