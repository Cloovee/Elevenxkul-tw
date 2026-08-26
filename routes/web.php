<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PembinaController;
use App\Http\Controllers\Admin\EkskulController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'Admin'    => redirect()->route('admin.dashboard'),
        'Pembina'  => redirect()->route('pembina.dashboard'),
        'Ketua'    => redirect()->route('ketua.dashboard'),
        default    => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('/admin')
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');

        Route::prefix('/siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::get('/create', [SiswaController::class, 'create'])->name('create');
            Route::post('/store', [SiswaController::class, 'store'])->name('store');
            Route::get('/{id}', [SiswaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SiswaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SiswaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');

            Route::get('/import', [SiswaController::class, 'showImportForm'])->name('import.form');
            Route::post('/import', [SiswaController::class, 'import'])->name('import');
            Route::get('/export', [SiswaController::class, 'export'])->name('export');
            Route::get('/template', [SiswaController::class, 'downloadTemplate'])->name('template');
        });

        Route::prefix('/user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('/pembina')->name('pembina.')->group(function () {
            Route::get('/', [PembinaController::class, 'index'])->name('index');
            Route::get('/create', [PembinaController::class, 'create'])->name('create');
            Route::post('/store', [PembinaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PembinaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PembinaController::class, 'update'])->name('update');
            Route::delete('/{id}', [PembinaController::class, 'destroy'])->name('destroy');
        });

        // Kelola Ekskul — pakai resource, otomatis generate semua route CRUD
        // (index, create, store, show, edit, update, destroy) dengan parameter {ekskul}
        Route::resource('ekskul', EkskulController::class);
    });

/*
|--------------------------------------------------------------------------
| PEMBINA ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('/pembina')
    ->middleware(['auth', 'verified', 'role:Pembina'])
    ->name('pembina.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pembina.dashboard');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| KETUA ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('/ketua')
    ->middleware(['auth', 'verified', 'role:Ketua'])
    ->name('ketua.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('ketua.dashboard');
        })->name('dashboard');
    });

Route::get('/dashboard-ketua', function () {
    return view('dashboard-ketua.index');
})->middleware(['auth', 'verified', 'role:Ketua'])->name('dashboard.ketua');

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

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});