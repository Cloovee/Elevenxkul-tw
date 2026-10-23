<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notifikasi (toast) di pojok kanan bawah layar untuk SEMUA halaman web.
 *
 * Kenapa lewat middleware: halaman di proyek ini ada yang memakai layout, ada yang
 * berupa HTML penuh sendiri-sendiri. Dengan menyisipkan partial `partials.toast`
 * tepat sebelum </body> pada respons HTML, semua halaman otomatis dapat toast tanpa
 * perlu mengedit view satu per satu.
 *
 * Yang ditampilkan:
 *   - error validasi (login salah, data tidak valid, dst.) + petunjuk cara memperbaikinya
 *   - flash session 'error', 'success', 'warning', dan 'status'
 *   - (di sisi browser) pesan untuk kolom form yang belum valid sebelum dikirim
 */
class InjectToast
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->bolehDisisipi($request, $response)) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || str_contains($content, 'data-toast-root')) {
            return $response;
        }

        $pos = strripos($content, '</body>');
        if ($pos === false) {
            return $response;
        }

        $html = view('partials.toast', ['toasts' => $this->susunToast($request)])->render();

        $response->setContent(substr($content, 0, $pos) . $html . substr($content, $pos));

        return $response;
    }

    private function bolehDisisipi(Request $request, Response $response): bool
    {
        return $response instanceof HttpResponse
            && $response->getStatusCode() < 500
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            && $request->hasSession()
            && ! $request->expectsJson()
            && ! $request->ajax();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function susunToast(Request $request): array
    {
        $session = $request->session();
        $toasts = [];

        // ---- 1. Error validasi (termasuk login salah) ----
        $pesan = [];
        $bags = $session->get('errors');

        if ($bags instanceof ViewErrorBag) {
            foreach ($bags->getBags() as $bag) {
                foreach ($bag->messages() as $daftar) {
                    foreach ((array) $daftar as $m) {
                        $pesan[] = $m;
                    }
                }
            }
        }

        $pesan = array_values(array_unique($pesan));

        if ($pesan) {
            $toasts[] = $request->is('login')
                ? $this->toastLogin($pesan)
                : $this->toastValidasi($request, $pesan);
        }

        // ---- 2. Flash session ----
        if ($session->has('error')) {
            $teks = trim((string) $session->get('error'));
            $toasts[] = [
                'type' => 'error',
                'title' => 'Proses gagal',
                'message' => $teks !== ''
                    ? $teks
                    : 'Proses gagal, tetapi sistem tidak memberi keterangan. Coba ulangi; kalau tetap gagal, hubungi admin.',
                'key' => 'flash:error',
            ];
        }

        if ($session->has('warning') && trim((string) $session->get('warning')) !== '') {
            $toasts[] = [
                'type' => 'warning',
                'title' => 'Perhatian',
                'message' => trim((string) $session->get('warning')),
                'key' => 'flash:warning',
            ];
        }

        if ($session->has('success') && trim((string) $session->get('success')) !== '') {
            $toasts[] = [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => trim((string) $session->get('success')),
                'key' => 'flash:success',
            ];
        }

        if ($session->has('status') && is_string($session->get('status')) && trim($session->get('status')) !== '') {
            $toasts[] = $this->toastStatus((string) $session->get('status'));
        }

        return $toasts;
    }

    private function toastLogin(array $pesan): array
    {
        return [
            'type' => 'error',
            'title' => 'Login gagal',
            'message' => $pesan[0],
            'hint' => 'Periksa email/username dan password-mu, dan pastikan Caps Lock tidak aktif. Lupa password? Hubungi admin atau pembina.',
            'key' => 'validasi',
        ];
    }

    private function toastValidasi(Request $request, array $pesan): array
    {
        $judul = $request->is('register') ? 'Pendaftaran gagal' : 'Data belum valid';

        $maks = 5;
        $sisa = max(0, count($pesan) - $maks);

        $toast = [
            'type' => 'error',
            'title' => $judul,
            'message' => count($pesan) === 1
                ? 'Salah satu data yang kamu isi tidak valid:'
                : 'Beberapa data yang kamu isi tidak valid. Mohon isi dengan data yang benar:',
            'list' => array_slice($pesan, 0, $maks),
            'key' => 'validasi',
        ];

        if ($sisa > 0) {
            $toast['list'][] = "...dan {$sisa} masalah lainnya.";
        }

        return $toast;
    }

    private function toastStatus(string $status): array
    {
        $dikenal = [
            'profile-updated' => 'Profil berhasil diperbarui.',
            'password-updated' => 'Password berhasil diubah.',
            'verification-link-sent' => 'Link verifikasi baru sudah dikirim ke email kamu.',
        ];

        $teks = $dikenal[$status] ?? __($status);

        return [
            'type' => isset($dikenal[$status]) ? 'success' : 'info',
            'title' => isset($dikenal[$status]) ? 'Berhasil' : 'Info',
            'message' => Str::limit((string) $teks, 300),
            'key' => 'flash:status',
        ];
    }
}
