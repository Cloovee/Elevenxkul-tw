<?php

namespace App\Support;

use App\Models\Ekskul;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Menentukan ekskul yang sedang dikelola di halaman role Ketua.
 *
 * - Ketua : selalu ekskul yang dia pimpin (siswa -> ekskulDipimpin). Tidak bisa diganti.
 * - Admin : TIDAK dibatasi. Ekskul dipilih PER HALAMAN lewat parameter ?ekskul=ID
 *           (tidak disimpan di session, jadi tiap halaman mulai dari "belum memilih").
 *             * halaman absen / tambah anggota / import / laporan : wajib pilih ekskul dulu
 *             * dashboard / riwayat / kelola anggota             : kosong = semua ekskul
 */
class EkskulAktif
{
    public static function admin(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->role === 'Admin';
    }

    /** Ekskul yang sedang dikelola (Ketua: miliknya, Admin: yang dipilih lewat ?ekskul=, bisa null). */
    public static function untuk(?User $user): ?Ekskul
    {
        if (! $user) {
            return null;
        }

        if ($user->role === 'Admin') {
            $id = (int) request()->input('ekskul');

            return $id > 0 ? Ekskul::find($id) : null;
        }

        return optional($user->siswa)->ekskulDipimpin;
    }

    public static function id(?User $user): ?int
    {
        return optional(self::untuk($user))->id_ekskul;
    }

    /** Daftar id ekskul yang boleh dilihat: Ketua [miliknya]; Admin [yang dipilih] atau SEMUA ekskul. */
    public static function ids(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        if ($user->role === 'Admin') {
            $id = self::id($user);

            return $id ? collect([$id]) : Ekskul::pluck('id_ekskul');
        }

        $id = self::id($user);

        return $id ? collect([$id]) : collect();
    }

    /** Semua ekskul (untuk dropdown filter Admin). */
    public static function pilihan(): Collection
    {
        return Ekskul::orderBy('nama_ekskul')->get(['id_ekskul', 'nama_ekskul']);
    }

    /**
     * route() yang membawa ?ekskul=ID untuk Admin supaya pilihan ekskul tidak hilang
     * saat pindah halaman / submit form / redirect. Untuk Ketua tidak menambah apa pun.
     * Boleh menimpa lewat $params['ekskul'] (mis. link per baris di tabel semua ekskul).
     */
    public static function route(string $name, $params = []): string
    {
        $params = is_array($params) ? $params : [$params];

        if (self::admin()) {
            if (! array_key_exists('ekskul', $params)) {
                if ($id = self::id(auth()->user())) {
                    $params['ekskul'] = $id;
                }
            } elseif (empty($params['ekskul'])) {
                unset($params['ekskul']);
            }
        } else {
            unset($params['ekskul']);
        }

        return route($name, $params);
    }

    /** <input hidden name="ekskul"> untuk form GET (query string di action form dibuang browser). */
    public static function hiddenInput(): string
    {
        $id = self::admin() ? self::id(auth()->user()) : null;

        return $id ? '<input type="hidden" name="ekskul" value="' . (int) $id . '">' : '';
    }

    /** Halaman "pilih ekskul dulu" untuk Admin yang membuka halaman yang butuh satu ekskul. */
    public static function halamanPilih(string $judul, string $deskripsi, string $routeName)
    {
        return view('dashboard-ketua.pilih-ekskul', [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'routeName' => $routeName,
        ]);
    }
}
