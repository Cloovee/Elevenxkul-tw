<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Dashboard Pembina — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes dashIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes dashPop {
            from { opacity: 0; transform: scale(.85); }
            to   { opacity: 1; transform: scale(1); }
        }
        .dash-in {
            opacity: 0;
            animation: dashIn .65s cubic-bezier(.16,.84,.44,1) forwards;
            animation-delay: var(--d, 0ms);
        }
        .dash-pop {
            opacity: 0;
            animation: dashPop .5s cubic-bezier(.34,1.56,.64,1) forwards;
            animation-delay: var(--d, 0ms);
        }
        .dash-bar {
            height: 0;
            transition: height 1.1s cubic-bezier(.16,.84,.44,1);
            transition-delay: var(--d, 0ms);
        }
        @media (prefers-reduced-motion: reduce) {
            .dash-in, .dash-pop { animation: none !important; opacity: 1 !important; }
            .dash-bar { transition: none !important; }
        }
    </style>
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen"
      style="background-image:
            radial-gradient(circle at 100% 0%, rgba(174,226,255,0.3), transparent 45%),
            radial-gradient(circle at 0% 100%, rgba(217,249,223,0.35), transparent 40%);">

<div class="flex gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'dashboard'])

    <main class="flex-1 min-w-0 flex flex-col gap-5 pb-24 md:pb-0">

        @if (session('success'))
            <div class="dash-in flex items-start gap-3 bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="dash-in flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- TOPBAR --}}
        <div class="dash-in flex items-center justify-between flex-wrap gap-3" style="--d:0ms">
            <div>
                <span class="text-[11px] font-bold tracking-[0.14em] uppercase text-lavender">Dashboard Pembina</span>
                <h1 class="font-display text-2xl md:text-[26px] font-bold tracking-tight leading-tight mt-0.5">
                    Halo, {{ $pembina->nama_pembina ?? auth()->user()->name ?? 'Pembina' }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pembina.validasi.index') }}"
                   class="relative w-[44px] h-[44px] rounded-2xl bg-lavender/10 flex items-center justify-center text-lavender hover:bg-lavender/20 hover:-translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @if($pendingValidasi > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-bgsoft">
                            {{ $pendingValidasi > 9 ? '9+' : $pendingValidasi }}
                        </span>
                    @endif
                </a>

                @php
                    // supports either a full accessor (foto_url) or a raw storage path (foto)
                    $fotoSrc = $pembina->foto_url
                        ?? (isset($pembina->foto) && $pembina->foto ? asset('storage/'.$pembina->foto) : null);
                @endphp
                <a href="{{ route('pembina.profile.index') }}"
                   class="relative block w-[44px] h-[44px] rounded-2xl overflow-hidden shadow-[0_6px_18px_-10px_rgba(46,43,85,0.25)] hover:-translate-y-0.5 transition-transform">
                    <div class="absolute inset-0 bg-gradient-to-br from-lavender to-sky-400 flex items-center justify-center text-white font-bold text-sm">
                        {{ $pembina->inisial ?? 'P' }}
                    </div>
                    @if($fotoSrc)
                        <img src="{{ $fotoSrc }}"
                             class="absolute inset-0 w-full h-full object-cover"
                             alt="Foto {{ $pembina->nama_pembina ?? 'Pembina' }}"
                             loading="lazy"
                             onerror="this.remove()">
                    @endif
                </a>
            </div>
        </div>

        {{-- HERO CARD — same white-card language as the widgets below --}}
        <div class="dash-in relative overflow-hidden bg-white rounded-3xl p-7 md:p-9 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:80ms">
            <div class="pointer-events-none absolute -right-16 -top-20 w-72 h-72 rounded-full bg-lavender/10 blur-3xl"></div>
            <div class="pointer-events-none absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-sky-100 blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="max-w-sm">
                    <p class="text-inksoft text-xs font-semibold uppercase tracking-[0.14em]">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <h2 class="font-display text-2xl md:text-3xl font-bold mt-2 leading-snug text-ink">
                        Program Pembinaan Elevenxkul
                    </h2>
                    <p class="text-inksoft text-sm mt-2 leading-relaxed">
                        Pantau kehadiran, validasi laporan pelatih, dan beri nilai peserta — semua dari satu layar.
                    </p>
                </div>

                <div class="flex flex-wrap items-stretch gap-3">
                    <div class="dash-pop flex flex-col justify-center px-5 py-4 min-w-[112px] rounded-2xl bg-lavender/10" style="--d:180ms">
                        <span class="text-lavender text-[11px] font-bold">Pelatih</span>
                        <span class="font-display text-2xl font-bold mt-1 text-ink">{{ $pelatihCount }}</span>
                    </div>
                    <div class="dash-pop flex flex-col justify-center px-5 py-4 min-w-[112px] rounded-2xl {{ $pendingValidasi > 0 ? 'bg-red-50' : 'bg-mint/25' }}" style="--d:250ms">
                        <span class="{{ $pendingValidasi > 0 ? 'text-red-500' : 'text-[#1F7A3D]' }} text-[11px] font-bold flex items-center gap-1.5">
                            Validasi
                            @if($pendingValidasi > 0)
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            @endif
                        </span>
                        <span class="font-display text-2xl font-bold mt-1 text-ink">{{ $pendingValidasi }}</span>
                    </div>
                    <div class="dash-pop flex flex-col justify-center px-5 py-4 min-w-[112px] rounded-2xl bg-sky-50" style="--d:320ms">
                        <span class="text-sky-500 text-[11px] font-bold">Rata² Aktivitas</span>
                        <span class="font-display text-2xl font-bold mt-1 text-ink">{{ $trend->count() ? round($trend->avg('persentase')) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BALANCED 3-COLUMN ROW — equal width, equal height --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">

            {{-- COL A: Tren Aktivitas --}}
            <div class="dash-in bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex flex-col h-full" style="--d:140ms">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft">Tren Aktivitas</span>
                </div>
                <h3 class="font-display font-bold text-lg mb-4">Ekstrakulikuler</h3>

                <div class="flex-1 flex items-end gap-2.5 min-h-[140px]">
                    @php $barColors = ['bg-lavender', 'bg-sky-400', 'bg-mint', 'bg-periwinkle']; @endphp
                    @forelse($trend as $i => $t)
                        <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                            <span class="text-[10px] font-semibold text-inksoft">{{ $t['persentase'] }}%</span>
                            <div class="dash-bar w-full rounded-t-lg {{ $barColors[$i % count($barColors)] }}"
                                 style="--h: {{ max($t['persentase'], 4) }}%; --d: {{ 300 + $i * 90 }}ms;"
                                 data-final-height="{{ max($t['persentase'], 4) }}"
                                 title="{{ $t['bulan'] }}: {{ $t['persentase'] }}%"></div>
                            <span class="text-[11px] text-inksoft">{{ $t['bulan'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-inksoft self-center mx-auto">Belum ada data absensi.</p>
                    @endforelse
                </div>
            </div>

            {{-- COL B: Aksi Cepat --}}
            <div class="dash-in flex flex-col gap-3 h-full" style="--d:220ms">
                <span class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft px-1">Aksi Cepat</span>

                <a href="{{ route('pembina.absensi.index') }}"
                   class="group bg-white rounded-3xl p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex items-center gap-4 flex-1 hover:-translate-y-1 hover:shadow-[0_14px_34px_-16px_rgba(46,43,85,0.4)] transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-lavender flex items-center justify-center text-white shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6M9 8h1"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-sm font-semibold">Lihat Absensi Peserta</h3>
                        <p class="text-xs text-inksoft mt-0.5">Pantau kehadiran harian</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-inksoft/30 group-hover:text-inksoft/60 shrink-0 transition-colors"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <a href="{{ route('pembina.validasi.index') }}"
                   class="group bg-white rounded-3xl p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex items-center gap-4 flex-1 hover:-translate-y-1 hover:shadow-[0_14px_34px_-16px_rgba(46,43,85,0.4)] transition-all {{ $pendingValidasi > 0 ? 'ring-2 ring-red-100' : '' }}">
                    <div class="w-12 h-12 rounded-2xl {{ $pendingValidasi > 0 ? 'bg-red-50 text-red-500' : 'bg-sky-50 text-sky-500' }} flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-sm font-semibold">Validasi Laporan</h3>
                        <p class="text-xs text-inksoft mt-0.5">
                            {{ $pendingValidasi > 0 ? $pendingValidasi.' laporan menunggu' : 'Semua sudah tervalidasi' }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-inksoft/30 group-hover:text-inksoft/60 shrink-0 transition-colors"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <a href="{{ route('pembina.nilai.index') }}"
                   class="group bg-white rounded-3xl p-5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex items-center gap-4 flex-1 hover:-translate-y-1 hover:shadow-[0_14px_34px_-16px_rgba(46,43,85,0.4)] transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-mint flex items-center justify-center text-[#1F7A3D] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-sm font-semibold">Beri Nilai Peserta</h3>
                        <p class="text-xs text-inksoft mt-0.5">Input penilaian performa</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-inksoft/30 group-hover:text-inksoft/60 shrink-0 transition-colors"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>

            {{-- COL C: Riwayat Penilaian --}}
            <div class="dash-in bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex flex-col h-full" style="--d:300ms">
                <span class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft">Terbaru</span>
                <h3 class="font-display font-bold text-lg mb-4">Riwayat Penilaian</h3>

                <div class="flex-1 relative flex flex-col gap-5 overflow-y-auto max-h-[280px] pr-1">
                    @forelse($riwayatAktivitas as $item)
                        <div class="dash-in relative flex items-start gap-3" style="--d: {{ 380 + $loop->index * 70 }}ms">
                            @if(!$loop->last)
                                <div class="absolute left-[15px] top-8 bottom-[-20px] w-px bg-ink/10"></div>
                            @endif
                            <div class="w-8 h-8 rounded-full {{ ['bg-lavender','bg-sky-400','bg-mint','bg-periwinkle'][$loop->index % 4] }} shrink-0 mt-0.5 z-10"></div>
                            <div class="pt-0.5 min-w-0">
                                <div class="font-semibold text-sm truncate">{{ $item['nama'] }}</div>
                                <p class="text-xs text-inksoft mt-0.5">{{ $item['pesan'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-inksoft m-auto">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        requestAnimationFrame(function () {
            document.querySelectorAll('.dash-bar[data-final-height]').forEach(function (bar) {
                bar.style.height = bar.getAttribute('data-final-height') + '%';
            });
        });
    });
</script>

</body>
</html>