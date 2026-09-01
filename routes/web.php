<?php

use App\Http\Controllers\Pembina\AbsensiPelatihController;
use App\Http\Controllers\Pembina\AbsensiPesertaController;
use App\Http\Controllers\Pembina\NilaiPesertaController;
use App\Http\Controllers\Pembina\PembinaDashboardController;
use App\Http\Controllers\Pembina\PembinaProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PembinaController;
use App\Http\Controllers\Admin\EkskulController;
use App\Http\Controllers\Admin\PembinaController as AdminPembinaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// 3. Grup route khusus Pembina (wajib login)
Route::prefix('pembina')->middleware('auth')->name('pembina.')->group(function () {
    Route::get('/dashboard', [PembinaDashboardController::class, 'index'])->name('dashboard');

    // ===== Profil Pembina (halaman khusus, terpisah dari /profile umum) =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PembinaProfileController::class, 'index'])->name('index');
        Route::patch('/', [PembinaProfileController::class, 'update'])->name('update');
        Route::patch('/password', [PembinaProfileController::class, 'updatePassword'])->name('password');
    });

    // ===== Melihat & mengelola Absensi Peserta (data diinput oleh admin) =====
    Route::prefix('absensi-peserta')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiPesertaController::class, 'index'])->name('index');
        Route::get('/{absensiPeserta}/edit', [AbsensiPesertaController::class, 'edit'])->name('edit');
        Route::put('/{absensiPeserta}', [AbsensiPesertaController::class, 'update'])->name('update');
        Route::delete('/{absensiPeserta}', [AbsensiPesertaController::class, 'destroy'])->name('destroy');
    });

    // ===== Memvalidasi Absensi Pelatih (data diinput oleh admin) =====
    Route::prefix('validasi-pelatih')->name('validasi.')->group(function () {
        Route::get('/', [AbsensiPelatihController::class, 'index'])->name('index');
        Route::get('/{absensiPelatih}/edit', [AbsensiPelatihController::class, 'edit'])->name('edit');
        Route::put('/{absensiPelatih}', [AbsensiPelatihController::class, 'update'])->name('update');
        Route::delete('/{absensiPelatih}', [AbsensiPelatihController::class, 'destroy'])->name('destroy');
        Route::post('/{absensiPelatih}/setujui', [AbsensiPelatihController::class, 'setujui'])->name('setujui');
        Route::post('/{absensiPelatih}/tolak', [AbsensiPelatihController::class, 'tolak'])->name('tolak');
    });

    // ===== CRUD: Memberi Nilai Peserta =====
    Route::prefix('nilai-peserta')->name('nilai.')->group(function () {
        Route::get('/', [NilaiPesertaController::class, 'index'])->name('index');
        Route::post('/{peserta}', [NilaiPesertaController::class, 'store'])->name('simpan');
        Route::get('/{nilaiPeserta}/edit', [NilaiPesertaController::class, 'edit'])->name('edit');
        Route::put('/{nilaiPeserta}', [NilaiPesertaController::class, 'update'])->name('update');
        Route::delete('/{nilaiPeserta}', [NilaiPesertaController::class, 'destroy'])->name('destroy');
    });
});

// 4. Dashboard umum (redirect setelah login, dipakai role lain / fallback)
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

// 5. Dashboard Ketua
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
// 6. Profile (semua role yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});