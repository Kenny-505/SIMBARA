<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCivitasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated as user
        if (!auth()->guard('user')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai user terlebih dahulu.');
        }

        // Check if the authenticated user is active
        $user = auth()->guard('user')->user();
        if (!$user || !$user->is_active) {
            // Clear session data when user is inactive
            $request->session()->forget('cart');
            $request->session()->flush();
            
            auth()->guard('user')->logout();
            
            // Clear cache
            \Illuminate\Support\Facades\Cache::flush();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Akun user tidak aktif.');
        }

        // Check if the authenticated user is civitas akademik
        if (!$user || $user->role->nama_role !== 'user_fmipa') {
            abort(403, 'Akses ditolak. Hanya User Civitas Akademik FMIPA yang dapat mengakses halaman ini.');
        }

        // Check if account is not expired
        if ($user->tanggal_berakhir && $user->tanggal_berakhir < now()) {
            // Clear session data when account expired
            $request->session()->forget('cart');
            $request->session()->flush();
            
            auth()->guard('user')->logout();
            
            // Clear cache
            \Illuminate\Support\Facades\Cache::flush();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Akun Anda telah kedaluwarsa. Silakan daftar ulang.');
        }

        return $next($request);
    }
}
