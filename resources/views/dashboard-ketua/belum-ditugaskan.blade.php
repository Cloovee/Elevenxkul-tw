<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Belum Ditugaskan - Ekskul Sebelas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-bgsoft text-ink">
    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">
        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <div class="flex-1 flex items-center justify-center">
                    <div class="bg-white rounded-3xl shadow-xl shadow-black/5 ring-1 ring-black/5 p-8 max-w-lg text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        </div>
                        <h1 class="text-xl font-extrabold text-ink mb-2">Akun kamu belum ditugaskan</h1>
                        <p class="text-sm text-inksoft mb-5">
                            @if($punyaBiodata)
                                Akun ini belum ditetapkan sebagai ketua ekskul/organisasi manapun, jadi menu ini belum bisa dipakai.
                                Minta Pembina memilihmu sebagai ketua lewat menu <b>Kelola Ketua</b>.
                            @else
                                Akun ini belum terhubung ke data siswa. Hubungi Admin untuk menghubungkannya.
                            @endif
                        </p>
                        <a href="{{ route('dashboard.ketua') }}" class="inline-flex px-5 py-2.5 bg-periwinkle text-white text-sm font-bold rounded-full shadow-md shadow-periwinkle/30 hover:opacity-90">Ke Dashboard</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
