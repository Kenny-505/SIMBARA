<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Pengembalian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Get barang statistics for admin's lembaga
        $totalBarang = Barang::where('id_admin', $admin->id_admin)->count();
        $barangTersedia = Barang::where('id_admin', $admin->id_admin)
            ->where('stok_tersedia', '>', 0)
            ->count();
        $barangTidakTersedia = Barang::where('id_admin', $admin->id_admin)
            ->where('stok_tersedia', 0)
            ->count();
        
        // Get peminjaman statistics for admin's items
        $peminjamanQuery = Peminjaman::whereHas('peminjamanBarangs.barang', function($query) use ($admin) {
            $query->where('id_admin', $admin->id_admin);
        });
        
        $totalPeminjaman = (clone $peminjamanQuery)->count();
        
        // Get active peminjaman (approved/confirmed and not ended yet)
        $now = now();
        $peminjamanAktif = Peminjaman::select('peminjaman.*')
            ->join('peminjaman_barang', 'peminjaman.id_peminjaman', '=', 'peminjaman_barang.id_peminjaman')
            ->join('barang', 'peminjaman_barang.id_barang', '=', 'barang.id_barang')
            ->where('barang.id_admin', $admin->id_admin)
            ->whereIn('peminjaman.status_pengajuan', ['approved', 'confirmed'])
            ->where(function($query) {
                $query->whereNull('peminjaman.status_peminjaman')
                      ->orWhere('peminjaman.status_peminjaman', 'ongoing');
            })
            ->where('peminjaman.tanggal_selesai', '>=', $now)
            ->distinct()
            ->count('peminjaman.id_peminjaman');
        
        $menungguApproval = (clone $peminjamanQuery)
            ->where('status_pengajuan', 'pending_approval')
            ->count();
        
        // Get recent peminjaman for admin's items
        $recentPeminjaman = (clone $peminjamanQuery)
            ->with(['user', 'peminjamanBarangs.barang'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get low stock items (less than or equal to 20% of total stock)
        $lowStockItems = Barang::where('id_admin', $admin->id_admin)
            ->whereRaw('stok_tersedia <= (stok_total * 0.2)')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('stok_tersedia', 'asc')
            ->get();
        
        // Get monthly peminjaman trend for admin's items
        $monthlyTrend = (clone $peminjamanQuery)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        
        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyTrend->get($i, 0);
        }
        
        return view('admin.dashboard', compact(
            'totalBarang',
            'barangTersedia', 
            'barangTidakTersedia',
            'totalPeminjaman',
            'peminjamanAktif',
            'menungguApproval',
            'recentPeminjaman',
            'lowStockItems',
            'monthlyData'
        ));
    }
} 