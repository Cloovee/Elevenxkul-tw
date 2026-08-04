<?php

use App\Http\Controllers\AbsensiPelatihController;
use App\Http\Controllers\AbsensiPesertaController;
use App\Http\Controllers\NilaiPesertaController;
use App\Http\Controllers\PembinaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PembinaDashboardController::class, 'index']);

Route::prefix('pembina')->name('pembina.')->group(function () {
    Route::get('/dashboard', [PembinaDashboardController::class, 'index'])->name('dashboard');

    // ===== CRUD: Melihat & mengelola Absensi Peserta =====
    Route::prefix('absensi-peserta')->name('absensi.')->group(function () {
        Route::get('/create', [AbsensiPesertaController::class, 'create'])->name('create');
        Route::post('/', [AbsensiPesertaController::class, 'store'])->name('store');
        Route::get('/{absensiPeserta}/edit', [AbsensiPesertaController::class, 'edit'])->name('edit');
        Route::put('/{absensiPeserta}', [AbsensiPesertaController::class, 'update'])->name('update');
        Route::delete('/{absensiPeserta}', [AbsensiPesertaController::class, 'destroy'])->name('destroy');
    });

    // ===== CRUD: Memvalidasi Absensi Pelatih =====
    Route::prefix('validasi-pelatih')->name('validasi.')->group(function () {
        Route::get('/create', [AbsensiPelatihController::class, 'create'])->name('create');
        Route::post('/', [AbsensiPelatihController::class, 'store'])->name('store');
        Route::get('/{absensiPelatih}/edit', [AbsensiPelatihController::class, 'edit'])->name('edit');
        Route::put('/{absensiPelatih}', [AbsensiPelatihController::class, 'update'])->name('update');
        Route::delete('/{absensiPelatih}', [AbsensiPelatihController::class, 'destroy'])->name('destroy');
        Route::post('/{absensiPelatih}/setujui', [AbsensiPelatihController::class, 'setujui'])->name('setujui');
        Route::post('/{absensiPelatih}/tolak', [AbsensiPelatihController::class, 'tolak'])->name('tolak');
    });

    // ===== CRUD: Memberi Nilai Peserta =====
    Route::prefix('nilai-peserta')->name('nilai.')->group(function () {
        Route::post('/{peserta}', [NilaiPesertaController::class, 'store'])->name('simpan');
        Route::get('/{nilaiPeserta}/edit', [NilaiPesertaController::class, 'edit'])->name('edit');
        Route::put('/{nilaiPeserta}', [NilaiPesertaController::class, 'update'])->name('update');
        Route::delete('/{nilaiPeserta}', [NilaiPesertaController::class, 'destroy'])->name('destroy');
    });
});