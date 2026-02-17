<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     $user = Auth::user();
    //     $roleNames = $user->roles->pluck('name')->first();
    //     if ($roleNames === 'user') {
    //         return redirect()->intended(route('frontend.dashboards', absolute: false));
    //     }else{
    //         return redirect()->intended(route('dashboards', absolute: false));
    //     }

    // }

    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();
    // Mengambil nama role pertama
    $roleName = $user->roles->pluck('name')->first();

    // 1. Jika tidak punya role sama sekali, langsung logout
    if (!$roleName) {
        return $this->forceLogout($request, 'Akun Anda tidak memiliki role akses.');
    }

    // 2. Jika role adalah 'user', ke dashboard frontend
    if ($roleName === 'user') {
        return redirect()->intended(route('frontend.dashboards', absolute: false));
    }

    // 3. Jika role adalah admin, finance, atau lainnya (selain user), ke dashboard admin
    // Anda bisa menambahkan pengecekan spesifik di sini jika diperlukan
    return redirect()->intended(route('dashboards', absolute: false));
}

/**
 * Fungsi pembantu untuk logout paksa
 */
private function forceLogout($request, $message)
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->withErrors(['email' => $message]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
