<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPendaftaran;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    /**
     * Display the registration form
     */
    public function create()
    {
        return view('pendaftaran.create');
    }

    /**
     * Store a new registration request
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pengajuan_pendaftaran,email',
            'no_hp' => 'required|string|max:20',
            'no_identitas' => 'required|string|max:50',
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan_peminjaman' => 'required|string',
            'tanggal_mulai_kegiatan' => 'required|date|after_or_equal:today',
            'tanggal_berakhir_kegiatan' => 'required|date|after_or_equal:tanggal_mulai_kegiatan',
            'surat_keterangan' => 'required|file|max:5120', // 5MB max
            'jenis_peminjam' => 'required|in:civitas_akademik,non_civitas_akademik',
        ], [
            'tanggal_mulai_kegiatan.after_or_equal' => 'Tanggal mulai kegiatan tidak boleh kurang dari hari ini.',
            'tanggal_berakhir_kegiatan.after_or_equal' => 'Tanggal berakhir kegiatan tidak boleh kurang dari tanggal mulai kegiatan.',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle file upload
            $suratKeterangan = null;
            if ($request->hasFile('surat_keterangan')) {
                $suratKeterangan = file_get_contents($request->file('surat_keterangan')->getRealPath());
            }

            // Create new registration request
            $pengajuan = PengajuanPendaftaran::create([
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'no_identitas' => $request->no_identitas,
                'nama_kegiatan' => $request->nama_kegiatan,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'tanggal_mulai_kegiatan' => $request->tanggal_mulai_kegiatan,
                'tanggal_berakhir_kegiatan' => $request->tanggal_berakhir_kegiatan,
                'surat_keterangan' => $suratKeterangan,
                'jenis_peminjam' => $request->jenis_peminjam,
                'status_verifikasi' => 'pending',
                'tanggal_pengajuan' => now(),
            ]);

            DB::commit();

            return redirect()->route('pendaftaran.success')
                ->with('success', 'Pengajuan pendaftaran berhasil dikirim! Silakan tunggu verifikasi dari admin.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Display success page
     */
    public function success()
    {
        return view('pendaftaran.success');
    }

    /**
     * Generate username and password for approved user
     */
    public function generateCredentials($pengajuan)
    {
        // Generate username based on name and random number
        $baseName = strtolower(str_replace(' ', '', $pengajuan->nama_penanggung_jawab));
        $baseName = preg_replace('/[^a-z0-9]/', '', $baseName);
        $baseName = substr($baseName, 0, 8);
        
        $username = $baseName . rand(100, 999);
        
        // Ensure username is unique
        while (User::where('username', $username)->exists()) {
            $username = $baseName . rand(100, 999);
        }

        // Generate random password
        $password = Str::random(8);

        return [
            'username' => $username,
            'password' => $password,
            'password_hash' => Hash::make($password)
        ];
    }

    /**
     * Create user account from approved pengajuan
     */
    public function createUserAccount($pengajuan)
    {
        $credentials = $this->generateCredentials($pengajuan);
        
        // Determine role based on jenis_peminjam
        $roleId = $pengajuan->jenis_peminjam === 'civitas_akademik' ? 3 : 4; // 3 = civitas, 4 = non-civitas
        
        // Calculate expiry date (H+7 from tanggal_berakhir_kegiatan)
        $tanggalBerakhir = $pengajuan->getAccountExpiryDate();

        $user = User::create([
            'id_role' => $roleId,
            'username' => $credentials['username'],
            'password' => $credentials['password_hash'],
            'nama_penanggung_jawab' => $pengajuan->nama_penanggung_jawab,
            'email' => $pengajuan->email,
            'no_identitas' => $pengajuan->no_identitas,
            'no_hp' => $pengajuan->no_hp,
            'surat_keterangan' => $pengajuan->surat_keterangan,
            'tanggal_berakhir' => $tanggalBerakhir,
            'is_active' => 1,
        ]);

        // Send credentials via email
        $this->sendCredentialsEmail($user, $credentials['password']);

        return $user;
    }

    /**
     * Send credentials email to user
     */
    private function sendCredentialsEmail($user, $plainPassword)
    {
        $data = [
            'user' => $user,
            'username' => $user->username,
            'password' => $plainPassword,
            'login_url' => route('user.login'),
        ];

        try {
            Mail::send('emails.credentials', $data, function ($message) use ($user) {
                $message->to($user->email, $user->nama_penanggung_jawab)
                        ->subject('Akun SIMBARA - Kredensial Login Anda');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send credentials email: ' . $e->getMessage());
        }
    }

    /**
     * Check registration status
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $pengajuan = PengajuanPendaftaran::where('email', $request->email)->first();

        if (!$pengajuan) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        return view('pendaftaran.status', compact('pengajuan'));
    }
} 