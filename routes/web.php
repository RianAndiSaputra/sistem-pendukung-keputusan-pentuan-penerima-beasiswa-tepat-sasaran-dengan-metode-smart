<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\HasilSeleksiController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa Routes
    Route::resource('mahasiswa', MahasiswaController::class);
    
    // Kriteria Routes
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::post('/kriteria/update', [KriteriaController::class, 'update'])->name('kriteria.update');
    
    // Perhitungan Routes
    Route::get('perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
    Route::post('perhitungan/proses', [PerhitunganController::class, 'proses'])->name('perhitungan.proses');
    
    // Hasil Seleksi Routes
    Route::get('hasil', [HasilSeleksiController::class, 'index'])->name('hasil.index');
    
    // Periode Routes
    Route::resource('periode', PeriodeController::class);
    
    // Admin Management Routes - Tanpa middleware dulu untuk testing
    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('admin', [AdminController::class, 'store'])->name('admin.store');
    Route::put('admin/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
    
    // Laporan Routes
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    // Di dalam group middleware auth:admin, tambahkan:
    Route::get('laporan/preview', [LaporanController::class, 'preview'])->name('laporan.preview');
});

// Route::get('/', function () {
//     return redirect('/dashboard');
// });