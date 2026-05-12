<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        // 1. Hapus batasan 'ends_with' agar email umum (Gmail/Yahoo) bisa masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem kami.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // 2. UX Tambahan: Logika deteksi kategori user untuk Flash Message
        $isCivitas = str_ends_with($user->email, '@mahasiswa.pcr.ac.id') || str_ends_with($user->email, '@pcr.ac.id');

        if ($isCivitas) {
            $pesanSukses = 'Selamat datang! Anda berhasil mendaftar sebagai Civitas PCR.';
        } else {
            $pesanSukses = 'Selamat datang! Anda berhasil mendaftar sebagai Tamu Publik.';
        }

        return redirect()->route('parking.index')->with('success', $pesanSukses);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek role pengguna dan arahkan ke dashboard yang tepat
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('parking.index')->with('success', 'Berhasil login kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
