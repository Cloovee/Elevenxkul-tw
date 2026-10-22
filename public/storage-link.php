<?php
/**
 * SKRIP SEKALI PAKAI untuk membuat symlink public/storage -> storage/app/public
 * Cara pakai: upload ke folder public (atau public_html), buka di browser SEKALI,
 * lalu file ini akan menghapus dirinya sendiri kalau berhasil.
 *
 * Kalau struktur hosting Anda berbeda (isi "public" dipindah ke public_html,
 * sedangkan folder Laravel ada di tempat lain), isi $laravelPath di bawah ini
 * dengan path absolut folder Laravel, contoh: '/home/kyromannmy/elevenxkul'
 */
$laravelPath = ''; // kosongkan kalau folder "public" masih di dalam project Laravel

$target = ($laravelPath !== '' ? rtrim($laravelPath, '/') : dirname(__DIR__)) . '/storage/app/public';
$link   = __DIR__ . '/storage';

header('Content-Type: text/plain; charset=utf-8');

if (!is_dir($target)) {
    exit("GAGAL: folder target tidak ditemukan: $target\nIsi \$laravelPath dengan benar.");
}

if (is_link($link)) {
    if (@readlink($link) === $target && is_dir($link)) {
        @unlink(__FILE__);
        exit("Symlink sudah ada dan benar. Selesai (file ini dihapus).");
    }
    @unlink($link); // symlink rusak/salah arah
} elseif (file_exists($link)) {
    exit("GAGAL: '$link' sudah ada sebagai folder/file biasa. Hapus atau rename dulu lewat File Manager, lalu buka halaman ini lagi.");
}

if (!function_exists('symlink')) {
    exit("GAGAL: fungsi symlink() dinonaktifkan hosting. Pakai route fallback (routes/web.php) atau minta support hosting.");
}

if (@symlink($target, $link)) {
    @unlink(__FILE__);
    exit("BERHASIL: public/storage -> $target\nFile ini sudah dihapus otomatis. Silakan refresh halaman website Anda.");
}

exit("GAGAL membuat symlink (kemungkinan diblokir hosting).\nPakai route fallback di routes/web.php atau minta support hosting menjalankan:\nln -s $target $link");
