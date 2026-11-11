<?php

namespace App\Providers;

use App\Support\PublicStorage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Situs di hosting biasanya ada di belakang proxy (nginx -> Apache) sehingga Laravel
        // mengira request-nya http. Akibatnya asset() menghasilkan http:// dan gambar bisa
        // diblokir browser (mixed content). Kalau APP_URL sudah https, paksa semua URL https.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // PENGAMAN: file public/hot dibuat oleh `npm run dev` dan berisi alamat dev server
        // (mis. http://[::1]:5173 = localhost laptop developer). Kalau file ini ikut terupload
        // ke server, browser pengunjung mencoba memuat CSS/JS dari "localhost" milik mereka
        // sendiri -> tampilan berantakan tanpa styling + muncul popup Allow/Block dari Chrome.
        // Di production, abaikan file hot sehingga selalu memakai hasil build (public/build).
        if ($this->app->isProduction()) {
            Vite::useHotFile(storage_path('framework/vite.hot'));
        }

        // Symlink public/storage hilang tiap kali folder public diganti saat update.
        // Cek singkat tiap request web, dan buat ulang otomatis kalau hilang/rusak.
        // (Tidak jalan di artisan/queue/test.)
        if (! $this->app->runningInConsole()) {
            PublicStorage::ensureLink();
        }
    }
}
