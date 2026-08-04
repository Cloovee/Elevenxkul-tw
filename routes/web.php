<?php

use App\Http\Controllers\PembinaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PembinaDashboardController::class, 'index']);

Route::prefix('pembina')->name('pembina.')->group(function () {
    Route::get('/dashboard', [PembinaDashboardController::class, 'index'])->name('dashboard');

    Route::post('/validasi-pelatih/{absensiPelatih}/setujui', [PembinaDashboardController::class, 'setujuiValidasi'])
        ->name('validasi.setujui');

    Route::post('/validasi-pelatih/{absensiPelatih}/tolak', [PembinaDashboardController::class, 'tolakValidasi'])
        ->name('validasi.tolak');

    Route::post('/nilai-peserta/{peserta}', [PembinaDashboardController::class, 'simpanNilai'])
        ->name('nilai.simpan');
});