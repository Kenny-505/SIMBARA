<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPendaftaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display superadmin dashboard with comprehensive statistics
     */
    public function index(Request $request)
    {
        // Get filter period (default: month)
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        // Basic statistics
        $totalPengajuan = PengajuanPendaftaran::count();
        $disetujui = PengajuanPendaftaran::where('status_verifikasi', 'approved')->count();
        $menungguVerifikasi = PengajuanPendaftaran::where('status_verifikasi', 'pending')->count();
        
        // Peminjaman statistics for current period
        $totalPeminjamanPeriod = Peminjaman::where('created_at', '>=', $startDate)->count();
        
        // Revenue from non-civitas users for current period
        $revenueNonCivitas = Transaksi::whereHas('peminjaman.user.role', function($query) {
                $query->where('nama_role', 'user_non_fmipa');
            })
            ->where('status_verifikasi', 'approved')
            ->where('created_at', '>=', $startDate)
            ->sum('nominal');
        
        // Pending approvals count (peminjaman waiting for admin approval)
        $pendingApprovals = Peminjaman::where('status_pengajuan', 'pending_approval')->count();
        
        // Recent activities for dashboard
        $recentPeminjaman = Peminjaman::with(['user', 'peminjamanBarangs.barang.admin'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentPengembalian = Pengembalian::with(['peminjaman.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly trends for charts
        $monthlyData = $this->getMonthlyTrends();
        
        return view('superadmin.dashboard', compact(
            'totalPengajuan',
            'disetujui', 
            'menungguVerifikasi',
            'totalPeminjamanPeriod',
            'revenueNonCivitas',
            'pendingApprovals',
            'recentPeminjaman',
            'recentPengembalian',
            'monthlyData',
            'period'
        ));
    }
    
    /**
     * Get start date based on period filter
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->startOfWeek();
            case 'month':
                return Carbon::now()->startOfMonth();
            default:
                return Carbon::now()->startOfMonth();
        }
    }
    
    /**
     * Get monthly trends data for charts
     */
    private function getMonthlyTrends()
    {
        $months = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $peminjaman = Peminjaman::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $revenue = Transaksi::whereHas('peminjaman.user.role', function($query) {
                    $query->where('nama_role', 'user_non_fmipa');
                })
                ->where('status_verifikasi', 'approved')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('nominal');
            
            $months->push([
                'month' => $date->format('M Y'),
                'peminjaman' => $peminjaman,
                'revenue' => $revenue
            ]);
        }
        
        return $months;
    }
    
    /**
     * Get dashboard statistics via AJAX
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        return response()->json([
            'total_peminjaman_period' => Peminjaman::where('created_at', '>=', $startDate)->count(),
            'revenue_non_civitas' => Transaksi::whereHas('peminjaman.user.role', function($query) {
                    $query->where('nama_role', 'user_non_fmipa');
                })
                ->where('status_verifikasi', 'approved')
                ->where('created_at', '>=', $startDate)
                ->sum('nominal'),
            'pending_approvals' => Peminjaman::where('status_pengajuan', 'pending_approval')->count(),
            'period' => $period
        ]);
    }
}
