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

        // Check if the authenticated admin is superadmin
        $admin = auth()->guard('admin')->user();
        if (!$admin || $admin->role->nama_role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
