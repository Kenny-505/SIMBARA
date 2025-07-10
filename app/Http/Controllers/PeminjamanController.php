<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display list of user's peminjaman
     */
    public function index()
    {
        $user = Auth::guard('user')->user();
        
        $peminjamans = Peminjaman::with(['peminjamanBarangs.barang.admin', 'transaksi', 'pengembalian'])
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.peminjaman', compact('peminjamans'));
    }

    /**
     * Show pengajuan form
     */
    public function showPengajuanForm(Request $request)
    {
        $user = Auth::guard('user')->user();
        
        // Check if user has active loan
        $activeLoan = Peminjaman::where('id_user', $user->id_user)
            ->where(function($query) {
                $query->whereIn('status_pengajuan', ['draft', 'pending_approval', 'approved', 'confirmed'])
                      ->orWhere('status_peminjaman', 'ongoing');
            })
            ->exists();

        if ($activeLoan) {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Anda masih memiliki peminjaman aktif. Selesaikan peminjaman sebelumnya terlebih dahulu.');
        }

        // Get item if pre-selected
        $selectedItem = null;
        if ($request->filled('item_id')) {
            $selectedItem = Barang::with('admin')->findOrFail($request->item_id);
        }

        // Get all available items for selection
        $barangs = Barang::with('admin')
            ->where('is_active', true)
            ->where('stok_tersedia', '>', 0)
            ->orderBy('nama_barang', 'asc')
            ->get();

        return view('user.pengajuan-form', compact('selectedItem', 'barangs', 'request'));
    }

    /**
     * Display pengajuan list
     */
    public function showPengajuan()
    {
        $user = Auth::guard('user')->user();
        
        $peminjamans = Peminjaman::with(['peminjamanBarangs.barang.admin', 'pengembalian'])
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.pengajuan', compact('peminjamans'));
    }

    /**
     * Show pengajuan detail for confirmation
     */
    public function show($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with(['peminjamanBarangs' => function($query) {
                // Filter out items deleted by user
                $query->whereNull('user_action')->with('barang.admin');
            }])
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        $userType = $user->role->nama_role === 'user_non_fmipa' ? 'non_civitas' : 'civitas';

        return view('user.confirmation', compact('peminjaman', 'userType'));
    }

    /**
     * Show peminjaman detail for user
     */
    public function showPeminjamanDetail($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with(['peminjamanBarangs' => function($query) {
                // Filter out items deleted by user
                $query->whereNull('user_action')->with('barang.admin');
            }, 'transaksi'])
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();
        
        return view('user.peminjaman-detail', compact('peminjaman'));
    }

    /**
     * Store new peminjaman from cart
     */
    public function store(Request $request)
    {
        \Log::info('PeminjamanController store method called', [
            'request_data' => $request->all(),
            'user_id' => Auth::guard('user')->id()
        ]);
        
        $user = Auth::guard('user')->user();
        
        // Validate request
        $request->validate([
            'tujuan_peminjaman' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date|after_or_equal:' . Carbon::now()->addDays(3)->format('Y-m-d'),
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'nama_pengambil' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|max:50',
            'nomor_hp' => 'required|string|max:20'
        ], [
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman harus minimal H-3 dari hari ini',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam'
        ]);

        \Log::info('Validation passed');

        // Check if user has active loan
        $activeLoan = Peminjaman::where('id_user', $user->id_user)
            ->where(function($query) {
                $query->whereIn('status_pengajuan', ['draft', 'pending_approval', 'approved', 'confirmed'])
                      ->orWhere('status_peminjaman', 'ongoing');
            })
            ->exists();

        if ($activeLoan) {
            \Log::warning('User has active loan', ['user_id' => $user->id_user]);
            return back()->with('error', 'Anda masih memiliki peminjaman aktif.');
        }

        // Get cart items
        $cart = session()->get('cart', []);
        \Log::info('Cart contents', ['cart' => $cart]);
        
        if (empty($cart)) {
            \Log::warning('Cart is empty');
            return redirect()->route('user.cart.index')->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();
        try {
            // Generate kode peminjaman
            $kodePeminjaman = $this->generateKodePeminjaman();
            \Log::info('Generated kode peminjaman', ['kode' => $kodePeminjaman]);
            
            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'id_user' => $user->id_user,
                'kode_peminjaman' => $kodePeminjaman,
                'nama_pengambil' => $request->nama_pengambil,
                'no_identitas_pengambil' => $request->nomor_identitas,
                'no_hp_pengambil' => $request->nomor_hp,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'tanggal_mulai' => $request->tanggal_pinjam,
                'tanggal_selesai' => $request->tanggal_kembali,
                'status_pengajuan' => 'draft',
                'status_pembayaran' => 'pending',
                'total_biaya' => 0
            ]);

            \Log::info('Peminjaman created', ['id' => $peminjaman->id_peminjaman]);

            $totalBiaya = 0;
            $isNonCivitas = $user->role->nama_role === 'user_non_fmipa';

            // Process cart items
            foreach ($cart as $barangId => $cartItem) {
                $barang = Barang::findOrFail($barangId);
                
                // Check stock availability
                if ($cartItem['quantity'] > $barang->stok_tersedia) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_tersedia}");
                }

                // Calculate duration in days
                $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
                $tanggalKembali = Carbon::parse($request->tanggal_kembali);
                $durasi = $tanggalPinjam->diffInDays($tanggalKembali) + 1;

                // Calculate subtotal (only for non-civitas)
                $hargaSatuan = $isNonCivitas ? $barang->harga_sewa : 0;
                $subtotal = $hargaSatuan * $cartItem['quantity'] * $durasi;
                $totalBiaya += $subtotal;

                // Create peminjaman_barang
                PeminjamanBarang::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_barang' => $barangId,
                    'jumlah_pinjam' => $cartItem['quantity'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'status_persetujuan' => 'pending'
                ]);
            }

            // Update total biaya
            $peminjaman->update(['total_biaya' => $totalBiaya]);

            // Clear cart after successful creation
            session()->forget('cart');

            DB::commit();

            \Log::info('Peminjaman stored successfully', [
                'id' => $peminjaman->id_peminjaman,
                'kode' => $kodePeminjaman
            ]);

            return redirect()->route('user.pengajuan.show', $peminjaman->id_peminjaman)
                ->with('success', 'Pengajuan peminjaman berhasil dibuat dengan kode: ' . $kodePeminjaman);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error storing peminjaman', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal membuat pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Submit pengajuan (change status to pending_approval)
     */
    public function submitPengajuan($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'draft')
            ->firstOrFail();

        $peminjaman->update([
            'status_pengajuan' => 'pending_approval'
        ]);

        return redirect()->route('user.pengajuan.index')
            ->with('success', 'Pengajuan berhasil disubmit dan menunggu persetujuan admin.');
    }

    /**
     * Confirm final peminjaman (after all items approved)
     */
    public function confirmPeminjaman($id)
    {
        try {
            $user = Auth::guard('user')->user();
            
            $peminjaman = Peminjaman::with('peminjamanBarangs')
                ->where('id_peminjaman', $id)
                ->where('id_user', $user->id_user)
                ->where('status_pengajuan', 'approved')
                ->firstOrFail();

            // Check if all active items (not deleted by user) are approved
            $activeItems = $peminjaman->peminjamanBarangs->whereNull('user_action');
            $allApproved = $activeItems->every(function($item) {
                return $item->status_persetujuan === 'approved';
            });

            if (!$allApproved) {
                return back()->with('error', 'Tidak semua barang telah disetujui admin.');
            }

            $isNonCivitas = $user->role->nama_role === 'user_non_fmipa';

            if ($isNonCivitas && $peminjaman->total_biaya > 0) {
                // Non-civitas needs to upload payment proof after confirmation
                $peminjaman->update([
                    'status_pengajuan' => 'confirmed',
                    'status_pembayaran' => 'pending'
                ]);

                return redirect()->route('user.peminjaman.payment', $id)
                    ->with('info', 'Peminjaman berhasil dikonfirmasi. Silakan upload bukti pembayaran untuk melanjutkan proses.');
            } else {
                // Civitas can proceed directly (or non-civitas with no cost)
                $this->processConfirmedLoan($peminjaman);
                
                return redirect()->route('user.peminjaman.index')
                    ->with('success', 'Peminjaman berhasil dikonfirmasi. Silakan ambil barang sesuai jadwal.');
            }
        } catch (\Exception $e) {
            \Log::error('Error in confirmPeminjaman', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Confirm partial peminjaman (proceed with only approved items)
     */
    public function confirmPartialPeminjaman($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with('peminjamanBarangs.barang')
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'partial')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Get approved and rejected items
            $approvedItems = $peminjaman->peminjamanBarangs->where('status_persetujuan', 'approved');
            $rejectedItems = $peminjaman->peminjamanBarangs->where('status_persetujuan', 'rejected');

            if ($approvedItems->isEmpty()) {
                return back()->with('error', 'Tidak ada barang yang disetujui untuk dilanjutkan.');
            }

            // Mark rejected items as deleted by user (soft delete)
            foreach ($rejectedItems as $rejectedItem) {
                $rejectedItem->update([
                    'user_action' => 'deleted',
                    'action_timestamp' => now()
                ]);
            }

            // Recalculate total_biaya for approved items only
            $newTotalBiaya = $approvedItems->sum('subtotal');
            
            // Update peminjaman status and cost
            $peminjaman->update([
                'status_pengajuan' => 'approved',
                'total_biaya' => $newTotalBiaya
            ]);

            DB::commit();

            // Continue with normal confirmation flow
            return $this->confirmPeminjaman($id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Show payment form for non-civitas
     */
    public function showPaymentForm($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with('peminjamanBarangs.barang')
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'confirmed')
            ->whereIn('status_pembayaran', ['pending', 'waiting_verification'])
            ->firstOrFail();

        if ($user->role->nama_role !== 'user_non_fmipa') {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Halaman ini hanya untuk user non-civitas.');
        }

        return view('user.upload-payment', compact('peminjaman'));
    }

    /**
     * Upload payment proof for non-civitas users
     */
    public function uploadPayment(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        
        // Validate user is non-civitas
        if ($user->role->nama_role !== 'user_non_fmipa') {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Fitur ini hanya untuk user non-civitas');
        }

        // Find peminjaman (allow both pending and waiting_verification for re-upload)
        $peminjaman = Peminjaman::where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'confirmed')
            ->whereIn('status_pembayaran', ['pending', 'waiting_verification'])
            ->firstOrFail();

        // Validate request
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
            'catatan_pembayaran' => 'nullable|string|max:500'
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format file harus JPG, JPEG, atau PNG',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB'
        ]);

        DB::beginTransaction();
        try {
            // Check if transaction already exists
            $existingTransaction = \App\Models\Transaksi::where('id_peminjaman', $peminjaman->id_peminjaman)->first();
            
            if ($existingTransaction) {
                // Allow re-upload if existing data is dummy from migration
                if ($existingTransaction->bukti_pembayaran !== 'dummy_payment_proof') {
                    return back()->with('error', 'Bukti pembayaran sudah pernah diupload untuk peminjaman ini.');
                }
            }

            // Process image upload
            $file = $request->file('bukti_pembayaran');
            $imageData = file_get_contents($file->getRealPath());
            
            // Update peminjaman status
            $peminjaman->update([
                'status_pembayaran' => 'waiting_verification'
            ]);

            if ($existingTransaction && $existingTransaction->bukti_pembayaran === 'dummy_payment_proof') {
                // Update existing dummy transaction with real data
                $existingTransaction->update([
                    'bukti_pembayaran' => $imageData,
                    'tanggal_pembayaran' => now(),
                    'notes_admin' => $request->catatan_pembayaran,
                    'updated_at' => now()
                ]);
            } else {
                // Create new transaction record for superadmin verification
                \App\Models\Transaksi::create([
                    'id_user' => $user->id_user,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'jenis_transaksi' => 'sewa',
                    'nominal' => $peminjaman->total_biaya,
                    'tanggal_pembayaran' => now(),
                    'bukti_pembayaran' => $imageData,
                    'status_verifikasi' => 'pending',
                    'notes_admin' => $request->catatan_pembayaran,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('user.peminjaman.index')
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error uploading payment proof', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
                'user_id' => $user->id_user
            ]);

            return back()->with('error', 'Terjadi kesalahan saat upload bukti pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Process confirmed loan (reduce stock) - Public wrapper for external calls
     */
    public function processConfirmedLoanPublic($peminjaman)
    {
        return $this->processConfirmedLoan($peminjaman);
    }

    /**
     * Process confirmed loan (reduce stock)
     */
    private function processConfirmedLoan($peminjaman)
    {
        DB::beginTransaction();
        try {
            // Reduce stock for each active item (not deleted by user)
            $activeItems = $peminjaman->peminjamanBarangs->whereNull('user_action');
            
            foreach ($activeItems as $item) {
                $barang = $item->barang;
                $barang->decrement('stok_tersedia', $item->jumlah_pinjam);
            }

            // Update status
            $peminjaman->update([
                'status_peminjaman' => 'ongoing',
                'status_pembayaran' => 'verified'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in processConfirmedLoan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate kode peminjaman
     */
    private function generateKodePeminjaman()
    {
        $monthYear = Carbon::now()->format('my'); // Format: 1224 for Dec 2024
        
        // Get last number for this month
        $lastPeminjaman = Peminjaman::where('kode_peminjaman', 'LIKE', "PJM-{$monthYear}-%")
            ->orderBy('kode_peminjaman', 'desc')
            ->first();

        if ($lastPeminjaman) {
            $lastNumber = (int) substr($lastPeminjaman->kode_peminjaman, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('PJM-%s-%03d', $monthYear, $nextNumber);
    }

    /**
     * Cancel peminjaman (only for draft status)
     */
    public function cancel($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with('peminjamanBarangs')
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'draft')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Update status pengajuan
            $peminjaman->update([
                'status_pengajuan' => 'rejected',
                'status_peminjaman' => null // Reset status_peminjaman when cancelled
            ]);

            // Update all pending items to cancelled status
            PeminjamanBarang::where('id_peminjaman', $peminjaman->id_peminjaman)
                ->where('status_persetujuan', 'pending')
                ->update([
                    'status_persetujuan' => 'cancelled',
                    'notes_admin' => 'Dibatalkan oleh user',
                    'tanggal_persetujuan' => now()
                ]);

            DB::commit();

            \Log::info('Peminjaman cancelled by user', [
                'peminjaman_id' => $id,
                'user_id' => $user->id_user,
                'cancelled_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'pending')->count()
            ]);

            return redirect()->route('user.pengajuan.index')
                ->with('success', 'Pengajuan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error cancelling peminjaman', [
                'peminjaman_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Gagal membatalkan pengajuan.');
        }
    }

    /**
     * Edit peminjaman (for draft or partial status)
     */
    public function edit($id)
    {
        $user = Auth::guard('user')->user();
        
        $peminjaman = Peminjaman::with(['peminjamanBarangs.barang.admin'])
            ->where('id_peminjaman', $id)
            ->where('id_user', $user->id_user)
            ->whereIn('status_pengajuan', ['draft', 'partial'])
            ->firstOrFail();

        // Always fetch available barangs for replacement/template consistency
        $barangs = Barang::with('admin')
            ->where('is_active', true)
            ->where('stok_tersedia', '>', 0)
            ->orderBy('nama_barang', 'asc')
            ->get();

        if ($peminjaman->status_pengajuan === 'partial') {
            // For partial status, only show rejected items for action
            $approvedItems = $peminjaman->peminjamanBarangs->where('status_persetujuan', 'approved')
                ->whereNull('user_action');
                
            $rejectedItems = $peminjaman->peminjamanBarangs->where('status_persetujuan', 'rejected')
                ->whereNull('user_action'); 
            
            // Find similar items for replacement options
            $similarItemsMap = [];
            foreach ($rejectedItems as $rejectedItem) {
                $words = explode(' ', $rejectedItem->barang->nama_barang);
                
                $searchWords = array_filter($words, function($word) {
                    return strlen($word) > 3;
                });

                if (empty($searchWords)) {
                    $searchWords = $words;
                }
                
                $similarItemsMap[$rejectedItem->id_peminjaman_barang] = Barang::where('is_active', true)
                    ->where('stok_tersedia', '>', 0)
                    ->where('id_barang', '!=', $rejectedItem->id_barang)
                    ->where(function ($query) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $query->orWhere('nama_barang', 'LIKE', '%' . $word . '%');
                        }
                    })
                    ->with('admin')
                    ->orderBy('nama_barang')
                    ->limit(10)
                    ->get();
            }
            
            return view('user.pengajuan-edit', compact(
                'peminjaman', 'approvedItems', 'rejectedItems', 'similarItemsMap', 'barangs'
            ));
        } else {
            // For draft status, show all available items
            return view('user.pengajuan-edit', compact('peminjaman', 'barangs'));
        }
    }

    /**
     * Update peminjaman
     */
    public function update(Request $request, $id)
    {
        \Log::info('PeminjamanController update called', [
            'id' => $id,
            'user_id' => auth()->guard('user')->id(),
            'request_data' => $request->all()
        ]);

        try {
            $user = Auth::guard('user')->user();
            
            $peminjaman = Peminjaman::with('peminjamanBarangs')
                ->where('id_peminjaman', $id)
                ->where('id_user', $user->id_user)
                ->whereIn('status_pengajuan', ['draft', 'partial'])
                ->firstOrFail();

            if ($peminjaman->status_pengajuan === 'partial') {
                // For partial status, only update rejected items
                \Log::info('Processing partial peminjaman update');
                $this->updatePartialPeminjaman($request, $peminjaman);
                \Log::info('Partial peminjaman update completed successfully');
                return redirect()->route('user.pengajuan.index')
                    ->with('success', 'Pengajuan berhasil diperbarui. Item yang ditolak telah diproses.');
            } else {
                // For draft status, full update
                \Log::info('Processing draft peminjaman update');
                $this->updateDraftPeminjaman($request, $peminjaman);
                \Log::info('Draft peminjaman update completed successfully');
                return redirect()->route('user.pengajuan.index')
                    ->with('success', 'Pengajuan berhasil diperbarui.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in update', [
                'errors' => $e->errors(),
                'peminjaman_id' => $id,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'peminjaman_id' => $id,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update draft peminjaman (full update)
     */
    private function updateDraftPeminjaman($request, $peminjaman)
    {
        $request->validate([
            'tujuan_peminjaman' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date|after_or_equal:' . Carbon::now()->addDays(3)->format('Y-m-d'),
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // Delete existing items
            PeminjamanBarang::where('id_peminjaman', $peminjaman->id_peminjaman)->delete();

            // Update peminjaman basic info
            $peminjaman->update([
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'tanggal_mulai' => $request->tanggal_pinjam,
                'tanggal_selesai' => $request->tanggal_kembali,
            ]);

            $totalBiaya = 0;
            $isNonCivitas = auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa';

            // Add new items
            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['id_barang']);
                
                if ($item['jumlah'] > $barang->stok_tersedia) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi.");
                }

                $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
                $tanggalKembali = Carbon::parse($request->tanggal_kembali);
                $durasi = $tanggalPinjam->diffInDays($tanggalKembali) + 1;

                $hargaSatuan = $isNonCivitas ? $barang->harga_sewa : 0;
                $subtotal = $hargaSatuan * $item['jumlah'] * $durasi;
                $totalBiaya += $subtotal;

                PeminjamanBarang::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_barang' => $item['id_barang'],
                    'jumlah_pinjam' => $item['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'status_persetujuan' => 'pending'
                ]);
            }

            $peminjaman->update(['total_biaya' => $totalBiaya]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update partial peminjaman (only update rejected items)
     */
    private function updatePartialPeminjaman($request, $peminjaman)
    {
        \Log::info('updatePartialPeminjaman called', [
            'request_data' => $request->all(),
            'peminjaman_id' => $peminjaman->id_peminjaman
        ]);

        try {
            // Custom validation for nested array with conditional rules
            $rules = [
                'rejected_actions' => 'required|array|min:1',
                'rejected_actions.*.action' => 'required|in:delete,replace',
            ];
            
            // Add conditional validation for replace action
            foreach ($request->rejected_actions as $key => $action) {
                if ($action['action'] === 'replace') {
                    $rules["rejected_actions.{$key}.id_barang"] = 'required|exists:barang,id_barang';
                    $rules["rejected_actions.{$key}.jumlah"] = 'required|integer|min:1';
                }
            }
            
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in updatePartialPeminjaman', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        DB::beginTransaction();
        try {
            $isNonCivitas = auth()->guard('user')->user()->role->nama_role === 'user_non_fmipa';
            $totalBiayaChange = 0;
            $processedActions = [];

            foreach ($request->rejected_actions as $rejectedId => $action) {
                \Log::info('Processing rejected action', [
                    'rejected_id' => $rejectedId,
                    'action' => $action
                ]);

                $rejectedItem = PeminjamanBarang::findOrFail($rejectedId);
                
                // Ensure this item belongs to this peminjaman and is rejected
                if ($rejectedItem->id_peminjaman != $peminjaman->id_peminjaman || 
                    $rejectedItem->status_persetujuan != 'rejected') {
                    throw new \Exception("Invalid item to update: Item {$rejectedId} does not belong to this peminjaman or is not rejected.");
                }

                if ($action['action'] === 'delete') {
                    // Mark rejected item as deleted by user (soft delete)
                    $totalBiayaChange -= $rejectedItem->subtotal;
                    $rejectedItem->update([
                        'user_action' => 'deleted',
                        'action_timestamp' => now()
                    ]);
                    $processedActions[] = "Deleted item: {$rejectedItem->barang->nama_barang}";
                    
                } elseif ($action['action'] === 'replace') {
                    // Replace with new item
                    $newBarang = Barang::findOrFail($action['id_barang']);
                    
                    if ($action['jumlah'] > $newBarang->stok_tersedia) {
                        throw new \Exception("Stok {$newBarang->nama_barang} tidak mencukupi.");
                    }

                    $tanggalPinjam = Carbon::parse($peminjaman->tanggal_mulai);
                    $tanggalKembali = Carbon::parse($peminjaman->tanggal_selesai);
                    $durasi = $tanggalPinjam->diffInDays($tanggalKembali) + 1;

                    $hargaSatuan = $isNonCivitas ? $newBarang->harga_sewa : 0;
                    $subtotal = $hargaSatuan * $action['jumlah'] * $durasi;
                    
                    // Remove old subtotal and add new
                    $totalBiayaChange = $totalBiayaChange - $rejectedItem->subtotal + $subtotal;

                    // Update the rejected item with new item info
                    $rejectedItem->update([
                        'id_barang' => $action['id_barang'],
                        'jumlah_pinjam' => $action['jumlah'],
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                        'status_persetujuan' => 'pending' // Reset to pending for new review
                    ]);
                    
                    $processedActions[] = "Replaced {$rejectedItem->barang->nama_barang} with {$newBarang->nama_barang}";
                }
            }

            // Update total biaya
            $newTotalBiaya = $peminjaman->total_biaya + $totalBiayaChange;
            $peminjaman->update(['total_biaya' => $newTotalBiaya]);

            // REFRESH the relationship to get the most up-to-date item statuses
            $peminjaman->load('peminjamanBarangs');

            // Check remaining active items (not deleted by user) and update status accordingly
            $remainingItems = $peminjaman->peminjamanBarangs()->whereNull('user_action')->get();
            $hasPendingItems = $remainingItems->where('status_persetujuan', 'pending')->count() > 0;
            $hasApprovedItems = $remainingItems->where('status_persetujuan', 'approved')->count() > 0;
            $hasRejectedItems = $remainingItems->where('status_persetujuan', 'rejected')->count() > 0;

            if ($hasPendingItems) {
                // There are new pending items, set to pending_approval
                $peminjaman->update(['status_pengajuan' => 'pending_approval']);
            } elseif ($hasApprovedItems && $hasRejectedItems) {
                // If there are still both approved and other rejected items
                $peminjaman->update(['status_pengajuan' => 'partial']);
            } elseif ($hasApprovedItems && !$hasRejectedItems) {
                // Only approved items left, set to approved, allowing confirmation
                $peminjaman->update(['status_pengajuan' => 'approved']);
            } elseif (!$hasApprovedItems && !$hasRejectedItems && !$hasPendingItems) {
                // No items left, set back to draft
                $peminjaman->update(['status_pengajuan' => 'draft']);
            } else {
                // Default fallback to keep it partial if other conditions aren't met
                $peminjaman->update(['status_pengajuan' => 'partial']);
            }

            \Log::info('updatePartialPeminjaman completed successfully', [
                'processed_actions' => $processedActions,
                'total_biaya_change' => $totalBiayaChange,
                'new_status' => $peminjaman->fresh()->status_pengajuan
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in updatePartialPeminjaman', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a rejected item from peminjaman
     */
    public function deleteRejectedItem($itemId)
    {
        try {
            $user = auth()->guard('user')->user();
            
            // Find the peminjaman item
            $item = PeminjamanBarang::with(['peminjaman', 'barang'])->findOrFail($itemId);
            $peminjaman = $item->peminjaman;
            
            // Verify ownership
            if ($peminjaman->id_user !== $user->id_user) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Verify item can be deleted (rejected and not already deleted)
            if ($item->status_persetujuan !== 'rejected' || $item->user_action !== null) {
                return redirect()->back()->with('error', 'Item tidak dapat dihapus. Hanya item yang ditolak dan belum dihapus yang dapat dihapus.');
            }
            
            // Verify peminjaman status allows editing
            if (!in_array($peminjaman->status_pengajuan, ['draft', 'pending_approval', 'approved', 'partial'])) {
                return redirect()->back()->with('error', 'Peminjaman tidak dapat diubah pada status saat ini.');
            }
            
            DB::beginTransaction();
            try {
                // Mark item as deleted by user
                $item->update([
                    'user_action' => 'deleted',
                    'action_timestamp' => now()
                ]);
                
                // Update total biaya (subtract deleted item cost)
                $newTotalBiaya = $peminjaman->total_biaya - $item->subtotal;
                $peminjaman->update(['total_biaya' => $newTotalBiaya]);
                
                // Update peminjaman status based on remaining items logic
                $this->updatePeminjamanStatusAfterItemDelete($peminjaman);
                
                DB::commit();
                
                return redirect()->back()->with('success', "Item '{$item->barang->nama_barang}' berhasil dihapus dari pengajuan.");
                
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error('Error deleting rejected item', [
                    'item_id' => $itemId,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus item.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error in deleteRejectedItem', [
                'item_id' => $itemId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }
    }
    
    /**
     * Update peminjaman status after item deletion
     */
    private function updatePeminjamanStatusAfterItemDelete($peminjaman)
    {
        // Get remaining items (not deleted by user)
        $remainingItems = $peminjaman->peminjamanBarangs()->whereNull('user_action')->get();
        
        if ($remainingItems->count() === 0) {
            // No items left, set back to draft
            $peminjaman->update(['status_pengajuan' => 'draft']);
            return;
        }
        
        $approvedCount = $remainingItems->where('status_persetujuan', 'approved')->count();
        $rejectedCount = $remainingItems->where('status_persetujuan', 'rejected')->count();
        $pendingCount = $remainingItems->where('status_persetujuan', 'pending')->count();
        $totalCount = $remainingItems->count();
        
        // Apply status logic to remaining items
        if ($pendingCount > 0) {
            $newStatus = 'pending_approval';
        } elseif ($approvedCount > 0 && $rejectedCount > 0) {
            $newStatus = 'partial';
        } elseif ($approvedCount == $totalCount) {
            $newStatus = 'approved';
        } elseif ($rejectedCount == $totalCount) {
            $newStatus = 'rejected';
        } else {
            $newStatus = 'pending_approval';
        }
        
        $peminjaman->update(['status_pengajuan' => $newStatus]);
        
        \Log::info('Peminjaman status updated after item delete', [
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'remaining_items' => $totalCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
            'pending' => $pendingCount,
            'new_status' => $newStatus
        ]);
    }


}
