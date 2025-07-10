<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated as admin
        if (!auth()->guard('admin')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        // Check if the authenticated admin is active
        $admin = auth()->guard('admin')->user();
        if (!$admin || !$admin->is_active) {
            // Clear session data when admin is inactive
            $request->session()->forget('cart');
            $request->session()->flush();
            
            auth()->guard('admin')->logout();
            
            // Clear cache
            \Illuminate\Support\Facades\Cache::flush();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Akun admin tidak aktif.');
        }

        // Check if the authenticated admin is superadmin
        if (!$admin || $admin->role->nama_role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
