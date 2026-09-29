{{--
    Modal notifikasi absensi ekstrakurikuler untuk PEMBINA.
    Dibuka lewat tombol lonceng (#ekk-notif-trigger) di notifikasi-button.blade.php.

    Isi popup:
      1. Ringkasan absensi peserta hari ini (hadir / izin / sakit / alpha)
      2. Laporan absensi pelatih yang menunggu validasi
      3. Peserta yang perlu perhatian (alpha berulang)
      4. Ekskul yang belum ada absensi 7 hari terakhir
      5. Absensi peserta terbaru per ekskul

    Ditutup lewat: tombol X, klik area blur, atau tombol Esc.
    CSS ditulis mentah (bukan Tailwind) untuk bagian struktural, sama seperti sidebar,
    supaya posisi/blur tetap jalan walau aset belum di-rebuild.
--}}
@php
    $n = $notifikasi ?? null;
    $statusStyle = [
        'hadir' => ['label' => 'Hadir', 'cls' => 'bg-mint text-[#1F7A3D]'],
        'izin'  => ['label' => 'Izin',  'cls' => 'bg-sky-50 text-sky-600'],
        'sakit' => ['label' => 'Sakit', 'cls' => 'bg-amber-50 text-amber-600'],
        'alpha' => ['label' => 'Alpha', 'cls' => 'bg-red-50 text-red-500'],
    ];
    $kosong = $n && $n['menunggu']->isEmpty() && $n['perlu_perhatian']->isEmpty() && $n['belum_absen']->isEmpty();
@endphp

<style>
    .ekk-notif-overlay {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        align-items: center;      /* tengah vertikal */
        justify-content: center;  /* tengah horizontal */
        padding: 1.25rem;
        background: rgba(31, 32, 80, .28);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
        opacity: 0;
        visibility: hidden;
        transition: opacity .25s ease, visibility .25s;
    }
    .ekk-notif-overlay.is-open { opacity: 1; visibility: visible; }

    .ekk-notif-panel {
        width: 100%;
        max-width: 44rem;
        max-height: calc(100vh - 2.5rem);
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 30px 70px -20px rgba(31, 32, 80, .55);
        transform: translateY(12px) scale(.96);
        transform-origin: center;
        transition: transform .3s cubic-bezier(.16, .84, .44, 1);
        overflow: hidden;
    }
    .ekk-notif-overlay.is-open .ekk-notif-panel { transform: translateY(0) scale(1); }

    /* Tetap bisa di-scroll (mouse/touch), tapi scrollbar disembunyikan */
    .ekk-notif-body {
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: none;        /* Firefox */
        -ms-overflow-style: none;     /* Edge lama */
    }
    .ekk-notif-body::-webkit-scrollbar {
        display: none;                /* Chrome, Edge, Safari */
        width: 0;
        height: 0;
    }

    /* ===== Ukuran diperbesar (ditulis mentah agar tidak perlu rebuild Tailwind) ===== */
    .ekk-notif-panel .text-\[10px\] { font-size: .8125rem; }
    .ekk-notif-panel .text-\[11px\] { font-size: .875rem; }
    .ekk-notif-panel .text-xs       { font-size: .9375rem; line-height: 1.45; }
    .ekk-notif-panel .text-sm       { font-size: 1.0625rem; line-height: 1.4; }
    .ekk-notif-panel .text-lg       { font-size: 1.625rem; line-height: 1.25; }
    .ekk-notif-panel .text-xl       { font-size: 1.875rem; }
    .ekk-notif-panel .px-5          { padding-left: 1.75rem; padding-right: 1.75rem; }
    .ekk-notif-panel .pt-5          { padding-top: 1.75rem; }
    .ekk-notif-panel .py-4          { padding-top: 1.375rem; padding-bottom: 1.375rem; }
    .ekk-notif-panel .gap-5         { gap: 1.75rem; }
    .ekk-notif-panel .py-3          { padding-top: .9rem; padding-bottom: .9rem; }
    .ekk-notif-panel .py-2\.5       { padding-top: .75rem; padding-bottom: .75rem; }
    .ekk-notif-panel .px-3\.5       { padding-left: 1.1rem; padding-right: 1.1rem; }
    .ekk-notif-panel .w-9.h-9       { width: 2.75rem; height: 2.75rem; }
    .ekk-notif-panel .w-9           { width: 2.75rem; }
    .ekk-notif-panel .h-9           { height: 2.75rem; }
    .ekk-notif-panel .h-1\.5        { height: .5rem; }
    .ekk-notif-panel svg            { min-width: 1rem; }
    @media (max-width: 640px) {
        .ekk-notif-panel .px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
        .ekk-notif-panel .text-lg { font-size: 1.375rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .ekk-notif-overlay, .ekk-notif-panel { transition: none; }
    }
</style>

<div id="ekk-notif-modal"
     class="ekk-notif-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="ekk-notif-title"
     aria-hidden="true">

    <div class="ekk-notif-panel" id="ekk-notif-panel">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3 px-5 pt-5 pb-4 border-b border-ink/5">
            <div>
                <span class="text-[11px] font-bold tracking-[0.12em] uppercase text-lavender">Notifikasi</span>
                <h2 id="ekk-notif-title" class="font-display text-lg font-bold leading-tight mt-0.5">Absensi Ekstrakurikuler</h2>
                <p class="text-xs text-inksoft mt-1">
                    @if($n && $n['total'] > 0)
                        {{ $n['total'] }} hal perlu perhatianmu
                    @else
                        Tidak ada yang perlu ditindaklanjuti
                    @endif
                </p>
            </div>
            <button type="button" data-notif-close aria-label="Tutup notifikasi"
                    class="shrink-0 w-9 h-9 rounded-xl bg-ink/5 hover:bg-ink/10 flex items-center justify-center text-inksoft transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-lavender/40">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        @if(! $n)
            <div class="ekk-notif-body px-5 py-10 text-center">
                <p class="text-sm font-semibold">Akun belum terhubung dengan data pembina</p>
                <p class="text-xs text-inksoft mt-1">Hubungi admin agar notifikasi absensi ekskul kamu bisa ditampilkan.</p>
            </div>
        @else
            <div class="ekk-notif-body px-5 py-4 flex flex-col gap-5">

                {{-- 1. RINGKASAN HARI INI --}}
                <section>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft">Absensi peserta hari ini</h3>
                        <span class="text-[11px] text-inksoft">{{ now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($statusStyle as $key => $st)
                            <div class="rounded-2xl px-2 py-3 text-center {{ $st['cls'] }}">
                                <div class="font-display text-xl font-bold leading-none">{{ $n['hari_ini'][$key] }}</div>
                                <div class="text-[10px] font-bold mt-1">{{ $st['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    @if(array_sum($n['hari_ini']) === 0)
                        <p class="text-[11px] text-inksoft mt-2">Belum ada absensi peserta yang masuk hari ini.</p>
                    @endif
                </section>

                {{-- 2. MENUNGGU VALIDASI --}}
                @if($n['menunggu']->isNotEmpty())
                    <section>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                Menunggu validasi
                            </h3>
                            <span class="text-[11px] font-bold text-red-500">{{ $n['menunggu_total'] }} laporan</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            @foreach($n['menunggu'] as $m)
                                @php $st = $statusStyle[$m['status']] ?? ['label' => ucfirst($m['status']), 'cls' => 'bg-ink/5 text-inksoft']; @endphp
                                <a href="{{ $m['url'] }}"
                                   class="group flex items-start gap-3 rounded-2xl bg-bgsoft hover:bg-lavender/10 px-3.5 py-3 transition-colors">
                                    <div class="w-9 h-9 rounded-xl bg-white text-lavender flex items-center justify-center shrink-0 font-bold text-xs shadow-sm">
                                        {{ strtoupper(mb_substr($m['pelatih'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-sm font-semibold truncate">{{ $m['pelatih'] }}</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $st['cls'] }}">{{ $st['label'] }}</span>
                                        </div>
                                        <p class="text-xs text-inksoft mt-0.5 truncate">
                                            {{ $m['ekskul'] }}@if($m['kegiatan']) · {{ $m['kegiatan'] }}@endif
                                        </p>
                                        <p class="text-[11px] text-inksoft/80 mt-0.5">
                                            {{ $m['tanggal']->translatedFormat('D, d M') }}
                                            · dikirim {{ $m['dikirim']?->diffForHumans() }}
                                            @if($m['ada_foto']) · <span class="text-lavender font-semibold">ada foto</span>@endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if($n['menunggu_total'] > $n['menunggu']->count())
                            <p class="text-[11px] text-inksoft mt-2">+ {{ $n['menunggu_total'] - $n['menunggu']->count() }} laporan lainnya</p>
                        @endif
                    </section>
                @endif

                {{-- 3. PESERTA PERLU PERHATIAN --}}
                @if($n['perlu_perhatian']->isNotEmpty())
                    <section>
                        <h3 class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft mb-2">
                            Peserta perlu perhatian
                            <span class="normal-case tracking-normal font-medium">· alpha ≥ {{ $n['batas_alpha'] }}× dalam 30 hari</span>
                        </h3>
                        <div class="flex flex-col gap-2">
                            @foreach($n['perlu_perhatian'] as $p)
                                <div class="flex items-center gap-3 rounded-2xl bg-red-50/60 px-3.5 py-2.5">
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold truncate">{{ $p['nama'] }}</div>
                                        <p class="text-xs text-inksoft truncate">{{ $p['ekskul'] }}@if($p['kelas']) · {{ $p['kelas'] }}@endif</p>
                                    </div>
                                    <span class="text-[11px] font-bold text-red-500 bg-white rounded-full px-2.5 py-1 shrink-0">{{ $p['alpha'] }}× alpha</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- 4. EKSKUL BELUM ABSEN --}}
                @if($n['belum_absen']->isNotEmpty())
                    <section>
                        <h3 class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft mb-2">
                            Belum ada absensi
                            <span class="normal-case tracking-normal font-medium">· 7 hari terakhir</span>
                        </h3>
                        <div class="flex flex-col gap-2">
                            @foreach($n['belum_absen'] as $b)
                                <div class="flex items-center gap-3 rounded-2xl bg-amber-50/70 px-3.5 py-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="text-amber-500 shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold truncate">{{ $b['ekskul'] }}</div>
                                        <p class="text-xs text-inksoft">{{ $b['anggota'] }} anggota · belum ada absensi masuk</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- 5. ABSENSI PESERTA TERBARU --}}
                <section>
                    <h3 class="text-[11px] font-bold tracking-[0.1em] uppercase text-inksoft mb-2">Absensi peserta terbaru</h3>
                    @forelse($n['absensi_terbaru'] as $a)
                        @php $persen = $a['total'] > 0 ? round($a['hadir'] / $a['total'] * 100) : 0; @endphp
                        <div class="py-2.5 {{ ! $loop->last ? 'border-b border-ink/5' : '' }}">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-semibold truncate">{{ $a['ekskul'] }}</span>
                                <span class="text-[11px] text-inksoft shrink-0">{{ $a['tanggal']->translatedFormat('D, d M') }}</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-ink/5 overflow-hidden mt-1.5">
                                <div class="h-full rounded-full bg-lavender" style="width: {{ $persen }}%"></div>
                            </div>
                            <p class="text-[11px] text-inksoft mt-1.5">
                                {{ $a['total'] }} peserta · {{ $persen }}% hadir
                                <span class="text-inksoft/70">— {{ $a['hadir'] }} hadir, {{ $a['izin'] }} izin, {{ $a['sakit'] }} sakit, {{ $a['alpha'] }} alpha</span>
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-inksoft">Belum ada absensi peserta dalam 7 hari terakhir.</p>
                    @endforelse
                </section>

                @if($kosong)
                    <div class="rounded-2xl bg-mint/25 text-[#1F7A3D] text-xs font-semibold px-4 py-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Semua laporan sudah tervalidasi dan tidak ada peserta yang perlu ditindaklanjuti.
                    </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="grid grid-cols-2 gap-2 px-5 py-4 border-t border-ink/5 bg-white">
                <a href="{{ route('pembina.absensi.index') }}"
                   class="text-center text-xs font-semibold rounded-xl bg-lavender/10 text-lavender hover:bg-lavender/20 py-2.5 transition-colors">
                    Semua absensi peserta
                </a>
                <a href="{{ route('pembina.validasi.index') }}"
                   class="text-center text-xs font-semibold rounded-xl bg-lavender text-white hover:opacity-90 py-2.5 transition-opacity">
                    Validasi laporan{{ $n['menunggu_total'] > 0 ? ' ('.$n['menunggu_total'].')' : '' }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    (function () {
        var trigger = document.getElementById('ekk-notif-trigger');
        var modal   = document.getElementById('ekk-notif-modal');
        if (!trigger || !modal) return;

        var panel = document.getElementById('ekk-notif-panel');
        var closeBtn = modal.querySelector('[data-notif-close]');

        function open() {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            trigger.setAttribute('aria-expanded', 'true');
            document.documentElement.style.overflow = 'hidden';
            setTimeout(function () { closeBtn && closeBtn.focus(); }, 50);
        }

        function close() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            trigger.setAttribute('aria-expanded', 'false');
            document.documentElement.style.overflow = '';
            trigger.focus();
        }

        trigger.addEventListener('click', function () {
            modal.classList.contains('is-open') ? close() : open();
        });

        // Klik area blur (di luar panel) atau tombol X => tutup
        modal.addEventListener('click', function (e) {
            if (!panel.contains(e.target) || e.target.closest('[data-notif-close]')) close();
        });

        document.addEventListener('keydown', function (e) {
            if (!modal.classList.contains('is-open')) return;
            if (e.key === 'Escape') { close(); return; }

            // Fokus dikunci di dalam modal selama terbuka
            if (e.key === 'Tab') {
                var f = panel.querySelectorAll('a[href], button:not([disabled])');
                if (!f.length) return;
                var first = f[0], last = f[f.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        });
    })();
</script>