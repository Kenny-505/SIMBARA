<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Display global peminjaman overview
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'peminjamanBarangs.barang.admin']);
        
        // Filter by status
        if ($request->filled('status_pengajuan')) {
            $query->where('status_pengajuan', $request->status_pengajuan);
        }
        
        if ($request->filled('status_peminjaman')) {
            $query->where('status_peminjaman', $request->status_peminjaman);
        }
        
        // Filter by admin/lembaga
        if ($request->filled('admin_id')) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($request) {
                $q->where('id_admin', $request->admin_id);
            });
        }
        
        // Filter by user type
        if ($request->filled('user_type')) {
            $roleMap = [
                'civitas' => 'user_fmipa',
                'non_civitas' => 'user_non_fmipa'
            ];
            
            if (isset($roleMap[$request->user_type])) {
                $query->whereHas('user.role', function($q) use ($roleMap, $request) {
                    $q->where('nama_role', $roleMap[$request->user_type]);
                });
            }
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_selesai', '<=', $request->end_date);
        }
        
        // Search by kode peminjaman or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_penanggung_jawab', 'like', "%{$search}%")
                               ->orWhere('nama_lembaga', 'like', "%{$search}%");
                  });
            });
        }
        
        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get all admins for filter dropdown
        $admins = Admin::where('id_role', 2)
            ->orderBy('asal', 'asc')
            ->get();
        
        // Get statistics
        $stats = [
            'total' => Peminjaman::count(),
            'pending_approval' => Peminjaman::where('status_pengajuan', 'pending_approval')->count(),
            'approved' => Peminjaman::where('status_pengajuan', 'approved')->count(),
            'confirmed' => Peminjaman::where('status_pengajuan', 'confirmed')->count(),
            'ongoing' => Peminjaman::where('status_peminjaman', 'ongoing')->count(),
            'returned' => Peminjaman::where('status_peminjaman', 'returned')->count(),
            'this_month' => Peminjaman::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'overdue' => Peminjaman::where('status_peminjaman', 'ongoing')
                ->where('tanggal_selesai', '<', now())->count()
        ];
        
        return view('superadmin.peminjaman', compact(
            'peminjaman',
            'admins',
            'stats'
        ));
    }
    
    /**
     * Show detailed view of specific peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'user.role',
            'peminjamanBarangs.barang.admin',
            'peminjamanBarangs.approvedBy',
            'transaksi',
            'pengembalian.pengembalianBarangs.barang'
        ])->findOrFail($id);
        
        // Get timeline of status changes
        $timeline = $this->getPeminjamanTimeline($peminjaman);
        
        // Calculate summary statistics for this peminjaman
        $summary = [
            'total_items' => $peminjaman->peminjamanBarangs->count(),
            'approved_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'approved')->count(),
            'pending_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'pending')->count(),
            'rejected_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'rejected')->count(),
            'duration_days' => Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(Carbon::parse($peminjaman->tanggal_selesai)) + 1,
            'is_overdue' => $peminjaman->status_peminjaman === 'ongoing' && Carbon::parse($peminjaman->tanggal_selesai)->isPast(),
            'days_overdue' => $peminjaman->status_peminjaman === 'ongoing' && Carbon::parse($peminjaman->tanggal_selesai)->isPast() 
                ? Carbon::parse($peminjaman->tanggal_selesai)->diffInDays(now()) 
                : 0
        ];
        
        return view('superadmin.peminjaman.show', compact('peminjaman', 'timeline', 'summary'));
    }
    
    /**
     * Get peminjaman timeline for detailed view
     */
    private function getPeminjamanTimeline($peminjaman)
    {
        $timeline = collect();
        
        // Created
        $timeline->push([
            'date' => $peminjaman->created_at,
            'title' => 'Pengajuan Dibuat',
            'description' => "Pengajuan peminjaman dibuat oleh {$peminjaman->user->nama_penanggung_jawab}",
            'type' => 'created',
            'icon' => 'plus-circle'
        ]);
        
        // Item approvals
        foreach ($peminjaman->peminjamanBarangs as $item) {
            if ($item->tanggal_persetujuan) {
                $timeline->push([
                    'date' => $item->tanggal_persetujuan,
                    'title' => $item->status_persetujuan === 'approved' ? 'Item Disetujui' : 'Item Ditolak',
                    'description' => "{$item->barang->nama_barang} " . 
                        ($item->status_persetujuan === 'approved' ? 'disetujui' : 'ditolak') . 
                        " oleh " . ($item->approvedBy->nama_lengkap ?? 'Admin'),
                    'type' => $item->status_persetujuan,
                    'icon' => $item->status_persetujuan === 'approved' ? 'check-circle' : 'x-circle'
                ]);
            }
        }
        
        // Payment verification
        if ($peminjaman->transaksi && $peminjaman->transaksi->tanggal_verifikasi) {
            $timeline->push([
                'date' => $peminjaman->transaksi->tanggal_verifikasi,
                'title' => 'Pembayaran Diverifikasi',
                'description' => "Pembayaran sebesar Rp " . number_format($peminjaman->transaksi->nominal, 0, ',', '.') . " diverifikasi",
                'type' => 'payment_verified',
                'icon' => 'credit-card'
            ]);
        }
        
        // Return processing
        if ($peminjaman->pengembalian) {
            $timeline->push([
                'date' => $peminjaman->pengembalian->tanggal_pengembalian_aktual,
                'title' => 'Barang Dikembalikan',
                'description' => "Proses pengembalian selesai" . 
                    ($peminjaman->pengembalian->total_denda > 0 
                        ? " dengan denda Rp " . number_format($peminjaman->pengembalian->total_denda, 0, ',', '.') 
                        : ""),
                'type' => 'returned',
                'icon' => 'arrow-left-circle'
            ]);
        }
        
        return $timeline->sortBy('date');
    }
    
    /**
     * Export peminjaman data to CSV
     */
    public function export(Request $request)
    {
        $query = Peminjaman::with(['user.role', 'peminjamanBarangs.barang.admin']);
        
        // Apply same filters as index
        if ($request->filled('status_pengajuan')) {
            $query->where('status_pengajuan', $request->status_pengajuan);
        }
        
        if ($request->filled('status_peminjaman')) {
            $query->where('status_peminjaman', $request->status_peminjaman);
        }
        
        if ($request->filled('admin_id')) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($request) {
                $q->where('id_admin', $request->admin_id);
            });
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_selesai', '<=', $request->end_date);
        }
        
        $peminjaman = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'peminjaman_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($peminjaman) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Kode Peminjaman',
                'Nama User',
                'Lembaga',
                'Tipe User',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Status Pengajuan',
                'Status Peminjaman',
                'Total Biaya',
                'Jumlah Items',
                'Items Disetujui',
                'Tujuan Peminjaman',
                'Tanggal Dibuat'
            ]);
            
            foreach ($peminjaman as $p) {
                $approvedItems = $p->peminjamanBarangs->where('status_persetujuan', 'approved')->count();
                $userType = $p->user->role->nama_role === 'user_fmipa' ? 'Civitas' : 'Non-Civitas';
                
                fputcsv($file, [
                    $p->kode_peminjaman,
                    $p->user->nama_penanggung_jawab ?? '',
                    $p->user->nama_lembaga ?? '',
                    $userType,
                    $p->tanggal_mulai ? $p->tanggal_mulai->format('Y-m-d') : '',
                    $p->tanggal_selesai ? $p->tanggal_selesai->format('Y-m-d') : '',
                    $p->status_pengajuan,
                    $p->status_peminjaman ?? '',
                    $p->total_biaya,
                    $p->peminjamanBarangs->count(),
                    $approvedItems,
                    $p->tujuan_peminjaman,
                    $p->created_at ? $p->created_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get peminjaman statistics via AJAX
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $adminId = $request->get('admin_id');
        
        $startDate = $period === 'week' 
            ? Carbon::now()->startOfWeek()
            : Carbon::now()->startOfMonth();
        
        $query = Peminjaman::where('created_at', '>=', $startDate);
        
        if ($adminId) {
            $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $baseQuery = clone $query;
        
        return response()->json([
            'total_period' => $baseQuery->count(),
            'pending_approval' => $baseQuery->where('status_pengajuan', 'pending_approval')->count(),
            'ongoing' => $baseQuery->where('status_peminjaman', 'ongoing')->count(),
            'completed' => $baseQuery->where('status_peminjaman', 'returned')->count(),
            'overdue' => Peminjaman::where('status_peminjaman', 'ongoing')
                ->where('tanggal_kembali', '<', now())
                ->when($adminId, function($q) use ($adminId) {
                    $q->whereHas('peminjamanBarangs.barang', function($subQ) use ($adminId) {
                        $subQ->where('id_admin', $adminId);
                    });
                })
                ->count(),
            'revenue_period' => $baseQuery->sum('total_biaya')
        ]);
    }
    
    /**
     * Get monthly trends for charts
     */
    public function getMonthlyTrends(Request $request)
    {
        $adminId = $request->get('admin_id');
        $year = $request->get('year', now()->year);
        
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            
            $query = Peminjaman::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            
            if ($adminId) {
                $query->whereHas('peminjamanBarangs.barang', function($q) use ($adminId) {
                    $q->where('id_admin', $adminId);
                });
            }
            
            $monthlyData[] = [
                'month' => Carbon::create($year, $month, 1)->format('M'),
                'total' => $query->count(),
                'approved' => (clone $query)->where('status_pengajuan', 'approved')->count(),
                'ongoing' => (clone $query)->where('status_peminjaman', 'ongoing')->count(),
                'completed' => (clone $query)->where('status_peminjaman', 'returned')->count(),
                'revenue' => (clone $query)->sum('total_biaya')
            ];
        }
        
        return response()->json($monthlyData);
    }
}
