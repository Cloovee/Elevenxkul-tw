@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="flex flex-col gap-4 lg:gap-6 pb-4">

        <!-- ================= BAGIAN ATAS: SAPAAN + STATISTIK ================= -->
        
            <!-- 1. Card Nyapa Admin -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 lg:p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] relative overflow-hidden border border-white/60">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#0B409C]/10 rounded-full blur-3xl"></div>

                <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#10316B] mb-2 leading-tight">Program Pembinaan<br>Aplikasi Ekskul</h3>
                <p class="text-[#7C8DB5] font-medium text-sm w-full sm:w-4/5 mb-6 lg:mb-8">
                    Pantau kehadiran, kelola data pengguna, validasi laporan pembina, dan cek tren aktivitas — semua dari satu layar.
                </p>

                <!-- 5 Card Total — layout vertikal supaya label tidak pernah kepotong -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                    <div class="bg-white rounded-2xl px-4 sm:px-5 py-3 sm:py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#F2F7FF] rounded-xl flex items-center justify-center text-[#0B409C] flex-shrink-0">
                            <i class="fas fa-building text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[#7C8DB5] font-bold text-[9px] sm:text-[10px] uppercase tracking-wider truncate">Total Ekskul</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#10316B] leading-none mt-1">
                                {{ $totalEkskul ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-4 sm:px-5 py-3 sm:py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#FFF9DB] rounded-xl flex items-center justify-center text-orange-400 flex-shrink-0">
                            <i class="fas fa-user-graduate text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[#7C8DB5] font-bold text-[9px] sm:text-[10px] uppercase tracking-wider truncate">Total Siswa</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#10316B] leading-none mt-1">{{ $totalSiswa ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-4 sm:px-5 py-3 sm:py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 flex-shrink-0">
                            <i class="fas fa-school text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[#7C8DB5] font-bold text-[9px] sm:text-[10px] uppercase tracking-wider truncate">Total Kelas</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#10316B] leading-none mt-1">{{ $totalKelas ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-4 sm:px-5 py-3 sm:py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500 flex-shrink-0">
                            <i class="fas fa-user-tie text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[#7C8DB5] font-bold text-[9px] sm:text-[10px] uppercase tracking-wider truncate">Total Pembina</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#10316B] leading-none mt-1">{{ $totalPembina ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl px-4 sm:px-5 py-3 sm:py-4 shadow-sm border border-gray-50 flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 flex-shrink-0">
                            <i class="fas fa-user-cog text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[#7C8DB5] font-bold text-[9px] sm:text-[10px] uppercase tracking-wider truncate">Total User</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#10316B] leading-none mt-1">{{ $totalUser ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Menu Kelola (menyesuaikan lebar layar) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 flex-1">

                <a href="{{ route('admin.siswa.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-[#10316B] font-bold text-sm sm:text-base">Kelola Siswa</p>
                    <p class="text-[#7C8DB5] text-xs font-medium mt-1">Data anggota ekskul</p>
                </a>

                <a href="{{ route('admin.kelas.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-school"></i>
                    </div>
                    <p class="text-[#10316B] font-bold text-sm sm:text-base">Kelola Kelas</p>
                    <p class="text-[#7C8DB5] text-xs font-medium mt-1">Data jurusan &amp; rombel</p>
                </a>

                <a href="{{ route('admin.user.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <p class="text-[#10316B] font-bold text-sm sm:text-base">Kelola Users</p>
                    <p class="text-[#7C8DB5] text-xs font-medium mt-1">Manajemen akun sistem</p>
                </a>

                <a href="{{ route('admin.pembina.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-100 rounded-xl flex items-center justify-center text-teal-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="text-[#10316B] font-bold text-sm sm:text-base">Kelola Pembina</p>
                    <p class="text-[#7C8DB5] text-xs font-medium mt-1">Manajemen guru pembina</p>
                </a>

                <a href="{{ route('admin.ekskul.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-50 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <p class="text-[#10316B] font-bold text-sm sm:text-base">Kelola Ekskul</p>
                    <p class="text-[#7C8DB5] text-xs font-medium mt-1">Manajemen data ekskul</p>
                </a>

        </div>

        <!-- ================= BAGIAN BAWAH: STATISTIK + RIWAYAT ================= -->

            <!-- 3. LAPORAN DATA EKSKUL -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50">
                <p class="text-[#7C8DB5] font-bold text-xs tracking-wider mb-1 uppercase">Statistik</p>
                <h4 class="text-[#10316B] font-bold text-base sm:text-lg mb-4 sm:mb-6">Laporan Data Ekskul</h4>

                @if(!empty($ekskulChart) && count($ekskulChart) > 0)
                    <div class="flex items-end justify-between h-28 sm:h-32 gap-2 sm:gap-3 px-1 sm:px-2 overflow-x-auto no-scrollbar">
                        @foreach($ekskulChart as $ek)
                            @php
                                $maxVal = $maxAnggota ?? 1;
                                $heightPercent = $maxVal > 0 ? ($ek->anggota_count / $maxVal) * 100 : 0;
                                $heightPx = max(8, round($heightPercent / 100 * 112));
                            @endphp
                            <div class="w-full min-w-[2.5rem] flex flex-col items-center gap-2 group">
                                <div class="w-full bg-periwinkle/40 group-hover:bg-periwinkle rounded-md transition-all duration-300 relative" style="height: {{ $heightPx }}px;">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#10316B] text-white text-[9px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $ek->anggota_count ?? 0 }} siswa
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#7C8DB5] font-bold text-center leading-tight" title="{{ $ek->nama_ekskul }}">
                                    {{ \Illuminate\Support\Str::limit($ek->nama_ekskul, 8) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-28 sm:h-32 flex items-center justify-center text-[#7C8DB5] text-sm bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        Belum ada data ekskul.
                    </div>
                @endif
            </div>

            <!-- 4. Card History -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-[#7C8DB5] font-bold text-xs tracking-wider mb-1 uppercase">Terbaru</p>
                        <h4 class="text-[#10316B] font-bold text-base sm:text-lg">Riwayat Admin</h4>
                    </div>
                    <a href="#" class="text-[#0B409C] text-xs font-bold hover:underline flex-shrink-0">Lihat Semua</a>
                </div>
                
                <div class="flex-1 flex flex-col gap-3 justify-center">
                    <div class="p-5 rounded-xl bg-[#F2F7FF]/50 border border-dashed border-[#0B409C]/30 flex flex-col items-center justify-center text-center">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#7C8DB5] mb-2 shadow-sm">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="text-[#0B409C] font-bold text-sm">Belum ada aktivitas.</p>
                        <p class="text-[#7C8DB5] text-xs mt-1">Riwayat tindakan akan muncul di sini.</p>
                    </div>
                </div>
            </div>

    </div>
@endsection