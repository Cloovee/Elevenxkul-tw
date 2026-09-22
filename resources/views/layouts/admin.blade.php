<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Elevenxkul</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Sidebar rail: posisi "fixed" ditulis sebagai CSS mentah (bukan
           class Tailwind) supaya tidak bergantung pada proses build/compile
           Tailwind — jadi selalu aktif walau aset belum di-rebuild. */
        @media (min-width: 1024px) {
            .ekk-sidebar-rail {
                position: fixed;
                top: 2rem;      /* selaras dengan py-8 pada wrapper halaman */
                left: 2.5rem;   /* selaras dengan lg:px-10 pada wrapper halaman */
                width: 5rem;    /* setara w-20 */
                min-height: 33.75rem; /* setara min-h-[540px] */
                z-index: 30;
            }
        }
    </style>
</head>

{{-- Format layout disamakan dengan tampilan role ketua --}}
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- ================= SIDEBAR RAIL ================= --}}
                <aside class="lg:w-20 shrink-0">
                    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3
                                flex lg:flex-col items-center gap-2 overflow-x-auto lg:overflow-visible
                                shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20">

                        <a href="{{ route('admin.dashboard') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl bg-white flex items-center justify-center text-periwinkle lg:mb-4 shadow-md">
                            <i class="fas fa-graduation-cap"></i>
                        </a>

                        <a href="{{ route('admin.dashboard') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Dashboard">
                            <i class="fas fa-border-all"></i>
                        </a>

                        <a href="{{ route('admin.siswa.index') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.siswa.*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Kelola Siswa">
                            <i class="fas fa-users"></i>
                        </a>

                        <a href="{{ route('admin.kelas.index') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.kelas.*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Kelola Kelas">
                            <i class="fas fa-school"></i>
                        </a>

                        <a href="{{ route('admin.user.index') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.user.*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Kelola Users">
                            <i class="fas fa-user-cog"></i>
                        </a>

                        <a href="{{ route('admin.ekskul.index') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.ekskul.*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Kelola Ekskul">
                            <i class="fas fa-cube"></i>
                        </a>

                        <a href="{{ route('admin.pembina.index') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('admin.pembina.*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Kelola Pembina">
                            <i class="fas fa-user-tie"></i>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('profile.edit') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
                           title="Profil">
                            <i class="fas fa-user"></i>
                        </a>

                        <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" class="lg:mt-auto shrink-0">
                            @csrf
                            <button type="submit"
                                    class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all"
                                    title="Keluar">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </aside>

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
