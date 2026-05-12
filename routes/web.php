<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

// Import Auth Controller milik Anda
use App\Http\Controllers\AuthController;

// Import Controller Modular yang baru saja kita buat
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\DatasetController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\Admin\ManagementController;

// ==========================================
// 1. RUTE AUTENTIKASI (Hanya untuk tamu/belum login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost']);
});

// ==========================================
// 2. RUTE PUBLIK UTAMA (Bebas Akses Tanpa Login)
// ==========================================
Route::get('/', [PublicController::class, 'index'])->name('parking.index');
Route::post('/predict', [PublicController::class, 'predict'])->name('parking.predict');

// ==========================================
// 3. RUTE USER (Wajib Login, Bebas Akses Role Apa Saja)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/riwayat-saya', [PublicController::class, 'myHistory'])->name('user.history');
});

// ==========================================
// 4. RUTE KHUSUS ADMIN (Wajib Login & Role = Admin)
// ==========================================
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // 1. Dashboard Analitik
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. Halaman Riwayat Pencarian
    Route::get('/admin/riwayat', [HistoryController::class, 'index'])->name('admin.history');
    Route::get('/admin/riwayat/export-pdf', [HistoryController::class, 'exportPdf'])->name('admin.history.pdf');

    // 3. Kelola Dataset CSV
    Route::get('/admin/dataset', [DatasetController::class, 'index'])->name('admin.dataset');
    Route::post('/admin/dataset/import', [DatasetController::class, 'importCsv'])->name('admin.dataset.import');

    // 4. Manajemen Sistem & Pengguna
    Route::get('/admin/visualisasi', [ManagementController::class, 'visualisasi'])->name('admin.visualisasi');
    Route::get('/admin/users', [ManagementController::class, 'users'])->name('admin.users');
});
