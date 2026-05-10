<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [ParkingController::class, 'index'])->name('parking.index');
    Route::post('/predict', [ParkingController::class, 'predict'])->name('parking.predict');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [ParkingController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dataset', [ParkingController::class, 'adminView'])->name('admin.dataset');
    Route::post('/admin/dataset/import', [ParkingController::class, 'importCsv'])->name('admin.dataset.import');
});

