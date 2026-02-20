<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login
        if (Auth::check()) {
            // Cek apakah statusnya 0 (Nonaktif)
            if (Auth::user()->status == 0) {
                Auth::logout(); // Paksa keluar

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi HR.');
            }
        }
        return $next($request);
    }
}
