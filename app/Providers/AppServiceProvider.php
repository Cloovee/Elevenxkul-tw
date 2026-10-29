<?php

namespace App\Providers;

use App\Support\PublicStorage;
use Illuminate\Support\Facades\URL;
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

        // Symlink public/storage hilang tiap kali folder public diganti saat update.
        // Cek singkat tiap request web, dan buat ulang otomatis kalau hilang/rusak.
        // (Tidak jalan di artisan/queue/test.)
        if (! $this->app->runningInConsole()) {
            PublicStorage::ensureLink();
        }
    }
}
