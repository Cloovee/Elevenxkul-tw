<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * "Pembina virtual" untuk akun ADMIN.
 *
 * Halaman role Pembina membatasi data lewat $pembina->ekskuls() (hanya ekskul yang dibina).
 * Untuk Admin pembatasan itu dibuang: ekskuls() mengembalikan SEMUA ekskul, sehingga
 * seluruh halaman Pembina otomatis tampil/bekerja untuk semua ekskul tanpa mengubah
 * query di tiap controller.
 *
 * - Tidak pernah disimpan ke database (exists = false, id_pembina = null).
 * - Kolom yang menyimpan id_pembina (berita, validasi absensi) bersifat nullable,
 *   jadi aksi yang dilakukan Admin tercatat tanpa pembina.
 */
class AdminPembina extends Pembina
{
    public $exists = false;

    public static function untuk(User $user): self
    {
        $p = new self();
        $p->id_user = $user->id;
        $p->nama_pembina = $user->name ?: 'Administrator';

        return $p;
    }

    /** Semua ekskul (tanpa filter id_pembina). */
    public function ekskuls()
    {
        return Relation::noConstraints(fn () => parent::ekskuls());
    }
}
