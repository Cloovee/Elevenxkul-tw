<?php

use App\Http\Controllers\Api\V1\AbsensiPelatihController;
use App\Http\Controllers\Api\V1\AbsensiPesertaController;
use App\Http\Controllers\Api\V1\AnggotaController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BerandaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API untuk aplikasi mobile ElevenXkul (khusus role Ketua)
|--------------------------------------------------------------------------
| Prefix /api otomatis dari bootstrap/app.php. Autentikasi: token Sanctum
| (header "Authorization: Bearer <token>"), BUKAN cookie sesi seperti web.
| Aplikasi harus selalu mengirim header "Accept: application/json".
*/
Route::prefix('v1')->group(function () {

    // Cek koneksi dari aplikasi / browser HP (tanpa login).
    Route::get('/ping', fn () => response()->json([
        'ok'   => true,
        'app'  => config('app.name'),
        'time' => now()->toIso8601String(),
    ]));

    // Maks. 10 percobaan login per menit per IP.
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware(['auth:sanctum', 'role:Ketua'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/aktivitas', [BerandaController::class, 'aktivitas']);
        Route::get('/anggota', [AnggotaController::class, 'index']);

        Route::prefix('absensi-peserta')->group(function () {
            Route::get('/', [AbsensiPesertaController::class, 'index']);
            Route::post('/scan', [AbsensiPesertaController::class, 'scan']);
            Route::post('/status', [AbsensiPesertaController::class, 'status']);
            Route::post('/selesai', [AbsensiPesertaController::class, 'selesai']);
            Route::get('/riwayat', [AbsensiPesertaController::class, 'riwayat']);
        });

        Route::get('/absensi-pelatih', [AbsensiPelatihController::class, 'index']);
        Route::post('/absensi-pelatih', [AbsensiPelatihController::class, 'store']);
    });
});
