<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // <--- PENTING: Import Model ActivityLog

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- CATAT LOG LOGIN ---
            ActivityLog::record('LOGIN', 'User has logged in to the system.');

            return redirect()->intended('/')->with('success', 'Login Berhasil! Selamat Datang.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        // --- CATAT LOG LOGOUT (Sebelum session dihancurkan) ---
        ActivityLog::record('LOGOUT', 'User logged out from system.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}