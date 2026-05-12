<?php

namespace App\Http\Controllers\Admin; // Tambahkan \Admin di sini

use App\Http\Controllers\Controller; // Tambahkan ini agar tidak error
use Illuminate\Http\Request;
use App\Models\User;

class ManagementController extends Controller // Ubah nama class-nya
{
    /**
     * Halaman Visualisasi Model (Melihat Gambar Pohon)
     */
    public function visualisasi()
    {
        return view('admin.visualisasi');
    }

    /**
     * Halaman Manajemen Pengguna (Daftar Mahasiswa)
     */
   public function users(Request $request)
{
    // 1. Inisialisasi Query (Tampilkan semua selain admin)
    $query = User::where('role', '!=', 'admin');

    // 2. Tambahkan Logika Search (Jika input search diisi)
    if ($request->filled('search')) {
        $keyword = $request->search;
        $query->where(function($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%') // Cari berdasarkan Nama
              ->orWhere('email', 'like', '%' . $keyword . '%'); // Atau Email
        });
    }

    // 3. Ambil hasil dengan Pagination (10 data per halaman)
    // Jangan lupa appends agar saat pindah halaman search tidak hilang
    $users = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.users.index', compact('users'));
}

}
