<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $jabatan = strtolower($user->jabatan->nama ?? '');

        $allowedRoles = array_map('strtolower', $roles);

        if (in_array('administrator', $allowedRoles) || in_array($jabatan, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
