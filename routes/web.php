<?php

use App\Http\Controllers\Pembina\AbsensiPelatihController;
use App\Http\Controllers\Pembina\AbsensiPesertaController;
use App\Http\Controllers\Pembina\NilaiPesertaController;
use App\Http\Controllers\Pembina\PelatihController as PembinaPelatihController;
use App\Http\Controllers\Pembina\PembinaDashboardController;
use App\Http\Controllers\Pembina\PembinaProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PembinaController;
use App\Http\Controllers\Admin\EkskulController;
use App\Http\Controllers\Admin\KelasController;
use Illuminate\Support\Facades\Route;
use App\Models\Peserta;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// 3. Grup route khusus Pembina (wajib login)
Route::prefix('pembina')->middleware(['auth', 'role:Pembina'])->name('pembina.')->group(function () {
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

    // ===== CRUD: Kelola Pelatih (untuk ekskul yang dibina) =====
    // Relasi: Admin menentukan pembina pemilik ekskul (CRUD Ekskul) -> Pembina di sini
    // mengelola biodata pelatih ekskulnya -> Ketua memakai data pelatih ini saat input absensi.
    Route::prefix('pelatih')->name('pelatih.')->group(function () {
        Route::get('/', [PembinaPelatihController::class, 'index'])->name('index');
        Route::get('/create', [PembinaPelatihController::class, 'create'])->name('create');
        Route::post('/store', [PembinaPelatihController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PembinaPelatihController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PembinaPelatihController::class, 'update'])->name('update');
        Route::delete('/{id}', [PembinaPelatihController::class, 'destroy'])->name('destroy');
    });
});

// 4. Dashboard umum (redirect setelah login, dipakai role lain / fallback)
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'Admin'    => redirect()->route('admin.dashboard'),
        'Pembina'  => redirect()->route('pembina.dashboard'),
        'Ketua'    => redirect()->route('dashboard.ketua'),
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
    // Sementara: Ketua mengelola ekskul dengan id_ekskul = 1
    $jumlahPeserta = App\Models\Peserta::where('id_ekskul', 1)->count();

    $totalPeserta = App\Models\Peserta::where('id_ekskul', 1)
        ->where('status', 'aktif')
        ->count();

    // Riwayat aktivitas: gabungan dari 3 sumber, diurutkan dari yang terbaru.
    $riwayatPelatih = App\Models\AbsensiPelatih::with('pelatih')
        ->latest('created_at')
        ->take(5)
        ->get()
        ->map(fn ($r) => [
            'warna' => 'periwinkle',
            'teks' => 'Menginput absensi pelatih ' . ($r->pelatih->nama_pelatih ?? '-'),
            'waktu' => $r->created_at,
        ]);

    $riwayatPeserta = App\Models\AbsensiPeserta::whereHas('peserta', fn ($q) => $q->where('id_ekskul', 1))
        ->selectRaw('tanggal_absensi, COUNT(*) as jumlah, MAX(created_at) as waktu')
        ->groupBy('tanggal_absensi')
        ->orderByDesc('waktu')
        ->take(5)
        ->get()
        ->map(fn ($r) => [
            'warna' => 'mint',
            'teks' => 'Mengisi absensi peserta (' . $r->jumlah . ' orang)',
            'waktu' => \Illuminate\Support\Carbon::parse($r->waktu),
        ]);

    $riwayatAnggota = App\Models\Peserta::with('siswa')
        ->where('id_ekskul', 1)
        ->latest('created_at')
        ->take(5)
        ->get()
        ->map(fn ($p) => [
            'warna' => 'sky',
            'teks' => 'Menambahkan anggota baru: ' . ($p->siswa->nama_siswa ?? '-'),
            'waktu' => $p->created_at,
        ]);

    $riwayat = $riwayatPelatih
        ->concat($riwayatPeserta)
        ->concat($riwayatAnggota)
        ->sortByDesc('waktu')
        ->take(5)
        ->values();

    return view('dashboard-ketua.index', [
        'jumlahPeserta' => $jumlahPeserta,
        'totalPeserta' => $totalPeserta,
        'riwayat' => $riwayat,
    ]);
})->middleware(['auth', 'verified', 'role:Ketua'])->name('dashboard.ketua');


