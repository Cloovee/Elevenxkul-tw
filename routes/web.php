<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\EkskulController;

Route::get('/', function () {
    return redirect('/login');
});

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');
    Route::resource('ekskul', EkskulController::class);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard-ketua', function () {
    return view('dashboard-ketua.index');
})->middleware(['auth', 'verified', 'role:Ketua'])->name('dashboard.ketua');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/absensi-pelatih', function () {
    return view('dashboard-ketua.absensi-pelatih');
})->middleware(['auth', 'verified'])->name('ketua.absensi-pelatih');

Route::get('/absensi-peserta', function () {
    return view('dashboard-ketua.absensi-peserta');
})->middleware(['auth', 'verified'])->name('ketua.absensi-peserta');

Route::get('/kelola-anggota', function () {
    $anggota = [
        ['id' => 1, 'nama' => 'Raka Pratama', 'nis' => '2023001', 'tanggal_bergabung' => '12 Jan 2026', 'status' => 'aktif'],
        ['id' => 2, 'nama' => 'Dinda Ayu', 'nis' => '2023002', 'tanggal_bergabung' => '15 Jan 2026', 'status' => 'aktif'],
        ['id' => 3, 'nama' => 'Bagas Wirawan', 'nis' => '2023003', 'tanggal_bergabung' => '20 Feb 2026', 'status' => 'tidak aktif'],
    ];

    return view('dashboard-ketua.kelola-anggota.index', compact('anggota'));
})->middleware(['auth', 'verified'])->name('ketua.kelola-anggota');

Route::get('/kelola-anggota/{id}', function ($id) {
    $daftar = [
        1 => ['nama' => 'Raka Pratama', 'nis' => '2023001', 'tanggal_bergabung' => '2026-01-12', 'status' => 'aktif'],
        2 => ['nama' => 'Dinda Ayu', 'nis' => '2023002', 'tanggal_bergabung' => '2026-01-15', 'status' => 'aktif'],
        3 => ['nama' => 'Bagas Wirawan', 'nis' => '2023003', 'tanggal_bergabung' => '2026-02-20', 'status' => 'tidak aktif'],
    ];

    $anggota = $daftar[$id] ?? abort(404);

    return view('dashboard-ketua.kelola-anggota.detail', compact('anggota', 'id'));
})->middleware(['auth', 'verified'])->name('ketua.kelola-anggota.detail');

require __DIR__.'/auth.php';