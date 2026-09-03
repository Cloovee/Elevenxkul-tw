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

            <!-- Panel kiri: foto SMKN 11 (khusus halaman login) -->
            <div class="hidden lg:flex flex-col justify-between relative overflow-hidden bg-panelnight px-14 py-12 lg:rounded-r-[2.5rem] lg:shadow-2xl lg:shadow-ink/20">

                {{--
                    ==== GANTI FOTO SMKN 11 DI SINI ====
                    Timpa file public/images/smkn11foto.png dengan foto SMKN 11 milikmu.
                    Kalau formatnya bukan .png, sesuaikan ekstensi di baris "src" di bawah.
                    Foto akan otomatis memenuhi seluruh panel kiri (object-cover) dan diberi
                    lapisan gelap tipis agar teks di atasnya tetap terbaca.
                --}}
                <img
                    src="{{ asset('images/smkn11foto.jpg') }}"
                    alt="Foto SMKN 11"
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-panelnight/70"></div>
                {{-- ==== BATAS AREA FOTO ==== --}}

                <div class="relative z-10 m-auto flex flex-col items-center text-center max-w-xs">
                    <img
                        src="{{ asset('images/smkn11logo.png') }}"
                        alt="Logo SMKN 11"
                        class="w-28 h-28 object-contain drop-shadow-[0_8px_24px_rgba(0,0,0,0.25)]"
                    />

                    <h1 class="font-display font-bold text-white text-2xl mt-6">
                        SMKN 11 Bandung
                    </h1>
                    <p class="text-white font-semibold text-sm mt-1">
                        Sistem Ekstrakurikuler
                    </p>
                    <p class="text-white/70 text-sm mt-4 leading-relaxed">
                        Kelola presensi, validasi laporan, dan penilaian kegiatan ekskul dalam satu platform.
                    </p>
                </div>
            </div>

            <!-- Panel kanan: form -->
            <div class="relative flex items-center justify-center bg-bgsoft lg:bg-white px-6 py-12 sm:px-12 overflow-hidden">

                {{-- ==== Foto SMKN 11 sebagai background penuh (khusus tampilan mobile) ==== --}}
                <img
                    src="{{ asset('images/smkn11foto.jpg') }}"
                    alt="Foto SMKN 11"
                    class="lg:hidden absolute inset-0 w-full h-full object-cover"
                />
                <div class="lg:hidden absolute inset-0 bg-panelnight/55"></div>
                {{-- ==== BATAS AREA FOTO ==== --}}

                <div class="relative z-10 w-full max-w-sm">
                    <div class="lg:hidden mb-8 flex items-center gap-3">
                        <img src="{{ asset('images/smkn11logo.png') }}" alt="Logo SMKN 11" class="w-11 h-11 object-contain shrink-0 drop-shadow-[0_4px_12px_rgba(0,0,0,0.35)]" />
                        <div class="leading-tight">
                            <p class="font-display font-bold text-white text-sm">SMKN 11 Bandung</p>
                            <p class="text-white/80 text-xs">Sistem Ekstrakurikuler</p>
                        </div>
                    </div>

                    <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 sm:p-6 shadow-xl shadow-black/10 lg:bg-transparent lg:backdrop-blur-none lg:rounded-none lg:p-0 lg:shadow-none">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>