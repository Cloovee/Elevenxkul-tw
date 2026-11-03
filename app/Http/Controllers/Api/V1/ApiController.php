<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPeserta;
use App\Models\Ekskul;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Dasar semua controller API aplikasi mobile Ketua.
 *
 * Prinsip keamanan: id_ekskul TIDAK PERNAH diterima dari aplikasi. Ekskul selalu
 * diambil dari akun yang login (users -> siswa -> ekskuls.id_ketua), persis seperti
 * middleware 'ketua.ekskul' di versi web.
 */
abstract class ApiController extends Controller
{
    /** Ekskul yang dipimpin Ketua yang login. 403 (JSON) kalau belum ditugaskan. */
    protected function ekskulKetua(Request $request): Ekskul
    {
        $ekskul = $request->user()?->siswa?->ekskulDipimpin;

        if (! $ekskul) {
            abort(403, 'Akun kamu belum ditugaskan sebagai ketua ekskul manapun. Hubungi admin untuk menugaskanmu dulu.');
        }

        return $ekskul;
    }

    /** URL relatif (/storage/..., /media?p=...) diubah jadi absolut supaya bisa dibuka aplikasi. */
    protected function urlAbsolut(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return url($url);
    }

    protected function userJson(User $user): array
    {
        $siswa = $user->siswa;

        return [
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'username' => $user->username,
            'id_siswa' => $siswa?->id_siswa,
            'id_ekskul' => $siswa?->ekskulDipimpin?->id_ekskul,
        ];
    }

    protected function ekskulJson(?Ekskul $ekskul): ?array
    {
        if (! $ekskul) {
            return null;
        }

        $pelatih = $ekskul->pelatih;

        return [
            'id'        => $ekskul->id_ekskul,
            'nama'      => $ekskul->nama_ekskul,
            'kategori'  => $ekskul->kategori,
            'deskripsi' => $ekskul->deskripsi,
            'pelatih'   => $pelatih ? [
                'id'       => $pelatih->id_pelatih,
                'nama'     => $pelatih->nama_pelatih,
                'foto_url' => $this->urlAbsolut($pelatih->foto_url),
            ] : null,
        ];
    }

    protected function pesertaJson(Peserta $p): array
    {
        return [
            'id_anggota'        => $p->id_anggota,
            'nama'              => $p->nama,
            'nis'               => $p->siswa->NIS ?? '-',
            'kelas'             => $p->kelas ?? '-',
            'aktif'             => $p->status === 'aktif',
            'tanggal_bergabung' => $p->tanggal_bergabung?->toDateString(),
        ];
    }

    protected function absensiPesertaJson(AbsensiPeserta $a): array
    {
        return [
            'id_absensi'         => $a->id_absensi,
            'id_anggota'         => $a->id_anggota,
            'nama'               => $a->peserta->nama ?? '-',
            'tanggal'            => $a->tanggal_absensi->toDateString(),
            'status'             => $a->status_kehadiran,
            'catatan'            => $a->catatan,
            'deskripsi_kegiatan' => $a->deskripsi_kegiatan,
            'created_at'         => $a->created_at?->toIso8601String(),
        ];
    }
}
