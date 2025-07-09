<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Admin;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    /**
     * Display global inventory overview
     */
    public function index(Request $request)
    {
        $query = Barang::with(['admin']);
        
        // Filter by admin/lembaga
        if ($request->filled('admin_id')) {
            $query->where('id_admin', $request->admin_id);
        }
        
        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('stok_tersedia', 0);
            } elseif ($request->availability === 'low_stock') {
                $query->whereRaw('stok_tersedia <= (stok_total * 0.2)');
            }
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        
        $barangs = $query->orderBy('nama_barang', 'asc')->paginate(20);
        
        // Get all admins for filter dropdown
        $admins = Admin::where('id_role', 2) // Only admin lembaga, not superadmin
            ->orderBy('asal', 'asc')
            ->get();
        
        // Get inventory statistics
        $stats = [
            'total_barang' => Barang::count(),
            'tersedia' => Barang::where('stok_tersedia', '>', 0)->count(),
            'sedang_dipinjam' => Barang::where('stok_tersedia', '<', DB::raw('stok_total'))->count(),
            'stok_rendah' => Barang::whereRaw('stok_tersedia <= 2 AND stok_tersedia > 0')->count(),
            'total_items' => Barang::count(),
            'total_stock' => Barang::sum('stok_total'),
            'available_stock' => Barang::sum('stok_tersedia'),
            'borrowed_stock' => Barang::sum(DB::raw('stok_total - stok_tersedia')),
            'active_items' => Barang::where('is_active', true)->count(),
            'inactive_items' => Barang::where('is_active', false)->count(),
            'low_stock_items' => Barang::whereRaw('stok_tersedia <= (stok_total * 0.2)')->count(),
            'out_of_stock_items' => Barang::where('stok_tersedia', 0)->count()
        ];
        
        // Get top borrowed items
        $topBorrowedItems = Barang::withCount([
                'peminjamanBarangs as total_borrowed' => function($query) {
                    $query->select(DB::raw('COALESCE(SUM(jumlah_pinjam), 0)'));
                }
            ])
            ->orderBy('total_borrowed', 'desc')
            ->limit(10)
            ->get();
        
        // Get inventory by lembaga
        $inventoryByLembaga = Admin::where('id_role', 2)
            ->withCount(['barangs as total_items'])
            ->withSum('barangs as total_stock', 'stok_total')
            ->withSum('barangs as available_stock', 'stok_tersedia')
            ->orderBy('asal', 'asc')
            ->get();
        
        return view('superadmin.inventaris', compact(
            'barangs',
            'admins', 
            'stats',
            'topBorrowedItems',
            'inventoryByLembaga'
        ));
    }
    
    /**
     * Show detailed view of specific item
     */
    public function show($id)
    {
        $barang = Barang::with(['admin'])->findOrFail($id);
        

        
        // Get current borrowings (ongoing loans)
        $currentBorrowings = PeminjamanBarang::with(['peminjaman.user'])
            ->where('id_barang', $id)
            ->whereHas('peminjaman', function($query) {
                $query->where('status_peminjaman', 'ongoing');
            })
            ->get();
        
        // Calculate utilization statistics
        $totalBorrowed = PeminjamanBarang::where('id_barang', $id)->sum('jumlah_pinjam');
        $utilizationRate = $barang->stok_total > 0 
            ? (($barang->stok_total - $barang->stok_tersedia) / $barang->stok_total) * 100 
            : 0;
        
        return view('superadmin.inventaris.show', compact(
            'barang',
            'currentBorrowings',
            'totalBorrowed',
            'utilizationRate'
        ));
    }
    
    /**
     * Export inventory data to CSV
     */
    public function export(Request $request)
    {
        $query = Barang::with(['admin']);
        
        // Apply same filters as index
        if ($request->filled('admin_id')) {
            $query->where('id_admin', $request->admin_id);
        }
        
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('stok_tersedia', 0);
            } elseif ($request->availability === 'low_stock') {
                $query->whereRaw('stok_tersedia <= (stok_total * 0.2)');
            }
        }
        
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }
        
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        
        $barangs = $query->orderBy('nama_barang', 'asc')->get();
        
        $filename = 'inventaris_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($barangs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID Barang',
                'Nama Barang',
                'Lembaga',
                'Stok Total',
                'Stok Tersedia',
                'Stok Dipinjam',
                'Harga Sewa',
                'Status',
                'Tingkat Utilisasi (%)',
                'Denda Ringan',
                'Denda Sedang', 
                'Denda Parah'
            ]);
            
            foreach ($barangs as $item) {
                $stokDipinjam = $item->stok_total - $item->stok_tersedia;
                $utilizationRate = $item->stok_total > 0 
                    ? round((($item->stok_total - $item->stok_tersedia) / $item->stok_total) * 100, 2)
                    : 0;
                    
                fputcsv($file, [
                    $item->id_barang,
                    $item->nama_barang,
                    $item->admin->asal ?? '',
                    $item->stok_total,
                    $item->stok_tersedia,
                    $stokDipinjam,
                    $item->harga_sewa,
                    $item->is_active ? 'Aktif' : 'Tidak Aktif',
                    $utilizationRate,
                    $item->denda_ringan,
                    $item->denda_sedang,
                    $item->denda_parah
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get inventory statistics via AJAX
     */
    public function getStats(Request $request)
    {
        $adminId = $request->get('admin_id');
        
        $query = Barang::query();
        if ($adminId) {
            $query->where('id_admin', $adminId);
        }
        
        $baseQuery = clone $query;
        
        return response()->json([
            'total_items' => $baseQuery->count(),
            'total_stock' => $baseQuery->sum('stok_total'),
            'available_stock' => $baseQuery->sum('stok_tersedia'),
            'borrowed_stock' => $baseQuery->sum(DB::raw('stok_total - stok_tersedia')),
            'active_items' => $baseQuery->where('is_active', true)->count(),
            'low_stock_items' => $baseQuery->whereRaw('stok_tersedia <= (stok_total * 0.2)')->count(),
            'out_of_stock_items' => $baseQuery->where('stok_tersedia', 0)->count()
        ]);
    }
    
    /**
     * Get utilization report for charts
     */
    public function getUtilizationReport(Request $request)
    {
        $period = $request->get('period', 'month');
        $adminId = $request->get('admin_id');
        
        $query = PeminjamanBarang::with(['barang', 'peminjaman'])
            ->whereHas('peminjaman', function($q) use ($period) {
                if ($period === 'week') {
                    $q->where('created_at', '>=', now()->startOfWeek());
                } else {
                    $q->where('created_at', '>=', now()->startOfMonth());
                }
            });
        
        if ($adminId) {
            $query->whereHas('barang', function($q) use ($adminId) {
                $q->where('id_admin', $adminId);
            });
        }
        
        $data = $query->select('id_barang')
            ->selectRaw('SUM(jumlah_pinjam) as total_borrowed')
            ->with(['barang:id_barang,nama_barang'])
            ->groupBy('id_barang')
            ->orderBy('total_borrowed', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json($data);
    }
}
