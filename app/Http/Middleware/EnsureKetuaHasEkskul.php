<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pastikan akun Ketua yang login memang sudah ditetapkan memimpin satu ekskul
 * (ekskuls.id_ketua = siswa milik akun ini).
 *
 * - Kalau sudah: ekskulnya ditaruh di $request->attributes ('ekskul_ketua')
 *   supaya controller/route tidak perlu menebak atau hardcode id_ekskul.
 * - Kalau belum: tampilkan halaman penjelasan yang JELAS (bukan redirect diam-diam
 *   ke dashboard, yang membuat klik menu sidebar terlihat "tidak berfungsi").
 */
class EnsureKetuaHasEkskul
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $siswa = $user?->siswa;

        // ADMIN: tidak dibatasi. Ekskul dipilih per halaman lewat ?ekskul=ID (boleh kosong).
        // Controller yang butuh satu ekskul menampilkan halaman "pilih ekskul dulu";
        // controller yang boleh "semua ekskul" memakai 'ekskul_ids'.
        if ($user?->role === 'Admin') {
            $ekskul = \App\Support\EkskulAktif::untuk($user);

            $request->attributes->set('ekskul_ketua', $ekskul);      // Ekskul|null
            $request->attributes->set('ekskul_ids', \App\Support\EkskulAktif::ids($user));
            $request->attributes->set('ekskul_admin', true);

            return $next($request);
        }

        // KETUA: hanya ekskul yang dia pimpin.
        $ekskul = \App\Support\EkskulAktif::untuk($user);

        if (! $ekskul) {
            return response()->view('dashboard-ketua.belum-ditugaskan', [
                'punyaBiodata' => (bool) $siswa,
                'adminTanpaEkskul' => false,
            ], 403);
        }

        $request->attributes->set('ekskul_ketua', $ekskul);
        $request->attributes->set('ekskul_ids', collect([$ekskul->id_ekskul]));
        $request->attributes->set('ekskul_admin', false);

        return $next($request);
    }
}
