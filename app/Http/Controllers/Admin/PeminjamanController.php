<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of peminjaman requests for admin's items
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Peminjaman::with(['user', 'peminjamanBarangs.barang'])
            ->whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
                $q->where('id_admin', $admin->id_admin);
            });
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('status_pengajuan', 'pending_approval');
            } elseif ($request->status === 'approved') {
                $query->where('status_pengajuan', 'approved');
            } elseif ($request->status === 'rejected') {
                $query->where('status_pengajuan', 'rejected');
            } elseif ($request->status === 'partial') {
                $query->where('status_pengajuan', 'partial');
            }
        }
        // Default: show all peminjaman (no status filter)
        
        // Search by user or kode peminjaman
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_lembaga', 'like', "%{$search}%")
                               ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%");
                  });
            });
        }
        
        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $totalPeminjaman = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })->count();
        
        $pendingCount = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })->where('status_pengajuan', 'pending_approval')->count();
        
        $approvedCount = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })->where('status_pengajuan', 'approved')->count();
        
        $rejectedCount = Peminjaman::whereHas('peminjamanBarangs.barang', function($q) use ($admin) {
            $q->where('id_admin', $admin->id_admin);
        })->where('status_pengajuan', 'rejected')->count();
        
        return view('admin.peminjaman.index', compact(
            'peminjaman',
            'totalPeminjaman',
            'pendingCount', 
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Display the specified peminjaman for approval
     */
    public function show(Peminjaman $peminjaman)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin has items in this peminjaman
        $hasItems = $peminjaman->peminjamanBarangs()
            ->whereHas('barang', function($q) use ($admin) {
                $q->where('id_admin', $admin->id_admin);
            })->exists();
            
        if (!$hasItems) {
            abort(403, 'Unauthorized action.');
        }
        
        $peminjaman->load(['user']);
        
        // Get ALL items that belong to this admin (including deleted by user for history)
        $adminItems = $peminjaman->peminjamanBarangs()
            ->whereHas('barang', function($q) use ($admin) {
                $q->where('id_admin', $admin->id_admin);
            })
            ->with('barang')
            ->get();
            
        \Log::info('Admin viewing peminjaman items', [
            'admin_id' => $admin->id_admin,
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'total_items' => $adminItems->count(),
            'items_data' => $adminItems->map(function($item) {
                return [
                    'id' => $item->id_peminjaman_barang,
                    'nama_barang' => $item->barang->nama_barang,
                    'status_persetujuan' => $item->status_persetujuan,
                    'user_action' => $item->user_action,
                    'action_timestamp' => $item->action_timestamp
                ];
            })
        ]);
        
        return view('admin.peminjaman.show', compact('peminjaman', 'adminItems'));
    }

    /**
     * Approve specific items in peminjaman
     */
    public function approve(Request $request, Peminjaman $peminjaman)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if peminjaman status allows approval
        if (!in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial'])) {
            $statusText = $this->getStatusText($peminjaman->status_pengajuan);
            return back()->with('error', 'Peminjaman dengan status "' . $statusText . '" tidak dapat disetujui lagi.');
        }
        
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:peminjaman_barang,id_peminjaman_barang',
            'notes' => 'nullable|string|max:500'
        ]);
        
        DB::transaction(function() use ($request, $peminjaman, $admin) {
            // Get items that belong to this admin
            $adminItems = PeminjamanBarang::whereIn('id_peminjaman_barang', $request->item_ids)
                ->whereHas('barang', function($q) use ($admin) {
                    $q->where('id_admin', $admin->id_admin);
                })
                ->get();
            
            $approvedItems = [];
            
            foreach ($adminItems as $item) {
                // Check if item is cancelled by user
                if ($item->status_persetujuan === 'cancelled') {
                    return back()->with('error', 
                        "Item {$item->barang->nama_barang} telah dibatalkan oleh user dan tidak dapat disetujui.");
                }
                
                // Check if item is not pending
                if ($item->status_persetujuan !== 'pending') {
                    return back()->with('error', 
                        "Item {$item->barang->nama_barang} sudah diproses sebelumnya.");
                }
                
                // Check stock availability (but don't deduct yet)
                if ($item->barang->stok_tersedia < $item->jumlah_pinjam) {
                    return back()->with('error', 
                        "Stok {$item->barang->nama_barang} tidak mencukupi. 
                        Tersedia: {$item->barang->stok_tersedia}, Diminta: {$item->jumlah_pinjam}");
                }
                
                // Update item approval (DO NOT REDUCE STOCK YET)
                $item->update([
                    'status_persetujuan' => 'approved',
                    'approved_by' => $admin->id_admin,
                    'notes_admin' => $request->notes,
                    'tanggal_persetujuan' => now()
                ]);
                
                // NOTE: Stock will be reduced when user confirms (civitas) or payment is verified (non-civitas)
                
                $approvedItems[] = $item->barang->nama_barang;
            }
            
            // Create notification for user
            $this->createNotification(
                $peminjaman->id_user,
                'approval_update',
                'Item Peminjaman Disetujui',
                "Admin {$admin->nama_lengkap} telah menyetujui item: " . implode(', ', $approvedItems) . " dalam peminjaman {$peminjaman->kode_peminjaman}",
                ['peminjaman_id' => $peminjaman->id]
            );
            
            // Check if all items in peminjaman are approved/rejected
            $this->updatePeminjamanStatus($peminjaman);
        });
        
        return back()->with('success', 'Item berhasil disetujui.');
    }

    /**
     * Reject specific items in peminjaman
     */
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if peminjaman status allows rejection
        if (!in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial'])) {
            $statusText = $this->getStatusText($peminjaman->status_pengajuan);
            return back()->with('error', 'Peminjaman dengan status "' . $statusText . '" tidak dapat ditolak lagi.');
        }
        
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:peminjaman_barang,id_peminjaman_barang',
            'notes' => 'required|string|max:500'
        ]);
        
        DB::transaction(function() use ($request, $peminjaman, $admin) {
            // Get items that belong to this admin
            $adminItems = PeminjamanBarang::whereIn('id_peminjaman_barang', $request->item_ids)
                ->whereHas('barang', function($q) use ($admin) {
                    $q->where('id_admin', $admin->id_admin);
                })
                ->get();
            
            $rejectedItems = [];
            
            foreach ($adminItems as $item) {
                // Check if item is cancelled by user
                if ($item->status_persetujuan === 'cancelled') {
                    return back()->with('error', 
                        "Item {$item->barang->nama_barang} telah dibatalkan oleh user dan tidak dapat ditolak.");
                }
                
                // Check if item is not pending
                if ($item->status_persetujuan !== 'pending') {
                    return back()->with('error', 
                        "Item {$item->barang->nama_barang} sudah diproses sebelumnya.");
                }
                
                $item->update([
                    'status_persetujuan' => 'rejected',
                    'approved_by' => $admin->id_admin,
                    'notes_admin' => $request->notes,
                    'tanggal_persetujuan' => now()
                ]);
                
                $rejectedItems[] = $item->barang->nama_barang;
            }
            
            // Create notification for user
            $this->createNotification(
                $peminjaman->id_user,
                'approval_update',
                'Item Peminjaman Ditolak',
                "Admin {$admin->nama_lengkap} telah menolak item: " . implode(', ', $rejectedItems) . " dalam peminjaman {$peminjaman->kode_peminjaman}. Alasan: {$request->notes}",
                ['peminjaman_id' => $peminjaman->id]
            );
            
            // Check if all items in peminjaman are approved/rejected
            $this->updatePeminjamanStatus($peminjaman);
        });
        
        return back()->with('success', 'Item berhasil ditolak.');
    }

    /**
     * Approve all items from this admin in the peminjaman
     */
    public function approveAll(Request $request, Peminjaman $peminjaman)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if peminjaman status allows approval
        if (!in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial'])) {
            $statusText = $this->getStatusText($peminjaman->status_pengajuan);
            return back()->with('error', 'Peminjaman dengan status "' . $statusText . '" tidak dapat disetujui lagi.');
        }
        
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);
        
        DB::transaction(function() use ($request, $peminjaman, $admin) {
            $adminItems = $peminjaman->peminjamanBarangs()
                ->whereHas('barang', function($q) use ($admin) {
                    $q->where('id_admin', $admin->id_admin);
                })
                ->where('status_persetujuan', 'pending')
                ->get();
            
            $approvedItems = [];
            
            foreach ($adminItems as $item) {
                // Check stock availability (but don't deduct yet)
                if ($item->barang->stok_tersedia < $item->jumlah_pinjam) {
                    return back()->with('error', 
                        "Stok {$item->barang->nama_barang} tidak mencukupi. 
                        Tersedia: {$item->barang->stok_tersedia}, Diminta: {$item->jumlah_pinjam}");
                }
                
                $item->update([
                    'status_persetujuan' => 'approved',
                    'approved_by' => $admin->id_admin,
                    'notes_admin' => $request->notes,
                    'tanggal_persetujuan' => now()
                ]);
                
                // NOTE: Stock will be reduced when user confirms (civitas) or payment is verified (non-civitas)
                
                $approvedItems[] = $item->barang->nama_barang;
            }
            
            // Create notification for user
            if (!empty($approvedItems)) {
                $this->createNotification(
                    $peminjaman->id_user,
                    'approval_update',
                    'Semua Item Peminjaman Disetujui',
                    "Admin {$admin->nama_lengkap} telah menyetujui semua item dari lembaga mereka dalam peminjaman {$peminjaman->kode_peminjaman}: " . implode(', ', $approvedItems),
                    ['peminjaman_id' => $peminjaman->id]
                );
            }
            
            $this->updatePeminjamanStatus($peminjaman);
        });
        
        return back()->with('success', 'Semua item berhasil disetujui.');
    }

    /**
     * Reject all items from this admin in the peminjaman
     */
    public function rejectAll(Request $request, Peminjaman $peminjaman)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if peminjaman status allows rejection
        if (!in_array($peminjaman->status_pengajuan, ['pending_approval', 'partial'])) {
            $statusText = $this->getStatusText($peminjaman->status_pengajuan);
            return back()->with('error', 'Peminjaman dengan status "' . $statusText . '" tidak dapat ditolak lagi.');
        }
        
        $request->validate([
            'notes' => 'required|string|max:500'
        ]);
        
        DB::transaction(function() use ($request, $peminjaman, $admin) {
            $adminItems = $peminjaman->peminjamanBarangs()
                ->whereHas('barang', function($q) use ($admin) {
                    $q->where('id_admin', $admin->id_admin);
                })
                ->where('status_persetujuan', 'pending')
                ->get();
            
            $rejectedItems = [];
            
            foreach ($adminItems as $item) {
                $item->update([
                    'status_persetujuan' => 'rejected',
                    'approved_by' => $admin->id_admin,
                    'notes_admin' => $request->notes,
                    'tanggal_persetujuan' => now()
                ]);
                
                $rejectedItems[] = $item->barang->nama_barang;
            }
            
            // Create notification for user
            if (!empty($rejectedItems)) {
                $this->createNotification(
                    $peminjaman->id_user,
                    'approval_update',
                    'Semua Item Peminjaman Ditolak',
                    "Admin {$admin->nama_lengkap} telah menolak semua item dari lembaga mereka dalam peminjaman {$peminjaman->kode_peminjaman}: " . implode(', ', $rejectedItems) . ". Alasan: {$request->notes}",
                    ['peminjaman_id' => $peminjaman->id]
                );
            }
            
            $this->updatePeminjamanStatus($peminjaman);
        });
        
        return back()->with('success', 'Semua item berhasil ditolak.');
    }

    /**
     * Update overall peminjaman status based on item approvals
     */
    private function updatePeminjamanStatus(Peminjaman $peminjaman)
    {
        // Only consider active items (not deleted by user)
        $items = $peminjaman->peminjamanBarangs->whereNull('user_action');
        $pendingCount = $items->where('status_persetujuan', 'pending')->count();
        $approvedCount = $items->where('status_persetujuan', 'approved')->count();
        $rejectedCount = $items->where('status_persetujuan', 'rejected')->count();
        $cancelledCount = $items->where('status_persetujuan', 'cancelled')->count();
        
        $oldStatus = $peminjaman->status_pengajuan;
        
        // If there are no pending items (all items processed)
        if ($pendingCount == 0) {
            if ($approvedCount > 0 && $rejectedCount == 0 && $cancelledCount >= 0) {
                // All remaining items approved (cancelled items don't affect approval status)
                $peminjaman->update(['status_pengajuan' => 'approved']);
                $newStatus = 'approved';
            } elseif ($approvedCount == 0 && ($rejectedCount > 0 || $cancelledCount > 0)) {
                // All items either rejected or cancelled (no approved items)
                $peminjaman->update(['status_pengajuan' => 'rejected']);
                $newStatus = 'rejected';
            } else {
                // Mixed (partial approval) - some approved, some rejected/cancelled
                $peminjaman->update(['status_pengajuan' => 'partial']);
                $newStatus = 'partial';
            }
            
            // Create notification if overall status changed
            if ($oldStatus !== $newStatus) {
                $statusText = [
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'partial' => 'Sebagian Disetujui'
                ];
                
                $this->createNotification(
                    $peminjaman->id_user,
                    'status_change',
                    'Status Peminjaman Berubah',
                    "Status peminjaman {$peminjaman->kode_peminjaman} telah berubah menjadi: {$statusText[$newStatus]}",
                    ['peminjaman_id' => $peminjaman->id]
                );
            }
        }
    }
    
    /**
     * Create notification for user
     */
    private function createNotification($userId, $type, $title, $message, $data = null)
    {
        try {
            Notification::create([
                'id_user' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data ? json_encode($data) : null,
                'is_read' => false,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            \Log::error('Failed to create notification: ' . $e->getMessage());
        }
    }

    /**
     * Get user-friendly status text
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'draft' => 'Draft',
            'pending_approval' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'partial' => 'Sebagian Disetujui',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $statusTexts[$status] ?? ucfirst($status);
    }
} 