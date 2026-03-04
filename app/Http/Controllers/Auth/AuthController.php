<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function($query) use ($request) {
                $query->where('nip', $request->nip)->orWhere('email', $request->nip);
            })
            ->where('status_karyawan', 'aktif')
            ->where('status_login', 'aktif')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'nip' => 'NIP/Email tidak ditemukan atau akun tidak aktif.',
            ])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'))->with('success', 'Login berhasil!');
    }

    /**
     * Handle logout process
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
