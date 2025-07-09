<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\PengembalianBarang;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    /**
     * Display user's return requests and history
     */
    public function index(Request $request)
    {
        $user = auth()->guard('user')->user();
        
        // Get user's peminjaman that are ongoing (can be returned)
        $ongoingPeminjaman = Peminjaman::where('id_user', $user->id_user)
            ->where('status_peminjaman', 'ongoing')
            ->whereDoesntHave('pengembalian')
            ->with(['peminjamanBarangs.barang'])
            ->orderBy('tanggal_selesai', 'asc')
            ->get();
        
        // Get user's return history
        $returnHistory = Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })
            ->with(['peminjaman', 'pengembalianBarangs.barang'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate overdue items
        $overdueItems = $ongoingPeminjaman->filter(function($peminjaman) {
            return Carbon::parse($peminjaman->tanggal_selesai)->isPast();
        });
        
        return view('user.pengembalian', compact('ongoingPeminjaman', 'returnHistory', 'overdueItems'));
    }
    
    /**
     * Show return request form for specific peminjaman
     */
    public function create($peminjamanId)
    {
        $user = auth()->guard('user')->user();
        
        $peminjaman = Peminjaman::where('id_user', $user->id_user)
            ->where('id_peminjaman', $peminjamanId)
            ->where('status_peminjaman', 'ongoing')
            ->whereDoesntHave('pengembalian')
            ->with(['peminjamanBarangs.barang'])
            ->firstOrFail();
        
        // Calculate if there's any lateness
        $tanggalSelesai = Carbon::parse($peminjaman->tanggal_selesai);
        $isLate = $tanggalSelesai->isPast();
        $daysLate = $isLate ? $tanggalSelesai->diffInDays(now()) : 0;
        
        // Calculate estimated penalties
        $estimatedPenalties = [];
        foreach ($peminjaman->peminjamanBarangs as $peminjamanBarang) {
            $barang = $peminjamanBarang->barang;
            $estimatedPenalties[$barang->id_barang] = $barang->getAllPenaltyRates($daysLate);
        }
        
        return view('user.pengembalian.create', compact('peminjaman', 'isLate', 'daysLate', 'estimatedPenalties'));
    }
    
    /**
     * Store return request from user
     */
    public function store(Request $request, $peminjamanId)
    {
        $request->validate([
            'tanggal_pengembalian' => 'required|date|after_or_equal:today',
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah_kembali' => 'required|integer|min:1',
            'items.*.kondisi_barang' => 'required|in:baik,ringan,sedang,parah',
            'items.*.catatan_user' => 'nullable|string|max:500',
            'notes_user' => 'nullable|string|max:1000'
        ]);
        
        $user = auth()->guard('user')->user();
        
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('id_user', $user->id_user)
                ->where('id_peminjaman', $peminjamanId)
                ->where('status_peminjaman', 'ongoing')
                ->whereDoesntHave('pengembalian')
                ->with('peminjamanBarangs.barang')
                ->firstOrFail();
            
            // Validate that all items belong to this peminjaman
            foreach ($request->items as $itemData) {
                $peminjamanBarang = $peminjaman->peminjamanBarangs()
                    ->where('id_barang', $itemData['id_barang'])
                    ->first();
                
                if (!$peminjamanBarang) {
                    throw new \Exception("Barang tidak ditemukan dalam peminjaman ini.");
                }
                
                if ($itemData['jumlah_kembali'] > $peminjamanBarang->jumlah_pinjam) {
                    throw new \Exception("Jumlah pengembalian tidak boleh melebihi jumlah peminjaman.");
                }
            }
            
            // Create pengembalian record with pending status
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status_pengembalian' => 'pending',
                'notes_user' => $request->notes_user,
                'created_at' => now()
            ]);
            
            // Calculate estimated penalties
            $totalEstimatedDenda = 0;
            $tanggalSeharusnyaSelesai = Carbon::parse($peminjaman->tanggal_selesai);
            $tanggalPengembalian = Carbon::parse($request->tanggal_pengembalian);
            $isLate = $tanggalPengembalian->gt($tanggalSeharusnyaSelesai);
            $daysLate = $isLate ? $tanggalSeharusnyaSelesai->diffInDays($tanggalPengembalian) : 0;
            
            // Process each item
            foreach ($request->items as $itemData) {
                $barang = Barang::findOrFail($itemData['id_barang']);
                
                // Calculate estimated penalty
                $estimatedDenda = $barang->calculatePenalty($itemData['kondisi_barang'], $daysLate);
                $subtotalEstimated = $estimatedDenda * $itemData['jumlah_kembali'];
                $totalEstimatedDenda += $subtotalEstimated;
                
                // Create pengembalian_barang record
                PengembalianBarang::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_barang' => $itemData['id_barang'],
                    'jumlah_kembali' => $itemData['jumlah_kembali'],
                    'kondisi_barang' => $itemData['kondisi_barang'],
                    'denda_per_item' => $estimatedDenda, // This will be updated by admin
                    'subtotal_denda' => $subtotalEstimated, // This will be updated by admin
                    'catatan_user' => $itemData['catatan_user'] ?? null
                ]);
            }
            
            // Update pengembalian with estimated total denda
            $pengembalian->update(['total_denda' => $totalEstimatedDenda]);
            
            DB::commit();
            
            return redirect()->route('user.pengembalian.index')
                ->with('success', 'Permintaan pengembalian berhasil diajukan. Menunggu verifikasi dari admin.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Submit return request directly without form (new simplified flow)
     */
    public function submitReturnRequest($peminjamanId)
    {
        $user = auth()->guard('user')->user();
        
        \Log::info('Return request submitted', [
            'user_id' => $user->id_user,
            'peminjaman_id' => $peminjamanId
        ]);
        
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('id_user', $user->id_user)
                ->where('id_peminjaman', $peminjamanId)
                ->where('status_peminjaman', 'ongoing')
                ->whereDoesntHave('pengembalian')
                ->with('peminjamanBarangs.barang')
                ->firstOrFail();
            
            \Log::info('Peminjaman found', ['peminjaman' => $peminjaman->toArray()]);
            
            // Create pengembalian record with pending status
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_pengembalian_aktual' => now(), // Set to current datetime
                'status_pengembalian' => 'pending',
                'notes_admin' => 'Pengajuan pengembalian otomatis oleh user',
                'total_denda' => 0.00
            ]);
            
            \Log::info('Pengembalian created', ['pengembalian_id' => $pengembalian->id_pengembalian]);
            
            // Process each item with default "baik" condition
            // Super Admin will update these later
            foreach ($peminjaman->peminjamanBarangs as $peminjamanBarang) {
                PengembalianBarang::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_barang' => $peminjamanBarang->id_barang,
                    'jumlah_kembali' => $peminjamanBarang->jumlah_pinjam, // Return all borrowed items
                    'kondisi_barang' => null, // Will be determined by Super Admin during verification
                    'denda_kerusakan' => 0.00, // Will be calculated by Super Admin
                    'keterangan_kerusakan' => null
                ]);
            }
            
            DB::commit();
            
            \Log::info('Return request completed successfully');
            
            return redirect()->route('user.pengembalian.index')
                ->with('success', 'Permintaan pengembalian berhasil diajukan. Super Admin akan melakukan verifikasi kondisi barang dan menghitung denda jika ada.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Return request failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show return request details
     */
    public function show($id)
    {
        $user = auth()->guard('user')->user();
        
        $pengembalian = Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })
            ->with([
                'peminjaman.user', 
                'pengembalianBarangs.barang',
                'processedBy'
            ])
            ->findOrFail($id);
        
        return view('user.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Show penalty payment form
     */
    public function showPenaltyPayment($id)
    {
        $user = auth()->guard('user')->user();
        
        $pengembalian = Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })
            ->where('status_pengembalian', 'payment_required')
            ->with([
                'peminjaman.user', 
                'pengembalianBarangs.barang'
            ])
            ->findOrFail($id);
        
        return view('user.pengembalian.penalty-payment', compact('pengembalian'));
    }

    /**
     * Upload penalty payment proof
     */
    public function uploadPenaltyPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'catatan_pembayaran' => 'nullable|string|max:1000'
        ]);

        $user = auth()->guard('user')->user();
        
        $pengembalian = Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })
            ->where('status_pengembalian', 'payment_required')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Convert uploaded image to base64
            $imageData = file_get_contents($request->file('bukti_pembayaran')->getRealPath());
            $base64Image = base64_encode($imageData);

            // Create transaction record for penalty payment
            $transaksi = \App\Models\Transaksi::create([
                'id_user' => $user->id_user,
                'id_peminjaman' => null, // This is for penalty, not sewa
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'jenis_transaksi' => 'denda',
                'nominal' => $pengembalian->total_denda,
                'bukti_pembayaran' => $base64Image,
                'status_verifikasi' => 'pending',
                'tanggal_pembayaran' => now(),
                'notes_admin' => $request->catatan_pembayaran
            ]);

            // Update pengembalian status
            $pengembalian->update([
                'status_pengembalian' => 'payment_uploaded'
            ]);

            DB::commit();

            return redirect()->route('user.pengembalian.show', $pengembalian->id_pengembalian)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi dari Super Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Cancel return request (only if still pending)
     */
    public function cancel($id)
    {
        $user = auth()->guard('user')->user();
        
        $pengembalian = Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })
            ->where('status_pengembalian', 'pending')
            ->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete pengembalian barang records
            $pengembalian->pengembalianBarangs()->delete();
            
            // Delete pengembalian record
            $pengembalian->delete();
            
            DB::commit();
            
            return redirect()->route('user.pengembalian.index')
                ->with('success', 'Permintaan pengembalian berhasil dibatalkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    
    /**
     * Get user's return statistics
     */
    public function getStats()
    {
        $user = auth()->guard('user')->user();
        
        return response()->json([
            'ongoing_peminjaman' => Peminjaman::where('id_user', $user->id_user)
                ->where('status_peminjaman', 'ongoing')
                ->whereDoesntHave('pengembalian')
                ->count(),
            'pending_returns' => Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                    $query->where('id_user', $user->id_user);
                })
                ->where('status_pengembalian', 'pending')
                ->count(),
            'completed_returns' => Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                    $query->where('id_user', $user->id_user);
                })
                ->where('status_pengembalian', 'completed')
                ->count(),
            'total_penalties' => Pengembalian::whereHas('peminjaman', function($query) use ($user) {
                    $query->where('id_user', $user->id_user);
                })
                ->where('status_pengembalian', 'completed')
                ->sum('total_denda')
        ]);
    }
} 