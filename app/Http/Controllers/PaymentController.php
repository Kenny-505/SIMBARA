<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Show payment status for user
     */
    public function status(Request $request)
    {
        $user = Auth::guard('user')->user();
        
        $query = Peminjaman::with(['peminjamanBarangs.barang.admin', 'transaksi'])
            ->where('id_user', $user->id_user);
        
        // Filter by payment status
        if ($request->filled('status')) {
            if ($request->status === 'waiting_payment') {
                $query->where('status_pengajuan', 'confirmed')
                      ->where('status_pembayaran', 'pending');
            } elseif ($request->status === 'waiting_verification') {
                $query->where('status_pembayaran', 'waiting_verification');
            } elseif ($request->status === 'verified') {
                $query->where('status_pembayaran', 'verified');
            }
        }
        
        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get payment statistics for user
        $stats = [
            'total_payments' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->count(),
            'pending_payments' => Peminjaman::where('id_user', $user->id_user)
                ->where('status_pengajuan', 'confirmed')
                ->where('status_pembayaran', 'pending')
                ->count(),
            'waiting_verification' => Peminjaman::where('id_user', $user->id_user)
                ->where('status_pembayaran', 'waiting_verification')
                ->count(),
            'verified_payments' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'approved')->count(),
        ];
        
        return view('payment.status', compact('peminjaman', 'stats'));
    }
    
    /**
     * Show payment summary for specific peminjaman
     */
    public function summary($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with(['peminjamanBarangs.barang.admin', 'transaksi'])
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();
        
        // Only allow non-civitas users or users with payment requirements
        if ($user->role->nama_role === 'user_fmipa' && $peminjaman->total_biaya <= 0) {
            return redirect()->route('user.peminjaman.index')
                ->with('info', 'Anda tidak perlu melakukan pembayaran untuk peminjaman ini.');
        }
        
        return view('payment.summary', compact('peminjaman'));
    }
    
    /**
     * Upload payment proof (delegated to PeminjamanController for consistency)
     */
    public function upload(Request $request, $id)
    {
        // Redirect to existing upload method in PeminjamanController
        return app(PeminjamanController::class)->uploadPayment($request, $id);
    }
    
    /**
     * Show payment history for user
     */
    public function history(Request $request)
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
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transaksi = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('payment.history', compact('transaksi'));
    }
    
    /**
     * Get payment statistics for AJAX requests
     */
    public function getStats()
    {
        $user = Auth::guard('user')->user();
        
        $stats = [
            'total_amount' => Transaksi::whereHas('peminjaman', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->where('status_verifikasi', 'approved')->sum('nominal'),
            'pending_amount' => Peminjaman::where('id_user', $user->id_user)
                ->where('status_pengajuan', 'confirmed')
                ->where('status_pembayaran', 'pending')
                ->sum('total_biaya'),
            'waiting_verification_count' => Peminjaman::where('id_user', $user->id_user)
                ->where('status_pembayaran', 'waiting_verification')
                ->count(),
        ];
        
        return response()->json($stats);
    }
}
