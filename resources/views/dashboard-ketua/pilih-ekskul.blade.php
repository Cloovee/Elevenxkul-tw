<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $judul }} - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6">

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua &middot; Admin</p>
                        <h1 class="text-2xl font-extrabold text-ink">{{ $judul }}</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $deskripsi }}</p>
                    </div>

                    @include('dashboard-ketua.partials.filter-ekskul', ['mode' => 'pilih'])

                    @if (session('error'))
                        <div class="bg-red-50 text-red-500 text-sm font-semibold px-4 py-3 rounded-2xl">{{ session('error') }}</div>
                    @endif

                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-10 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-periwinkle/10 text-periwinkle flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="font-semibold text-ink">Pilih ekskul dulu</p>
                        <p class="text-sm text-inksoft mt-1 max-w-md mx-auto">
                            Halaman ini bekerja per ekskul. Pilih ekskul di atas, setelah itu kamu bisa melanjutkan.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
