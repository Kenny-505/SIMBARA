<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserLoginController extends Controller
{
    /**
     * Show the user login form.
     */
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    /**
     * Handle user login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        // Attempt to authenticate user
        if (Auth::guard('user')->attempt($credentials)) {
            $user = Auth::guard('user')->user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::guard('user')->logout();
                return back()->withErrors([
                    'username' => 'Akun user tidak aktif. Silakan hubungi admin.',
                ]);
            }

            // Check if account is not expired
            if ($user->tanggal_berakhir && $user->tanggal_berakhir < now()) {
                Auth::guard('user')->logout();
                return back()->withErrors([
                    'username' => 'Akun Anda telah kedaluwarsa. Silakan daftar ulang.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role->nama_role === 'user_fmipa') {
                return redirect()->intended(route('user.civitas.dashboard'));
            } else {
                return redirect()->intended(route('user.non_civitas.dashboard'));
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    /**
     * Handle user logout request.
     */
    public function logout(Request $request)
    {
        // Clear all session data including cart before logout
        $request->session()->forget('cart');
        $request->session()->flush();
        
        Auth::guard('user')->logout();

        // Clear all cache
        \Illuminate\Support\Facades\Cache::flush();
        
        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any remember tokens by setting a new session
        $request->session()->migrate(true);

        return redirect()->route('user.login');
    }
}
