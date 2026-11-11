<?php

namespace App\Support;

use App\Models\Ekskul;
use App\Models\User;

/**
 * Menentukan "ekskul yang sedang dikelola" untuk halaman role Ketua.
 *
 * - Ketua  : ekskul yang dia pimpin (siswa -> ekskulDipimpin). Tidak bisa diganti.
 * - Admin  : ekskul yang dipilih lewat sidebar (disimpan di session). Kalau belum
 *            memilih, dipakai ekskul pertama (urut nama). Admin tidak dibatasi.
 */
class EkskulAktif
{
    public const SESSION_KEY = 'admin_ekskul_id';

    public static function untuk(?User $user): ?Ekskul
    {
        if (! $user) {
            return null;
        }

        if ($user->role === 'Admin') {
            $id = session(self::SESSION_KEY);

            if ($id && ($ekskul = Ekskul::find($id))) {
                return $ekskul;
            }

            return Ekskul::orderBy('nama_ekskul')->first();
        }

        return optional($user->siswa)->ekskulDipimpin;
    }

    public static function id(?User $user): ?int
    {
        return optional(self::untuk($user))->id_ekskul;
    }
}
