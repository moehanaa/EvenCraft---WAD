<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login.
     */
    public function showLoginForm()
    {
        // Jika pengguna sudah login, langsung arahkan ke modul tiket
        if (Auth::check()) {
            return redirect()->route('tickets.index');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna (Login).
     */
    public function login(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // 2. Percobaan autentikasi (Auth::attempt)
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleLabel = ($user->role === 'admin') ? 'Administrator' : 'Staff Tiket';

            return redirect()->intended(route('tickets.index'))
                ->with('success', "Selamat datang, {$user->name}! Anda berhasil login sebagai {$roleLabel}.");
        }

        // 3. Jika autentikasi gagal
        return back()->withErrors([
            'email' => 'Kombinasi email dan password yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Memproses keluar sistem (Logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout dari sistem EventCraft.');
    }
}
