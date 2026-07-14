<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            if (Auth::user()->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('staff.dashboard');
            }
        }
        
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if (Auth::user()->role === 'Admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
            } else {
                return redirect()->route('staff.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate token
        $request->session()->regenerateToken();
        
        // Redirect ke halaman login dengan pesan
        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
}