Route::get('/absensi-pelatih', [App\Http\Controllers\Ketua\AbsensiPelatihController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('ketua.absensi-pelatih');

Route::post('/absensi-pelatih', [App\Http\Controllers\Ketua\AbsensiPelatihController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('ketua.absensi-pelatih.store');

Route::get('/absensi-peserta', [App\Http\Controllers\Ketua\AbsensiPesertaController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('ketua.absensi-peserta');

Route::post('/absensi-peserta', [App\Http\Controllers\Ketua\AbsensiPesertaController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('ketua.absensi-peserta.store');

Route::get('/kelola-anggota', function () {

    $idEkskul = optional(optional(auth()->user()->siswa)->ekskulDipimpin)->id_ekskul;

    if (! $idEkskul) {
        return redirect()->route('dashboard.ketua')
            ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
    }

    $anggota = Peserta::with('siswa')
        ->where('id_ekskul', $idEkskul)
        ->get();

    return view(
        'dashboard-ketua.kelola-anggota.index',
        compact('anggota')
    );

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota');


Route::get('/kelola-anggota/tambah', function () {

    $idEkskul = optional(optional(auth()->user()->siswa)->ekskulDipimpin)->id_ekskul;

    // Siswa yang sudah jadi anggota ekskul ini gak usah muncul lagi di pilihan
    $idSiswaSudahAnggota = $idEkskul
        ? \App\Models\Peserta::where('id_ekskul', $idEkskul)->pluck('id_siswa')
        : collect();

    $daftarSiswa = \App\Models\Siswa::with('kelas')
        ->whereNotIn('id_siswa', $idSiswaSudahAnggota)
        ->orderBy('nama_siswa')
        ->get()
        ->map(fn ($s) => [
            'id_siswa'   => $s->id_siswa,
            'nama_siswa' => $s->nama_siswa,
            'nis'        => $s->NIS,
            'nama_kelas' => $s->nama_kelas,
        ]);

    $daftarKelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('jurusan')->orderBy('rombel')->get();

    return view('dashboard-ketua.kelola-anggota.tambah', compact('daftarSiswa', 'daftarKelas'));
})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.tambah');


Route::post('/kelola-anggota/tambah', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'id_siswa' => ['required', 'array', 'min:1'],
        'id_siswa.*' => ['integer', 'exists:siswa,id_siswa'],
    ], [
        'id_siswa.required' => 'Pilih minimal 1 siswa dulu.',
    ]);

    $idEkskul = optional(optional(auth()->user()->siswa)->ekskulDipimpin)->id_ekskul;

    if (! $idEkskul) {
        return redirect()->route('dashboard.ketua')
            ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
    }

    $idSiswaDipilih = collect($request->id_siswa)->unique()->values();

    // Siswa yang udah jadi anggota ekskul ini dilewati, biar gak dobel / kena unique constraint
    $idSudahAnggota = \App\Models\Peserta::where('id_ekskul', $idEkskul)
        ->whereIn('id_siswa', $idSiswaDipilih)
        ->pluck('id_siswa');

    $idBaru = $idSiswaDipilih->diff($idSudahAnggota)->values();

    if ($idBaru->isEmpty()) {
        return back()
            ->with('error', 'Semua siswa yang dipilih sudah jadi anggota ekskul ini.');
    }

    $now = now();
    $rows = $idBaru->map(fn ($id) => [
        'id_siswa'          => $id,
        'id_ekskul'         => $idEkskul,
        'tanggal_bergabung' => $now->toDateString(),
        'status'            => 'aktif',
        'created_at'        => $now,
        'updated_at'        => $now,
    ])->all();

    \App\Models\Peserta::insert($rows);

    $jumlahBerhasil  = count($rows);
    $jumlahDilewati  = $idSiswaDipilih->count() - $jumlahBerhasil;

    $pesan = $jumlahBerhasil > 1
        ? "{$jumlahBerhasil} anggota berhasil ditambahkan."
        : 'Anggota berhasil ditambahkan.';

    if ($jumlahDilewati > 0) {
        $pesan .= " {$jumlahDilewati} siswa dilewati karena sudah jadi anggota.";
    }

    return redirect()
        ->route('ketua.kelola-anggota')
        ->with('success', $pesan);

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.store');


Route::get('/kelola-anggota/{id}', function ($id) {

    $idEkskul = optional(optional(auth()->user()->siswa)->ekskulDipimpin)->id_ekskul;

    if (! $idEkskul) {
        return redirect()->route('dashboard.ketua')
            ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
    }

    $anggota = \App\Models\Peserta::with('siswa')
        ->where('id_anggota', $id)
        ->where('id_ekskul', $idEkskul)
        ->firstOrFail();

    return view(
        'dashboard-ketua.kelola-anggota.detail',
        compact('anggota')
    );

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.detail');

Route::delete('/kelola-anggota/{id}', function ($id) {

    $idEkskul = optional(optional(auth()->user()->siswa)->ekskulDipimpin)->id_ekskul;

    if (! $idEkskul) {
        return redirect()->route('dashboard.ketua')
            ->with('error', 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
    }

    // Cuma boleh hapus anggota yang emang ada di ekskul yang dia pimpin
    $anggota = \App\Models\Peserta::where('id_anggota', $id)
        ->where('id_ekskul', $idEkskul)
        ->first();

    if (! $anggota) {
        return redirect()
            ->route('ketua.kelola-anggota')
            ->with('error', 'Anggota tidak ditemukan.');
    }

    $namaSiswa = $anggota->nama;

    $anggota->delete();

    return redirect()
        ->route('ketua.kelola-anggota')
        ->with('success', "{$namaSiswa} berhasil dihapus dari anggota.");

})->middleware(['auth', 'verified', 'role:Ketua'])
  ->name('ketua.kelola-anggota.destroy');

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