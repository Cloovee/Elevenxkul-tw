<?php

use App\Http\Controllers\AbsensiPelatihController;
use App\Http\Controllers\AbsensiPesertaController;
use App\Http\Controllers\NilaiPesertaController;
use App\Http\Controllers\PembinaDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\EkskulController;
use Illuminate\Support\Facades\Route;

// 1. Arahkan route utama '/' langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Grup route khusus Admin (wajib login)
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');
    Route::resource('ekskul', EkskulController::class);
});

// 3. Grup route khusus Pembina (wajib login)
Route::prefix('pembina')->middleware('auth')->name('pembina.')->group(function () {
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

// 4. Dashboard umum (redirect setelah login, dipakai role lain / fallback)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 5. Dashboard Ketua
Route::get('/dashboard-ketua', function () {
    return view('dashboard-ketua.index');
})->middleware(['auth', 'verified'])->name('dashboard.ketua');

// 6. Profile (semua role yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';