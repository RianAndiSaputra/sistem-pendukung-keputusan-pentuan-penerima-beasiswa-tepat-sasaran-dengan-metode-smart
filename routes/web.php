<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\HasilSeleksiController;

// Redirect root URL ke login page
Route::get('/', function () {
    return redirect()->route('admin.login.form');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa Routes - menggunakan parameter biasa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    
    // Kriteria Routes
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::post('/kriteria/update', [KriteriaController::class, 'update'])->name('kriteria.update');
    
    // Perhitungan Routes
    Route::get('perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
    Route::post('perhitungan/proses', [PerhitunganController::class, 'proses'])->name('perhitungan.proses');
    Route::get('perhitungan/mahasiswa/{id}/detail', [PerhitunganController::class, 'getDetailMahasiswa'])->name('perhitungan.mahasiswa.detail');
    
    // Hasil Seleksi Routes
    Route::get('hasil', [HasilSeleksiController::class, 'index'])->name('hasil.index');
    Route::get('hasil/{id}/detail', [HasilSeleksiController::class, 'getDetail'])->name('hasil.detail');
    Route::get('hasil/export', [HasilSeleksiController::class, 'export'])->name('hasil.export');
    
    // Periode Routes
 Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::put('/periode/{periode}', [PeriodeController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');
    
    // AJAX route untuk get data periode
    Route::get('/periode/{periode}/ajax', [PeriodeController::class, 'getAjaxData'])->name('periode.ajax');
    Route::get('/periode/{periode}/modal', [PeriodeController::class, 'getForModal'])->name('periode.modal');
    
    // Admin Management Routes
    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('admin', [AdminController::class, 'store'])->name('admin.store');
    Route::put('admin/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
    
    // Laporan Routes
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('laporan/preview', [LaporanController::class, 'preview'])->name('laporan.preview');
    Route::get('laporan/komprehensif', [LaporanController::class, 'laporanKomprehensif'])->name('laporan.komprehensif');
    Route::get('laporan/komprehensif/export', [LaporanController::class, 'exportKomprehensif'])->name('laporan.komprehensif.export');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
});