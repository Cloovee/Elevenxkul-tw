<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/*
|--------------------------------------------------------------------------
| ANTI-CACHE-BASI (otomatis setelah update/upload ulang)
|--------------------------------------------------------------------------
| Kalau di hosting pernah dijalankan `php artisan optimize` / `route:cache` /
| `config:cache`, Laravel memakai route & config LAMA dan mengabaikan file baru
| yang kamu upload (akibatnya route baru seperti /media tidak dikenal -> foto rusak).
|
| Blok ini dijalankan SEBELUM Laravel memuat cache: ia membandingkan "sidik jari"
| isi file routes/, config/, dan .env dengan yang tercatat terakhir kali. Kalau
| berbeda (artinya ada update), file cache route/config dihapus otomatis sehingga
| request ini langsung memakai file terbaru. Tanpa update = hampir tanpa beban
| (hanya cek ada/tidaknya file cache). Tidak pernah melempar error.
*/
(static function (): void {
    try {
        $cacheDir = __DIR__ . '/cache';
        $files = array_merge(
            glob($cacheDir . '/routes-*.php') ?: [],
            glob($cacheDir . '/config.php') ?: [],
            glob($cacheDir . '/events.php') ?: []
        );

        if ($files === []) {
            return; // tidak ada cache -> tidak ada yang perlu dibersihkan
        }

        $base = dirname(__DIR__);
        $sumber = array_merge(
            glob($base . '/routes/*.php') ?: [],
            glob($base . '/config/*.php') ?: [],
            [$base . '/.env']
        );
        sort($sumber);

        $hash = '';
        foreach ($sumber as $f) {
            $hash .= basename($f) . ':' . (is_file($f) ? md5_file($f) : '-') . ';';
        }
        $sidikJari = md5($hash);

        $catatan = $base . '/storage/framework/cache-fingerprint.txt';
        $lama = is_file($catatan) ? trim((string) @file_get_contents($catatan)) : '';

        if ($lama !== $sidikJari) {
            foreach ($files as $f) {
                @unlink($f);
            }
            @file_put_contents($catatan, $sidikJari);
        }
    } catch (\Throwable) {
        // abaikan: lebih baik tetap jalan daripada website error
    }
})();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'ketua.ekskul' => \App\Http\Middleware\EnsureKetuaHasEkskul::class,
        ]);

        // Notifikasi (toast) pojok kanan bawah untuk semua halaman web.
        $middleware->web(append: [
            \App\Http\Middleware\InjectToast::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Semua error di /api/* selalu dibalas JSON (bukan halaman HTML / redirect ke login),
        // supaya aplikasi mobile bisa membaca pesannya.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, \Throwable $e) => $request->is('api/*') || $request->expectsJson()
        );

        /*
        | Supaya kegagalan saat submit form TIDAK berakhir di halaman error kosong/tanpa
        | keterangan: kegagalan diubah jadi "kembali ke form + isian dipertahankan + pesan
        | alasan" yang tampil sebagai toast di pojok kanan bawah (lihat InjectToast).
        |
        | Hanya berlaku untuk request web biasa yang MENGIRIM data (POST/PUT/PATCH/DELETE).
        | Request JSON/AJAX (mis. scan absensi) dan halaman GET tetap ditangani seperti biasa.
        */
        $bisaDialihkan = fn (Request $r): bool => ! $r->expectsJson()
            && ! $r->ajax()
            && ! $r->isMethod('GET')
            && ! $r->isMethod('HEAD')
            && $r->hasSession()
            && ! app()->runningUnitTests();

        // Isian form dikembalikan, tapi password tidak pernah ikut dikembalikan.
        $isianLama = fn (Request $r): array => $r->except(['_token', 'password', 'password_confirmation', 'current_password']);

        $kembali = fn (Request $r, string $pesan) => redirect()->back()
            ->withInput($isianLama($r))
            ->with('error', $pesan);

        // 1) Halaman kedaluwarsa (419 / token CSRF habis): form dibiarkan terbuka terlalu lama.
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) use ($bisaDialihkan, $kembali) {
            if ($e->getStatusCode() !== 419 || ! $bisaDialihkan($request)) {
                return null;
            }

            return $kembali($request, 'Halaman ini sudah terlalu lama terbuka sehingga sesinya kedaluwarsa. Data belum terkirim. Silakan kirim ulang formulirnya.');
        });

        // 2) Error database (data ganda, kolom wajib kosong, data masih terpakai, dst.).
        $exceptions->render(function (QueryException $e, Request $request) use ($bisaDialihkan, $kembali) {
            if (! $bisaDialihkan($request)) {
                return null;
            }

            report($e);

            $detail = (string) ($e->getPrevious()?->getMessage() ?? $e->getMessage());
            $kode = (int) ($e->errorInfo[1] ?? 0);

            $pesan = match (true) {
                $kode === 1062 => str_contains($detail, 'email')
                    ? 'Email ini sudah dipakai akun lain. Gunakan email yang berbeda.'
                    : (str_contains($detail, 'username')
                        ? 'Username ini sudah dipakai akun lain. Gunakan username yang berbeda.'
                        : 'Data yang sama sudah pernah tersimpan. Periksa isian yang harus unik, seperti email, username, NIS, atau NISN.'),
                in_array($kode, [1048, 1364], true) => 'Ada kolom wajib yang masih kosong'
                    . (preg_match("/(?:Column|Field) '([^']+)'/", $detail, $m) ? " (kolom {$m[1]})" : '')
                    . '. Lengkapi isiannya lalu coba lagi.',
                $kode === 1406 => 'Ada isian yang terlalu panjang'
                    . (preg_match("/column '([^']+)'/i", $detail, $m) ? " (kolom {$m[1]})" : '')
                    . '. Persingkat lalu coba lagi.',
                in_array($kode, [1451, 1217], true) => 'Data ini masih terhubung dengan data lain sehingga tidak bisa dihapus/diubah. Lepaskan keterkaitannya dulu.',
                $kode === 1452 => 'Data yang dipilih sudah tidak ada atau tidak valid. Muat ulang halaman lalu pilih lagi.',
                default => 'Data belum bisa disimpan karena ada masalah pada database. Periksa isian lalu coba lagi.',
            };

            if (config('app.debug')) {
                $pesan .= ' [Detail teknis, hanya tampil saat APP_DEBUG=true: ' . Str::limit($detail, 220) . ']';
            }

            return $kembali($request, $pesan);
        });

        // 3) Kegagalan tak terduga lainnya saat submit: tetap beri tahu alasannya.
        $exceptions->render(function (\Throwable $e, Request $request) use ($bisaDialihkan, $kembali) {
            // Yang sudah punya penanganan sendiri dibiarkan.
            if ($e instanceof \Illuminate\Validation\ValidationException
                || $e instanceof \Illuminate\Auth\AuthenticationException
                || $e instanceof \Illuminate\Auth\Access\AuthorizationException
                || $e instanceof \Illuminate\Http\Exceptions\HttpResponseException
                || $e instanceof \Illuminate\Contracts\Support\Responsable
                || $e instanceof \Illuminate\Database\RecordsNotFoundException
                || $e instanceof HttpExceptionInterface
                || $e instanceof QueryException
            ) {
                return null;
            }

            if (! $bisaDialihkan($request)) {
                return null;
            }

            report($e);

            $pesan = 'Terjadi kesalahan di sistem saat memproses data, sehingga datanya belum tersimpan. Coba lagi; kalau tetap gagal, hubungi admin.';

            if (config('app.debug')) {
                $pesan .= ' [Detail teknis, hanya tampil saat APP_DEBUG=true: ' . class_basename($e) . ': ' . Str::limit($e->getMessage(), 220) . ']';
            }

            return $kembali($request, $pesan);
        });
    })->create();