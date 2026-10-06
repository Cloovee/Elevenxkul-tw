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
        $siswa = $request->user()?->siswa;
        $ekskul = $siswa?->ekskulDipimpin;

        if (! $ekskul) {
            return response()->view('dashboard-ketua.belum-ditugaskan', [
                'punyaBiodata' => (bool) $siswa,
            ], 403);
        }

        $request->attributes->set('ekskul_ketua', $ekskul);

        return $next($request);
    }
}
