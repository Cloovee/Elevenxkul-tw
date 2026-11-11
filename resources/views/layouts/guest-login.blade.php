<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $attributes->get('title', 'Masuk') }} - ElevenXkul</title>
        @include('partials.favicon')

        {{--
            Font dimuat NON-BLOCKING: kalau Google Fonts lambat/diblokir, halaman tetap tampil
            rapi memakai font sistem (lihat font-family di bawah), tidak menunggu / tidak rusak.
        --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" media="print" onload="this.media='all'"
              href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap">
        <noscript>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap">
        </noscript>

        {{--
            SENGAJA TIDAK memakai Vite (CSS/JS hasil build) di halaman login.
            Semua gaya layout ditulis mentah (prefix .gl-) supaya tampilan login TIDAK bergantung
            pada `npm run build`, file public/hot, atau lokasi folder build di hosting.
            Itu penyebab tampilan login berantakan tanpa styling di laptop lain.
        --}}
        <style>
            *, *::before, *::after { box-sizing: border-box; }
            html { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }
            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                color: #10316B;
                background: #F2F7FF;
                line-height: 1.5;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            img { display: block; max-width: 100%; }

            /* ===== Kerangka halaman ===== */
            .gl-page { min-height: 100vh; display: grid; grid-template-columns: minmax(0, 1fr); }

            /* ===== Panel kiri (foto) : hanya desktop ===== */
            .gl-left {
                display: none;
                position: relative;
                overflow: hidden;
                background: #10316B;
                padding: 3rem 3.5rem;
                flex-direction: column;
                justify-content: space-between;
                border-radius: 0 2.5rem 2.5rem 0;
                box-shadow: 0 25px 50px -12px rgba(16, 49, 107, .25);
            }
            .gl-bg      { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
            .gl-overlay { position: absolute; inset: 0; background: rgba(16, 49, 107, .70); }

            .gl-brand {
                position: relative; z-index: 10;
                margin: auto; max-width: 20rem;
                display: flex; flex-direction: column; align-items: center; text-align: center;
            }
            .gl-brand-logo { width: 7rem; height: 7rem; object-fit: contain; filter: drop-shadow(0 8px 24px rgba(0, 0, 0, .25)); }
            .gl-brand-name { margin: 1.5rem 0 0; font-family: 'Fredoka', ui-sans-serif, system-ui, sans-serif; font-weight: 700; font-size: 1.5rem; line-height: 1.2; color: #fff; }
            .gl-brand-sub  { margin: .25rem 0 0; font-weight: 600; font-size: .875rem; color: #fff; }
            .gl-brand-desc { margin: 1rem 0 0; font-size: .875rem; line-height: 1.65; color: rgba(255, 255, 255, .72); }

            /* ===== Panel kanan (form) ===== */
            .gl-right {
                position: relative;
                display: flex; align-items: center; justify-content: center;
                overflow: hidden;
                min-height: 100vh;
                padding: 3rem 1.5rem 6rem; /* ruang bawah: tombol info "i" (fixed) tidak menimpa konten */
                background: #F2F7FF;
            }
            .gl-right-bg      { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
            .gl-right-overlay { position: absolute; inset: 0; background: rgba(16, 49, 107, .55); }

            .gl-inner { position: relative; z-index: 10; width: 100%; max-width: 24rem; }

            .gl-mobile-brand { display: flex; align-items: center; gap: .75rem; margin-bottom: 2rem; }
            .gl-mobile-brand img { width: 2.75rem; height: 2.75rem; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 4px 12px rgba(0, 0, 0, .35)); }
            .gl-mobile-brand .t1 { margin: 0; font-family: 'Fredoka', ui-sans-serif, system-ui, sans-serif; font-weight: 700; font-size: .875rem; line-height: 1.25; color: #fff; }
            .gl-mobile-brand .t2 { margin: 0; font-size: .75rem; line-height: 1.25; color: rgba(255, 255, 255, .8); }

            .gl-box {
                /* JANGAN pakai backdrop-filter di sini: membuat elemen position:fixed di dalamnya
                   (tombol info "i") menempel ke kotak ini, bukan ke layar -> menimpa tombol lain. */
                background: rgba(255, 255, 255, .96);
                border-radius: 1.5rem;
                padding: 1.25rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, .10), 0 8px 10px -6px rgba(0, 0, 0, .10);
            }

            @media (min-width: 640px) {
                .gl-right { padding: 3rem 3rem 6rem; }
                .gl-box   { padding: 1.5rem; }
            }

            /* ===== Desktop: dua kolom ===== */
            @media (min-width: 1024px) {
                .gl-page  { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .gl-left  { display: flex; }
                .gl-right { background: #fff; padding: 3rem; }
                .gl-right-bg, .gl-right-overlay, .gl-mobile-brand { display: none; }
                .gl-box   { background: transparent; border-radius: 0; padding: 0; box-shadow: none; }
            }

            /* Layar desktop sempit / tinggi pendek: jangan ada yang terpotong */
            @media (min-width: 1024px) and (max-height: 640px) {
                .gl-left { padding: 2rem 2.5rem; }
                .gl-brand-logo { width: 5.5rem; height: 5.5rem; }
            }
        </style>
    </head>
    <body>
        <div class="gl-page">

            <!-- Panel kiri: foto SMKN 11 (khusus halaman login, tampil di desktop) -->
            <div class="gl-left">

                {{--
                    ==== GANTI FOTO SMKN 11 DI SINI ====
                    Timpa file public/images/smkn11foto.jpg dengan foto SMKN 11 milikmu.
                    Kalau formatnya bukan .jpg, sesuaikan ekstensi di baris "src" di bawah.
                    Foto otomatis memenuhi panel kiri (object-fit: cover) dan diberi lapisan gelap
                    tipis agar teks di atasnya tetap terbaca.
                --}}
                <img src="{{ asset('images/smkn11foto.jpg') }}" alt="Foto SMKN 11" class="gl-bg" decoding="async" />
                <div class="gl-overlay"></div>
                {{-- ==== BATAS AREA FOTO ==== --}}

                <div class="gl-brand">
                    <img src="{{ asset('images/smkn11logo.png') }}" alt="Logo SMKN 11" class="gl-brand-logo" />
                    <h1 class="gl-brand-name">ElevenXkul</h1>
                    <p class="gl-brand-sub">Ekskul Management</p>
                    <p class="gl-brand-desc">Kelola presensi, validasi laporan, dan penilaian kegiatan ekskul dalam satu platform.</p>
                </div>
            </div>

            <!-- Panel kanan: form -->
            <div class="gl-right">

                {{-- Foto SMKN 11 sebagai background penuh (khusus tampilan mobile / tablet) --}}
                <img src="{{ asset('images/smkn11foto.jpg') }}" alt="" aria-hidden="true" class="gl-right-bg" decoding="async" />
                <div class="gl-right-overlay"></div>

                <div class="gl-inner">
                    <div class="gl-mobile-brand">
                        <img src="{{ asset('images/smkn11logo.png') }}" alt="Logo SMKN 11" />
                        <div>
                            <p class="t1">ElevenXkul</p>
                            <p class="t2">Ekskul Management</p>
                        </div>
                    </div>

                    <div class="gl-box">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
