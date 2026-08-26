<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Admin') - Ekskul App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-[#f4f7fe] font-sans antialiased text-[#2b3674]">
    <div class="flex h-screen overflow-hidden p-4 lg:p-6 gap-6">
        
        <!-- SIDEBAR (Smooth Gradient & Added Report Icon) -->
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

                <!-- Kelola Ekskul (belum aktif) -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Kelola Ekskul">
                    <i class="fas fa-cube text-xl"></i>
                </a>
                
                <!-- Kelola Pembina (belum aktif) -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Kelola Pembina">
                    <i class="fas fa-user-tie text-xl"></i>
                </a>

                <!-- Laporan / Grafik (belum aktif) -->
                <a href="#" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] transition-all duration-300 text-white/70 hover:bg-white/20 hover:text-white" title="Laporan & Statistik">
                    <i class="fas fa-chart-line text-xl"></i>
                </a>
                
                <div class="flex-1"></div>
                
                <!-- Logout -->
                <a href="{{ route('admin.dashboard') }}" class="w-14 h-14 mx-auto flex items-center justify-center rounded-[1.2rem] text-white/70 hover:bg-red-400 hover:text-white transition-all duration-300 group" title="Logout">
                    <i class="fas fa-sign-out-alt text-xl group-hover:scale-110 transition-transform"></i>
                </a>
            </nav>
        </aside>
        
        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            <header class="px-2 py-4 mb-2 flex justify-between items-end">
                <div>
                    <p class="text-[#868dfb] font-bold text-xs uppercase tracking-wider mb-1">@yield('page-title', 'Dashboard')</p>
                    <h2 class="text-3xl font-extrabold text-[#2b3674] tracking-tight">Halo, Admin</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white flex items-center justify-center shadow-md shadow-[#868dfb]/30 ring-2 ring-white cursor-pointer">
                        <span class="text-sm font-bold">A</span>
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