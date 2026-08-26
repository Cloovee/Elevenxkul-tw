@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 h-full pb-4">
        
        <!-- ================= KOLOM KIRI (Span 7) ================= -->
        <div class="xl:col-span-7 flex flex-col gap-6">
            
            <!-- 1. Card Nyapa Admin -->
            <div class="bg-gradient-to-br from-white to-[#f8faff] rounded-[1.5rem] p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] relative overflow-hidden border border-white/60">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#868dfb]/10 rounded-full blur-3xl"></div>

                <h3 class="text-3xl font-extrabold text-[#2b3674] mb-2 leading-tight">Program Pembinaan<br>Aplikasi Ekskul</h3>
                <p class="text-[#a3aed1] font-medium text-sm w-4/5 mb-8">
                    Pantau kehadiran, kelola data pengguna, validasi laporan pembina, dan cek tren aktivitas — semua dari satu layar.
                </p>

                <!-- 4 Card Total, sejajar -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#f4f7fe] rounded-xl flex items-center justify-center text-[#868dfb] flex-shrink-0">
                            <i class="fas fa-building text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[#a3aed1] font-bold text-[10px] uppercase tracking-wider">Total Ekskul</p>
                            <p class="text-2xl font-extrabold text-[#2b3674] leading-none mt-1">
                                {{ $totalEkskul ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#fff4e5] rounded-xl flex items-center justify-center text-orange-400 flex-shrink-0">
                            <i class="fas fa-user-graduate text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[#a3aed1] font-bold text-[10px] uppercase tracking-wider">Total Siswa</p>
                            <p class="text-2xl font-extrabold text-[#2b3674] leading-none mt-1">{{ $totalSiswa ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500 flex-shrink-0">
                            <i class="fas fa-user-tie text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[#a3aed1] font-bold text-[10px] uppercase tracking-wider">Total Pembina</p>
                            <p class="text-2xl font-extrabold text-[#2b3674] leading-none mt-1">{{ $totalPembina ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 flex-shrink-0">
                            <i class="fas fa-user-cog text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[#a3aed1] font-bold text-[10px] uppercase tracking-wider">Total User</p>
                            <p class="text-2xl font-extrabold text-[#2b3674] leading-none mt-1">{{ $totalUser ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Menu Kelola (Grid 2x2: Semua Aktif) -->
            <div class="grid grid-cols-2 gap-4 flex-1">

                <!-- Kelola Siswa -->
                <a href="{{ route('admin.siswa.index') }}" class="bg-white rounded-[1.5rem] p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center text-blue-500 text-xl mb-4 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-[#2b3674] font-bold text-base">Kelola Siswa</p>
                    <p class="text-[#a3aed1] text-xs font-medium mt-1">Data anggota ekskul</p>
                </a>

                <!-- Kelola Users -->
                <a href="{{ route('admin.user.index') }}" class="bg-white rounded-[1.5rem] p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 text-xl mb-4 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <p class="text-[#2b3674] font-bold text-base">Kelola Users</p>
                    <p class="text-[#a3aed1] text-xs font-medium mt-1">Manajemen akun sistem</p>
                </a>

                <!-- Kelola Pembina -->
                <a href="{{ route('admin.pembina.index') }}" class="bg-white rounded-[1.5rem] p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-teal-50 rounded-xl flex items-center justify-center text-teal-500 text-xl mb-4 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="text-[#2b3674] font-bold text-base">Kelola Pembina</p>
                    <p class="text-[#a3aed1] text-xs font-medium mt-1">Manajemen guru pembina</p>
                </a>

                <!-- Kelola Ekskul (Aktif) -->
                <a href="{{ route('admin.ekskul.index') }}" class="bg-white rounded-[1.5rem] p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center text-purple-500 text-xl mb-4 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <p class="text-[#2b3674] font-bold text-base">Kelola Ekskul</p>
                    <p class="text-[#a3aed1] text-xs font-medium mt-1">Manajemen data ekskul</p>
                </a>
                
            </div>
        </div>

        <!-- ================= KOLOM KANAN (Span 5) ================= -->
        <div class="xl:col-span-5 flex flex-col gap-6">

            <!-- 3. LAPORAN DATA EKSKUL (Grafik jumlah siswa per ekskul) -->
            <div class="bg-white rounded-[1.5rem] p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50">
                <p class="text-[#a3aed1] font-bold text-xs tracking-wider mb-1 uppercase">Statistik</p>
                <h4 class="text-[#2b3674] font-bold text-lg mb-6">Laporan Data Ekskul</h4>

                @if(!empty($ekskulChart) && count($ekskulChart) > 0)
                    <div class="flex items-end justify-between h-32 gap-3 px-2">
                        @foreach($ekskulChart as $ek)
                            @php
                                $maxVal = $maxAnggota ?? 1;
                                $heightPercent = $maxVal > 0 ? ($ek->anggota_count / $maxVal) * 100 : 0;
                                $heightPx = max(8, round($heightPercent / 100 * 112));
                            @endphp
                            <div class="w-full flex flex-col items-center gap-2 group">
                                <div class="w-full bg-gradient-to-t from-[#868dfb]/40 to-[#b6bbfc]/40 group-hover:from-[#868dfb] group-hover:to-[#b6bbfc] rounded-md transition-all duration-300 relative" style="height: {{ $heightPx }}px;">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#2b3674] text-white text-[9px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $ek->anggota_count ?? 0 }} siswa
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#a3aed1] font-bold text-center leading-tight" title="{{ $ek->nama_ekskul }}">
                                    {{ \Illuminate\Support\Str::limit($ek->nama_ekskul, 8) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-32 flex items-center justify-center text-[#a3aed1] text-sm bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        Belum ada data ekskul.
                    </div>
                @endif
            </div>

            <!-- 4. Card History (Bottom Right) -->
            <div class="bg-white rounded-[1.5rem] p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-[#a3aed1] font-bold text-xs tracking-wider mb-1 uppercase">Terbaru</p>
                        <h4 class="text-[#2b3674] font-bold text-lg">Riwayat Admin</h4>
                    </div>
                    <a href="#" class="text-[#868dfb] text-xs font-bold hover:underline">Lihat Semua</a>
                </div>
                
                <div class="flex-1 flex flex-col gap-3 justify-center">
                    <div class="p-5 rounded-xl bg-[#f4f7fe]/50 border border-dashed border-[#868dfb]/30 flex flex-col items-center justify-center text-center">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#a3aed1] mb-2 shadow-sm">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="text-[#868dfb] font-bold text-sm">Belum ada aktivitas.</p>
                        <p class="text-[#a3aed1] text-xs mt-1">Riwayat tindakan akan muncul di sini.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection