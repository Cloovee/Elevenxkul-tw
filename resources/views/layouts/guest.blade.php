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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">

            <!-- Panel kiri: gradient dekoratif -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-periwinkle via-periwinkle to-sky flex-col justify-between p-12">
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10"></div>
                <div class="absolute top-1/3 -left-16 w-64 h-64 rounded-full bg-white/10"></div>
                <div class="absolute bottom-10 right-24 w-32 h-32 rounded-full border border-white/20"></div>
                <div class="absolute top-16 right-40 w-3 h-3 rounded-full bg-white/40"></div>
                <div class="absolute top-40 right-64 w-2 h-2 rounded-full bg-white/40"></div>

                <div class="relative z-10">
                    <a href="/" class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center font-bold text-white">
                        ES
                    </a>
                </div>

                <div class="relative z-10">
                    <p class="text-white/70 text-sm tracking-[0.2em] uppercase mb-3 font-semibold">Sistem Ekstrakurikuler</p>
                    <h1 class="text-5xl font-bold text-white leading-[1.1]">
                        Ekskul Sebelas
                    </h1>
                    <p class="text-white/80 mt-6 max-w-sm leading-relaxed">
                        Satu tempat untuk mengelola kegiatan, kehadiran, dan penilaian
                        seluruh ekstrakurikuler sekolah.
                    </p>
                </div>

                <div class="relative z-10 flex gap-8 text-white/80 text-sm font-medium">
                    <span>Admin</span>
                    <span class="w-px bg-white/30"></span>
                    <span>Pembina</span>
                    <span class="w-px bg-white/30"></span>
                    <span>Ketua</span>
                </div>
            </div>

            <!-- Panel kanan: konten form -->
            <div class="flex-1 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-sm">
                    <div class="lg:hidden mb-8 text-center">
                        <h1 class="text-3xl font-bold text-periwinkle">Ekskul Sebelas</h1>
                    </div>
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>