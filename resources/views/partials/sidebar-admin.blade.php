{{--
    Sidebar rail role ADMIN — 3 kolom: Admin | Pembina | Ketua.
    Dipakai oleh layouts/admin.blade.php, dan otomatis juga oleh halaman role Pembina & Ketua
    ketika yang membukanya Admin (partials/sidebar-ketua & pembina/partials/sidebar
    mendelegasikan ke file ini).

    - Tertutup (default) : rail ramping berisi avatar, 3 tombol grup (Admin / Pembina / Ketua)
                           dan Keluar. Tidak ada scroll.
    - Hover / fokus (>= lg): rail melebar jadi panel 3 kolom berisi SEMUA menu, tanpa scroll.
      Halaman di belakangnya di-blur lewat .ekk-sidebar-backdrop.
    - Mobile (< lg)      : bar di bawah layar. Ketuk avatar / tombol grup untuk membuka panel,
                           lalu pilih tab Admin / Pembina / Ketua.
    - Admin TIDAK dibatasi ekskul di halaman Pembina. Di halaman Ketua, ekskul yang dikelola
      dipilih lewat dropdown "Ekskul aktif" di kolom Ketua.

    Parameter opsional saat di-include:
      insetY / insetX : jarak rail dari tepi layar (harus sama dengan padding wrapper halaman).
                        Default 2rem / 2.5rem (halaman admin & ketua). Halaman pembina: 1.25rem.
    Tambah menu baru: tambahkan satu baris di array grup yang sesuai pada $groups.
--}}
@php
    $insetY = $insetY ?? '2rem';
    $insetX = $insetX ?? '2.5rem';

    $dash = 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z';
    $users = 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4';
    $okCircle = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
    $team = 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z';

    // 'active' = pola nama route; 'icon' = path SVG (beberapa path dipisah "|")
    $groups = [
        'admin' => [
            'title' => 'Admin', 'dash' => 'admin.dashboard',
            'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'items' => [
                ['active' => 'admin.dashboard', 'route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => $dash],
                ['active' => 'admin.siswa.*', 'route' => 'admin.siswa.index', 'label' => 'Kelola Siswa', 'icon' => $users],
                ['active' => 'admin.kelas.*', 'route' => 'admin.kelas.index', 'label' => 'Kelola Kelas',
                 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['active' => 'admin.user.*', 'route' => 'admin.user.index', 'label' => 'Kelola Users',
                 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z|M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['active' => 'admin.ekskul.*', 'route' => 'admin.ekskul.index', 'label' => 'Kelola Ekskul',
                 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['active' => 'admin.monitoring-ekskul.*', 'route' => 'admin.monitoring-ekskul.index', 'label' => 'Lihat Semua Ekskul',
                 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['active' => 'admin.galeri.*', 'route' => 'admin.galeri.index', 'label' => 'Kelola Galeri',
                 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['active' => 'admin.tahun-ajaran.*', 'route' => 'admin.tahun-ajaran.index', 'label' => 'Tahun Ajaran',
                 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                // Landing page publik dibuka di TAB BARU supaya admin tetap di dashboard.
                ['active' => null, 'route' => 'landing', 'label' => 'Lihat Beranda', 'target' => '_blank',
                 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                ['active' => 'profile.edit', 'route' => 'profile.edit', 'label' => 'Profil',
                 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ],
        ],
        'pembina' => [
            'title' => 'Pembina', 'dash' => 'pembina.dashboard',
            'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'items' => [
                ['active' => 'pembina.dashboard', 'route' => 'pembina.dashboard', 'label' => 'Dashboard', 'icon' => $dash],
                ['active' => 'pembina.absensi.*', 'route' => 'pembina.absensi.index', 'label' => 'Absensi Peserta',
                 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['active' => 'pembina.validasi.*', 'route' => 'pembina.validasi.index', 'label' => 'Absensi Pelatih', 'icon' => $okCircle],
                ['active' => 'pembina.pelatih.*', 'route' => 'pembina.pelatih.index', 'label' => 'Kelola Pelatih', 'icon' => $team],
                ['active' => 'pembina.ketua.*', 'route' => 'pembina.ketua.index', 'label' => 'Kelola Ketua',
                 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['active' => 'pembina.berita.*', 'route' => 'pembina.berita.index', 'label' => 'Berita Ketua',
                 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                ['active' => 'pembina.nilai.*', 'route' => 'pembina.nilai.index', 'label' => 'Nilai Peserta',
                 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
            ],
        ],
        'ketua' => [
            'title' => 'Ketua', 'dash' => 'dashboard.ketua',
            'icon'  => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9',
            'items' => [
                ['active' => 'dashboard.ketua', 'route' => 'dashboard.ketua', 'label' => 'Dashboard', 'icon' => $dash],
                ['active' => 'ketua.absensi-pelatih*', 'route' => 'ketua.absensi-pelatih', 'label' => 'Absensi Pelatih', 'icon' => $okCircle],
                ['active' => 'ketua.absensi-peserta*', 'route' => 'ketua.absensi-peserta', 'label' => 'Absensi Peserta', 'icon' => $users],
                ['active' => 'ketua.riwayat-absensi*', 'route' => 'ketua.riwayat-absensi', 'label' => 'Riwayat Absensi',
                 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['active' => 'ketua.laporan-kegiatan*', 'route' => 'ketua.laporan-kegiatan', 'label' => 'Laporan Kegiatan',
                 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['active' => 'ketua.kelola-anggota*', 'route' => 'ketua.kelola-anggota', 'label' => 'Kelola Anggota', 'icon' => $team],
            ],
        ],
    ];

    // Grup yang sedang dibuka (untuk tombol grup yang menyala & tab default di mobile)
    $grupAktif = 'admin';
    foreach ($groups as $kunci => $g) {
        foreach ($g['items'] as $it) {
            if (! empty($it['active']) && request()->routeIs($it['active'])) { $grupAktif = $kunci; break 2; }
        }
    }

    $ekskulPilihan = \App\Models\Ekskul::orderBy('nama_ekskul')->get(['id_ekskul', 'nama_ekskul']);
    $ekskulAktifId = \App\Support\EkskulAktif::id(auth()->user());
    $namaAdmin = auth()->user()->name ?? 'Admin';
@endphp

<style>
    .ekk-sidebar-rail {
        --ekk-y: {{ $insetY }};
        --ekk-x: {{ $insetX }};
        background-image: linear-gradient(180deg, rgba(255,255,255,0) 35%, rgba(125,180,255,.28) 100%);
        user-select: none;
        -webkit-user-select: none;
    }
    .ekk-sidebar-rail::-webkit-scrollbar { display: none; }
    .ekk-sidebar-rail a, .ekk-sidebar-rail img { -webkit-user-drag: none; }

    /* ---------- bagian umum ---------- */
    .ekk-ico { width: 2.75rem; height: 2.75rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .ekk-btn { display: flex; align-items: center; border-radius: 1rem; color: rgba(255,255,255,.9); transition: background-color .2s ease, box-shadow .2s ease; }
    .ekk-btn:hover { background: rgba(255,255,255,.25); box-shadow: 0 4px 10px rgba(0,0,0,.08); }
    .ekk-btn.is-active { background: #fff; color: var(--color-periwinkle, #0B409C); box-shadow: 0 8px 16px rgba(0,0,0,.12); }
    .ekk-btn svg { transition: transform .2s ease; }
    .ekk-btn:hover svg { transform: scale(1.12); }
    .ekk-avatar { width: 2.75rem; height: 2.75rem; border-radius: 1rem; background: #fff; color: var(--color-periwinkle, #0B409C); font-weight: 700; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,.15); }

    /* ---------- tampilan TERTUTUP ---------- */
    .ekk-compact { display: flex; flex-direction: column; align-items: center; gap: .5rem; height: 100%; }
    .ekk-compact .ekk-spacer { flex: 1 1 auto; }

    /* ---------- tampilan TERBUKA (3 kolom) ---------- */
    .ekk-full { display: none; flex-direction: column; gap: .75rem; width: 100%; min-width: 0; }
    .ekk-head { display: flex; align-items: center; gap: .75rem; }
    .ekk-head-name { flex: 1 1 auto; min-width: 0; color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.2; }
    .ekk-head-name small { display: block; font-weight: 500; font-size: .72rem; color: rgba(255,255,255,.7); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ekk-tabs { display: none; }
    .ekk-cols { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .5rem; }
    .ekk-col { min-width: 0; display: flex; flex-direction: column; gap: .125rem; background: rgba(255,255,255,.07); border-radius: 1.25rem; padding: .5rem; }
    .ekk-col-title { color: rgba(255,255,255,.75); font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: .25rem .5rem .375rem; }
    .ekk-col .ekk-btn { height: 2.375rem; padding-right: .5rem; }
    .ekk-col .ekk-ico { width: 2.375rem; height: 2.375rem; }
    .ekk-col .ekk-label { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .85rem; font-weight: 600; }
    .ekk-pilih { padding: .125rem .25rem .375rem; }
    .ekk-pilih label { display: block; color: rgba(255,255,255,.75); font-size: .65rem; font-weight: 600; margin-bottom: .25rem; padding-left: .25rem; }
    .ekk-pilih select { width: 100%; border: 0; border-radius: .75rem; padding: .4rem .5rem; font-size: .8rem; font-weight: 600; background: rgba(255,255,255,.95); color: #10316B; }

    @media (min-width: 1024px) {
        .ekk-sidebar-rail {
            position: fixed;
            top: var(--ekk-y);
            bottom: var(--ekk-y);
            left: var(--ekk-x);
            width: 5rem;
            z-index: 30;
            overflow: hidden;
            transition: width .32s cubic-bezier(.4, 0, .2, 1), box-shadow .32s ease;
        }
        .ekk-sidebar-rail:hover,
        .ekk-sidebar-rail:focus-within {
            width: min(44rem, calc(100vw - (var(--ekk-x) * 2)));
            box-shadow: 0 30px 60px -15px rgba(31, 32, 80, .55);
        }
        .ekk-full { min-width: 42.5rem; }

        .ekk-sidebar-rail:hover .ekk-compact,
        .ekk-sidebar-rail:focus-within .ekk-compact { display: none; }
        .ekk-sidebar-rail:hover .ekk-full,
        .ekk-sidebar-rail:focus-within .ekk-full { display: flex; animation: ekk-fade .25s ease .1s both; }
        @keyframes ekk-fade { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: none; } }

        .ekk-sidebar-backdrop {
            position: fixed; inset: 0; z-index: 29;
            background: rgba(31, 32, 80, .18);
            -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity .3s ease, visibility .3s;
        }
        .ekk-sidebar-rail:hover ~ .ekk-sidebar-backdrop,
        .ekk-sidebar-rail:focus-within ~ .ekk-sidebar-backdrop { opacity: 1; visibility: visible; }
    }

    /* ---------- MOBILE / TABLET: bar bawah + panel ---------- */
    @media (max-width: 1023.98px) {
        .ekk-sidebar-rail {
            position: fixed;
            left: .75rem; right: .75rem;
            bottom: calc(.75rem + env(safe-area-inset-bottom, 0px));
            z-index: 30;
            padding: .5rem;
        }
        .ekk-aside { margin-bottom: -1.5rem; }
        /* ruang kosong di bawah halaman supaya konten terakhir tidak tertutup bar */
        div:has(> .ekk-aside) { padding-bottom: calc(6.75rem + env(safe-area-inset-bottom, 0px)); }
        .ekk-admin-wrap { padding-bottom: calc(7rem + env(safe-area-inset-bottom, 0px)); }

        .ekk-compact { flex-direction: row; justify-content: space-between; height: auto; }
        .ekk-compact .ekk-spacer { display: none; }

        .ekk-full {
            display: none;
            position: fixed;
            left: .75rem; right: .75rem;
            bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px));
            max-height: calc(100vh - 7rem);
            overflow-y: auto;
            padding: .75rem;
            border-radius: 1.5rem;
            background: var(--color-periwinkle, #0B409C);
            box-shadow: 0 20px 40px -10px rgba(31, 32, 80, .55);
        }
        .ekk-sidebar-rail.is-open .ekk-full { display: flex; }
        .ekk-tabs { display: flex; gap: .375rem; }
        .ekk-tabs button { flex: 1; padding: .45rem .5rem; border-radius: .875rem; font-size: .8rem; font-weight: 700; color: rgba(255,255,255,.85); background: rgba(255,255,255,.12); }
        .ekk-tabs button.is-on { background: #fff; color: var(--color-periwinkle, #0B409C); }
        .ekk-cols { display: block; }
        .ekk-col { display: none; }
        .ekk-col.is-tab { display: flex; }
        .ekk-col-title { display: none; }
        .ekk-col .ekk-btn { height: 2.75rem; }
        .ekk-col .ekk-ico { height: 2.75rem; }
        .ekk-col .ekk-label { font-size: .9rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .ekk-sidebar-rail, .ekk-sidebar-rail *, .ekk-sidebar-backdrop { transition: none !important; animation: none !important; }
    }
</style>

<aside class="ekk-aside lg:w-20 shrink-0">
    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3 shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20" id="ekk-rail">

        {{-- ================= TERTUTUP: avatar + 3 grup + keluar ================= --}}
        <nav class="ekk-compact" aria-label="Menu admin">
            <button type="button" class="ekk-btn lg:mb-3 shrink-0" id="ekk-menu-toggle" aria-label="Buka menu" title="Menu">
                <span class="ekk-ico"><span class="ekk-avatar">{{ strtoupper(substr($namaAdmin, 0, 1)) }}</span></span>
            </button>

            @foreach($groups as $kunci => $g)
                <a href="{{ route($g['dash']) }}" draggable="false" data-open-group="{{ $kunci }}"
                   aria-label="{{ $g['title'] }}" title="{{ $g['title'] }}"
                   class="ekk-btn shrink-0 {{ $grupAktif === $kunci ? 'is-active' : '' }}">
                    <span class="ekk-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $g['icon'] }}" />
                        </svg>
                    </span>
                </a>
            @endforeach

            <span class="ekk-spacer"></span>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" aria-label="Keluar" title="Keluar" class="ekk-btn">
                    <span class="ekk-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </span>
                </button>
            </form>
        </nav>

        {{-- ================= TERBUKA: 3 kolom (Admin | Pembina | Ketua) ================= --}}
        <div class="ekk-full" id="ekk-full">
            <div class="ekk-head">
                <a href="{{ route('admin.dashboard') }}" draggable="false" aria-label="Admin"><span class="ekk-avatar">{{ strtoupper(substr($namaAdmin, 0, 1)) }}</span></a>
                <div class="ekk-head-name">Akun Admin<small>{{ $namaAdmin }}</small></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" aria-label="Keluar" title="Keluar" class="ekk-btn">
                        <span class="ekk-ico">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        <span class="ekk-label" style="padding-right:.75rem;font-size:.85rem;font-weight:600;">Keluar</span>
                    </button>
                </form>
            </div>

            {{-- Tab (hanya tampil di mobile) --}}
            <div class="ekk-tabs" role="tablist">
                @foreach($groups as $kunci => $g)
                    <button type="button" role="tab" data-tab="{{ $kunci }}" class="{{ $grupAktif === $kunci ? 'is-on' : '' }}">{{ $g['title'] }}</button>
                @endforeach
            </div>

            <div class="ekk-cols">
                @foreach($groups as $kunci => $g)
                    <div class="ekk-col {{ $grupAktif === $kunci ? 'is-tab' : '' }}" data-col="{{ $kunci }}">
                        <div class="ekk-col-title">{{ $g['title'] }}@if($kunci === 'pembina') · semua ekskul @endif</div>

                        @if($kunci === 'ketua')
                            {{-- Halaman Ketua bekerja per-ekskul: Admin memilih ekskul yang dikelola --}}
                            <form method="POST" action="{{ route('admin.ekskul-aktif') }}" class="ekk-pilih">
                                @csrf
                                <label for="ekk-ekskul-aktif">Ekskul aktif</label>
                                <select name="id_ekskul" id="ekk-ekskul-aktif" onchange="this.form.submit()" @disabled($ekskulPilihan->isEmpty())>
                                    @forelse($ekskulPilihan as $e)
                                        <option value="{{ $e->id_ekskul }}" {{ (int) $ekskulAktifId === (int) $e->id_ekskul ? 'selected' : '' }}>{{ $e->nama_ekskul }}</option>
                                    @empty
                                        <option>Belum ada ekskul</option>
                                    @endforelse
                                </select>
                            </form>
                        @endif

                        @foreach($g['items'] as $item)
                            @php
                                $isActive = ! empty($item['active']) && request()->routeIs($item['active']);
                                $isExternal = ($item['target'] ?? null) === '_blank';
                            @endphp
                            <a href="{{ route($item['route']) }}" draggable="false"
                               @if($isExternal) target="_blank" rel="noopener" title="{{ $item['label'] }} (tab baru)" @endif
                               @if($isActive) aria-current="page" @endif
                               class="ekk-btn {{ $isActive ? 'is-active' : '' }}">
                                <span class="ekk-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @foreach(explode('|', $item['icon']) as $iconPath)
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                                        @endforeach
                                    </svg>
                                </span>
                                <span class="ekk-label">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Backdrop blur: harus SAUDARA (sibling) langsung setelah rail agar bisa dipicu lewat :hover --}}
    <div class="ekk-sidebar-backdrop" aria-hidden="true"></div>
</aside>

<script>
    (function () {
        var rail = document.getElementById('ekk-rail');
        if (!rail) return;
        var mobile = function () { return window.matchMedia('(max-width: 1023.98px)').matches; };

        function pilihTab(grup) {
            rail.querySelectorAll('[data-col]').forEach(function (c) { c.classList.toggle('is-tab', c.dataset.col === grup); });
            rail.querySelectorAll('[data-tab]').forEach(function (t) { t.classList.toggle('is-on', t.dataset.tab === grup); });
        }

        // Mobile: ketuk tombol grup / avatar membuka panel (di desktop tombol grup tetap link biasa)
        rail.querySelectorAll('[data-open-group]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                if (!mobile()) return;
                e.preventDefault();
                var grup = a.dataset.openGroup;
                var sudahTerbuka = rail.classList.contains('is-open') && rail.querySelector('[data-col].is-tab').dataset.col === grup;
                pilihTab(grup);
                rail.classList.toggle('is-open', !sudahTerbuka);
            });
        });
        var toggle = document.getElementById('ekk-menu-toggle');
        if (toggle) toggle.addEventListener('click', function () { if (mobile()) rail.classList.toggle('is-open'); });
        rail.querySelectorAll('[data-tab]').forEach(function (t) { t.addEventListener('click', function () { pilihTab(t.dataset.tab); }); });
        document.addEventListener('click', function (e) { if (!rail.contains(e.target)) rail.classList.remove('is-open'); });
    })();
</script>
