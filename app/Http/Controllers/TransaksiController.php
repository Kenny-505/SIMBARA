<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display user's transactions
     */
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        
        $query = Transaksi::with(['peminjaman.peminjamanBarangs.barang'])
            ->whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            });
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        
        // Filter by jenis transaksi
        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transaksi = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get user's transaction statistics
        $stats = [
            'total_transactions' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->count(),
            'pending_transactions' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'pending')->count(),
            'approved_transactions' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'approved')->count(),
            'total_amount' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'approved')->sum('nominal'),
        ];
        
        return view('user.transaksi', compact('transaksi', 'stats'));
    }
    
    /**
     * Show transaction detail
     */
    public function show($id)
    {
        $user = Auth::guard('user')->user();
        
        $transaksi = Transaksi::with(['peminjaman.peminjamanBarangs.barang.admin', 'peminjaman.user'])
            ->whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->findOrFail($id);
        
        return view('user.transaksi-detail', compact('transaksi'));
    }
    
    /**
     * Create transaction record for payment
     */
    public function create(Request $request)
    {
        $user = Auth::guard('user')->user();
        
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'nominal' => 'required|numeric|min:0',
            'jenis_transaksi' => 'required|in:peminjaman,denda'
        ]);
        
        $peminjaman = Peminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->where('id_user', $user->id_user)
            ->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Create transaction record
            $transaksi = Transaksi::create([
                'id_user' => $user->id_user,
                'id_peminjaman' => $request->id_peminjaman,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal' => $request->nominal,
                'status_verifikasi' => 'pending',
                'tanggal_pembayaran' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('user.payment.summary', $request->id_peminjaman)
                ->with('success', 'Transaksi berhasil dibuat. Silakan upload bukti pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }
    
    /**
     * Get transaction statistics for AJAX
     */
    public function getStats()
    {
        $user = Auth::guard('user')->user();
        
        $stats = [
            'monthly_spending' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->where('status_verifikasi', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('nominal'),
            
            'yearly_spending' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->where('status_verifikasi', 'approved')
            ->whereYear('created_at', now()->year)
            ->sum('nominal'),
            
            'pending_payments' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'pending')->count(),
        ];
        
        return response()->json($stats);
    }
}
