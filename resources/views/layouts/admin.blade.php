<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Admin') - Ekskul App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js untuk penanganan dropdown UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#f4f7fe] font-sans antialiased text-[#2b3674]">
    <div class="flex h-screen overflow-hidden p-4 lg:p-6 gap-6">
        
        <!-- SIDEBAR -->
        <aside class="w-24 bg-gradient-to-b from-[#7b84fc] via-[#868dfb] to-[#6069e6] rounded-[2.5rem] flex-shrink-0 flex flex-col items-center py-8 shadow-xl shadow-[#868dfb]/20 z-10">
            
            <!-- Logo / Profile -->
            <div class="w-14 h-14 bg-white/20 rounded-[1.2rem] flex items-center justify-center mb-10 border border-white/30 shadow-inner backdrop-blur-sm">
                <i class="fas fa-graduation-cap text-2xl text-white"></i>
            </div>
            
            <nav class="flex flex-col space-y-5 w-full px-4 flex-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.dashboard')) bg-white text-[#868dfb] shadow-lg shadow-white/20 scale-105 @else text-white/70 hover:bg-white/20 hover:text-white @endif" title="Dashboard">
                    <i class="fas fa-border-all text-xl"></i>
                </a>

                <!-- Kelola Siswa -->
                <a href="{{ route('admin.siswa.index') }}" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.siswa.*')) bg-white text-[#868dfb] shadow-lg shadow-white/20 scale-105 @else text-white/70 hover:bg-white/20 hover:text-white @endif" title="Kelola Siswa">
                    <i class="fas fa-users text-xl"></i>
                </a>

                <!-- Kelola Users -->
                <a href="{{ route('admin.user.index') }}" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 @if(request()->routeIs('admin.user.*')) bg-white text-[#868dfb] shadow-lg shadow-white/20 scale-105 @else text-white/70 hover:bg-white/20 hover:text-white @endif" title="Kelola Users">
                    <i class="fas fa-user-cog text-xl"></i>
                </a>

                <!-- Kelola Ekskul -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Kelola Ekskul">
                    <i class="fas fa-cube text-xl"></i>
                </a>
                
                <!-- Kelola Pembina -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Kelola Pembina">
                    <i class="fas fa-user-tie text-xl"></i>
                </a>

                <!-- Laporan / Grafik -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Laporan & Statistik">
                    <i class="fas fa-chart-line text-xl"></i>
                </a>
                
                <div class="flex-1"></div>
                
                <!-- Form Logout (Sidebar) -->
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button type="submit" form="logout-form-sidebar" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] text-white/70 hover:bg-red-400 hover:text-white transition-all duration-300 group" title="Logout">
                    <i class="fas fa-sign-out-alt text-xl group-hover:scale-110 transition-transform"></i>
                </button>
            </nav>
        </aside>
        
        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            <header class="px-2 py-4 mb-2 flex justify-between items-end">
                <div>
                    <p class="text-[#868dfb] font-bold text-xs uppercase tracking-wider mb-1">@yield('page-title', 'Dashboard')</p>
                    <h2 class="text-3xl font-extrabold text-[#2b3674] tracking-tight">Halo, {{ Auth::user()->name ?? 'Admin' }}</h2>
                </div>
                
                <!-- PROFILE DROPDOWN HEADER -->
                <div class="flex items-center space-x-4 relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-11 h-11 rounded-full bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white flex items-center justify-center shadow-md shadow-[#868dfb]/30 ring-2 ring-white cursor-pointer focus:outline-none transition-transform active:scale-95">
                        <span class="text-sm font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
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
                         class="absolute right-0 top-14 w-48 bg-white rounded-2xl shadow-xl py-2 z-50 border border-gray-100">
                        
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-[10px] font-bold text-[#868dfb] uppercase">Pengguna</p>
                            <p class="text-sm font-bold text-[#2b3674] truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-[#2b3674] hover:bg-[#f4f7fe] hover:text-[#868dfb] transition-colors">
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
            
            <!-- CONTENT -->
            <main class="flex-1 overflow-y-auto px-2 pb-4 scrollbar-hide">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl shadow-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>