# Vercel Deployment — File yang Ditambahkan

File baru yang sudah dibuat di project ini:

- `Caddyfile` — konfigurasi web server (FrankenPHP).
- `Dockerfile.vercel` — build asset Vite + install Composer + jalankan FrankenPHP.
- `.dockerignore` — file yang tidak ikut masuk ke image Docker.
- `vercel.json` — konfigurasi service container Vercel.
- `database/migrations/2026_09_17_000000_create_sessions_table.php` — tabel `sessions`, dibutuhkan karena `SESSION_DRIVER=database` (tabel `cache` sudah ada bawaan Laravel).

## Langkah deploy

```bash
npm install -g vercel      # sekali saja
vercel login
php artisan key:generate --show   # simpan hasilnya
vercel link
vercel env add APP_KEY
vercel env add APP_ENV
vercel env add DB_CONNECTION
vercel env add DB_HOST
vercel env add DB_PORT
vercel env add DB_DATABASE
vercel env add DB_USERNAME
vercel env add DB_PASSWORD
vercel deploy            # preview dulu
vercel deploy --prod     # production
```

Jalankan migrasi dari lokal (arahkan `.env` lokal ke DB eksternal yang sama):
```bash
php artisan migrate --force
```

## Environment variables wajib di Vercel Dashboard
```
APP_KEY=base64:xxxxx
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql        # atau pgsql, sesuai provider DB eksternal Anda
DB_HOST=...
DB_PORT=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

## Catatan
- `vendor/` dan `node_modules/` sengaja **tidak** ikut di-commit (lihat `.gitignore`) — akan di-install ulang otomatis saat `docker build` di Vercel.
- Proyek ini pakai `maatwebsite/excel`, ekstensi PHP `zip` sudah ditambahkan di `Dockerfile.vercel`.
- Penjelasan lengkap (kenapa tiap file dibuat, alternatif database, troubleshooting) ada di panduan yang sudah dikirim sebelumnya di chat.
