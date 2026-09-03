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
use App\Http\Controllers\Admin\KelasController;
use Illuminate\Support\Facades\Route;
use App\Models\Peserta;

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

            // Route spesifik (nama tetap, bukan wildcard) HARUS di atas /{id}
            Route::get('/import', [SiswaController::class, 'showImportForm'])->name('import.form');
            Route::post('/import', [SiswaController::class, 'import'])->name('import');
            Route::get('/export', [SiswaController::class, 'export'])->name('export');
            Route::get('/template', [SiswaController::class, 'downloadTemplate'])->name('template');

            // Wildcard /{id} ditaruh PALING BAWAH, biar nggak "nyerobot" route di atasnya
            Route::get('/{id}', [SiswaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SiswaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SiswaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');
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

        Route::prefix('/kelas')->name('kelas.')->group(function () {
            Route::get('/', [KelasController::class, 'index'])->name('index');
            Route::get('/create', [KelasController::class, 'create'])->name('create');
            Route::post('/store', [KelasController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KelasController::class, 'update'])->name('update');
            Route::delete('/{id}', [KelasController::class, 'destroy'])->name('destroy');
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

Route::middleware(['auth', 'verified', 'role:Ketua'])->group(function () {
    Route::get('/absensi-pelatih', [\App\Http\Controllers\Ketua\AbsensiPelatihController::class, 'index'])
        ->name('ketua.absensi-pelatih');
    Route::post('/absensi-pelatih', [\App\Http\Controllers\Ketua\AbsensiPelatihController::class, 'store'])
        ->name('ketua.absensi-pelatih.store');

    Route::get('/absensi-peserta', [\App\Http\Controllers\Ketua\AbsensiPesertaController::class, 'index'])
        ->name('ketua.absensi-peserta');
    Route::post('/absensi-peserta', [\App\Http\Controllers\Ketua\AbsensiPesertaController::class, 'store'])
        ->name('ketua.absensi-peserta.store');
});

Route::get('/kelola-anggota', function () {

    $anggota = Peserta::with('siswa')->get();

    return view(
        'dashboard-ketua.kelola-anggota.index',
        compact('anggota')
    );

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota');


Route::get('/kelola-anggota', function () {

    $anggota = Peserta::with('siswa')
        ->where('id_ekskul', 1)
        ->get();

    return view(
        'dashboard-ketua.kelola-anggota.index',
        compact('anggota')
    );

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota');


Route::get('/kelola-anggota/tambah', function () {
    return view('dashboard-ketua.kelola-anggota.tambah');
})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.tambah');


Route::post('/kelola-anggota/tambah', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'nama' => ['required', 'string'],
        'nis' => ['required', 'string'],
    ]);

    // Cari siswa berdasarkan nama dan NIS
    $siswa = \App\Models\Siswa::where('nama_siswa', $request->nama)
        ->where('NIS', $request->nis)
        ->first();

    // Jika siswa tidak ditemukan
    if (!$siswa) {
        return back()
            ->withInput()
            ->with('error', 'Siswa dengan nama dan NIS tersebut tidak ditemukan.');
    }

    // Sementara: Ketua mengelola Basket
    $idEkskul = 1;

    // Cek apakah siswa sudah menjadi anggota Basket
    $sudahAnggota = \App\Models\Peserta::where('id_siswa', $siswa->id_siswa)
        ->where('id_ekskul', $idEkskul)
        ->exists();

    if ($sudahAnggota) {
        return back()
            ->withInput()
            ->with('error', 'Siswa tersebut sudah menjadi anggota ekskul ini.');
    }

    // Simpan anggota baru
    \App\Models\Peserta::create([
        'id_siswa' => $siswa->id_siswa,
        'id_ekskul' => $idEkskul,
        'tanggal_bergabung' => now(),
        'status' => 'aktif',
    ]);

    return redirect()
        ->route('ketua.kelola-anggota')
        ->with('success', 'Anggota berhasil ditambahkan.');

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.store');


Route::get('/kelola-anggota/{id}', function ($id) {

    $anggota = \App\Models\Peserta::with('siswa')
        ->where('id_anggota', $id)
        ->where('id_ekskul', 1)
        ->firstOrFail();

    return view(
        'dashboard-ketua.kelola-anggota.detail',
        compact('anggota')
    );

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.detail');

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