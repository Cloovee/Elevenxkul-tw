<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Penyaji gambar upload (foto pembina, poster, logo, galeri, dst.) yang TAHAN UPDATE.
 *
 * MASALAH YANG DISELESAIKAN
 * -------------------------
 * File upload disimpan di storage/app/public. Supaya bisa dibuka browser, biasanya dibuat
 * symlink  public/storage -> storage/app/public.  Symlink ini:
 *   - tidak ikut ter-upload saat zip/File Manager DirectAdmin dipakai (tidak ada di git/zip),
 *   - ikut hilang kalau folder public / public_html diganti saat update,
 *   - dan saat hilang, request "/storage/foto.jpg" sering DICEGAT web server (file berekstensi
 *     gambar dilayani langsung oleh nginx/Apache) sehingga 404 tanpa pernah sampai ke Laravel.
 *
 * CARA KERJA SEKARANG
 * -------------------
 * 1. url()         : kalau file bisa dilayani langsung lewat /storage/... -> pakai itu (cepat).
 *                    Kalau tidak -> pakai /media?p=... (diproses PHP, tanpa ekstensi di path,
 *                    jadi tidak bisa dicegat web server). Gambar SELALU muncul.
 * 2. ensureLink()  : dipanggil otomatis tiap request web; membuat ulang symlink kalau hilang/rusak.
 * 3. resolve()     : mencari file fisik dengan aman (anti path traversal, hanya gambar).
 * 4. diagnose()    : laporan untuk admin (/admin/cek-gambar) kalau masih ada yang janggal.
 */
