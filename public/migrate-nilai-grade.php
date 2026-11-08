<?php
/**
 * Migrasi fitur Penilaian Grade (kehadiran + keaktifan) tanpa SSH.
 *
 * CARA PAKAI
 *  1. Ganti $KUNCI di bawah dengan kata acak panjang buatanmu sendiri.
 *  2. Upload file ini ke folder  public/  di server.
 *  3. Buka di browser:  https://DOMAIN-KAMU/migrate-nilai-grade.php?kunci=KATA_ACAKMU
 *  4. Kalau sudah muncul "SELESAI", file ini otomatis menghapus dirinya sendiri.
 *     (Cek di File Manager; kalau masih ada, HAPUS manual. Jangan dibiarkan.)
 *
 * Yang dijalankan hanya migration grade (kolom baru di tabel `penilaian` + nilai lama
 * dibawa ke nilai keaktifan), bukan seluruh migration. Aman dijalankan ulang.
 */

$KUNCI = 'GANTI-DENGAN-KATA-ACAK-PANJANG';

// ---------------------------------------------------------------------------
header('Content-Type: text/plain; charset=utf-8');

if ($KUNCI === 'GANTI-DENGAN-KATA-ACAK-PANJANG') {
    http_response_code(500);
    exit("Ubah dulu \$KUNCI di dalam file ini.\n");
}
if (! hash_equals($KUNCI, (string) ($_GET['kunci'] ?? ''))) {
    http_response_code(403);
    exit("Kunci salah.\n");
}

// Lokasi proyek: file ini ada di public/, jadi proyek ada satu tingkat di atasnya.
// Kalau hosting memisahkan isi public/ ke public_html, ubah $root ke folder proyek.
$root = dirname(__DIR__);

if (! is_file($root . '/vendor/autoload.php')) {
    http_response_code(500);
    exit("vendor/autoload.php tidak ditemukan di {$root}. Sesuaikan \$root di file ini.\n");
}

require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

function jalankan(string $perintah, array $opsi = []): void
{
    echo "> php artisan {$perintah}\n";
    try {
        $kode = Artisan::call($perintah, $opsi);
        echo trim(Artisan::output()) . "\n";
        echo $kode === 0 ? "  [OK]\n\n" : "  [GAGAL, kode {$kode}]\n\n";
    } catch (Throwable $e) {
        echo '  [ERROR] ' . $e->getMessage() . "\n\n";
        throw $e;
    }
}

try {
    // 1) Hanya migration grade penilaian.
    jalankan('migrate', [
        '--force' => true,
        '--path'  => 'database/migrations/2026_11_09_000000_add_grade_to_penilaian_table.php',
    ]);

    // 2) Bersihkan cache supaya rute & menu baru langsung terbaca.
    jalankan('optimize:clear');
} catch (Throwable $e) {
    http_response_code(500);
    exit("BERHENTI karena error di atas. File ini TIDAK dihapus; perbaiki lalu coba lagi.\n");
}

// 3) Hapus diri sendiri.
$hapus = @unlink(__FILE__);
echo $hapus
    ? "SELESAI. File migrate-nilai-grade.php sudah dihapus otomatis.\n"
    : "SELESAI. TAPI hapus manual file public/migrate-nilai-grade.php lewat File Manager!\n";
