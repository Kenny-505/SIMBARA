<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display list of transactions for verification
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['peminjaman.user', 'pengembalian.peminjaman.user', 'verifiedBy']);
        
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
            $query->whereDate('tanggal_pembayaran', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pembayaran', '<=', $request->end_date);
        }
        
        // Search by kode peminjaman or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($mainQuery) use ($search) {
                // Search in peminjaman transactions
                $mainQuery->whereHas('peminjaman', function($q) use ($search) {
                    $q->where('kode_peminjaman', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('nama_penanggung_jawab', 'like', "%{$search}%");
                      });
                })
                // Or search in denda transactions via pengembalian
                ->orWhereHas('pengembalian.peminjaman', function($q) use ($search) {
                    $q->where('kode_peminjaman', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('nama_penanggung_jawab', 'like', "%{$search}%");
                      });
                })
                // Or search by user directly for both types
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('nama_penanggung_jawab', 'like', "%{$search}%");
                });
            });
        }
        
        $transaksi = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Transaksi::count(),
            'pending' => Transaksi::where('status_verifikasi', 'pending')->count(),
            'verified' => Transaksi::where('status_verifikasi', 'approved')->count(),
            'rejected' => Transaksi::where('status_verifikasi', 'rejected')->count(),
            'total_revenue' => Transaksi::where('status_verifikasi', 'approved')->sum('nominal')
        ];
        
        return view('superadmin.transaksi', compact('transaksi', 'stats'));
    }
    
    /**
     * Show transaction detail for verification
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['peminjaman.user', 'peminjaman.peminjamanBarangs.barang', 'verifiedBy'])
            ->findOrFail($id);
            
        return view('superadmin.transaksi.detail', compact('transaksi'));
    }
    
    /**
     * Verify payment proof
     */
    public function verify(Request $request, $id)
    {
        return $this->updateStatus($id, 'approved', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Reject payment proof
     */
    public function reject(Request $request, $id)
    {
        return $this->updateStatus($id, 'rejected', 'Pembayaran ditolak.');
    }

    /**
     * Update transaction status
     *
     * @param int $id
     * @param string $status
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    private function updateStatus($id, $status, $successMessage)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            if ($transaksi->status_verifikasi !== 'pending') {
                return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
            }

            $transaksi->status_verifikasi = $status;
            $transaksi->tanggal_verifikasi = now();
            $transaksi->verified_by = Auth::guard('admin')->id();
            $transaksi->notes_admin = $status === 'rejected' ? 'Pembayaran ditolak oleh Super Admin.' : null;
            $transaksi->save();

            // Update peminjaman or pengembalian status if payment verified
            if ($status === 'approved') {
                if ($transaksi->jenis_transaksi === 'sewa') {
                    // Handle sewa payment
                    $peminjaman = $transaksi->peminjaman;
                    $peminjaman->update([
                        'status_pembayaran' => 'verified'
                    ]);
                    
                    // If all items are approved and payment verified, set to confirmed and ongoing
                    $allApproved = $peminjaman->peminjamanBarangs()
                        ->where('status_persetujuan', '!=', 'approved')
                        ->count() === 0;
                        
                    if ($allApproved && in_array($peminjaman->status_pengajuan, ['approved', 'confirmed'])) {
                        // Set to confirmed if not already
                        if ($peminjaman->status_pengajuan === 'approved') {
                            $peminjaman->update(['status_pengajuan' => 'confirmed']);
                        }
                        
                        // Process confirmed loan using PeminjamanController to avoid duplication
                        app(\App\Http\Controllers\PeminjamanController::class)->processConfirmedLoanPublic($peminjaman);
                    }
                } else {
                    // Handle denda payment - complete the return process
                    $pengembalian = $transaksi->pengembalian;
                    $peminjaman = $pengembalian->peminjaman;
                    
                    \Log::info('TransaksiController processing denda payment approval', [
                        'transaksi_id' => $transaksi->id_transaksi,
                        'pengembalian_id' => $pengembalian->id_pengembalian,
                        'peminjaman_id' => $peminjaman->id_peminjaman,
                        'current_status_pengembalian' => $pengembalian->status_pengembalian
                    ]);
                    
                    // Update pengembalian status to fully_completed
                    $pengembalian->update([
                        'status_pengembalian' => 'fully_completed',
                        'status_pembayaran_denda' => 'verified'
                    ]);
                    
                    // Update peminjaman status to returned
                    $peminjaman->update([
                        'status_peminjaman' => 'returned'
                    ]);
                    
                    \Log::info('About to call updateStockAfterReturnPublic from TransaksiController', [
                        'pengembalian_id' => $pengembalian->id_pengembalian,
                        'caller' => 'TransaksiController::updateStatus'
                    ]);
                    
                    // Return stock using proper logic that considers item condition
                    // Call the method from PengembalianController to maintain consistency
                    $pengembalianController = app(\App\Http\Controllers\SuperAdmin\PengembalianController::class);
                    $pengembalianController->updateStockAfterReturnPublic($pengembalian);
                    
                    \Log::info('Stock update completed via TransaksiController', [
                        'pengembalian_id' => $pengembalian->id_pengembalian
                    ]);
                }
            } elseif ($status === 'rejected') {
                // Handle rejection based on transaction type
                if ($transaksi->jenis_transaksi === 'sewa') {
                    // Untuk sewa, hanya update status dan catatan, JANGAN hapus bukti pembayaran
                    $transaksi->update([
                        'notes_admin' => 'Pembayaran sewa ditolak oleh Super Admin. Silakan upload ulang bukti pembayaran.'
                    ]);
                    $successMessage = "Pembayaran sewa ditolak. User akan diminta untuk upload ulang bukti pembayaran.";
                } else {
                    // Handle denda payment rejection - reset pengembalian for re-upload
                    $pengembalian = $transaksi->pengembalian;
                    $admin = auth()->guard('admin')->user();
                    
                    $pengembalian->update([
                        'status_pengembalian' => 'payment_required',
                        'status_pembayaran_denda' => 'rejected',
                        'verified_payment_by' => $admin->id_admin,
                        'verified_payment_at' => now(),
                        'catatan_pembayaran' => 'Pembayaran denda ditolak oleh Super Admin. Silakan upload ulang bukti pembayaran.',
                        'bukti_pembayaran_denda' => null, // Clear previous proof
                        'tanggal_upload_pembayaran' => null
                    ]);
                    
                    $successMessage = "Pembayaran denda ditolak. User akan diminta untuk upload ulang bukti pembayaran.";
                }
            }

            DB::commit();

            // Determine redirect based on transaction type
            if ($transaksi->jenis_transaksi === 'denda') {
                // For penalty payments, redirect to pengembalian index
                return redirect()->route('superadmin.pengembalian.index')->with('success', $successMessage);
            } else {
                // For regular payments, go back to previous page (transaksi)
                return back()->with('success', $successMessage);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }
    
    /**
     * Display payment proof image
     */
    public function showPaymentProof($id)
    {
        $transaksi = Transaksi::with('pengembalian')->findOrFail($id);
        
        $buktiPembayaran = null;
        $filename = 'bukti_pembayaran_' . $id . '.jpg';
        
        // Determine where to get the payment proof based on transaction type
        if ($transaksi->jenis_transaksi === 'sewa') {
            // For sewa payments, get from transaksi table
            $buktiPembayaran = $transaksi->bukti_pembayaran;
            
            // Check if it's dummy data from migration
            if ($buktiPembayaran === 'dummy_payment_proof') {
                $message = "Bukti pembayaran belum tersedia.\n\nData ini berasal dari migrasi.\nUser perlu mengupload ulang bukti pembayaran yang sebenarnya.";
                return response($message, 404)->header('Content-Type', 'text/plain');
            }
        } else if ($transaksi->jenis_transaksi === 'denda') {
            // For denda payments, get from pengembalian table
            if ($transaksi->pengembalian && $transaksi->pengembalian->bukti_pembayaran_denda) {
                $buktiPembayaran = base64_decode($transaksi->pengembalian->bukti_pembayaran_denda);
                $filename = 'bukti_pembayaran_denda_' . $id . '.jpg';
            }
        }
        
        if (!$buktiPembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }
        
        // For real image data, detect content type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($buktiPembayaran);
        
        // Default to jpeg if detection fails
        if (!$mimeType || strpos($mimeType, 'image/') !== 0) {
            $mimeType = 'image/jpeg';
        }
        
        return response($buktiPembayaran)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    
    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $query = Transaksi::with(['peminjaman.user']);
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pembayaran', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pembayaran', '<=', $request->end_date);
        }
        
        $transaksi = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'transaksi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transaksi) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID Transaksi',
                'Kode Peminjaman', 
                'Nama User',
                'Jenis Transaksi',
                'Jumlah Bayar',
                'Tanggal Bayar',
                'Status Verifikasi',
                'Tanggal Verifikasi',
                'Catatan Admin'
            ]);
            
            foreach ($transaksi as $t) {
                fputcsv($file, [
                    $t->id_transaksi,
                    $t->peminjaman->kode_peminjaman ?? '',
                    $t->peminjaman->user->nama_penanggung_jawab ?? '',
                    $t->jenis_transaksi,
                    $t->nominal,
                    $t->tanggal_pembayaran ? $t->tanggal_pembayaran->format('Y-m-d H:i:s') : '',
                    $t->status_verifikasi,
                    $t->tanggal_verifikasi ? $t->tanggal_verifikasi->format('Y-m-d H:i:s') : '',
                    $t->notes_admin ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get transaction statistics via AJAX
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month');
        
        $startDate = $period === 'week' 
            ? Carbon::now()->startOfWeek()
            : Carbon::now()->startOfMonth();
        
        return response()->json([
            'total_revenue_period' => Transaksi::where('status_verifikasi', 'approved')
                ->where('created_at', '>=', $startDate)
                ->sum('nominal'),
            'pending_count' => Transaksi::where('status_verifikasi', 'pending')->count(),
            'verified_today' => Transaksi::where('status_verifikasi', 'approved')
                ->whereDate('tanggal_verifikasi', today())
                ->count()
        ]);
    }
}