class PublicStorage
{
    /** Ekstensi yang boleh disajikan lewat jalur PHP (sengaja tanpa svg/html/php). */
    public const EKSTENSI_GAMBAR = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'bmp'];

    /** Folder fisik tempat file upload disimpan (disk "public"). */
    public static function sumber(): string
    {
        return rtrim(Storage::disk('public')->path(''), '/\\');
    }

    /**
     * Folder yang BENAR-BENAR dilayani web server (document root).
     * Penting untuk hosting yang isi folder "public" dipindah ke public_html,
     * sehingga public_path() Laravel menunjuk ke tempat yang salah.
     */
    public static function webRoot(): string
    {
        $manual = config('filesystems.public_web_root');
        if (is_string($manual) && $manual !== '' && is_dir($manual)) {
            return rtrim($manual, '/\\');
        }

        if (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') {
            $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
            if (is_string($docRoot) && $docRoot !== '' && @is_dir($docRoot)) {
                return rtrim($docRoot, '/\\');
            }
        }

        return rtrim(public_path(), '/\\');
    }

    /** Lokasi symlink/folder "storage" di document root. */
    public static function linkPath(): string
    {
        return self::webRoot() . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * URL untuk sebuah path file di disk "public" (mis. "poster-ekskul/abc.jpg").
     * Selalu menghasilkan URL yang bisa dibuka, apa pun kondisi symlink-nya.
     */
    public static function url(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        // Sudah URL lengkap (link eksternal)
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        // Jalur cepat: file memang tersedia di document root lewat /storage/...
        if (self::bisaDilayaniLangsung($path)) {
            return '/storage/' . $path;
        }

        // Jalur aman: lewat PHP. Tidak ada ekstensi di bagian path, jadi web server tidak mencegat.
        return '/media?p=' . str_replace('%2F', '/', rawurlencode($path));
    }

    private static function bisaDilayaniLangsung(string $path): bool
    {
        try {
            return @is_file(self::linkPath() . DIRECTORY_SEPARATOR . $path);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Cari file fisik untuk path relatif di disk "public". Null kalau tidak ada / tidak aman.
     */
    public static function resolve(string $path): ?string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if ($path === '' || str_contains($path, "\0")) {
            return null;
        }

        // Tolak file/folder tersembunyi (.gitignore, .env, dst.)
        foreach (explode('/', $path) as $bagian) {
            if ($bagian === '' || $bagian[0] === '.') {
                return null;
            }
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, self::EKSTENSI_GAMBAR, true)) {
            return null;
        }

        $dasar = realpath(self::sumber());
        $file  = $dasar ? realpath($dasar . DIRECTORY_SEPARATOR . $path) : false;

        // Wajib berada di dalam folder sumber (cegah ../)
        if (! $dasar || ! $file || ! str_starts_with($file, $dasar . DIRECTORY_SEPARATOR) || ! is_file($file)) {
            return null;
        }

        return $file;
    }

    /**
     * Pastikan symlink document-root/storage -> storage/app/public ada dan benar.
     * Aman dipanggil berulang; tidak pernah melempar error.
     *
     * @return string ok | dibuat | folder-biasa | symlink-dimatikan | gagal | sumber-tidak-ada
     */
    public static function ensureLink(): string
    {
        try {
            $target = self::sumber();

            if (! is_dir($target) && ! @mkdir($target, 0755, true) && ! is_dir($target)) {
                return 'sumber-tidak-ada';
            }

            $link = self::linkPath();

            if (is_link($link)) {
                $real = @realpath($link);
                if ($real !== false && $real === realpath($target)) {
                    return 'ok';
                }
                @unlink($link); // symlink rusak / menunjuk ke tempat lama
            } elseif (file_exists($link)) {
                // Folder biasa yang dibuat manual: jangan disentuh.
                return 'folder-biasa';
            }

            if (! function_exists('symlink') || self::fungsiDimatikan('symlink')) {
                return 'symlink-dimatikan';
            }

            return @symlink($target, $link) ? 'dibuat' : 'gagal';
        } catch (\Throwable) {
            return 'gagal';
        }
    }

    private static function fungsiDimatikan(string $fungsi): bool
    {
        $daftar = array_map('trim', explode(',', (string) ini_get('disable_functions')));

        return in_array($fungsi, $daftar, true);
    }

    /**
     * Laporan kondisi gambar, untuk halaman admin /admin/cek-gambar.
     */
    public static function laporan(): string
    {
        $baris   = [];
        $baris[] = 'DIAGNOSA GAMBAR - ' . now()->format('d-m-Y H:i:s');
        $baris[] = str_repeat('=', 60);

        $status = self::ensureLink();
        $arti = [
            'ok'                => 'OK - symlink sudah benar',
            'dibuat'            => 'OK - symlink baru saja dibuat otomatis',
            'folder-biasa'      => 'PERHATIAN - "storage" di document root berupa FOLDER BIASA (bukan symlink). Gambar baru tetap tampil lewat jalur /media, tapi sebaiknya hapus folder itu lewat File Manager agar dibuatkan symlink otomatis.',
            'symlink-dimatikan' => 'INFO - hosting mematikan fungsi symlink(). Tidak masalah: gambar disajikan lewat jalur /media (PHP).',
            'gagal'             => 'INFO - symlink gagal dibuat (diblokir hosting). Tidak masalah: gambar disajikan lewat jalur /media (PHP).',
            'sumber-tidak-ada'  => 'ERROR - folder storage/app/public tidak ada dan tidak bisa dibuat. Cek izin folder (permission) storage.',
        ];

        $baris[] = 'Document root terdeteksi : ' . self::webRoot();
        $baris[] = 'Lokasi symlink           : ' . self::linkPath();
        $baris[] = 'Folder sumber file       : ' . self::sumber();
        $baris[] = 'Status symlink           : ' . ($arti[$status] ?? $status);
        $baris[] = 'APP_URL                  : ' . config('app.url');
        $baris[] = 'Halaman ini diakses via  : ' . (request()->isSecure() ? 'https' : 'http');
        $baris[] = '';

        // Isi folder sumber
        $baris[] = 'FILE DI storage/app/public';
        $baris[] = str_repeat('-', 60);
        $sumber  = self::sumber();
        $total   = 0;
        if (is_dir($sumber)) {
            foreach (glob($sumber . '/*', GLOB_ONLYDIR) ?: [] as $folder) {
                $jumlah = count(array_filter(glob($folder . '/*') ?: [], 'is_file'));
                $total += $jumlah;
                $baris[] = sprintf('  %-22s %d file', basename($folder) . '/', $jumlah);
            }
        }
        $baris[] = "  TOTAL                  {$total} file";
        $baris[] = '';

        // Cocokkan database dengan file fisik
        $baris[] = 'DATA DI DATABASE YANG FILE-NYA HILANG';
        $baris[] = str_repeat('-', 60);

        $cek = [
            [\App\Models\Ekskul::class,        'poster',         'Ekskul (poster)'],
            [\App\Models\Ekskul::class,        'logo',           'Ekskul (logo)'],
            [\App\Models\Pembina::class,       'foto',           'Pembina (foto)'],
            [\App\Models\Pelatih::class,       'foto',           'Pelatih (foto)'],
            [\App\Models\Galeri::class,        'foto',           'Galeri (foto)'],
            [\App\Models\AbsensiPelatih::class, 'foto_kehadiran', 'Absensi Pelatih (foto)'],
        ];

        $hilang = 0;
        $diperiksa = 0;
        foreach ($cek as [$model, $kolom, $label]) {
            try {
                foreach ($model::query()->whereNotNull($kolom)->where($kolom, '!=', '')->get() as $row) {
                    $nilai = (string) $row->{$kolom};
                    if (preg_match('#^https?://#i', $nilai)) {
                        continue;
                    }
                    $diperiksa++;
                    if (self::resolve($nilai) === null) {
                        $hilang++;
                        if ($hilang <= 60) {
                            $baris[] = sprintf('  [HILANG] %s #%s -> %s', $label, $row->getKey(), $nilai);
                        }
                    }
                }
            } catch (\Throwable $e) {
                $baris[] = "  (gagal memeriksa {$label}: " . class_basename($e) . ')';
            }
        }

        if ($hilang === 0) {
            $baris[] = "  Semua {$diperiksa} gambar di database ada file fisiknya.";
        } else {
            $baris[] = '';
            $baris[] = "  {$hilang} dari {$diperiksa} gambar HILANG dari server. Penyebab paling umum: folder";
            $baris[] = '  storage/app/public ikut terhapus/tertimpa saat update. Solusi: upload ulang foto';
            $baris[] = '  lewat menu admin (Edit -> pilih foto), atau kembalikan file dari backup.';
        }

        return implode("\n", $baris) . "\n";
    }
}
