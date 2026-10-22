@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    @php
        $now   = \Carbon\Carbon::now()->locale('id');
        $jam   = (int) $now->format('G');
        $sapa  = $jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam'));
        $tanggal = $now->translatedFormat('l, j F Y');

        $totalEkskul  = $totalEkskul  ?? 0;
        $totalSiswa   = $totalSiswa   ?? 0;
        $totalKelas   = $totalKelas   ?? 0;
        $totalPembina = $totalPembina ?? 0;
        $totalUser    = $totalUser    ?? 0;
        $ekskulTanpaPembina = $ekskulTanpaPembina ?? 0;
        $ekskulTanpaKetua   = $ekskulTanpaKetua   ?? 0;
        $recent = collect($recentEkskuls ?? []);

        // Kartu ringkasan sekaligus jadi pintu masuk ke menu kelola masing-masing.
        $stats = [
            ['label' => 'Total ekskul',  'value' => $totalEkskul,  'icon' => 'fa-layer-group',   'route' => 'admin.ekskul.index',  'hint' => 'Kelola ekskul'],
            ['label' => 'Total siswa',   'value' => $totalSiswa,   'icon' => 'fa-user-graduate', 'route' => 'admin.siswa.index',   'hint' => 'Kelola siswa'],
            ['label' => 'Total kelas',   'value' => $totalKelas,   'icon' => 'fa-school',        'route' => 'admin.kelas.index',   'hint' => 'Kelola kelas'],
            ['label' => 'Total pembina', 'value' => $totalPembina, 'icon' => 'fa-user-tie',      'route' => 'admin.pembina.index', 'hint' => 'Kelola pembina'],
            ['label' => 'Total user',    'value' => $totalUser,    'icon' => 'fa-user-gear',     'route' => 'admin.user.index',    'hint' => 'Kelola user'],
        ];

        // Panel "Perlu perhatian": hanya tampil item yang memang butuh tindakan.
        $todo = [];
        if ($totalSiswa === 0) {
            $todo[] = ['icon' => 'fa-user-graduate', 'text' => 'Belum ada data siswa', 'sub' => 'Impor atau tambahkan siswa dulu', 'cta' => 'Impor', 'url' => route('admin.siswa.import.form')];
        }
        if ($ekskulTanpaPembina > 0) {
            $todo[] = ['icon' => 'fa-user-tie', 'text' => $ekskulTanpaPembina . ' ekskul belum punya pembina', 'sub' => 'Tentukan pembina di tiap ekskul', 'cta' => 'Atur', 'url' => route('admin.ekskul.index')];
        }
        if ($ekskulTanpaKetua > 0) {
            $todo[] = ['icon' => 'fa-crown', 'text' => $ekskulTanpaKetua . ' ekskul belum punya ketua', 'sub' => 'Pilih ketua dari daftar siswa', 'cta' => 'Atur', 'url' => route('admin.ekskul.index')];
        }

        // Kategori: warna dipakai konsisten di donut, legenda, dan ikon daftar.
        $kategori = [
            'organisasi'      => ['label' => 'Organisasi',      'color' => '#0B409C'],
            'ekstrakulikuler' => ['label' => 'Ekstrakurikuler', 'color' => '#FFE867'],
            'komunitas'       => ['label' => 'Komunitas',       'color' => '#8DB1F2'],
        ];
        $kats = [];
        $totalKat = 0;
        foreach ($kategori as $key => $def) {
            $c = (int) (($kategoriCounts ?? [])[$key] ?? 0);
            $totalKat += $c;
            $kats[$key] = $def + ['count' => $c];
        }
        $acc = 0; $stops = [];
        foreach ($kats as $key => $k) {
            $pct = $totalKat ? ($k['count'] / $totalKat) * 100 : 0;
            $kats[$key]['pct'] = round($pct);
            if ($k['count'] > 0) {
                $stops[] = $k['color'] . ' ' . round($acc, 2) . '% ' . round($acc + $pct, 2) . '%';
            }
            $acc += $pct;
        }
        $conic = $totalKat ? 'conic-gradient(from -90deg, ' . implode(', ', $stops) . ')' : 'none';

        $topKat = collect($kats)->sortByDesc('count')->first();

        // Tumpukan kartu di hero: 3 ekskul terbaru (yang terbaru paling depan).
        $stack = $recent->take(3)->values();
    @endphp

    <style>
        /* CSS ditulis mentah (prefix .dsh-) supaya tampil benar tanpa perlu rebuild Tailwind.
           Warna: blue #0B409C, navy #10316B, gold #FFE867, cloud #F2F7FF. */
        .dsh{--blue:#0B409C;--navy:#10316B;--gold:#FFE867;--cloud:#F2F7FF;--soft:#5B6E96;--line:rgba(16,49,107,.09);
            font-family:'Plus Jakarta Sans','Instrument Sans',ui-sans-serif,system-ui,sans-serif;color:var(--navy)}
        .dsh *{box-sizing:border-box}
        .dsh a{text-decoration:none;color:inherit}
        .dsh-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:1.1rem}
        .dsh-grid>*{grid-column:1/-1;min-width:0}
        @media(min-width:1024px){
            .dsh-grid>.dsh-hero{grid-column:span 8}
            .dsh-grid>.dsh-attn{grid-column:span 4}
            .dsh-grid>.dsh-recent{grid-column:span 7}
            .dsh-grid>.dsh-kat{grid-column:span 5}
        }

        /* animasi masuk bertahap */
        @keyframes dshUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
        @keyframes dshGrow{from{transform:scaleX(0)}to{transform:scaleX(1)}}
        @keyframes dshPop{from{opacity:0;scale:.86}to{opacity:1;scale:1}}
        .dsh [data-in]{animation:dshUp .6s cubic-bezier(.16,1,.3,1) both;animation-delay:calc(var(--i,0)*70ms)}

        /* ---------- HERO ---------- */
        .dsh-hero{position:relative;overflow:hidden;isolation:isolate;border-radius:1.75rem;color:#fff;min-height:16.5rem;
            padding:1.9rem 1.9rem 2rem;display:flex;align-items:center;container-type:inline-size;
            background:radial-gradient(110% 130% at 100% 0%,#1f5fd8 0%,rgba(31,95,216,0) 58%),linear-gradient(135deg,#0B409C 0%,#10316B 100%);
            box-shadow:0 24px 48px -24px rgba(11,64,156,.6)}
        .dsh-hero::before{content:'';position:absolute;z-index:-1;right:-7rem;top:-7rem;width:23rem;height:23rem;border-radius:50%;
            border:1px solid rgba(255,255,255,.14);box-shadow:0 0 0 2.6rem rgba(255,255,255,.035),0 0 0 5.2rem rgba(255,255,255,.025)}
        .dsh-hero::after{content:'';position:absolute;z-index:-1;left:-3rem;bottom:-5rem;width:13rem;height:13rem;border-radius:50%;
            background:radial-gradient(circle,rgba(255,232,103,.3),rgba(255,232,103,0) 68%)}
        .dsh-hero-txt{max-width:25rem;position:relative}
        .dsh-chip-date{display:inline-flex;align-items:center;gap:.5rem;font-size:.72rem;font-weight:600;letter-spacing:.01em;
            padding:.38rem .8rem;border-radius:999px;background:rgba(255,255,255,.12);box-shadow:inset 0 0 0 1px rgba(255,255,255,.16)}
        .dsh-chip-date i{color:var(--gold);font-size:.7rem}
        .dsh-hero h2{margin:.95rem 0 0;font-size:clamp(1.45rem,2.4vw,2rem);line-height:1.18;font-weight:800;letter-spacing:-.02em}
        .dsh-hero h2 em{font-style:normal;color:var(--gold)}
        .dsh-hero p{margin:.7rem 0 0;font-size:.875rem;line-height:1.55;color:rgba(255,255,255,.78)}
        .dsh-hero p b{color:#fff;font-weight:700}
        .dsh-cta{display:flex;flex-wrap:wrap;gap:.6rem;margin-top:1.3rem}
        .dsh-btn{display:inline-flex;align-items:center;gap:.5rem;font-size:.82rem;font-weight:700;padding:.7rem 1.1rem;border-radius:999px;
            transition:transform .2s,box-shadow .2s,background .2s}
        .dsh-btn:hover{transform:translateY(-2px)}
        .dsh-btn-gold{background:var(--gold);color:var(--navy)!important;box-shadow:0 10px 22px -10px rgba(255,232,103,.9)}
        .dsh-btn-gold:hover{box-shadow:0 14px 26px -10px rgba(255,232,103,1)}
        .dsh-btn-ghost{color:#fff!important;background:rgba(255,255,255,.1);box-shadow:inset 0 0 0 1px rgba(255,255,255,.28)}
        .dsh-btn-ghost:hover{background:rgba(255,255,255,.18)}

        /* tumpukan kartu (mengulang motif carousel di landing page) */
        .dsh-stack{position:absolute;right:2.2rem;top:50%;width:19.2rem;height:13rem;transform:translateY(-50%);display:none}
        /* tumpukan hanya tampil kalau hero cukup lebar (mengikuti lebar hero, bukan lebar layar, karena ada sidebar) */
        @container (min-width:780px){.dsh-stack{display:block}}
        .dsh-sc{position:absolute;top:.4rem;width:8.8rem;height:11.6rem;border-radius:1.2rem;padding:.95rem;display:flex;flex-direction:column;justify-content:flex-end;
            box-shadow:0 22px 34px -14px rgba(0,0,0,.5);transition:transform .55s cubic-bezier(.2,.8,.2,1);animation:dshPop .7s cubic-bezier(.16,1,.3,1) both}
        .dsh-sc .l{position:absolute;left:.9rem;top:.55rem;font-size:3.7rem;font-weight:800;line-height:1;letter-spacing:-.04em;opacity:.95}
        .dsh-sc .n{font-size:.8rem;font-weight:700;line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .dsh-sc .k{margin-top:.3rem;font-size:.62rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;opacity:.7}
        .dsh-sc.is-back{left:0;z-index:1;transform:rotate(-9deg) translateY(10px);background:#1f58c2;color:#fff;box-shadow:inset 0 0 0 1px rgba(255,255,255,.2),0 22px 34px -14px rgba(0,0,0,.5);animation-delay:.25s}
        .dsh-sc.is-mid{left:4.5rem;z-index:2;transform:rotate(-1deg);background:var(--gold);color:var(--navy);animation-delay:.15s}
        .dsh-sc.is-front{left:9rem;z-index:3;transform:rotate(8deg) translateY(4px);background:#fff;color:var(--navy);animation-delay:.05s}
        .dsh-sc.is-back .n,.dsh-sc.is-back .k,.dsh-sc.is-mid .n,.dsh-sc.is-mid .k{display:none}  /* teks kartu belakang tertutup kartu depan: cukup huruf besarnya */
        .dsh-hero:hover .is-back{transform:rotate(-15deg) translate(-12px,12px)}
        .dsh-hero:hover .is-mid{transform:rotate(-2deg) translateY(-6px)}
        .dsh-hero:hover .is-front{transform:rotate(14deg) translate(12px,2px)}

        /* ---------- PANEL UMUM ---------- */
        .dsh-panel{background:#fff;border-radius:1.75rem;box-shadow:0 1px 2px rgba(16,49,107,.04),0 18px 36px -22px rgba(16,49,107,.28),inset 0 0 0 1px var(--line);display:flex;flex-direction:column;overflow:hidden}
        .dsh-ph{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:1.1rem 1.4rem .5rem}
        .dsh-ph h3{margin:0;font-size:1rem;font-weight:800;letter-spacing:-.01em}
        .dsh-badge{font-size:.7rem;font-weight:800;min-width:1.5rem;height:1.5rem;padding:0 .45rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:var(--gold);color:var(--navy)}
        .dsh-more{font-size:.75rem;font-weight:700;color:var(--blue)!important;padding:.35rem .8rem;border-radius:999px;background:rgba(11,64,156,.07);transition:background .2s,gap .2s;display:inline-flex;gap:.35rem;align-items:center}
        .dsh-more:hover{background:rgba(11,64,156,.14);gap:.55rem}

        /* ---------- PERLU PERHATIAN ---------- */
        .dsh-attn::before{content:'';display:block;height:.32rem;flex:none;background:linear-gradient(90deg,var(--gold),#ffd21f)}
        .dsh-todo{list-style:none;margin:0;padding:.4rem .7rem 1rem;display:flex;flex-direction:column;gap:.15rem}
        .dsh-todo a{display:flex;align-items:center;gap:.8rem;padding:.7rem .7rem;border-radius:1rem;transition:background .2s}
        .dsh-todo a:hover{background:var(--cloud)}
        .dsh-todo .ic{width:2.4rem;height:2.4rem;border-radius:.85rem;background:rgba(255,232,103,.45);color:var(--navy);display:flex;align-items:center;justify-content:center;flex:none;font-size:.9rem}
        .dsh-todo .tx{flex:1;min-width:0}
        .dsh-todo .tx b{display:block;font-size:.85rem;font-weight:700;line-height:1.25}
        .dsh-todo .tx small{display:block;margin-top:.12rem;font-size:.74rem;color:var(--soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .dsh-todo .go{font-size:.72rem;font-weight:800;color:var(--blue);padding:.38rem .75rem;border-radius:999px;background:rgba(11,64,156,.08);flex:none}
        .dsh-ok{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1.2rem 1.5rem 1.8rem;gap:.35rem}
        .dsh-ok .ic{width:3.4rem;height:3.4rem;border-radius:50%;background:rgba(11,64,156,.08);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:.4rem}
        .dsh-ok b{font-size:.95rem}
        .dsh-ok small{font-size:.78rem;color:var(--soft);max-width:15rem;line-height:1.5}

        /* ---------- KARTU RINGKASAN ---------- */
        .dsh-stats{display:grid;gap:1.1rem;grid-template-columns:repeat(2,minmax(0,1fr))}
        .dsh-stats .dsh-stat:first-child{grid-column:1/-1}
        @media(min-width:640px){
            .dsh-stats{grid-template-columns:repeat(6,minmax(0,1fr))}
            .dsh-stats .dsh-stat,.dsh-stats .dsh-stat:first-child{grid-column:span 2}
            .dsh-stats .dsh-stat:nth-child(n+4){grid-column:span 3}
        }
        @media(min-width:1180px){
            .dsh-stats{grid-template-columns:repeat(5,minmax(0,1fr))}
            .dsh-stats .dsh-stat,.dsh-stats .dsh-stat:first-child,.dsh-stats .dsh-stat:nth-child(n+4){grid-column:span 1}
        }
        .dsh-stat{position:relative;overflow:hidden;display:block;border-radius:1.5rem;padding:1.1rem 1.2rem 1.15rem;background:#fff;
            box-shadow:0 1px 2px rgba(16,49,107,.04),0 14px 30px -20px rgba(16,49,107,.3),inset 0 0 0 1px var(--line);
            transition:transform .3s cubic-bezier(.2,.8,.2,1),box-shadow .3s}
        .dsh-stat:hover,.dsh-stat:focus-visible{transform:translateY(-5px);box-shadow:0 22px 38px -20px rgba(11,64,156,.45),inset 0 0 0 1px rgba(11,64,156,.18);outline:none}
        .dsh-stat .bg{position:absolute;right:-.7rem;bottom:-1.1rem;font-size:5.6rem;color:var(--blue);opacity:.055;transform:rotate(-12deg);transition:transform .4s,opacity .3s;pointer-events:none}
        .dsh-stat:hover .bg{transform:rotate(-4deg) scale(1.08);opacity:.09}
        .dsh-stat .top{display:flex;align-items:center;justify-content:space-between}
        .dsh-stat .ic{width:2.5rem;height:2.5rem;border-radius:.9rem;background:rgba(11,64,156,.08);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:.95rem}
        .dsh-stat .ar{width:1.9rem;height:1.9rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.68rem;color:var(--soft);background:var(--cloud);
            transition:background .25s,color .25s,transform .25s}
        .dsh-stat:hover .ar{background:var(--gold);color:var(--navy);transform:rotate(-45deg)}
        .dsh-stat .num{display:block;margin-top:1rem;font-size:2.35rem;line-height:1;font-weight:800;letter-spacing:-.04em;font-variant-numeric:tabular-nums}
        .dsh-stat .lb{display:block;margin-top:.4rem;font-size:.8rem;font-weight:600;color:var(--soft)}
        .dsh-stat .hn{display:block;margin-top:.55rem;font-size:.7rem;font-weight:700;color:var(--blue);opacity:0;transform:translateY(4px);transition:opacity .25s,transform .25s}
        .dsh-stat:hover .hn,.dsh-stat:focus-visible .hn{opacity:1;transform:none}
        @media(hover:none){.dsh-stat .hn{opacity:1;transform:none}}
        /* kartu pertama dibuat menonjol */
        .dsh-stat.is-main{background:linear-gradient(145deg,#0B409C,#10316B);color:#fff;box-shadow:0 20px 36px -18px rgba(11,64,156,.7)}
        .dsh-stat.is-main .ic{background:var(--gold);color:var(--navy)}
        .dsh-stat.is-main .lb{color:rgba(255,255,255,.72)}
        .dsh-stat.is-main .hn{color:var(--gold)}
        .dsh-stat.is-main .bg{color:#fff;opacity:.08}
        .dsh-stat.is-main .ar{background:rgba(255,255,255,.14);color:#fff}

        /* ---------- EKSKUL TERBARU ---------- */
        .dsh-list{list-style:none;margin:0;padding:.35rem .7rem .4rem;display:flex;flex-direction:column;gap:.1rem}
        .dsh-row{display:flex;align-items:center;gap:.9rem;padding:.7rem .7rem;border-radius:1.1rem;transition:background .2s}
        .dsh-row:hover{background:var(--cloud)}
        .dsh-tile{width:2.9rem;height:2.9rem;border-radius:1rem;flex:none;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;transition:transform .3s cubic-bezier(.2,.8,.2,1)}
        .dsh-row:hover .dsh-tile{transform:rotate(-8deg) scale(1.06)}
        .dsh-tile.c-organisasi{background:#0B409C;color:#fff}
        .dsh-tile.c-ekstrakulikuler{background:#FFE867;color:#10316B}
        .dsh-tile.c-komunitas{background:#DCE8FF;color:#0B409C}
        .dsh-tile.c-none{background:var(--cloud);color:var(--soft)}
        .dsh-row .mn{flex:1;min-width:0}
        .dsh-row .mn b{display:block;font-size:.9rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .dsh-row .mn small{display:flex;align-items:center;gap:.4rem;margin-top:.15rem;font-size:.76rem;color:var(--soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .dsh-row .mn small.warn{color:#8a6a00;font-weight:600}
        .dsh-row .mn small.warn::before{content:'';width:.45rem;height:.45rem;border-radius:50%;background:#f5b800;flex:none}
        .dsh-meta{display:flex;align-items:center;gap:.5rem;flex:none}
        .dsh-pill{display:inline-flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;padding:.32rem .65rem;border-radius:999px;background:var(--cloud);color:var(--blue);white-space:nowrap}
        .dsh-pill i{font-size:.66rem}
        .dsh-pill.cat{background:transparent;box-shadow:inset 0 0 0 1px var(--line);color:var(--soft)}
        @media(max-width:560px){.dsh-pill.cat{display:none}}
        .dsh-row .arrow{font-size:.7rem;color:var(--soft);opacity:.5;transition:transform .25s,opacity .25s,color .25s}
        .dsh-row:hover .arrow{transform:translateX(4px);opacity:1;color:var(--blue)}
        .dsh-add{margin:.3rem .7rem 1rem;display:flex;align-items:center;justify-content:center;gap:.55rem;padding:.8rem;border-radius:1.1rem;font-size:.82rem;font-weight:700;color:var(--blue)!important;
            border:1.5px dashed rgba(11,64,156,.28);transition:background .2s,border-color .2s}
        .dsh-add:hover{background:rgba(11,64,156,.05);border-color:var(--blue)}
        .dsh-empty{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:2rem 1.5rem;gap:.35rem}
        .dsh-empty .ic{width:3.4rem;height:3.4rem;border-radius:1.1rem;background:var(--cloud);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:.4rem}
        .dsh-empty b{font-size:.95rem}
        .dsh-empty small{font-size:.78rem;color:var(--soft);max-width:16rem;line-height:1.5}

        /* ---------- SEBARAN KATEGORI ---------- */
        .dsh-donut-wrap{flex:1;display:flex;align-items:center;gap:1.6rem;padding:.9rem 1.4rem 1rem;flex-wrap:wrap;justify-content:center}
        .dsh-donut{position:relative;width:9.5rem;height:9.5rem;border-radius:50%;flex:none;animation:dshPop .8s cubic-bezier(.16,1,.3,1) both .2s}
        .dsh-donut::after{content:'';position:absolute;inset:1.55rem;border-radius:50%;background:#fff;box-shadow:inset 0 0 0 1px var(--line)}
        .dsh-donut .ct{position:absolute;inset:0;z-index:1;display:flex;flex-direction:column;align-items:center;justify-content:center}
        .dsh-donut .ct b{font-size:1.9rem;font-weight:800;line-height:1;letter-spacing:-.04em}
        .dsh-donut .ct small{margin-top:.2rem;font-size:.68rem;font-weight:700;color:var(--soft);letter-spacing:.06em;text-transform:uppercase}
        .dsh-legend{list-style:none;margin:0;padding:0;flex:1;min-width:11rem;display:flex;flex-direction:column;gap:.95rem}
        .dsh-legend .hd{display:flex;align-items:center;gap:.5rem;font-size:.82rem;font-weight:700}
        .dsh-legend .dot{width:.7rem;height:.7rem;border-radius:50%;flex:none;box-shadow:inset 0 0 0 1px rgba(16,49,107,.12)}
        .dsh-legend .hd span:last-child{margin-left:auto;font-variant-numeric:tabular-nums}
        .dsh-legend .hd small{font-weight:600;color:var(--soft);margin-left:.4rem}
        .dsh-bar{margin-top:.45rem;height:.4rem;border-radius:999px;background:var(--cloud);overflow:hidden}
        .dsh-bar i{display:block;height:100%;border-radius:inherit;transform-origin:left;animation:dshGrow 1s cubic-bezier(.16,1,.3,1) both .35s}

        .dsh-insight{margin:0 1rem 1rem;padding:.85rem 1rem;border-radius:1.2rem;background:rgba(255,232,103,.38);display:flex;align-items:center;gap:.85rem}
        .dsh-insight .ic{width:2.4rem;height:2.4rem;border-radius:.85rem;background:var(--gold);color:var(--navy);display:flex;align-items:center;justify-content:center;flex:none;font-size:.9rem}
        .dsh-insight small{display:block;font-size:.7rem;font-weight:600;color:var(--soft)}
        .dsh-insight b{display:block;font-size:.9rem;font-weight:800;margin-top:.05rem}
        .dsh-insight .v{margin-left:auto;font-size:.78rem;font-weight:800;color:var(--blue);white-space:nowrap}

        @media(prefers-reduced-motion:reduce){
            .dsh [data-in],.dsh-sc,.dsh-donut,.dsh-bar i{animation:none!important}
            .dsh *{transition:none!important}
        }
    </style>

    <div class="dsh">
        <div class="dsh-grid">

            {{-- ================= HERO ================= --}}
            <section class="dsh-hero" data-in style="--i:0" aria-label="Sambutan">
                <div class="dsh-hero-txt">
                    <span class="dsh-chip-date"><i class="fas fa-calendar-day"></i> Selamat {{ $sapa }} &middot; {{ $tanggal }}</span>
                    <h2>Pantau semua ekskul sekolah <em>dalam satu pandangan.</em></h2>
                    <p>
                        Terdata <b>{{ $totalEkskul }} ekskul</b>, <b>{{ $totalPembina }} pembina</b>
                        dan <b>{{ $totalSiswa }} siswa</b> di <b>{{ $totalKelas }} kelas</b>.
                    </p>
                    <div class="dsh-cta">
                        <a href="{{ route('admin.ekskul.create') }}" class="dsh-btn dsh-btn-gold"><i class="fas fa-plus"></i> Tambah ekskul</a>
                        <a href="{{ route('admin.monitoring-ekskul.index') }}" class="dsh-btn dsh-btn-ghost"><i class="fas fa-eye"></i> Lihat semua ekskul</a>
                    </div>
                </div>

                {{-- Tumpukan kartu dekoratif: ekskul terbaru, yang paling baru di depan --}}
                <div class="dsh-stack" aria-hidden="true">
                    @if ($stack->isEmpty())
                        <div class="dsh-sc is-front" style="left:6rem">
                            <span class="l"><i class="fas fa-layer-group" style="font-size:2.4rem;margin-top:.5rem;display:inline-block;color:#0B409C"></i></span>
                            <span class="n">Belum ada ekskul</span>
                            <span class="k">Mulai dari sini</span>
                        </div>
                    @else
                        @foreach ($stack->reverse()->values() as $i => $ek)
                            @php
                                $pos = ['is-back', 'is-mid', 'is-front'][3 - $stack->count() + $i] ?? 'is-front';
                                $lbl = $kategori[$ek->kategori]['label'] ?? ucfirst((string) $ek->kategori);
                            @endphp
                            <div class="dsh-sc {{ $pos }}">
                                <span class="l">{{ mb_strtoupper(mb_substr($ek->nama_ekskul, 0, 1)) }}</span>
                                <span class="n">{{ $ek->nama_ekskul }}</span>
                                <span class="k">{{ $lbl }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>

            {{-- ================= PERLU PERHATIAN ================= --}}
            <section class="dsh-panel dsh-attn" data-in style="--i:1" aria-label="Perlu perhatian">
                <div class="dsh-ph">
                    <h3>Perlu perhatian</h3>
                    @if (count($todo) > 0)<span class="dsh-badge">{{ count($todo) }}</span>@endif
                </div>

                @if (count($todo) > 0)
                    <ul class="dsh-todo">
                        @foreach ($todo as $t)
                            <li>
                                <a href="{{ $t['url'] }}">
                                    <span class="ic"><i class="fas {{ $t['icon'] }}"></i></span>
                                    <span class="tx"><b>{{ $t['text'] }}</b><small>{{ $t['sub'] }}</small></span>
                                    <span class="go">{{ $t['cta'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="dsh-ok">
                        <span class="ic"><i class="fas fa-circle-check"></i></span>
                        <b>Semua data sudah lengkap</b>
                        <small>Tidak ada yang perlu ditindaklanjuti saat ini.</small>
                    </div>
                @endif
            </section>

            {{-- ================= RINGKASAN (sekaligus pintu ke menu kelola) ================= --}}
            <section class="dsh-stats" aria-label="Ringkasan data">
                @foreach ($stats as $s)
                    <a href="{{ route($s['route']) }}" class="dsh-stat {{ $loop->first ? 'is-main' : '' }}" data-in style="--i:{{ $loop->index + 2 }}">
                        <i class="fas {{ $s['icon'] }} bg" aria-hidden="true"></i>
                        <span class="top">
                            <span class="ic"><i class="fas {{ $s['icon'] }}"></i></span>
                            <span class="ar"><i class="fas fa-arrow-right"></i></span>
                        </span>
                        <span class="num" data-count="{{ $s['value'] }}">{{ $s['value'] }}</span>
                        <span class="lb">{{ $s['label'] }}</span>
                        <span class="hn">{{ $s['hint'] }} &rarr;</span>
                    </a>
                @endforeach
            </section>

            {{-- ================= EKSKUL TERBARU ================= --}}
            <section class="dsh-panel dsh-recent" data-in style="--i:7" aria-label="Ekskul terbaru">
                <div class="dsh-ph">
                    <h3>Ekskul terbaru</h3>
                    <a href="{{ route('admin.ekskul.index') }}" class="dsh-more">Lihat semua <i class="fas fa-arrow-right" style="font-size:.62rem"></i></a>
                </div>

                @if ($recent->isNotEmpty())
                    <ul class="dsh-list">
                        @foreach ($recent as $ek)
                            @php
                                $cat  = $ek->kategori ?: 'none';
                                $lbl  = $kategori[$ek->kategori]['label'] ?? ucfirst((string) $ek->kategori);
                                $nama = $ek->pembina->nama_pembina ?? null;
                            @endphp
                            <li>
                                <a href="{{ route('admin.monitoring-ekskul.show', $ek->id_ekskul) }}" class="dsh-row">
                                    <span class="dsh-tile c-{{ $cat }}">{{ mb_strtoupper(mb_substr($ek->nama_ekskul, 0, 1)) }}</span>
                                    <span class="mn">
                                        <b>{{ $ek->nama_ekskul }}</b>
                                        @if ($nama)
                                            <small>{{ $nama }}</small>
                                        @else
                                            <small class="warn">Belum ada pembina</small>
                                        @endif
                                    </span>
                                    <span class="dsh-meta">
                                        <span class="dsh-pill" title="Jumlah anggota"><i class="fas fa-user-group"></i> {{ $ek->peserta_count ?? 0 }}</span>
                                        @if ($ek->kategori)<span class="dsh-pill cat">{{ $lbl }}</span>@endif
                                    </span>
                                    <i class="fas fa-chevron-right arrow"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admin.ekskul.create') }}" class="dsh-add"><i class="fas fa-plus"></i> Tambah ekskul baru</a>
                @else
                    <div class="dsh-empty">
                        <span class="ic"><i class="fas fa-layer-group"></i></span>
                        <b>Belum ada ekskul</b>
                        <small>Tambahkan ekskul pertama supaya muncul di sini dan di halaman depan.</small>
                        <a href="{{ route('admin.ekskul.create') }}" class="dsh-btn dsh-btn-gold" style="margin-top:.8rem"><i class="fas fa-plus"></i> Tambah ekskul</a>
                    </div>
                @endif
            </section>

            {{-- ================= SEBARAN KATEGORI ================= --}}
            <section class="dsh-panel dsh-kat" data-in style="--i:8" aria-label="Sebaran kategori">
                <div class="dsh-ph">
                    <h3>Sebaran kategori</h3>
                </div>

                @if ($totalKat > 0)
                    <div class="dsh-donut-wrap">
                        <div class="dsh-donut" style="background:{{ $conic }}" role="img"
                             aria-label="Sebaran {{ $totalKat }} ekskul menurut kategori">
                            <div class="ct"><b>{{ $totalKat }}</b><small>ekskul</small></div>
                        </div>

                        <ul class="dsh-legend">
                            @foreach ($kats as $k)
                                <li>
                                    <div class="hd">
                                        <span class="dot" style="background:{{ $k['color'] }}"></span>
                                        <span>{{ $k['label'] }}</span>
                                        <span>{{ $k['count'] }}<small>{{ $k['pct'] }}%</small></span>
                                    </div>
                                    <div class="dsh-bar"><i style="width:{{ $k['pct'] }}%;background:{{ $k['color'] }}"></i></div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if ($topKat && $topKat['count'] > 0)
                        <div class="dsh-insight">
                            <span class="ic"><i class="fas fa-trophy"></i></span>
                            <span><small>Kategori terbanyak</small><b>{{ $topKat['label'] }}</b></span>
                            <span class="v">{{ $topKat['count'] }} ekskul</span>
                        </div>
                    @endif
                @else
                    <div class="dsh-empty">
                        <span class="ic"><i class="fas fa-chart-pie"></i></span>
                        <b>Belum ada data kategori</b>
                        <small>Grafik akan tampil otomatis setelah ada ekskul.</small>
                    </div>
                @endif
            </section>

        </div>
    </div>

    <script>
        /* Angka ringkasan berhitung naik dari 0 (dilewati kalau pengguna memilih "kurangi gerakan"). */
        (function () {
            var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
            document.querySelectorAll('.dsh [data-count]').forEach(function (el) {
                var to = parseInt(el.getAttribute('data-count'), 10) || 0;
                if (reduce || to === 0) { el.textContent = to; return; }
                var start = null, dur = 900;
                el.textContent = '0';
                (function tick(t) {
                    if (start === null) start = t;
                    var p = Math.min(1, (t - start) / dur);
                    el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(tick);
                })(performance.now());
            });
        })();
    </script>
@endsection