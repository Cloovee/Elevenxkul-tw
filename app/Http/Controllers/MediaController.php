<?php

namespace App\Http\Controllers;

use App\Support\PublicStorage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Menyajikan gambar upload dari storage/app/public lewat PHP.
 * Dipakai saat symlink public/storage tidak ada / diblokir web server.
 * Lihat App\Support\PublicStorage untuk penjelasan lengkap.
 */
class MediaController extends Controller
{
    /** GET /media?p=poster-ekskul/abc.jpg */
    public function show(Request $request): BinaryFileResponse
    {
        return $this->kirim((string) $request->query('p', ''));
    }

    /** GET /storage/{path}  (cadangan untuk URL lama) */
    public function legacy(string $path): BinaryFileResponse
    {
        return $this->kirim($path);
    }

    private function kirim(string $path): BinaryFileResponse
    {
        $file = PublicStorage::resolve($path);

        abort_unless($file, 404);

        return response()->file($file, [
            'Cache-Control'          => 'public, max-age=604800',
            'X-Content-Type-Options' => 'nosniff',
        ])->setAutoEtag()->setAutoLastModified();
    }
}
