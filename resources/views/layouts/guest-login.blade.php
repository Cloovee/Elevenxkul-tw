<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $attributes->get('title', 'Masuk') }} - Xkul11</title>
    @include('partials.favicon')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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

                <div class="relative z-10 m-auto flex flex-col items-center text-center max-w-xs">
                    <img
                        src="{{ asset('images/smkn11logo.png') }}"
                        alt="Logo SMKN 11"
                        class="w-28 h-28 object-contain drop-shadow-[0_8px_24px_rgba(0,0,0,0.25)]"
                    />

                    <h1 class="font-display font-bold text-white text-2xl mt-6">
                        Xkull11
                    </h1>
                    <p class="text-white font-semibold text-sm mt-1">
                        Ekskul Management
                    </p>
                    <p class="text-white/70 text-sm mt-4 leading-relaxed">
                        Kelola presensi, validasi laporan, dan penilaian kegiatan ekskul dalam satu platform.
                    </p>
                </div>
            </div>

            <!-- Panel kanan: form -->
            <div class="gl-right">

                {{-- Foto SMKN 11 sebagai background penuh (khusus tampilan mobile / tablet) --}}
                <img src="{{ asset('images/smkn11foto.jpg') }}" alt="" aria-hidden="true" class="gl-right-bg" decoding="async" />
                <div class="gl-right-overlay"></div>

                <div class="relative z-10 w-full max-w-sm">
                    <div class="lg:hidden mb-8 flex items-center gap-3">
                        <img src="{{ asset('images/smkn11logo.png') }}" alt="Logo SMKN 11" class="w-11 h-11 object-contain shrink-0 drop-shadow-[0_4px_12px_rgba(0,0,0,0.35)]" />
                        <div class="leading-tight">
                            <p class="font-display font-bold text-white text-sm">Xkull11</p>
                            <p class="text-white/80 text-xs">Ekskul Management</p>
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
