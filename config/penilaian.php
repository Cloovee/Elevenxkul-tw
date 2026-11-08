<?php

/**
 * Aturan penilaian peserta ekskul (grade A/B/C).
 *
 * Grade akhir = gabungan dua grade:
 *   1) Grade KEHADIRAN : dihitung otomatis dari absensi peserta pada semester tsb.
 *   2) Grade KEAKTIFAN : pembina mengisi angka, sistem mengubahnya jadi grade.
 *
 * Semua angka di bawah boleh diubah tanpa mengubah kode.
 */
return [

    /*
     | Batas minimal angka untuk tiap grade (urut dari tertinggi).
     | Angka di bawah batas terendah (70) tetap dihitung grade terendah (C):
     | 70 adalah nilai paling rendah di sistem ini.
     */
    'batas' => [
        'A' => 90,
        'B' => 80,
        'C' => 70,
    ],

    /*
     | Persentase kehadiran = jumlah (pertemuan x bobot status) / total pertemuan x 100.
     | Default: hanya "hadir" yang dihitung. Ubah izin/sakit jadi 1 kalau sekolah
     | menganggap izin/sakit sebagai hadir, atau 0.5 untuk setengah.
     */
    'bobot_status' => [
        'hadir' => 1,
        'izin'  => 0,
        'sakit' => 0,
        'alpha' => 0,
    ],

    /*
     | Poin tiap grade dan bobot saat menggabungkan dua grade (yang dipakai hanya
     | perbandingannya). Hasil gabungan dibulatkan ke poin terdekat; jika tepat di
     | tengah dibulatkan ke atas (mis. A + B = A, B + C = B).
     */
    'poin' => [
        'A' => 3,
        'B' => 2,
        'C' => 1,
    ],

    'bobot' => [
        'kehadiran' => 50,
        'keaktifan' => 50,
    ],
];
