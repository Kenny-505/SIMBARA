<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Admin;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Pengembalian;
use App\Models\PengembalianBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    
    /**
     * Audit stok barang untuk menemukan inconsistency
     */
    public function auditStock(Request $request)
    {
        $results = [];
        $issues = [];
        
        // Get all active items
        $barangs = Barang::where('is_active', true)->get();
        
        foreach ($barangs as $barang) {
            $audit = $this->auditSingleItem($barang);
            $results[] = $audit;
            
            if (!$audit['is_consistent']) {
                $issues[] = $audit;
            }
        }
        
        if ($request->ajax()) {
            return response()->json([
                'total_items' => count($results),
                'issues_found' => count($issues),
                'results' => $results,
                'issues' => $issues
            ]);
        }
        
        return view('superadmin.inventaris.audit', compact('results', 'issues'));
    }
    
    /**
     * Audit single item stock consistency
     */
    private function auditSingleItem(Barang $barang)
    {
        $result = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'current_stock_total' => $barang->stok_total,
            'current_stock_available' => $barang->stok_tersedia,
            'calculated_stock_total' => $barang->stok_total, // Start with current
            'calculated_stock_available' => 0,
            'stock_borrowed' => 0,
            'stock_returned' => 0,
            'stock_damaged_lost' => 0,
            'is_consistent' => true,
            'issues' => [],
            'peminjaman_data' => [],
            'pengembalian_data' => []
        ];
        
        // Calculate stock based on peminjaman and pengembalian
        $calculated = $this->calculateExpectedStock($barang);
        
        $result['calculated_stock_available'] = $calculated['expected_available'];
        $result['stock_borrowed'] = $calculated['total_borrowed'];
        $result['stock_returned'] = $calculated['total_returned'];
        $result['stock_damaged_lost'] = $calculated['total_damaged'];
        $result['peminjaman_data'] = $calculated['peminjaman_breakdown'];
        $result['pengembalian_data'] = $calculated['pengembalian_breakdown'];
        
        // Check for inconsistencies
        if ($barang->stok_tersedia != $calculated['expected_available']) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Stok tersedia tidak sesuai. Database: {$barang->stok_tersedia}, Calculated: {$calculated['expected_available']}";
        }
        
        if ($barang->stok_tersedia > $barang->stok_total) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Stok tersedia lebih besar dari stok total";
        }
        
        if ($barang->stok_tersedia < 0) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Stok tersedia negatif";
        }
        
        return $result;
    }
    
    /**
     * Calculate expected stock based on loan and return history
     */
    private function calculateExpectedStock(Barang $barang)
    {
        $initialStock = $barang->stok_total;
        
        // Get all approved peminjaman for this item
        $peminjamanBarangs = PeminjamanBarang::where('id_barang', $barang->id_barang)
            ->where('status_persetujuan', 'approved')
            ->whereHas('peminjaman', function($q) {
                $q->whereIn('status_peminjaman', ['ongoing', 'returned']);
            })
            ->with('peminjaman')
            ->get();
        
        $totalBorrowed = $peminjamanBarangs->sum('jumlah_pinjam');
        
        // Get all completed returns for this item
        $pengembalianBarangs = PengembalianBarang::where('id_barang', $barang->id_barang)
            ->whereHas('pengembalian', function($q) {
                $q->whereIn('status_pengembalian', ['completed', 'fully_completed']);
            })
            ->with('pengembalian')
            ->get();
        
        $totalReturned = 0;
        $totalDamaged = 0;
        
        foreach ($pengembalianBarangs as $item) {
            if ($item->kondisi_barang === 'parah') {
                $totalDamaged += $item->jumlah_kembali;
            } else {
                $totalReturned += $item->jumlah_kembali;
            }
        }
        
        // Expected available stock = initial - borrowed + returned
        // Total stock should be reduced by damaged items
        $expectedAvailable = $initialStock - $totalBorrowed + $totalReturned;
        $expectedTotal = $initialStock - $totalDamaged;
        
        return [
            'expected_available' => max(0, $expectedAvailable),
            'expected_total' => max(0, $expectedTotal),
            'total_borrowed' => $totalBorrowed,
            'total_returned' => $totalReturned,
            'total_damaged' => $totalDamaged,
            'peminjaman_breakdown' => $peminjamanBarangs->map(function($item) {
                return [
                    'kode_peminjaman' => $item->peminjaman->kode_peminjaman,
                    'jumlah' => $item->jumlah_pinjam,
                    'status' => $item->peminjaman->status_peminjaman,
                    'tanggal' => $item->created_at->format('Y-m-d')
                ];
            }),
            'pengembalian_breakdown' => $pengembalianBarangs->map(function($item) {
                return [
                    'kode_peminjaman' => $item->pengembalian->peminjaman->kode_peminjaman,
                    'jumlah' => $item->jumlah_kembali,
                    'kondisi' => $item->kondisi_barang,
                    'status' => $item->pengembalian->status_pengembalian,
                    'tanggal' => $item->created_at->format('Y-m-d')
                ];
            })
        ];
    }
    
    /**
     * Fix stock inconsistencies
     */
    public function fixStock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.action' => 'required|in:recalculate,manual_adjust',
            'items.*.new_available' => 'nullable|integer|min:0',
            'items.*.new_total' => 'nullable|integer|min:0'
        ]);
        
        $fixed = [];
        $errors = [];
        
        DB::beginTransaction();
        try {
            foreach ($request->items as $itemData) {
                $barang = Barang::findOrFail($itemData['id_barang']);
                $oldAvailable = $barang->stok_tersedia;
                $oldTotal = $barang->stok_total;
                
                if ($itemData['action'] === 'recalculate') {
                    // Recalculate based on transaction history
                    $calculated = $this->calculateExpectedStock($barang);
                    
                    $barang->update([
                        'stok_tersedia' => max(0, $calculated['expected_available']),
                        'stok_total' => max(0, $calculated['expected_total'])
                    ]);
                    
                    $fixed[] = [
                        'nama_barang' => $barang->nama_barang,
                        'action' => 'recalculate',
                        'old_available' => $oldAvailable,
                        'new_available' => $barang->stok_tersedia,
                        'old_total' => $oldTotal,
                        'new_total' => $barang->stok_total
                    ];
                    
                } elseif ($itemData['action'] === 'manual_adjust') {
                    // Manual adjustment
                    $newAvailable = $itemData['new_available'];
                    $newTotal = $itemData['new_total'] ?? $barang->stok_total;
                    
                    if ($newAvailable > $newTotal) {
                        $errors[] = "Stok tersedia tidak boleh lebih besar dari stok total untuk {$barang->nama_barang}";
                        continue;
                    }
                    
                    $barang->update([
                        'stok_tersedia' => $newAvailable,
                        'stok_total' => $newTotal
                    ]);
                    
                    $fixed[] = [
                        'nama_barang' => $barang->nama_barang,
                        'action' => 'manual_adjust',
                        'old_available' => $oldAvailable,
                        'new_available' => $newAvailable,
                        'old_total' => $oldTotal,
                        'new_total' => $newTotal
                    ];
                }
            }
            
            if (empty($errors)) {
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Stok berhasil diperbaiki',
                    'fixed_items' => $fixed
                ]);
            } else {
                DB::rollback();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Ada error dalam perbaikan stok',
                    'errors' => $errors
                ], 400);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get detailed stock analysis for a specific item
     */
    public function getStockAnalysis($id)
    {
        $barang = Barang::findOrFail($id);
        $audit = $this->auditSingleItem($barang);
        
        return response()->json($audit);
    }
}
