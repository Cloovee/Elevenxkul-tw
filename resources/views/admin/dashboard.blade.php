@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 lg:gap-6 h-full pb-4">
        
        <!-- ================= KOLOM KIRI ================= -->
        <div class="xl:col-span-7 flex flex-col gap-4 lg:gap-6">
            
            <!-- 1. Card Nyapa Admin -->
            <div class="bg-gradient-to-br from-white to-[#F3F4FC] rounded-3xl p-5 sm:p-6 lg:p-8 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] relative overflow-hidden border border-white/60">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#9FA1FF]/10 rounded-full blur-3xl"></div>

                <h3 class="text-xl sm:text-2xl lg:text-3xl font-display font-bold text-[#2E2B55] mb-2 leading-tight">Program Pembinaan<br>Aplikasi Ekskul</h3>
                <p class="text-[#6B6795] font-medium text-sm w-full sm:w-4/5 mb-6 lg:mb-8">
                    Pantau kehadiran, kelola data pengguna, validasi laporan pembina, dan cek tren aktivitas — semua dari satu layar.
                </p>

                <!-- 5 Card Total — layout vertikal supaya label tidak pernah kepotong -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                    @php
                        $statCards = [
                            ['label' => 'Total Ekskul',  'value' => $totalEkskul ?? 0,  'icon' => 'fa-building',      'accent' => 'lavender'],
                            ['label' => 'Total Siswa',   'value' => $totalSiswa ?? 0,   'icon' => 'fa-user-graduate', 'accent' => 'sky'],
                            ['label' => 'Total Kelas',   'value' => $totalKelas ?? 0,   'icon' => 'fa-school',        'accent' => 'mint'],
                            ['label' => 'Total Pembina', 'value' => $totalPembina ?? 0, 'icon' => 'fa-user-tie',      'accent' => 'periwinkle'],
                            ['label' => 'Total User',    'value' => $totalUser ?? 0,    'icon' => 'fa-user-cog',      'accent' => 'lavender'],
                        ];
                        $accentClasses = [
                            'lavender'   => ['bg' => 'bg-lavender/10',   'text' => 'text-lavender'],
                            'sky'        => ['bg' => 'bg-sky/20',        'text' => 'text-sky-500'],
                            'mint'       => ['bg' => 'bg-mint',          'text' => 'text-[#1F7A3D]'],
                            'periwinkle' => ['bg' => 'bg-periwinkle/15', 'text' => 'text-periwinkle'],
                        ];
                    @endphp
                    @foreach($statCards as $card)
                        @php $ac = $accentClasses[$card['accent']]; @endphp
                        <div class="bg-white rounded-2xl p-3.5 sm:p-4 shadow-sm border border-ink/5 flex flex-col gap-2.5">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 {{ $ac['bg'] }} rounded-xl flex items-center justify-center {{ $ac['text'] }} flex-shrink-0">
                                <i class="fas {{ $card['icon'] }} text-sm sm:text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[#2E2B55] font-display text-lg sm:text-xl font-bold leading-none">{{ $card['value'] }}</p>
                                <p class="text-[#6B6795] font-semibold text-[10px] sm:text-[11px] mt-1 leading-tight">{{ $card['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Menu Kelola (menyesuaikan lebar layar) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 flex-1">

                <a href="{{ route('admin.siswa.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-ink/5 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-sky/25 to-sky/15 rounded-xl flex items-center justify-center text-sky-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-[#2E2B55] font-bold text-sm sm:text-base">Kelola Siswa</p>
                    <p class="text-[#6B6795] text-xs font-medium mt-1">Data anggota ekskul</p>
                </a>

                <a href="{{ route('admin.kelas.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-ink/5 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-mint to-mint/60 rounded-xl flex items-center justify-center text-[#1F7A3D] text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-[#1F7A3D] group-hover:text-white transition-all duration-300">
                        <i class="fas fa-school"></i>
                    </div>
                    <p class="text-[#2E2B55] font-bold text-sm sm:text-base">Kelola Kelas</p>
                    <p class="text-[#6B6795] text-xs font-medium mt-1">Data jurusan &amp; rombel</p>
                </a>

                <a href="{{ route('admin.user.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-ink/5 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-lavender/20 to-lavender/10 rounded-xl flex items-center justify-center text-lavender text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-lavender group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <p class="text-[#2E2B55] font-bold text-sm sm:text-base">Kelola Users</p>
                    <p class="text-[#6B6795] text-xs font-medium mt-1">Manajemen akun sistem</p>
                </a>

                <a href="{{ route('admin.pembina.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-ink/5 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-periwinkle/25 to-periwinkle/10 rounded-xl flex items-center justify-center text-periwinkle text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-periwinkle group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="text-[#2E2B55] font-bold text-sm sm:text-base">Kelola Pembina</p>
                    <p class="text-[#6B6795] text-xs font-medium mt-1">Manajemen guru pembina</p>
                </a>

                <a href="{{ route('admin.ekskul.index') }}" class="bg-white rounded-3xl p-4 sm:p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-ink/5 block">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center text-amber-500 text-lg sm:text-xl mb-3 sm:mb-4 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <p class="text-[#2E2B55] font-bold text-sm sm:text-base">Kelola Ekskul</p>
                    <p class="text-[#6B6795] text-xs font-medium mt-1">Manajemen data ekskul</p>
                </a>
                
            </div>
        </div>

        <!-- ================= KOLOM KANAN ================= -->
        <div class="xl:col-span-5 flex flex-col gap-4 lg:gap-6">

            <!-- 3. LAPORAN DATA EKSKUL -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5">
                <p class="text-[#6B6795] font-bold text-xs tracking-wider mb-1 uppercase">Statistik</p>
                <h4 class="text-[#2E2B55] font-bold text-base sm:text-lg mb-4 sm:mb-6">Laporan Data Ekskul</h4>

                @if(!empty($ekskulChart) && count($ekskulChart) > 0)
                    <div class="flex items-end justify-between h-28 sm:h-32 gap-2 sm:gap-3 px-1 sm:px-2 overflow-x-auto no-scrollbar">
                        @foreach($ekskulChart as $ek)
                            @php
                                $maxVal = $maxAnggota ?? 1;
                                $heightPercent = $maxVal > 0 ? ($ek->anggota_count / $maxVal) * 100 : 0;
                                $heightPx = max(8, round($heightPercent / 100 * 112));
                            @endphp
                            <div class="w-full min-w-[2.5rem] flex flex-col items-center gap-2 group">
                                <div class="w-full bg-gradient-to-t from-[#9FA1FF]/40 to-[#B5BAFF]/40 group-hover:from-[#9FA1FF] group-hover:to-[#B5BAFF] rounded-md transition-all duration-300 relative" style="height: {{ $heightPx }}px;">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#2E2B55] text-white text-[9px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $ek->anggota_count ?? 0 }} siswa
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#6B6795] font-bold text-center leading-tight" title="{{ $ek->nama_ekskul }}">
                                    {{ \Illuminate\Support\Str::limit($ek->nama_ekskul, 8) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-28 sm:h-32 flex items-center justify-center text-[#6B6795] text-sm bg-bgsoft rounded-xl border border-dashed border-ink/10">
                        Belum ada data ekskul.
                    </div>
                @endif
            </div>

            <!-- 4. Card History -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-[#6B6795] font-bold text-xs tracking-wider mb-1 uppercase">Terbaru</p>
                        <h4 class="text-[#2E2B55] font-bold text-base sm:text-lg">Riwayat Admin</h4>
                    </div>
                    <a href="#" class="text-[#9FA1FF] text-xs font-bold hover:underline flex-shrink-0">Lihat Semua</a>
                </div>
                
                <div class="flex-1 flex flex-col gap-3 justify-center">
                    <div class="p-5 rounded-xl bg-[#F3F4FC]/50 border border-dashed border-[#9FA1FF]/30 flex flex-col items-center justify-center text-center">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#6B6795] mb-2 shadow-sm">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="text-[#9FA1FF] font-bold text-sm">Belum ada aktivitas.</p>
                        <p class="text-[#6B6795] text-xs mt-1">Riwayat tindakan akan muncul di sini.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection