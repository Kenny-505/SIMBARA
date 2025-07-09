<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPendaftaran;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    /**
     * Display superadmin dashboard with real data
     * @deprecated Use SuperAdmin\DashboardController@index instead
     */
    public function dashboard()
    {
        // Redirect to new dashboard controller
        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Display list of pending registration requests
     */
    public function index(Request $request)
    {
        $query = PengajuanPendaftaran::query();
        
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        
        $pengajuan = $query->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);

        return view('superadmin.verifikasi.index', compact('pengajuan'));
    }

    /**
     * Display detailed view of a specific registration request
     */
    public function show($id)
    {
        $pengajuan = PengajuanPendaftaran::findOrFail($id);
        return view('superadmin.verifikasi.detail', compact('pengajuan'));
    }

    /**
     * Download surat keterangan file
     */
    public function downloadSurat($id)
    {
        $pengajuan = PengajuanPendaftaran::findOrFail($id);
        
        if (!$pengajuan->surat_keterangan) {
            return redirect()->back()->with('error', 'File surat keterangan tidak ditemukan.');
        }

        $filename = 'surat_keterangan_' . $pengajuan->id_pengajuan . '.pdf';
        
        return response($pengajuan->surat_keterangan)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Approve registration request and create user account
     */
    public function approve($id)
    {
        try {
            \Log::info("Starting account creation process for pengajuan ID: {$id}");
            
            DB::beginTransaction();

            $pengajuan = PengajuanPendaftaran::findOrFail($id);
            \Log::info("Found pengajuan: " . json_encode($pengajuan->toArray()));
            
            if ($pengajuan->status_verifikasi !== 'pending') {
                \Log::warning("Pengajuan already processed with status: {$pengajuan->status_verifikasi}");
                return redirect()->back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
            }

            // Generate username from first word of kegiatan + increment
            $firstWord = strtolower(explode(' ', $pengajuan->tujuan_peminjaman)[0]);
            $counter = 1;
            $username = $firstWord . $counter;
            
            while (User::where('username', $username)->exists()) {
                $counter++;
                $username = $firstWord . $counter;
            }
            \Log::info("Generated username: {$username}");

            // Generate random password
            $password = Str::random(8);
            $hashedPassword = Hash::make($password);
            \Log::info("Generated password: {$password}");

            // Calculate account expiry (end date + 7 days)
            $accountExpiry = date('Y-m-d', strtotime($pengajuan->tanggal_berakhir_kegiatan . ' +7 days'));
            \Log::info("Account expiry date: {$accountExpiry}");

            // Determine role based on jenis_peminjam
            $roleId = $pengajuan->jenis_peminjam === 'civitas_akademik' ? 3 : 4; // 3 = user_fmipa, 4 = user_non_fmipa
            \Log::info("Assigned role ID: {$roleId} for jenis_peminjam: {$pengajuan->jenis_peminjam}");

            // Create user account
            $userData = [
                'username' => $username,
                'password' => $hashedPassword,
                'nama_penanggung_jawab' => $pengajuan->nama_penanggung_jawab,
                'email' => $pengajuan->email,
                'no_hp' => $pengajuan->no_hp,
                'no_identitas' => $pengajuan->no_identitas,
                'surat_keterangan' => $pengajuan->surat_keterangan,
                'id_role' => $roleId,
                'is_active' => 1,
                'tanggal_berakhir' => $accountExpiry,
                'created_at' => now(),
            ];
            \Log::info("Creating user with data: " . json_encode($userData));

            $user = User::create($userData);
            \Log::info("User created successfully with ID: {$user->id}");

            // Update pengajuan status
            $pengajuan->update([
                'status_verifikasi' => 'approved',
                'tanggal_verifikasi' => now(),
            ]);
            \Log::info("Pengajuan status updated to approved");

            // Try to send email with credentials
            try {
                \Log::info("Attempting to send email to: {$pengajuan->email}");
                
                Mail::send('emails.credentials', [
                    'username' => $username,
                    'password' => $password,
                    'nama' => $pengajuan->nama_penanggung_jawab,
                    'kegiatan' => $pengajuan->tujuan_peminjaman,
                    'expiry' => $accountExpiry
                ], function ($message) use ($pengajuan) {
                    $message->to($pengajuan->email)
                            ->subject('Kredensial Akun SIMBARA - ' . $pengajuan->tujuan_peminjaman);
                });
                
                \Log::info("Email sent successfully");
            } catch (\Exception $e) {
                // Email failed, but we'll continue - admin can see credentials in dashboard
                \Log::error('Failed to send credentials email: ' . $e->getMessage());
                \Log::error('Email error trace: ' . $e->getTraceAsString());
            }

            DB::commit();
            \Log::info("Transaction committed successfully");

            return redirect()->route('superadmin.verifikasi.index')
                ->with('success', 'Pengajuan berhasil disetujui dan akun telah dibuat.')
                ->with('credentials', [
                    'username' => $username,
                    'password' => $password,
                    'email' => $pengajuan->email
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Account creation failed: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject registration request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        try {
            $pengajuan = PengajuanPendaftaran::findOrFail($id);
            
            if ($pengajuan->status_verifikasi !== 'pending') {
                return redirect()->back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
            }

            $pengajuan->update([
                'status_verifikasi' => 'rejected',
                'tanggal_verifikasi' => now(),
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);

            // Try to send rejection email
            try {
                Mail::send('emails.rejection', [
                    'nama' => $pengajuan->nama_penanggung_jawab,
                    'kegiatan' => $pengajuan->tujuan_peminjaman,
                    'alasan' => $request->alasan_penolakan
                ], function ($message) use ($pengajuan) {
                    $message->to($pengajuan->email)
                            ->subject('Pengajuan Pendaftaran SIMBARA Ditolak');
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection email: ' . $e->getMessage());
            }

            return redirect()->route('superadmin.verifikasi.index')
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve multiple registration requests
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:pengajuan_pendaftaran,id_pengajuan'
        ]);

        $successCount = 0;
        $failedCount = 0;

        foreach ($request->selected_items as $id) {
            try {
                $this->approve($id);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Bulk approve failed for ID ' . $id . ': ' . $e->getMessage());
            }
        }

        $message = "Berhasil menyetujui {$successCount} pengajuan.";
        if ($failedCount > 0) {
            $message .= " {$failedCount} pengajuan gagal diproses.";
        }

        return redirect()->route('superadmin.verifikasi.index')->with('success', $message);
    }

    /**
     * Get statistics for API/AJAX calls
     */
    public function getStats()
    {
        return response()->json([
            'total_pengajuan' => PengajuanPendaftaran::count(),
            'pending_verifikasi' => PengajuanPendaftaran::where('status_verifikasi', 'pending')->count(),
            'approved' => PengajuanPendaftaran::where('status_verifikasi', 'approved')->count(),
            'rejected' => PengajuanPendaftaran::where('status_verifikasi', 'rejected')->count(),
        ]);
    }
} 