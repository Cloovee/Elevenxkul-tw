<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard Admin') - Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js untuk penanganan dropdown UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-body bg-bgsoft text-ink antialiased"
      style="background-image:
            radial-gradient(circle at 100% 0%, rgba(174,226,255,0.3), transparent 45%),
            radial-gradient(circle at 0% 100%, rgba(217,249,223,0.35), transparent 40%);">
    <div class="flex h-screen overflow-hidden p-3 sm:p-4 lg:p-6 gap-4 lg:gap-6">

        <!-- SIDEBAR DESKTOP (md ke atas) — samakan dengan sidebar Pembina -->
        <aside class="hidden md:flex w-20 lg:w-24 bg-gradient-to-b from-lavender to-periwinkle rounded-[2rem] lg:rounded-[2.5rem] flex-shrink-0 flex-col items-center py-6 lg:py-8 shadow-[0_18px_40px_-14px_rgba(94,92,199,0.55)] z-10">

            <!-- Logo -->
            <div class="w-11 h-11 lg:w-14 lg:h-14 bg-white/20 rounded-[1rem] lg:rounded-[1.2rem] flex items-center justify-center mb-6 lg:mb-10 border border-white/30 shadow-inner backdrop-blur-sm flex-shrink-0">
                <i class="fas fa-graduation-cap text-lg lg:text-2xl text-white"></i>
            </div>

            <!-- Nav (scrollable kalau kepanjangan) -->
            <nav class="flex flex-col space-y-3 lg:space-y-5 w-full px-3 lg:px-4 flex-1 overflow-y-auto no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.dashboard')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Dashboard">
                    <i class="fas fa-border-all text-base lg:text-xl"></i>
                </a>

                <a href="{{ route('admin.siswa.index') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.siswa.*')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Kelola Siswa">
                    <i class="fas fa-users text-base lg:text-xl"></i>
                </a>

                <a href="{{ route('admin.kelas.index') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.kelas.*')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Kelola Kelas">
                    <i class="fas fa-school text-base lg:text-xl"></i>
                </a>

                <a href="{{ route('admin.user.index') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.user.*')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Kelola Users">
                    <i class="fas fa-user-cog text-base lg:text-xl"></i>
                </a>

                <a href="{{ route('admin.ekskul.index') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.ekskul.*')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Kelola Ekskul">
                    <i class="fas fa-cube text-base lg:text-xl"></i>
                </a>

                <a href="{{ route('admin.pembina.index') }}" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.pembina.*')) bg-white text-lavender shadow-lg shadow-black/10 scale-105 @else text-white/75 hover:bg-white/20 hover:text-white @endif" title="Kelola Pembina">
                    <i class="fas fa-user-tie text-base lg:text-xl"></i>
                </a>

                <a href="#" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] transition-all duration-300 text-white/75 hover:bg-white/20 hover:text-white" title="Laporan & Statistik">
                    <i class="fas fa-chart-line text-base lg:text-xl"></i>
                </a>

                <div class="flex-1 min-h-2"></div>

                <!-- Logout -->
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button type="submit" form="logout-form-sidebar" class="w-11 h-11 lg:w-14 lg:h-14 mx-auto flex-shrink-0 flex items-center justify-center rounded-[1rem] lg:rounded-[1.2rem] text-white/75 hover:bg-red-400 hover:text-white transition-all duration-300 group" title="Logout">
                    <i class="fas fa-sign-out-alt text-base lg:text-xl group-hover:scale-110 transition-transform"></i>
                </button>
            </nav>

        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            <!-- HEADER -->
            <header class="px-1 sm:px-2 py-3 sm:py-4 mb-2 flex justify-between items-end gap-3">
                <div class="min-w-0">
                    <p class="text-lavender font-bold text-[10px] sm:text-xs uppercase tracking-[0.14em] mb-1 truncate">@yield('page-title', 'Dashboard')</p>
                    <h2 class="font-display text-xl sm:text-2xl lg:text-3xl font-bold text-ink tracking-tight truncate">Halo, {{ Auth::user()->name ?? 'Admin' }}</h2>
                </div>

                <!-- PROFILE DROPDOWN HEADER -->
                <div class="flex items-center space-x-4 relative flex-shrink-0" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-lavender to-sky-400 text-white flex items-center justify-center shadow-[0_6px_18px_-10px_rgba(46,43,85,0.5)] ring-2 ring-white cursor-pointer focus:outline-none transition-transform active:scale-95 hover:-translate-y-0.5">
                        <span class="text-xs sm:text-sm font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-12 sm:top-14 w-48 max-w-[85vw] bg-white rounded-2xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] py-2 z-50 ring-1 ring-ink/5">

                        <div class="px-4 py-2 border-b border-ink/10">
                            <p class="text-[10px] font-bold text-lavender uppercase tracking-wide">Pengguna</p>
                            <p class="text-sm font-bold text-ink truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-ink hover:bg-bgsoft hover:text-lavender transition-colors">
                            <i class="fas fa-user-edit text-xs"></i> Edit Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors text-left">
                                <i class="fas fa-sign-out-alt text-xs"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- CONTENT (padding bawah ekstra di mobile biar gak ketutup bottom nav) -->
            <main class="flex-1 overflow-y-auto px-1 sm:px-2 pb-24 md:pb-4 no-scrollbar">
                @if(session('success'))
                    <div class="mb-4 flex items-start gap-3 bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl">
                        <i class="fas fa-check-circle mt-0.5"></i><span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl">
                        <i class="fas fa-exclamation-circle mt-0.5"></i><span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- BOTTOM NAV MOBILE (di bawah md) -->
    <nav class="md:hidden fixed bottom-3 left-3 right-3 z-30">
        <div class="bg-gradient-to-r from-lavender to-periwinkle rounded-full shadow-[0_-10px_30px_-10px_rgba(94,92,199,0.55)] px-2 py-2">
            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.dashboard')) bg-white text-lavender @else text-white/75 @endif" title="Dashboard">
                    <i class="fas fa-border-all text-base"></i>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.siswa.*')) bg-white text-lavender @else text-white/75 @endif" title="Siswa">
                    <i class="fas fa-users text-base"></i>
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.kelas.*')) bg-white text-lavender @else text-white/75 @endif" title="Kelas">
                    <i class="fas fa-school text-base"></i>
                </a>
                <a href="{{ route('admin.user.index') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.user.*')) bg-white text-lavender @else text-white/75 @endif" title="Users">
                    <i class="fas fa-user-cog text-base"></i>
                </a>
                <a href="{{ route('admin.ekskul.index') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.ekskul.*')) bg-white text-lavender @else text-white/75 @endif" title="Ekskul">
                    <i class="fas fa-cube text-base"></i>
                </a>
                <a href="{{ route('admin.pembina.index') }}" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full transition-all @if(request()->routeIs('admin.pembina.*')) bg-white text-lavender @else text-white/75 @endif" title="Pembina">
                    <i class="fas fa-user-tie text-base"></i>
                </a>
                <button type="submit" form="logout-form-sidebar" class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-full text-white/75" title="Logout">
                    <i class="fas fa-sign-out-alt text-base"></i>
                </button>
            </div>
        </div>
    </nav>
</body>
</html>
