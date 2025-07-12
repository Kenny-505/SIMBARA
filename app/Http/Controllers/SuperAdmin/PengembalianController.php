<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\PengembalianBarang;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    /**
     * Display list of return requests and processing
     */
    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'verifiedBy']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pengembalian', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pengembalian_aktual', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pengembalian_aktual', '<=', $request->end_date);
        }
        
        // Search by kode peminjaman or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('peminjaman', function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_penanggung_jawab', 'like', "%{$search}%");
                  });
            });
        }
        
        $pengembalian = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Pengembalian::count(),
            'pending' => Pengembalian::where('status_pengembalian', 'pending')->count(),
            'completed' => Pengembalian::whereIn('status_pengembalian', ['completed', 'fully_completed'])->count(),
            'payment_required' => Pengembalian::where('status_pengembalian', 'payment_required')->count(),
            'payment_uploaded' => Pengembalian::where('status_pengembalian', 'payment_uploaded')->count(),
            'total_denda' => Pengembalian::whereIn('status_pengembalian', ['completed', 'fully_completed'])->sum('total_denda')
        ];
        
        // Get peminjaman ready for return (status ongoing and not returned yet)
        $readyForReturn = Peminjaman::where('status_peminjaman', 'ongoing')
            ->whereDoesntHave('pengembalian')
            ->with(['user', 'peminjamanBarangs.barang'])
            ->orderBy('tanggal_selesai', 'asc')
            ->get();
        
        // Get penalty payments awaiting verification
        $paymentVerificationQueue = Pengembalian::with(['peminjaman.user'])
            ->where('status_pengembalian', 'payment_uploaded')
            ->where('status_pembayaran_denda', 'uploaded')
            ->orderBy('tanggal_upload_pembayaran', 'asc')
            ->get();
        
        return view('superadmin.pengembalian', compact('pengembalian', 'stats', 'readyForReturn', 'paymentVerificationQueue'));
    }
    
    /**
     * Show return form for specific peminjaman
     */
    public function create($peminjamanId)
    {
        $peminjaman = Peminjaman::with(['user', 'peminjamanBarangs.barang'])
            ->where('status_peminjaman', 'ongoing')
            ->whereDoesntHave('pengembalian')
            ->findOrFail($peminjamanId);
        
        return view('superadmin.pengembalian.create', compact('peminjaman'));
    }
    
    /**
     * Store return processing
     */
    public function store(Request $request, $peminjamanId)
    {
        $request->validate([
            'is_late' => 'required|boolean',
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah_kembali' => 'required|integer|min:1',
            'items.*.units' => 'required|array',
            'items.*.units.*.kondisi_barang' => 'required|in:baik,ringan,sedang,parah'
        ]);
        
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('peminjamanBarangs.barang')
                ->where('status_peminjaman', 'ongoing')
                ->findOrFail($peminjamanId);
            
            // Create pengembalian record
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_pengembalian_aktual' => now(),
                'status_pengembalian' => 'completed',
                'notes_admin' => null,
                'verified_by' => auth()->guard('admin')->id()
            ]);
            
            $totalDenda = 0;
            $isLate = (bool) $request->is_late;
            $daysLate = $isLate ? 1 : 0; // Set to 1 if late for display purposes
            $itemsToReturn = []; // Store items for potential stock return
            
            // Process each item
            foreach ($request->items as $itemData) {
                $barang = Barang::findOrFail($itemData['id_barang']);
                $peminjamanBarang = $peminjaman->peminjamanBarangs()
                    ->where('id_barang', $itemData['id_barang'])
                    ->first();
                
                if (!$peminjamanBarang) {
                    throw new \Exception("Barang {$barang->nama_barang} tidak ditemukan dalam peminjaman ini.");
                }
                
                // Validate that units count matches jumlah_kembali
                $expectedUnits = $itemData['jumlah_kembali'];
                $actualUnits = count($itemData['units']);
                if ($actualUnits !== $expectedUnits) {
                    throw new \Exception("Jumlah unit kondisi ({$actualUnits}) tidak sesuai dengan jumlah dikembalikan ({$expectedUnits}) untuk barang {$barang->nama_barang}.");
                }
                
                // Calculate penalty for each unit
                $totalItemPenalty = 0;
                $conditionsSummary = [];
                
                foreach ($itemData['units'] as $unitData) {
                    $kondisi = $unitData['kondisi_barang'];
                    
                    // Calculate penalty for this unit
                    $dendaPerUnit = 0;
                    switch ($kondisi) {
                        case 'ringan':
                            $dendaPerUnit = $barang->denda_ringan;
                            break;
                        case 'sedang':
                            $dendaPerUnit = $barang->denda_sedang;
                            break;
                        case 'parah':
                            $dendaPerUnit = $barang->denda_parah;
                            break;
                        case 'baik':
                        default:
                            $dendaPerUnit = 0;
                            break;
                    }
                    
                    $totalItemPenalty += $dendaPerUnit;
                    
                    // Track conditions for summary
                    if (!isset($conditionsSummary[$kondisi])) {
                        $conditionsSummary[$kondisi] = 0;
                    }
                    $conditionsSummary[$kondisi]++;
                }
                
                $totalDenda += $totalItemPenalty;
                
                // Create summary of conditions for keterangan
                $conditionsText = implode(', ', array_map(function($count, $condition) {
                    return "{$count} unit {$condition}";
                }, $conditionsSummary, array_keys($conditionsSummary)));
                
                // Create pengembalian_barang record with average condition
                $averageCondition = $this->calculateAverageCondition($conditionsSummary);
                
                PengembalianBarang::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_barang' => $itemData['id_barang'],
                    'jumlah_kembali' => $itemData['jumlah_kembali'],
                    'kondisi_barang' => $averageCondition,
                    'denda_kerusakan' => $totalItemPenalty,
                    'keterangan_kerusakan' => "Kondisi per unit: {$conditionsText}"
                ]);
                
                // Store item for potential stock return (don't return immediately)
                $itemsToReturn[] = [
                    'barang' => $barang,
                    'jumlah' => $itemData['jumlah_kembali']
                ];
            }
            
            // Calculate late penalty (fixed amount)
            $dendaTelat = $isLate ? 250000 : 0;
            $totalAllDenda = $totalDenda + $dendaTelat;
            
            // Update pengembalian with total denda
            $pengembalian->update([
                'total_denda' => $totalAllDenda,
                'denda_telat' => $dendaTelat,
                'hari_telat' => $daysLate
            ]);
            
            // Determine flow based on penalty amount
            if ($totalAllDenda == 0) {
                // No penalty - complete immediately and return stock
                $pengembalian->update(['status_pengembalian' => 'completed']);
                $peminjaman->update([
                    'status_peminjaman' => 'returned'
                ]);
                
                // Return stock based on condition
                $this->updateStockAfterReturn($pengembalian);
                
                $message = "Pengembalian berhasil diproses tanpa denda. Stok telah diperbarui sesuai kondisi barang.";
            } else {
                // Has penalty - wait for user payment, don't return stock yet
                $pengembalian->update(['status_pengembalian' => 'payment_required']);
                // Don't update peminjaman status yet - keep as 'ongoing'
                // Don't return stock yet - wait for payment verification
                
                $message = "Verifikasi pengembalian selesai. User perlu membayar denda sebesar Rp " . number_format($totalAllDenda, 0, ',', '.') . " dan mengupload bukti pembayaran.";
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.pengembalian.index')
                ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process pending return request from user
     */
    public function processReturn($pengembalianId)
    {
        try {
            $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.peminjamanBarangs.barang', 'pengembalianBarangs.barang'])
                ->where('status_pengembalian', 'pending')
                ->findOrFail($pengembalianId);
            
            $peminjaman = $pengembalian->peminjaman;
            
            if (!$peminjaman) {
                return redirect()->route('superadmin.pengembalian.index')
                    ->with('error', 'Data peminjaman tidak ditemukan untuk pengembalian ini.');
            }
            
            if (!$peminjaman->peminjamanBarangs || $peminjaman->peminjamanBarangs->count() == 0) {
                return redirect()->route('superadmin.pengembalian.index')
                    ->with('error', 'Data barang peminjaman tidak ditemukan.');
            }
            
            return view('superadmin.pengembalian.create', compact('peminjaman', 'pengembalian'));
            
        } catch (\Exception $e) {
            return redirect()->route('superadmin.pengembalian.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process pending return request (submit processing)
     */
    public function processReturnSubmit(Request $request, $pengembalianId)
    {
        // Debug logging
        \Log::info('ProcessReturnSubmit called', [
            'pengembalian_id' => $pengembalianId,
            'request_data' => $request->all()
        ]);

        // Basic validation first
        $request->validate([
            'is_late' => 'required',
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah_kembali' => 'required|integer|min:1',
            'items.*.units' => 'required'
        ]);
        
        // Manual validation for units structure (comes as object with numeric keys)
        foreach ($request->items as $itemIndex => $itemData) {
            if (!isset($itemData['units']) || !is_array($itemData['units'])) {
                throw new \Exception("Data units tidak valid untuk item {$itemIndex}");
            }
            
            foreach ($itemData['units'] as $unitKey => $unitData) {
                if (!isset($unitData['kondisi_barang']) || !in_array($unitData['kondisi_barang'], ['baik', 'ringan', 'sedang', 'parah'])) {
                    throw new \Exception("Kondisi barang tidak valid: " . ($unitData['kondisi_barang'] ?? 'kosong') . " untuk unit {$unitKey}");
                }
            }
        }
        
        \Log::info('Validation passed successfully');
        
        DB::beginTransaction();
        try {
            \Log::info('Starting database transaction');
            
            $pengembalian = Pengembalian::with(['peminjaman.peminjamanBarangs.barang'])
                ->where('status_pengembalian', 'pending')
                ->findOrFail($pengembalianId);
            
            \Log::info('Pengembalian found', ['id' => $pengembalian->id_pengembalian]);
            
            $peminjaman = $pengembalian->peminjaman;
            
            \Log::info('Peminjaman loaded', ['id' => $peminjaman->id_peminjaman]);
            
            // Delete existing pengembalian_barang records if any
            $pengembalian->pengembalianBarangs()->delete();
            
            \Log::info('Existing pengembalian_barang records deleted');
            
            $totalDenda = 0;
            $isLate = (bool) $request->is_late;
            $daysLate = $isLate ? 1 : 0; // Set to 1 if late for display purposes
            $itemsToReturn = []; // Store items for potential stock return
            
            \Log::info('Starting item processing', [
                'is_late' => $isLate,
                'items_count' => count($request->items)
            ]);
            
            // Process each item
            foreach ($request->items as $itemIndex => $itemData) {
                \Log::info('Processing item', ['index' => $itemIndex, 'id_barang' => $itemData['id_barang']]);
                $barang = Barang::findOrFail($itemData['id_barang']);
                $peminjamanBarang = $peminjaman->peminjamanBarangs()
                    ->where('id_barang', $itemData['id_barang'])
                    ->first();
                
                if (!$peminjamanBarang) {
                    throw new \Exception("Barang {$barang->nama_barang} tidak ditemukan dalam peminjaman ini.");
                }
                
                // Validate that units count matches jumlah_kembali  
                $expectedUnits = (int) $itemData['jumlah_kembali']; // Convert string to integer
                $actualUnits = count($itemData['units']);
                \Log::info('Units validation', [
                    'expected_units' => $expectedUnits,
                    'actual_units' => $actualUnits,
                    'expected_type' => gettype($expectedUnits),
                    'actual_type' => gettype($actualUnits)
                ]);
                if ($actualUnits !== $expectedUnits) {
                    throw new \Exception("Jumlah unit kondisi ({$actualUnits}) tidak sesuai dengan jumlah dikembalikan ({$expectedUnits}) untuk barang {$barang->nama_barang}.");
                }
                
                // Calculate penalty for each unit
                $totalItemPenalty = 0;
                $conditionsSummary = [];
                
                foreach ($itemData['units'] as $unitData) {
                    $kondisi = $unitData['kondisi_barang'];
                    
                    // Calculate penalty for this unit
                    $dendaPerUnit = 0;
                    switch ($kondisi) {
                        case 'ringan':
                            $dendaPerUnit = $barang->denda_ringan;
                            break;
                        case 'sedang':
                            $dendaPerUnit = $barang->denda_sedang;
                            break;
                        case 'parah':
                            $dendaPerUnit = $barang->denda_parah;
                            break;
                        case 'baik':
                        default:
                            $dendaPerUnit = 0;
                            break;
                    }
                    
                    $totalItemPenalty += $dendaPerUnit;
                    
                    // Track conditions for summary
                    if (!isset($conditionsSummary[$kondisi])) {
                        $conditionsSummary[$kondisi] = 0;
                    }
                    $conditionsSummary[$kondisi]++;
                }
                
                            $totalDenda += $totalItemPenalty;
            
            // Create summary of conditions for keterangan
            $conditionsText = implode(', ', array_map(function($count, $condition) {
                return "{$count} unit {$condition}";
            }, $conditionsSummary, array_keys($conditionsSummary)));
            
            // Create pengembalian_barang record with average condition
            \Log::info('Before calculateAverageCondition', ['conditions_summary' => $conditionsSummary]);
            $averageCondition = $this->calculateAverageCondition($conditionsSummary);
            \Log::info('Average condition calculated', ['average_condition' => $averageCondition]);
            
            PengembalianBarang::create([
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'id_barang' => $itemData['id_barang'],
                'jumlah_kembali' => $itemData['jumlah_kembali'],
                'kondisi_barang' => $averageCondition,
                'denda_kerusakan' => $totalItemPenalty,
                'keterangan_kerusakan' => "Kondisi per unit: {$conditionsText}"
            ]);
            
            // Store item for potential stock return (don't return immediately)
            $itemsToReturn[] = [
                'barang' => $barang,
                'jumlah' => $itemData['jumlah_kembali']
            ];
        }
        
        // Calculate late penalty (fixed amount)
        $dendaTelat = $isLate ? 250000 : 0;
        $totalAllDenda = $totalDenda + $dendaTelat;
        
        // Update pengembalian base data - fix auth guard issue
        $adminId = auth()->guard('admin')->check() ? auth()->guard('admin')->id() : auth()->id();
        
        \Log::info('Admin ID check in processReturnSubmit', [
            'admin_guard_check' => auth()->guard('admin')->check(),
            'admin_guard_id' => auth()->guard('admin')->id(),
            'default_auth_id' => auth()->id(),
            'admin_id_used' => $adminId
        ]);
        
        \Log::info('About to update pengembalian', [
            'total_denda' => $totalAllDenda,
            'denda_telat' => $dendaTelat,
            'admin_id' => $adminId
        ]);
        
        $pengembalian->update([
            'tanggal_pengembalian_aktual' => now(),
            'total_denda' => $totalAllDenda,
            'denda_telat' => $dendaTelat,
            'hari_telat' => $daysLate,
            'notes_admin' => null,
            'verified_by' => $adminId
        ]);
        
        \Log::info('Pengembalian updated successfully');
            
            // Determine flow based on penalty amount
            if ($totalAllDenda == 0) {
                // No penalty - complete immediately and return stock
                $pengembalian->update(['status_pengembalian' => 'completed']);
                $peminjaman->update([
                    'status_peminjaman' => 'returned'
                ]);
                
                // Return stock based on condition
                $this->updateStockAfterReturn($pengembalian);
                
                $message = "Pengembalian berhasil diproses tanpa denda. Stok telah diperbarui sesuai kondisi barang.";
            } else {
                // Has penalty - wait for user payment, don't return stock yet
                $pengembalian->update(['status_pengembalian' => 'payment_required']);
                // Don't update peminjaman status yet - keep as 'ongoing'
                // Don't return stock yet - wait for payment verification
                
                $message = "Verifikasi pengembalian selesai. User perlu membayar denda sebesar Rp " . number_format($totalAllDenda, 0, ',', '.') . " dan mengupload bukti pembayaran.";
            }
            
            \Log::info('About to commit transaction and redirect', ['message' => $message]);
            
            DB::commit();
            
            \Log::info('Transaction committed successfully, redirecting');
            
            return redirect()->route('superadmin.pengembalian.index')
                ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in processReturnSubmit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update pending return request with admin verification
     */
    public function processReturnUpdate(Request $request, $pengembalianId)
    {
        $request->validate([
            'is_late' => 'required|boolean',
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah_kembali' => 'required|integer|min:1',
            'items.*.units' => 'required|array',
            'items.*.units.*.kondisi_barang' => 'required|in:baik,ringan,sedang,parah'
        ]);
        
        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::with(['peminjaman.peminjamanBarangs.barang'])
                ->where('status_pengembalian', 'pending')
                ->findOrFail($pengembalianId);
            
            $peminjaman = $pengembalian->peminjaman;
            
            // Delete existing pengembalian_barang records
            $pengembalian->pengembalianBarangs()->delete();
            
            $totalDenda = 0;
            $isLate = (bool) $request->is_late;
            $daysLate = $isLate ? 1 : 0; // Set to 1 if late for display purposes
            $itemsToReturn = []; // Store items for potential stock return
            
            // Process each item
            foreach ($request->items as $itemData) {
                $barang = Barang::findOrFail($itemData['id_barang']);
                $peminjamanBarang = $peminjaman->peminjamanBarangs()
                    ->where('id_barang', $itemData['id_barang'])
                    ->first();
                
                if (!$peminjamanBarang) {
                    throw new \Exception("Barang {$barang->nama_barang} tidak ditemukan dalam peminjaman ini.");
                }
                
                // Validate that units count matches jumlah_kembali
                $expectedUnits = $itemData['jumlah_kembali'];
                $actualUnits = count($itemData['units']);
                if ($actualUnits !== $expectedUnits) {
                    throw new \Exception("Jumlah unit kondisi ({$actualUnits}) tidak sesuai dengan jumlah dikembalikan ({$expectedUnits}) untuk barang {$barang->nama_barang}.");
                }
                
                // Calculate penalty for each unit
                $totalItemPenalty = 0;
                $conditionsSummary = [];
                
                foreach ($itemData['units'] as $unitData) {
                    $kondisi = $unitData['kondisi_barang'];
                    
                    // Calculate penalty for this unit
                    $dendaPerUnit = 0;
                    switch ($kondisi) {
                        case 'ringan':
                            $dendaPerUnit = $barang->denda_ringan;
                            break;
                        case 'sedang':
                            $dendaPerUnit = $barang->denda_sedang;
                            break;
                        case 'parah':
                            $dendaPerUnit = $barang->denda_parah;
                            break;
                        case 'baik':
                        default:
                            $dendaPerUnit = 0;
                            break;
                    }
                    
                    $totalItemPenalty += $dendaPerUnit;
                    
                    // Track conditions for summary
                    if (!isset($conditionsSummary[$kondisi])) {
                        $conditionsSummary[$kondisi] = 0;
                    }
                    $conditionsSummary[$kondisi]++;
                }
                
                $totalDenda += $totalItemPenalty;
                
                // Create summary of conditions for keterangan
                $conditionsText = implode(', ', array_map(function($count, $condition) {
                    return "{$count} unit {$condition}";
                }, $conditionsSummary, array_keys($conditionsSummary)));
                
                // Create pengembalian_barang record with average condition
                $averageCondition = $this->calculateAverageCondition($conditionsSummary);
                
                PengembalianBarang::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_barang' => $itemData['id_barang'],
                    'jumlah_kembali' => $itemData['jumlah_kembali'],
                    'kondisi_barang' => $averageCondition,
                    'denda_kerusakan' => $totalItemPenalty,
                    'keterangan_kerusakan' => "Kondisi per unit: {$conditionsText}"
                ]);
                
                // Store item for potential stock return (don't return immediately)
                $itemsToReturn[] = [
                    'barang' => $barang,
                    'jumlah' => $itemData['jumlah_kembali']
                ];
            }
            
            // Calculate late penalty (fixed amount)
            $dendaTelat = $isLate ? 250000 : 0;
            $totalAllDenda = $totalDenda + $dendaTelat;
            
            // Update pengembalian base data
            $pengembalian->update([
                'tanggal_pengembalian_aktual' => now(),
                'total_denda' => $totalAllDenda,
                'denda_telat' => $dendaTelat,
                'hari_telat' => $daysLate,
                'notes_admin' => null,
                'verified_by' => auth()->guard('admin')->id()
            ]);
            
            // Determine flow based on penalty amount
            if ($totalAllDenda == 0) {
                // No penalty - complete immediately and return stock
                $pengembalian->update(['status_pengembalian' => 'completed']);
                $peminjaman->update([
                    'status_peminjaman' => 'returned'
                ]);
                
                // Return stock to inventory based on condition
                $this->updateStockAfterReturn($pengembalian);
                
                $message = "Pengembalian berhasil diproses tanpa denda. Stok telah diperbarui sesuai kondisi barang.";
            } else {
                // Has penalty - wait for user payment, don't return stock yet
                $pengembalian->update(['status_pengembalian' => 'payment_required']);
                // Don't update peminjaman status yet - keep as 'ongoing'
                // Don't return stock yet - wait for payment verification
                
                $message = "Verifikasi pengembalian selesai. User perlu membayar denda sebesar Rp " . number_format($totalAllDenda, 0, ',', '.') . " dan mengupload bukti pembayaran.";
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.pengembalian.index')
                ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show return details
     */
    public function show($id)
    {
        $pengembalian = Pengembalian::with([
            'peminjaman.user', 
            'pengembalianBarangs.barang',
            'verifiedBy'
        ])->findOrFail($id);
        
        return view('superadmin.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Show penalty payment verification page
     */
    public function showPenaltyVerification($id)
    {
        $pengembalian = Pengembalian::with([
            'peminjaman.user', 
            'pengembalianBarangs.barang',
            'verifiedBy'
        ])
        ->where('status_pengembalian', 'payment_uploaded')
        ->where('status_pembayaran_denda', 'uploaded')
        ->findOrFail($id);
        
        return view('superadmin.pengembalian.penalty-verification', compact('pengembalian'));
    }

    /**
     * Verify penalty payment (approve/reject)
     */
    public function verifyPenaltyPayment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_verifikasi' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::with(['peminjaman', 'pengembalianBarangs.barang'])
                ->where('status_pengembalian', 'payment_uploaded')
                ->where('status_pembayaran_denda', 'uploaded')
                ->findOrFail($id);

            $peminjaman = $pengembalian->peminjaman;
            $admin = auth()->guard('admin')->user();

            if ($request->action === 'approve') {
                \Log::info('PengembalianController verifyPenaltyPayment - payment approved', [
                    'pengembalian_id' => $pengembalian->id_pengembalian,
                    'current_status_pengembalian' => $pengembalian->status_pengembalian,
                    'caller' => 'PengembalianController::verifyPenaltyPayment'
                ]);
                
                // Payment approved - complete the return process
                $pengembalian->update([
                    'status_pengembalian' => 'fully_completed',
                    'status_pembayaran_denda' => 'verified',
                    'verified_payment_by' => $admin->id_admin,
                    'verified_payment_at' => now(),
                    'catatan_pembayaran' => $request->catatan_verifikasi
                ]);

                // Update peminjaman status
                $peminjaman->update([
                    'status_peminjaman' => 'returned'
                ]);

                \Log::info('About to call updateStockAfterReturn from PengembalianController', [
                    'pengembalian_id' => $pengembalian->id_pengembalian,
                    'caller' => 'PengembalianController::verifyPenaltyPayment'
                ]);

                // Return stock to inventory based on condition
                $this->updateStockAfterReturn($pengembalian);

                \Log::info('Stock update completed via PengembalianController', [
                    'pengembalian_id' => $pengembalian->id_pengembalian
                ]);

                $message = "Pembayaran denda berhasil diverifikasi. Pengembalian telah selesai sepenuhnya dan stok diperbarui sesuai kondisi barang.";
                
            } else {
                // Payment rejected - ask user to reupload
                $pengembalian->update([
                    'status_pengembalian' => 'payment_required',
                    'status_pembayaran_denda' => 'rejected',
                    'verified_payment_by' => $admin->id_admin,
                    'verified_payment_at' => now(),
                    'catatan_pembayaran' => $request->catatan_verifikasi,
                    'bukti_pembayaran_denda' => null, // Clear previous proof
                    'tanggal_upload_pembayaran' => null
                ]);

                $message = "Pembayaran denda ditolak. User akan diminta untuk upload ulang bukti pembayaran.";
            }

            DB::commit();

            return redirect()->route('superadmin.pengembalian.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Public wrapper for updateStockAfterReturn method
     * Called from TransaksiController when verifying penalty payments
     */
    public function updateStockAfterReturnPublic(Pengembalian $pengembalian)
    {
        return $this->updateStockAfterReturn($pengembalian);
    }
    
    /**
     * Updates stock based on the condition of returned items.
     * Severely damaged items ('parah') are removed from total stock.
     * Other items are returned to available stock.
     */
    private function updateStockAfterReturn(Pengembalian $pengembalian)
    {
        // Prevent duplicate stock updates by checking if stock has already been returned
        $cacheKey = "stock_returned_pengembalian_{$pengembalian->id_pengembalian}";
        
        // Check if stock has already been returned for this pengembalian
        if (\Cache::has($cacheKey)) {
            \Log::warning('Duplicate stock update attempt prevented', [
                'pengembalian_id' => $pengembalian->id_pengembalian,
                'cache_key' => $cacheKey
            ]);
            return;
        }
        
        // Mark that stock update is in progress (prevent race conditions)
        \Cache::put($cacheKey, true, 300); // 5 minutes cache
        
        \Log::info('Starting stock update after return', [
            'pengembalian_id' => $pengembalian->id_pengembalian,
            'status_pengembalian' => $pengembalian->status_pengembalian
        ]);

        // Ensure relationships are loaded to be safe
        $pengembalian->loadMissing('pengembalianBarangs.barang');

        foreach ($pengembalian->pengembalianBarangs as $item) {
            // Check if barang relationship is loaded
            if (!$item->barang) continue;

            $jumlahKembali = $item->jumlah_kembali;
            $barang = $item->barang;
            
            $oldStokTersedia = $barang->stok_tersedia;
            $oldStokTotal = $barang->stok_total;

            if ($item->kondisi_barang === 'parah') {
                // Item is severely damaged/lost.
                // Decrease total stock. Available stock is not affected as it was already decremented on loan.
                $barang->decrement('stok_total', $jumlahKembali);

                // Ensure available stock is not higher than total stock
                if ($barang->stok_tersedia > $barang->stok_total) {
                    $barang->stok_tersedia = $barang->stok_total;
                    $barang->save();
                }
                
                \Log::info('Stock updated for damaged item', [
                    'barang_id' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'kondisi' => $item->kondisi_barang,
                    'jumlah_kembali' => $jumlahKembali,
                    'old_stok_total' => $oldStokTotal,
                    'new_stok_total' => $barang->fresh()->stok_total,
                    'stok_tersedia' => $barang->fresh()->stok_tersedia
                ]);

            } else {
                // Item is returned in a usable condition.
                // Return to available stock.
                $barang->increment('stok_tersedia', $jumlahKembali);
                
                \Log::info('Stock updated for returned item', [
                    'barang_id' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'kondisi' => $item->kondisi_barang,
                    'jumlah_kembali' => $jumlahKembali,
                    'old_stok_tersedia' => $oldStokTersedia,
                    'new_stok_tersedia' => $barang->fresh()->stok_tersedia,
                    'stok_total' => $barang->stok_total
                ]);
            }
        }
        
        \Log::info('Stock update completed', [
            'pengembalian_id' => $pengembalian->id_pengembalian
        ]);
    }
    
    /**
     * Get return statistics for API/AJAX
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        
        $startDate = $period === 'week' 
            ? Carbon::now()->startOfWeek()
            : Carbon::now()->startOfMonth();
        
        return response()->json([
            'returns_this_period' => Pengembalian::where('created_at', '>=', $startDate)->count(),
            'total_penalties' => Pengembalian::where('status_pengembalian', 'completed')
                ->where('created_at', '>=', $startDate)
                ->sum('total_denda'),
            'pending_returns' => Peminjaman::where('status_peminjaman', 'ongoing')
                ->whereDoesntHave('pengembalian')
                ->count(),
            'overdue_returns' => Peminjaman::where('status_peminjaman', 'ongoing')
                ->whereDoesntHave('pengembalian')
                ->where('tanggal_selesai', '<', now())
                ->count()
        ]);
    }
    
    /**
     * Export return data to CSV
     */
    public function export(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'pengembalianBarangs.barang']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status_pengembalian', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->end_date);
        }
        
        $pengembalian = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'pengembalian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($pengembalian) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID Pengembalian',
                'Kode Peminjaman',
                'Nama User',
                'Tanggal Pengembalian',
                'Status',
                'Total Denda',
                'Jumlah Items',
                'Catatan Admin'
            ]);
            
            foreach ($pengembalian as $p) {
                fputcsv($file, [
                    $p->id_pengembalian,
                    $p->peminjaman->kode_peminjaman ?? '',
                    $p->peminjaman->user->nama_penanggung_jawab ?? '',
                    $p->tanggal_pengembalian ? $p->tanggal_pengembalian->format('Y-m-d') : '',
                    $p->status_pengembalian,
                    $p->total_denda,
                    $p->pengembalianBarangs->count(),
                    $p->notes_admin ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Calculate average condition from conditions summary
     */
    private function calculateAverageCondition($conditionsSummary)
    {
        // Priority: parah > sedang > ringan > baik
        $priorities = ['parah' => 4, 'sedang' => 3, 'ringan' => 2, 'baik' => 1];
        
        $totalWeight = 0;
        $totalCount = 0;
        
        foreach ($conditionsSummary as $condition => $count) {
            $weight = $priorities[$condition] ?? 1;
            $totalWeight += $weight * $count;
            $totalCount += $count;
        }
        
        if ($totalCount === 0) {
            return 'baik';
        }
        
        $averageWeight = $totalWeight / $totalCount;
        
        // Determine condition based on average weight
        if ($averageWeight >= 3.5) {
            return 'parah';
        } elseif ($averageWeight >= 2.5) {
            return 'sedang';
        } elseif ($averageWeight >= 1.5) {
            return 'ringan';
        } else {
            return 'baik';
        }
    }
}
