<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear all session data including cart before logout
        $request->session()->forget('cart');
        $request->session()->flush();
        
        Auth::guard('web')->logout();

        // Clear all cache
        \Illuminate\Support\Facades\Cache::flush();
        
        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any remember tokens by setting a new session
        $request->session()->migrate(true);

        return redirect('/');
    }
}
