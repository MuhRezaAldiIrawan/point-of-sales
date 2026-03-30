<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['session' => 'Silakan login terlebih dahulu.']);
        }

        $user = Auth::user();

        if ($user->status_karyawan !== 'aktif' || $user->status_login !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['session' => 'Akun Anda tidak aktif.']);
        }

        return $next($request);
    }
}
