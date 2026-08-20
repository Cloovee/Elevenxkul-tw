<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Admin') - Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="font-body bg-bgsoft text-ink antialiased"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(174,226,255,0.30), transparent 45%), radial-gradient(circle at 0% 100%, rgba(217,249,223,0.35), transparent 40%);">
    <div class="flex h-screen overflow-hidden p-4 gap-4">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-64 shrink-0 rounded-3xl bg-gradient-to-b from-panelnight to-panelnight-soft
                       shadow-[0_18px_40px_-14px_rgba(38,36,80,0.55)] flex flex-col overflow-hidden">
            <div class="p-6 border-b border-white/10">
                <h1 class="font-display text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-mint"></i>
                    </span>
                    Elevenxkul
                </h1>
                <p class="text-xs text-white/50 mt-1.5 ml-0.5">Admin Panel</p>
            </div>

            <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-2xl text-sm font-medium transition-all
                          @if(request()->routeIs('admin.dashboard')) bg-white text-panelnight shadow-lg shadow-black/10 @else text-white/70 hover:bg-white/10 hover:text-white @endif">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('admin.ekskul.index') }}"
                   class="flex items-center px-4 py-3 rounded-2xl text-sm font-medium transition-all
                          @if(request()->routeIs('admin.ekskul.*')) bg-white text-panelnight shadow-lg shadow-black/10 @else text-white/70 hover:bg-white/10 hover:text-white @endif">
                    <i class="fas fa-building w-5"></i>
                    <span class="ml-3">Kelola Ekskul</span>
                </a>

                <a href="{{ route('admin.pembina.index') }}"
                   class="flex items-center px-4 py-3 rounded-2xl text-sm font-medium transition-all
                          @if(request()->routeIs('admin.pembina.*')) bg-white text-panelnight shadow-lg shadow-black/10 @else text-white/70 hover:bg-white/10 hover:text-white @endif">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="ml-3">Kelola Pembina</span>
                </a>

                <div class="flex items-center px-4 py-3 rounded-2xl text-sm text-white/35 cursor-not-allowed">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Kelola Siswa</span>
                    <span class="ml-auto text-[10px] font-bold bg-white/10 text-white/60 px-2 py-0.5 rounded-full">Segera</span>
                </div>

                <div class="border-t border-white/10 my-3"></div>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-3 rounded-2xl text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-user w-5"></i>
                    <span class="ml-3">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center px-4 py-3 rounded-2xl text-sm font-medium text-red-300 hover:bg-red-500/10 hover:text-red-200 transition-all">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </nav>

            <div class="p-4">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-lavender to-mint flex items-center justify-center text-panelnight font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[11px] text-white/45 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ================= MAIN ================= --}}
        <div class="flex-1 flex flex-col overflow-hidden rounded-3xl bg-white/60 shadow-[0_18px_40px_-20px_rgba(46,43,85,0.25)]">
            <header class="px-6 py-4 border-b border-[#EDEDF7] bg-white/70 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-xl font-semibold text-ink">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-inksoft mt-0.5">{{ date('d F Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="hidden sm:flex text-xs text-inksoft items-center bg-bgsoft px-3 py-2 rounded-xl">
                            <i class="far fa-calendar-alt mr-2"></i>
                            {{ date('d F Y') }}
                        </span>
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-lavender to-periwinkle text-white flex items-center justify-center shadow-md shadow-periwinkle/30">
                            <span class="text-sm font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6"
                  style="background-image: radial-gradient(circle at 0% 0%, rgba(181,186,255,0.10), transparent 40%);">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-mint/40 border-l-4 border-emerald-400 text-[#1F7A3D] rounded-2xl animate-fade-in-up">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-600 rounded-2xl animate-fade-in-up">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
