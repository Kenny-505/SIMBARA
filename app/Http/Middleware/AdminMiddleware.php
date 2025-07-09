<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
            auth()->guard('admin')->logout();
            return redirect()->route('login')->with('error', 'Akun admin tidak aktif.');
        }

        // Check if the authenticated admin has admin or superadmin role
        if (!in_array($admin->role->nama_role, ['admin', 'superadmin'])) {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
