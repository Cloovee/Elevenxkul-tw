<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen grid lg:grid-cols-2">

            <!-- Panel kiri: identitas sekolah, penuh satu sisi layar -->
            <div class="hidden lg:flex flex-col justify-between relative overflow-hidden bg-panelnight px-14 py-12 lg:rounded-r-[2.5rem] lg:shadow-2xl lg:shadow-ink/20">
                <!-- pola titik halus -->
                <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
                <!-- aksen cahaya tunggal -->
                <div class="absolute -bottom-40 -left-40 w-[32rem] h-[32rem] rounded-full bg-periwinkle/25 blur-[100px] pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-sky/10 blur-[80px] pointer-events-none"></div>

                {{--
                    ==== GANTI LOGO SEKOLAH DI SINI ====
                    Timpa file public/images/smkn11logo.png dengan logo SMKN 11 milikmu.
                    Kalau formatnya bukan .png, sesuaikan ekstensi di baris "src" di bawah (ada 2 tempat).
                    Logo sengaja tanpa kotak/background supaya menyatu dengan panel.
                --}}
                <div class="relative z-10 m-auto flex flex-col items-center text-center max-w-xs">
                    <img
                        src="{{ asset('images/smkn11logo.png') }}"
                        alt="Logo SMKN 11"
                        class="w-28 h-28 object-contain drop-shadow-[0_8px_24px_rgba(0,0,0,0.25)]"
                    />

                    <h1 class="font-display font-bold text-white text-2xl mt-6">
                        SMKN 11 Bandung
                    </h1>
                    <p class="text-periwinkle font-semibold text-sm mt-1">
                        Sistem Ekstrakurikuler
                    </p>
                    <p class="text-white/55 text-sm mt-4 leading-relaxed">
                        Kelola presensi, validasi laporan, dan penilaian kegiatan ekskul dalam satu platform.
                    </p>
                </div>
                {{-- ==== BATAS AREA LOGO ==== --}}
            </div>

            <!-- Panel kanan: form -->
            <div class="flex items-center justify-center bg-bgsoft lg:bg-white px-6 py-12 sm:px-12">
                <div class="w-full max-w-sm">
                    <div class="lg:hidden mb-8 flex items-center gap-3">
                        <img src="{{ asset('images/smkn11logo.png') }}" alt="Logo SMKN 11" class="w-11 h-11 object-contain shrink-0" />
                        <div class="leading-tight">
                            <p class="font-display font-bold text-ink text-sm">SMKN 11 Bandung</p>
                            <p class="text-inksoft text-xs">Sistem Ekstrakurikuler</p>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>