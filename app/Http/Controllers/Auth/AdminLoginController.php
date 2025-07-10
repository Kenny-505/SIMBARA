<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'identitas' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'kegiatan' => 'required|string|max:255',
            'tujuan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_peminjam' => 'required|in:civitas,non_civitas',
            'password' => 'required|confirmed|min:8',
        ]);

        // Here you would typically create a user registration request
        // For now, we'll just redirect with success message
        
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan tunggu verifikasi admin.');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        // Attempt to authenticate admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if admin is active
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'username' => 'Akun admin tidak aktif. Silakan hubungi Super Admin.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($admin->role->nama_role === 'superadmin') {
                return redirect()->intended(route('superadmin.dashboard'));
            } else {
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    /**
     * Handle admin logout request.
     */
    public function logout(Request $request)
    {
        // Clear all session data including cart before logout
        $request->session()->forget('cart');
        $request->session()->flush();
        
        Auth::guard('admin')->logout();

        // Clear all cache
        \Illuminate\Support\Facades\Cache::flush();
        
        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any remember tokens by setting a new session
        $request->session()->migrate(true);

        return redirect()->route('admin.login');
    }
}
