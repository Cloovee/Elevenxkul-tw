<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - ElevenXkul</title>
    @include('partials.favicon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    </style>
</head>

{{-- Format layout disamakan dengan tampilan role ketua --}}
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="ekk-admin-wrap py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- ================= SIDEBAR RAIL ================= --}}
                {{-- Hover melebar + latar blur, seragam dengan role pembina & ketua --}}
                @include('partials.sidebar-admin')

                {{-- ================= KONTEN ================= --}}
                <main class="flex-1 min-w-0 space-y-5">

                    {{-- Judul halaman + identitas user --}}
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="min-w-0">
                            <p class="text-xs text-inksoft tracking-wide uppercase font-semibold truncate">@yield('page-title', 'Dashboard')</p>
                            <h1 class="text-2xl font-extrabold text-ink truncate">Halo, {{ Auth::user()->name ?? 'Admin' }}</h1>
                        </div>

                        <div class="flex items-center gap-3 bg-white/90 backdrop-blur rounded-2xl pl-4 pr-2 py-2 shadow-lg shadow-black/5 ring-1 ring-black/5">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-ink leading-none">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-inksoft mt-0.5">Administrator</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-periwinkle flex items-center justify-center text-white font-semibold text-sm shadow-md shadow-periwinkle/30">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-50 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-2xl">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @yield('content')

                </main>
            </div>
        </div>
    </div>

</body>
</html>