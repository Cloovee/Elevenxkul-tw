<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembina — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(174,226,255,0.35), transparent 45%), radial-gradient(circle at 0% 100%, rgba(217,249,223,0.4), transparent 40%);">

<div class="flex gap-5 p-5 min-h-screen">

    {{-- ================= SIDEBAR MENGAMBANG ================= --}}
    <aside class="w-[84px] shrink-0 sticky top-5 h-[calc(100vh-40px)] rounded-3xl
                  bg-gradient-to-b from-lavender to-periwinkle
                  flex flex-col items-center py-6 gap-2.5
                  shadow-[0_18px_40px_-14px_rgba(94,92,199,0.55)]">

        <div class="w-[42px] h-[42px] rounded-2xl bg-white/25 flex items-center justify-center
                    text-white font-display font-bold text-lg mb-4">P</div>

        <button type="button" data-tab-btn="overview"
                class="tab-btn w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all text-white/75 hover:bg-white/20 hover:text-white"
                title="Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
        </button>

        <button type="button" data-tab-btn="absensi"
                class="tab-btn w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all text-white/75 hover:bg-white/20 hover:text-white"
                title="Absensi Peserta">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6M9 8h1"/></svg>
        </button>

        <button type="button" data-tab-btn="validasi"
                class="tab-btn w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all text-white/75 hover:bg-white/20 hover:text-white"
                title="Validasi Absensi Pelatih">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 11 2 2 4-4"/></svg>
        </button>

        <button type="button" data-tab-btn="nilai"
                class="tab-btn w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all text-white/75 hover:bg-white/20 hover:text-white"
                title="Beri Nilai Peserta">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
        </button>

        <div class="flex-1"></div>

        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center text-white/75 hover:bg-white/20 hover:text-white transition-all mb-1" title="Keluar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </aside>

    {{-- ================= MAIN CONTENT ================= --}}
    <main class="flex-1 min-w-0 flex flex-col gap-5">

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- TOPBAR --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="font-display text-2xl font-semibold">Halo, {{ auth()->user()->name ?? 'Pembina' }} </h1>
                <p class="text-inksoft text-sm mt-0.5">{{ now()->translatedFormat('l, d F Y') }} — Program Pembinaan Elevenxkul</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 bg-white rounded-2xl px-4 py-2.5 shadow-[0_6px_18px_-10px_rgba(46,43,85,0.25)] text-inksoft text-sm w-56">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Cari peserta / pelatih..." class="bg-transparent outline-none w-full text-sm">
                </div>

                <div class="relative w-[42px] h-[42px] rounded-2xl bg-white flex items-center justify-center shadow-[0_6px_18px_-10px_rgba(46,43,85,0.25)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @if($pendingValidasi > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-400 border-2 border-white"></span>
                    @endif
                </div>

                <div class="w-[42px] h-[42px] rounded-2xl overflow-hidden shadow-[0_6px_18px_-10px_rgba(46,43,85,0.25)]">
                    <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ auth()->user()->email ?? 'pembina' }}" class="w-full h-full object-cover" alt="Foto Pembina">
                </div>
            </div>
        </div>

        {{-- ================= FEATURE CARDS (sesuai use case) ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <button type="button" data-tab-btn="absensi"
                    class="tab-card text-left rounded-3xl p-5 border-2 border-transparent bg-gradient-to-br from-mint to-[#EFFDF1] hover:-translate-y-1 transition-all min-h-[130px] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-white/65 flex items-center justify-center text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6M9 8h1"/></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/70">{{ $hadirCount }}/{{ $pesertaAbsensi->count() }} hadir</span>
                </div>
                <div class="mt-7">
                    <h3 class="font-display text-lg font-semibold">Melihat Absensi Peserta</h3>
                    <p class="text-xs text-inksoft mt-1">Pantau kehadiran peserta di setiap sesi latihan</p>
                </div>
            </button>

            <button type="button" data-tab-btn="validasi"
                    class="tab-card text-left rounded-3xl p-5 border-2 border-transparent bg-gradient-to-br from-sky to-[#EAF7FF] hover:-translate-y-1 transition-all min-h-[130px] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-white/65 flex items-center justify-center text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 11 2 2 4-4"/></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/70">{{ $pendingValidasi }} menunggu</span>
                </div>
                <div class="mt-7">
                    <h3 class="font-display text-lg font-semibold">Validasi Absensi Pelatih</h3>
                    <p class="text-xs text-inksoft mt-1">Setujui atau tolak laporan kehadiran pelatih</p>
                </div>
            </button>

            <button type="button" data-tab-btn="nilai"
                    class="tab-card text-left rounded-3xl p-5 border-2 border-transparent bg-gradient-to-br from-periwinkle to-[#E4E5FF] hover:-translate-y-1 transition-all min-h-[130px] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-white/65 flex items-center justify-center text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/70">{{ $nilaiTerisi }}/{{ $daftarNilai->count() }} dinilai</span>
                </div>
                <div class="mt-7">
                    <h3 class="font-display text-lg font-semibold">Memberi Nilai Peserta</h3>
                    <p class="text-xs text-inksoft mt-1">Input dan simpan penilaian performa peserta</p>
                </div>
            </button>
        </div>

        {{-- ================= OVERVIEW ================= --}}
        <div data-tab-panel="overview" class="tab-panel grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-4 items-start">
            <div class="bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-base">Tren Kehadiran Peserta</h3>
                    <span class="text-xs font-bold text-lavender">
                        Rata-rata {{ $trend->count() ? round($trend->avg('persentase')) : 0 }}%
                    </span>
                </div>

                <div class="flex items-end gap-3 h-40 mt-4">
                    @forelse($trend as $t)
                        <div class="flex-1 flex flex-col items-center gap-1.5">
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-lavender/20 to-lavender"
                                 style="height: {{ max($t['persentase'], 4) }}%"
                                 title="{{ $t['persentase'] }}%"></div>
                            <span class="text-[11px] text-inksoft">{{ $t['bulan'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-inksoft">Belum ada data absensi.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
                <h3 class="font-bold text-base mb-3">Ringkasan Hari Ini</h3>
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-3.5 py-3">
                        <div class="w-9 h-9 rounded-xl bg-mint text-[#1F7A3D] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div class="font-extrabold text-sm">{{ $pesertaAbsensi->count() }} Peserta</div>
                            <div class="text-[11px] text-inksoft">Terdaftar di sesi terbaru</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-3.5 py-3">
                        <div class="w-9 h-9 rounded-xl bg-sky text-[#1E6FA8] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        </div>
                        <div>
                            <div class="font-extrabold text-sm">{{ $pendingValidasi }} Menunggu</div>
                            <div class="text-[11px] text-inksoft">Absensi pelatih perlu divalidasi</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-3.5 py-3">
                        <div class="w-9 h-9 rounded-xl bg-periwinkle text-[#3F41B0] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <div>
                            <div class="font-extrabold text-sm">{{ $nilaiTerisi }} Nilai</div>
                            <div class="text-[11px] text-inksoft">Sudah diberikan bulan ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= ABSENSI PESERTA ================= --}}
        <div data-tab-panel="absensi" class="tab-panel hidden bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-base">
                    Absensi Peserta @if($sesiTerbaru) — {{ $sesiTerbaru->nama_sesi }} @endif
                </h3>
                <span class="text-xs font-bold text-lavender">{{ $hadirCount }}/{{ $pesertaAbsensi->count() }} hadir</span>
            </div>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kelas</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Jam Masuk</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertaAbsensi as $a)
                        <tr class="border-b border-[#F5F5FA] last:border-none">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-periwinkle text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $a->peserta->nama))->map(fn($s) => $s[0])->take(2)->implode('') }}
                                    </div>
                                    {{ $a->peserta->nama }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $a->peserta->kelas ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $a->jam_hadir ?? '-' }}</td>
                            <td class="py-2.5 px-2">
                                @php
                                    $statusClass = match($a->status) {
                                        'Hadir' => 'bg-mint text-[#1F7A3D]',
                                        'Tidak Hadir' => 'bg-red-100 text-red-500',
                                        'Terlambat' => 'bg-yellow-100 text-yellow-700',
                                        'Izin' => 'bg-sky text-[#1E6FA8]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $statusClass }}">{{ $a->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-inksoft text-sm">Belum ada data absensi peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= VALIDASI ABSENSI PELATIH ================= --}}
        <div data-tab-panel="validasi" class="tab-panel hidden bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-base">Validasi Absensi Pelatih</h3>
                <span class="text-xs font-bold text-lavender">{{ $pendingValidasi }} menunggu tindakan</span>
            </div>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Pelatih</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Sesi</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Tanggal / Jam</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Status</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($validasiPelatih as $v)
                        <tr class="border-b border-[#F5F5FA] last:border-none">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-lavender text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $v->pelatih->nama))->map(fn($s) => $s[0])->take(2)->implode('') }}
                                    </div>
                                    {{ $v->pelatih->nama }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $v->sesi->nama_sesi ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ optional($v->sesi->tanggal)->translatedFormat('d M Y') }}, {{ $v->jam_lapor }}</td>
                            <td class="py-2.5 px-2">
                                @php
                                    $vClass = match($v->status) {
                                        'Divalidasi' => 'bg-mint text-[#1F7A3D]',
                                        'Ditolak' => 'bg-red-100 text-red-500',
                                        default => 'bg-sky text-[#1E6FA8]',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $vClass }}">{{ $v->status }}</span>
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('pembina.validasi.setujui', $v) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-[30px] h-[30px] rounded-lg bg-mint text-[#1F7A3D] flex items-center justify-center disabled:opacity-30"
                                                {{ $v->status !== 'Menunggu' ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('pembina.validasi.tolak', $v) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-[30px] h-[30px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center disabled:opacity-30"
                                                {{ $v->status !== 'Menunggu' ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-inksoft text-sm">Belum ada absensi pelatih yang perlu divalidasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= BERI NILAI PESERTA ================= --}}
        <div data-tab-panel="nilai" class="tab-panel hidden bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-base">Beri Nilai Peserta</h3>
                <span class="text-xs font-bold text-lavender">{{ $nilaiTerisi }}/{{ $daftarNilai->count() }} sudah dinilai bulan ini</span>
            </div>
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kategori</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Nilai terakhir</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Beri nilai baru</th>
                        <th class="border-b border-[#EFEFF7]"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarNilai as $p)
                        @php $terakhir = $p->nilai->first(); @endphp
                        <tr class="border-b border-[#F5F5FA] last:border-none">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-periwinkle text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $p->nama))->map(fn($s) => $s[0])->take(2)->implode('') }}
                                    </div>
                                    {{ $p->nama }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $terakhir->kategori ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $terakhir->nilai ?? '—' }}</td>
                            <td colspan="2" class="py-2.5 px-2">
                                <form method="POST" action="{{ route('pembina.nilai.simpan', $p) }}" class="flex items-center gap-2">
                                    @csrf
                                    <select name="kategori" class="px-2.5 py-1.5 rounded-lg border border-[#E7E7F4] text-xs focus:outline-none focus:border-lavender">
                                        <option value="Teknik">Teknik</option>
                                        <option value="Disiplin">Disiplin</option>
                                        <option value="Kerja Sama">Kerja Sama</option>
                                    </select>
                                    <input type="number" name="nilai" min="0" max="100" placeholder="0-100" required
                                           class="w-20 text-center px-2.5 py-1.5 rounded-lg border border-[#E7E7F4] focus:outline-none focus:border-lavender">
                                    <button type="submit" class="bg-lavender hover:bg-[#8385f0] text-white font-bold text-xs px-3.5 py-1.5 rounded-lg">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-inksoft text-sm">Belum ada data peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>

<script>
    // Tab switching sederhana (tanpa dependency JS tambahan)
    const tabButtons = document.querySelectorAll('[data-tab-btn]');
    const tabPanels = document.querySelectorAll('[data-tab-panel]');

    function activateTab(tabName) {
        tabPanels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabName);
        });
        tabButtons.forEach((btn) => {
            const isActive = btn.dataset.tabBtn === tabName;
            btn.classList.toggle('bg-white', isActive && btn.classList.contains('tab-btn'));
            btn.classList.toggle('text-lavender', isActive && btn.classList.contains('tab-btn'));
            btn.classList.toggle('text-white/75', !isActive && btn.classList.contains('tab-btn'));
        });
    }

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tabBtn));
    });

    // Buka tab sesuai query string ?tab=... (dikirim balik oleh controller setelah aksi)
    const initialTab = @json($activeTab);
    activateTab(['overview', 'absensi', 'validasi', 'nilai'].includes(initialTab) ? initialTab : 'overview');
</script>

</body>
</html>