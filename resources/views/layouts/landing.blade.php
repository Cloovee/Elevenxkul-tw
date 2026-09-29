@php
    $kat = ['organisasi' => 'Organisasi', 'ekstrakulikuler' => 'Ekstrakurikuler', 'komunitas' => 'Komunitas'];
    $foto = asset('images/smkn11foto.jpg');
    $logo = asset('images/smkn11logo.png');
    $q = urlencode($sekolah['peta_query']);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekstrakurikuler {{ $sekolah['nama'] }}</title>
    <meta name="description" content="Daftar ekstrakurikuler, pembina, galeri kegiatan, video, dan lokasi {{ $sekolah['nama'] }}.">
    <link rel="icon" href="{{ $logo }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Font sama dengan dashboard: Fredoka (judul) + Plus Jakarta Sans (isi) --}}
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Palet sama dengan resources/css/app.css */
        :root{--cloud:#F2F7FF;--blue:#0B409C;--navy:#10316B;--gold:#FFE867;--mute:#5B6E96;--line:#E3ECFB;
            --display:'Fredoka',system-ui,sans-serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
            --card:0 4px 24px -8px rgba(16,49,107,.10);--card-hover:0 24px 44px -20px rgba(11,64,156,.35);--ease:cubic-bezier(.16,1,.3,1)}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth;scroll-padding-top:6rem}
        body{font-family:var(--body);color:var(--navy);background:var(--cloud);line-height:1.65;overflow-x:hidden;-webkit-font-smoothing:antialiased}
        img{display:block;max-width:100%}
        a{color:inherit;text-decoration:none}
        :focus-visible{outline:3px solid var(--blue);outline-offset:3px;border-radius:12px}
        .wrap{width:min(1160px,92%);margin-inline:auto}
        .sec{padding:clamp(3.5rem,8vw,6.5rem) 0}
        h1,h2,h3{font-family:var(--display);font-weight:600;line-height:1.15;letter-spacing:-.01em}
        h2{font-size:clamp(1.9rem,3.6vw,2.6rem)}
        .lead{color:var(--mute);max-width:52ch;margin-top:.7rem}
        .panel{background:#fff;border-radius:2rem;box-shadow:var(--card);border:1px solid #F3F6FC}

        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.8rem 1.6rem;border-radius:999px;font-weight:700;font-size:.92rem;border:0;cursor:pointer;transition:transform .35s var(--ease),box-shadow .35s var(--ease),background .25s,color .25s}
        .btn:hover{transform:translateY(-3px)}
        .btn-blue{background:var(--blue);color:#fff;box-shadow:0 8px 20px -8px rgba(11,64,156,.55)}
        .btn-blue:hover{box-shadow:0 14px 26px -10px rgba(11,64,156,.7)}
        .btn-soft{background:#fff;color:var(--navy);box-shadow:var(--card)}
        .btn-soft:hover{background:var(--navy);color:#fff}
        .btn-gold{background:var(--gold);color:var(--navy)}

        /* ---------- Latar lembut (sama seperti layout admin) ---------- */
        .blob{position:absolute;border-radius:50%;filter:blur(64px);pointer-events:none;z-index:0}

        /* ---------- Navigasi ---------- */
        .nav{position:fixed;top:.9rem;left:0;right:0;z-index:50}
        .nav-in{width:min(1160px,92%);margin-inline:auto;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.55rem .6rem .55rem 1.1rem;border-radius:999px;background:rgba(255,255,255,.88);backdrop-filter:blur(12px);box-shadow:0 10px 30px -14px rgba(16,49,107,.35);transition:box-shadow .3s}
        .brand{display:flex;align-items:center;gap:.65rem;font-family:var(--display);font-weight:600;line-height:1.1}
        .brand img{width:30px;height:auto}
        .brand small{display:block;font:500 .7rem var(--body);color:var(--mute)}
        .links{display:flex;align-items:center;gap:.3rem;font-size:.9rem;font-weight:600}
        .links a:not(.btn){padding:.5rem .95rem;border-radius:999px;color:var(--mute);transition:background .25s,color .25s}
        .links a:not(.btn):hover{background:var(--cloud);color:var(--navy)}
        .links .btn{padding:.6rem 1.3rem;margin-left:.4rem}
        @media(max-width:820px){.links a:not(.btn){display:none}}

        /* ---------- Hero ---------- */
        .hero{position:relative;padding:8.2rem 0 0;overflow:hidden}
        .hero-grid{position:relative;z-index:1;display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(2rem,5vw,4.5rem);align-items:center}
        .hero h1{font-size:clamp(2.4rem,5.2vw,4rem);font-weight:700;max-width:14ch}
        .hero .sub{margin-top:1.2rem;max-width:46ch;color:var(--mute);font-size:1.05rem}
        .cta{display:flex;flex-wrap:wrap;gap:.8rem;margin-top:1.8rem}
        .rise{opacity:0;animation:rise .9s var(--ease) forwards;animation-delay:calc(var(--d,0)*90ms + 80ms)}
        @keyframes rise{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
        .frame-wrap{position:relative;justify-self:end;width:min(100%,470px)}
        .frame-back{position:absolute;inset:8% -5% -5% 8%;background:var(--gold);border-radius:2.2rem;will-change:transform;transition:none}
        .frame{position:relative;aspect-ratio:4/4.6;border-radius:2.2rem;overflow:hidden;background:var(--navy);box-shadow:0 30px 50px -28px rgba(16,49,107,.6)}
        .frame img{position:absolute;left:0;top:-12%;width:100%;height:124%;object-fit:cover;will-change:transform}
        .chip-card{position:absolute;left:-1.6rem;bottom:2rem;display:flex;align-items:center;gap:.8rem;padding:.8rem 1.2rem .8rem .8rem;background:#fff;border-radius:1.4rem;box-shadow:0 18px 34px -16px rgba(16,49,107,.45);transition:transform .5s var(--ease)}
        .chip-card:hover{transform:translateY(-6px) rotate(-1.5deg)}
        .chip-card img{width:40px}
        .chip-card b{display:block;font:600 .98rem var(--display);line-height:1.2}
        .chip-card span{font-size:.75rem;color:var(--mute)}
        .stats{position:relative;z-index:2;margin-top:3.5rem;display:grid;grid-template-columns:repeat(4,1fr);padding:1.6rem 1rem;transform:translateY(50%)}
        .stat{text-align:center;padding:.2rem 1rem}
        .stat+.stat{border-left:1px solid var(--line)}
        .stat b{display:block;font:700 2.1rem var(--display);color:var(--blue);line-height:1.1}
        .stat span{font-size:.85rem;color:var(--mute)}
        .hero-end{height:3.2rem}
        @media(max-width:860px){
            .hero-grid{grid-template-columns:1fr}
            .frame-wrap{justify-self:center;width:min(86%,420px);margin-top:1rem}
            .chip-card{left:-.6rem}
            .stats{grid-template-columns:repeat(2,1fr);row-gap:1.2rem}
            .stat:nth-child(3){border-left:0}
            .hero-end{height:6.5rem}
        }

        /* ---------- Sekolah + video ---------- */
        .school{display:grid;grid-template-columns:1.6fr 1fr;gap:1.6rem;margin-top:2.2rem;align-items:stretch}
        .video{position:relative;aspect-ratio:16/9;border-radius:1.6rem;overflow:hidden;background:var(--navy)}
        .video button,.video iframe{position:absolute;inset:0;width:100%;height:100%;border:0}
        .video button{cursor:pointer;display:grid;place-items:center;background:linear-gradient(rgba(16,49,107,.2),rgba(16,49,107,.5)),url('https://i.ytimg.com/vi/{{ $sekolah['youtube_id'] }}/hqdefault.jpg') center/cover,url('{{ $foto }}') center/cover}
        .play{width:76px;height:76px;border-radius:50%;background:var(--gold);display:grid;place-items:center;transition:transform .4s var(--ease);animation:pulse 2.4s infinite}
        .video button:hover .play{transform:scale(1.12)}
        .play svg{width:26px;margin-left:4px;fill:var(--navy)}
        @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(255,232,103,.65)}70%,100%{box-shadow:0 0 0 20px rgba(255,232,103,0)}}
        .school-info{display:flex;flex-direction:column;gap:1rem;padding:1.8rem}
        .school-info h3{font-size:1.4rem}
        .school-info p{color:var(--mute);font-size:.95rem}
        .school-info .btn{margin-top:auto;align-self:flex-start}
        .school-panel{padding:1.4rem}
        @media(max-width:860px){.school{grid-template-columns:1fr}.school-panel{padding:1rem}}

        /* ---------- Ekskul ---------- */
        .head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:end;gap:1.2rem;margin-bottom:2rem}
        .chips{display:flex;flex-wrap:wrap;gap:.45rem}
        .chip{border:0;background:#fff;color:var(--mute);font:600 .85rem var(--body);padding:.55rem 1.15rem;border-radius:999px;cursor:pointer;box-shadow:var(--card);transition:background .25s,color .25s,transform .3s var(--ease)}
        .chip:hover{transform:translateY(-2px);color:var(--navy)}
        .chip[aria-pressed=true]{background:var(--blue);color:#fff}
        .cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.4rem;align-items:start}
        .card{background:#fff;border-radius:1.8rem;padding:.8rem;box-shadow:var(--card);border:1px solid #F3F6FC;transition:transform .5s var(--ease),box-shadow .5s var(--ease)}
        .card:hover,.card:focus-visible{transform:translateY(-8px);box-shadow:var(--card-hover)}
        .card[hidden]{display:none}
        .cover{position:relative;aspect-ratio:16/10;border-radius:1.3rem;overflow:hidden;background:var(--cloud)}
        .cover img{width:100%;height:100%;object-fit:cover;transition:transform .8s var(--ease)}
        .card:hover .cover img,.card:focus-visible .cover img{transform:scale(1.08)}
        .ph{position:absolute;inset:0;display:grid;place-items:center;font:700 4.5rem var(--display);color:var(--blue);background:linear-gradient(135deg,#E4EEFF,#F7FAFF)}
        .tag{position:absolute;left:.8rem;top:.8rem;padding:.28rem .8rem;border-radius:999px;font-size:.72rem;font-weight:700;background:var(--gold);color:var(--navy)}
        .tag.k-organisasi{background:var(--blue);color:#fff}.tag.k-komunitas{background:var(--navy);color:#fff}
        .card-body{padding:1.1rem .7rem .8rem}
        .card h3{font-size:1.35rem}
        .desc{margin-top:.5rem;font-size:.9rem;color:var(--mute);max-height:4.6em;overflow:hidden;transition:max-height .7s var(--ease)}
        .card:hover .desc,.card:focus-visible .desc{max-height:18em}
        .meta{margin-top:1rem;padding:.9rem 1rem;border-radius:1.1rem;background:var(--cloud);display:grid;gap:.35rem;font-size:.85rem}
        .meta div{display:flex;gap:.6rem}
        .meta dt{width:4.2rem;flex:none;color:var(--mute)}
        .meta dd{font-weight:700}
        .count{margin-top:.9rem;font-size:.85rem;color:var(--mute)}
        .count b{font:700 1.25rem var(--display);color:var(--blue)}
        .empty{padding:3rem;text-align:center;color:var(--mute);background:#fff;border-radius:1.8rem;box-shadow:var(--card)}

        /* ---------- Pembina ---------- */
        .plist{display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1rem;margin-top:2rem}
        .person{display:flex;align-items:center;gap:1rem;padding:1rem 1.2rem;border-radius:1.5rem;background:#fff;box-shadow:var(--card);border:1px solid #F3F6FC;transition:transform .45s var(--ease),background .35s,color .35s}
        .person:hover{transform:translateY(-5px);background:var(--navy);color:#fff}
        .avatar{width:58px;height:58px;flex:none;border-radius:50%;object-fit:cover;display:grid;place-items:center;background:var(--blue);color:#fff;font:600 1.1rem var(--display);transition:transform .5s var(--ease)}
        .person:hover .avatar{transform:scale(1.08)}
        .person h3{font-size:1.02rem;font-weight:600}
        .person p{font-size:.8rem;color:var(--mute);margin-top:.15rem;transition:color .3s}
        .person:hover p{color:rgba(255,255,255,.75)}

        /* ---------- Pita parallax ---------- */
        .band{position:relative;border-radius:2.2rem;overflow:hidden;color:#fff;isolation:isolate;padding:clamp(3.5rem,8vw,6rem) 1.5rem;text-align:center}
        .band img{position:absolute;left:0;top:-20%;width:100%;height:140%;object-fit:cover;z-index:-2;will-change:transform}
        .band::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(rgba(16,49,107,.8),rgba(11,64,156,.78))}
        .band h2{max-width:22ch;margin:0 auto}
        .band .cta{justify-content:center}

        /* ---------- Galeri ---------- */
        .gal-panel{padding:clamp(1.5rem,4vw,3rem)}
        .gal{display:grid;grid-template-columns:1.1fr 1fr;gap:clamp(2rem,5vw,4.5rem);align-items:center}
        .stack{position:relative;aspect-ratio:4/3;width:min(100%,520px);margin-inline:auto;touch-action:pan-y;user-select:none;cursor:pointer}
        .stack:focus-visible{outline-offset:12px}
        .g-card{position:absolute;inset:0;margin:0;border-radius:1.6rem;overflow:hidden;background:#fff;border:6px solid #fff;box-shadow:0 20px 36px -18px rgba(16,49,107,.55);transition:transform .6s var(--ease),opacity .5s,filter .6s;will-change:transform}
        .g-card img{width:100%;height:100%;object-fit:cover;border-radius:1.1rem;transition:transform .8s var(--ease);pointer-events:none}
        .stack:hover .g-card.top img{transform:scale(1.05)}
        .g-info .badge{display:inline-block;padding:.28rem .9rem;border-radius:999px;background:var(--gold);color:var(--navy);font-size:.78rem;font-weight:700}
        .g-info h3{font-size:clamp(1.5rem,2.8vw,2rem);margin-top:.9rem}
        .g-info .d{margin-top:.7rem;color:var(--mute);max-width:42ch;min-height:4.8em}
        .g-txt{transition:opacity .3s,transform .45s var(--ease)}
        .g-txt.swap{opacity:0;transform:translateY(10px)}
        .ctrl{display:flex;align-items:center;gap:.8rem;margin-top:1.6rem}
        .arrow{width:52px;height:52px;border-radius:50%;border:0;background:var(--cloud);color:var(--navy);cursor:pointer;display:grid;place-items:center;transition:background .3s,color .3s,transform .4s var(--ease)}
        .arrow svg{width:20px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round;transition:transform .35s var(--ease)}
        .arrow:hover{background:var(--blue);color:#fff}
        .arrow.nx:hover svg{transform:translateX(4px)}.arrow.pv:hover svg{transform:translateX(-4px)}
        .arrow:active{transform:scale(.92)}
        .num{margin-left:.5rem;font:600 1.05rem var(--display)}
        .num span{color:var(--mute);font-weight:500}
        @media(max-width:860px){.gal{grid-template-columns:1fr}.g-info{text-align:center}.g-info .d{margin-inline:auto}.ctrl{justify-content:center}.stack{width:min(88%,480px);margin-bottom:.8rem}}

        /* ---------- Lokasi ---------- */
        .mapgrid{display:grid;grid-template-columns:1fr 1.7fr;gap:1.4rem;margin-top:2rem;align-items:stretch}
        .addr{padding:1.8rem;display:flex;flex-direction:column;gap:.9rem}
        .addr h3{font-size:1.4rem}
        .addr p{color:var(--mute);font-size:.95rem}
        .addr .cta{margin-top:auto;padding-top:1rem;flex-direction:column;align-items:stretch}
        .map{border-radius:2rem;overflow:hidden;min-height:380px;background:#fff;box-shadow:var(--card);border:6px solid #fff}
        .map iframe{display:block;width:100%;height:100%;min-height:368px;border:0}
        @media(max-width:860px){.mapgrid{grid-template-columns:1fr}}

        footer{padding:2rem 0 2.5rem;font-size:.88rem;color:var(--mute)}
        footer .wrap{display:flex;flex-wrap:wrap;gap:1rem;justify-content:space-between;align-items:center;border-top:1px solid var(--line);padding-top:1.6rem}

        @media(prefers-reduced-motion:reduce){
            html{scroll-behavior:auto}
            *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
            .rise{opacity:1}
        }
    </style>
</head>
<body>

<header class="nav">
    <div class="nav-in">
        <a href="#beranda" class="brand">
            <img src="{{ $logo }}" alt="Logo {{ $sekolah['nama'] }}">
            <span>SMKN 11 Bandung<small>Ekstrakurikuler</small></span>
        </a>
        <nav class="links" aria-label="Menu utama">
            <a href="#sekolah">Sekolah</a>
            <a href="#ekskul">Ekskul</a>
            @if($pembina->isNotEmpty())<a href="#pembina">Pembina</a>@endif
            <a href="#galeri">Galeri</a>
            <a href="#lokasi">Lokasi</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-blue">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-blue">Masuk</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    {{-- ================= HERO ================= --}}
    <section class="hero" id="beranda">
        <div class="blob" style="top:-6rem;right:-4rem;width:26rem;height:26rem;background:rgba(11,64,156,.12)"></div>
        <div class="blob" style="top:24rem;left:-6rem;width:18rem;height:18rem;background:rgba(255,232,103,.35)"></div>

        <div class="wrap">
            <div class="hero-grid">
                <div>
                    <h1 class="rise" style="--d:0">Temukan ekskul yang cocok denganmu</h1>
                    <p class="sub rise" style="--d:1">Lihat semua ekstrakurikuler {{ $sekolah['nama'] }}, kenali pembinanya, dan intip kegiatannya sebelum bergabung.</p>
                    <div class="cta rise" style="--d:2">
                        <a href="#ekskul" class="btn btn-blue">Lihat ekskul</a>
                        <a href="#sekolah" class="btn btn-soft">Tonton video sekolah</a>
                    </div>
                </div>

                <div class="frame-wrap rise" style="--d:2">
                    <div class="frame-back" data-speed="-0.05"></div>
                    <div class="frame">
                        <img src="{{ $foto }}" alt="Gedung {{ $sekolah['nama'] }}" data-speed="0.1" data-clamp>
                    </div>
                    <div class="chip-card">
                        <img src="{{ $logo }}" alt="">
                        <div><b>{{ $sekolah['nama'] }}</b><span>SMK Pusat Keunggulan</span></div>
                    </div>
                </div>
            </div>

            <div class="panel stats rise" style="--d:4">
                <div class="stat"><b>{{ $stat['ekskul'] }}</b><span>Ekskul</span></div>
                <div class="stat"><b>{{ $stat['anggota'] }}</b><span>Anggota aktif</span></div>
                <div class="stat"><b>{{ $stat['pembina'] }}</b><span>Pembina</span></div>
                <div class="stat"><b>{{ $stat['pelatih'] }}</b><span>Pelatih</span></div>
            </div>
            <div class="hero-end"></div>
        </div>
    </section>

    {{-- ================= SEKOLAH + VIDEO ================= --}}
    <section class="sec" id="sekolah">
        <div class="wrap">
            <h2>Kenali {{ $sekolah['nama'] }}</h2>
            <p class="lead">Tonton video profil sekolah untuk melihat suasananya sebelum memilih ekskul.</p>

            <div class="panel school-panel school">
                <div class="video">
                    <button type="button" id="video-play" aria-label="Putar video profil {{ $sekolah['nama'] }}">
                        <span class="play"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>
                    </button>
                </div>
                <div class="school-info">
                    <h3>{{ $sekolah['nama'] }}</h3>
                    <p>{{ $sekolah['alamat'] }}</p>
                    <a href="#lokasi" class="btn btn-blue">Lihat lokasi</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= EKSKUL ================= --}}
    <section class="sec" id="ekskul" style="padding-top:0">
        <div class="wrap">
            <div class="head">
                <div>
                    <h2>Ekstrakurikuler</h2>
                    <p class="lead">Arahkan kursor ke kartu (atau ketuk di ponsel) untuk membaca deskripsi lengkap.</p>
                </div>
                @if($ekskuls->isNotEmpty())
                <div class="chips" role="group" aria-label="Filter kategori">
                    <button class="chip" data-f="all" aria-pressed="true">Semua</button>
                    @foreach($kat as $k => $label)
                        @if($ekskuls->contains('kategori', $k))
                            <button class="chip" data-f="{{ $k }}" aria-pressed="false">{{ $label }}</button>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <div class="cards">
                @forelse($ekskuls as $e)
                    @php $sampul = $e->galeri->first(); @endphp
                    <article class="card" tabindex="0" data-kat="{{ $e->kategori }}">
                        <div class="cover">
                            @if($sampul)
                                <img src="{{ $sampul->foto_url }}" alt="Kegiatan {{ $e->nama_ekskul }}" loading="lazy">
                            @else
                                <span class="ph" aria-hidden="true">{{ strtoupper(mb_substr($e->nama_ekskul, 0, 1)) }}</span>
                            @endif
                            <span class="tag k-{{ $e->kategori }}">{{ $kat[$e->kategori] ?? ucfirst($e->kategori) }}</span>
                        </div>
                        <div class="card-body">
                            <h3>{{ $e->nama_ekskul }}</h3>
                            <p class="desc">{{ $e->deskripsi ?: 'Deskripsi belum ditambahkan.' }}</p>
                            <dl class="meta">
                                <div><dt>Pembina</dt><dd>{{ $e->pembina->nama_pembina ?? 'Belum ditentukan' }}</dd></div>
                                <div><dt>Pelatih</dt><dd>{{ $e->pelatih->nama_pelatih ?? 'Belum ada' }}</dd></div>
                                <div><dt>Ketua</dt><dd>{{ $e->ketua->nama_siswa ?? 'Belum ditentukan' }}</dd></div>
                            </dl>
                            <p class="count"><b>{{ $e->anggota_aktif }}</b> anggota aktif</p>
                        </div>
                    </article>
                @empty
                    <p class="empty">Belum ada ekskul. Data akan tampil di sini setelah admin menambahkannya.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ================= PEMBINA ================= --}}
    @if($pembina->isNotEmpty())
    <section class="sec" id="pembina" style="padding-top:0">
        <div class="wrap">
            <h2>Pembina ekskul</h2>
            <p class="lead">Guru yang mendampingi dan bertanggung jawab atas kegiatan ekskul.</p>
            <div class="plist">
                @foreach($pembina as $p)
                    <div class="person">
                        @if($p->foto_url)
                            <img class="avatar" src="{{ $p->foto_url }}" alt="Foto {{ $p->nama_pembina }}" loading="lazy">
                        @else
                            <div class="avatar" aria-hidden="true">{{ $p->inisial }}</div>
                        @endif
                        <div>
                            <h3>{{ $p->nama_pembina }}</h3>
                            <p>{{ $p->ekskuls->isNotEmpty() ? $p->ekskuls->pluck('nama_ekskul')->join(', ') : 'Belum membina ekskul' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ================= PITA PARALLAX ================= --}}
    <section class="wrap" aria-label="Ajakan bergabung">
        <div class="band">
            <img src="{{ $foto }}" alt="" data-speed="0.15" data-clamp>
            <h2>{{ $stat['ekskul'] }} ekskul menunggu kamu di satu sekolah yang sama</h2>
            <div class="cta"><a href="#ekskul" class="btn btn-gold">Pilih ekskulmu</a></div>
        </div>
    </section>

    {{-- ================= GALERI ================= --}}
    <section class="sec" id="galeri">
        <div class="wrap">
            <h2>Galeri kegiatan</h2>
            <p class="lead">Klik foto atau tekan panah untuk melihat foto berikutnya.</p>

            @if($galeri->isEmpty())
                <p class="empty" style="margin-top:2rem">Galeri masih kosong. Foto akan tampil di sini setelah admin mengunggahnya.</p>
            @else
                <div class="panel gal-panel" style="margin-top:2rem">
                    <div class="gal">
                        <div class="stack" id="stack" tabindex="0" aria-roledescription="carousel" aria-label="Galeri foto ekskul">
                            @foreach($galeri as $g)
                                <figure class="g-card"
                                    data-judul="{{ $g->judul }}"
                                    data-ekskul="{{ $g->ekskul->nama_ekskul ?? 'Umum' }}"
                                    data-ket="{{ $g->keterangan }}">
                                    <img src="{{ $g->foto_url }}" alt="{{ $g->judul }}" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}" draggable="false">
                                </figure>
                            @endforeach
                        </div>

                        <div class="g-info">
                            <div class="g-txt" id="g-txt" aria-live="polite">
                                <span class="badge" id="g-ekskul">{{ $galeri->first()->ekskul->nama_ekskul ?? 'Umum' }}</span>
                                <h3 id="g-judul">{{ $galeri->first()->judul }}</h3>
                                <p class="d" id="g-ket">{{ $galeri->first()->keterangan }}</p>
                            </div>
                            @if($galeri->count() > 1)
                            <div class="ctrl">
                                <button class="arrow pv" id="g-prev" aria-label="Foto sebelumnya"><svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
                                <button class="arrow nx" id="g-next" aria-label="Foto berikutnya"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
                                <div class="num"><b id="g-now">1</b> <span>/ {{ $galeri->count() }}</span></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ================= LOKASI ================= --}}
    <section class="sec" id="lokasi" style="padding-top:0">
        <div class="wrap">
            <h2>Lokasi sekolah</h2>
            <p class="lead">Temukan {{ $sekolah['nama'] }} di peta, lalu buka rutenya dari lokasimu.</p>
            <div class="mapgrid">
                <div class="panel addr">
                    <h3>{{ $sekolah['nama'] }}</h3>
                    <p>{{ $sekolah['alamat'] }}</p>
                    <div class="cta">
                        <a class="btn btn-blue" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&destination={{ $q }}">Petunjuk arah</a>
                        <a class="btn btn-soft" style="box-shadow:none;background:var(--cloud)" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ $q }}">Buka di Google Maps</a>
                    </div>
                </div>
                <div class="map">
                    <iframe title="Peta {{ $sekolah['nama'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                        src="https://www.google.com/maps?q={{ $q }}&output=embed"></iframe>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="wrap">
        <span>&copy; {{ date('Y') }} {{ $sekolah['nama'] }}. Sistem Ekstrakurikuler.</span>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-blue">Buka dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-blue">Masuk ke sistem ekskul</a>
        @endauth
    </div>
</footer>

<script>
(() => {
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Parallax: elemen [data-speed] bergeser pelan mengikuti scroll.
       Dengan atribut data-clamp, gerakannya dibatasi agar tidak keluar dari bingkai. */
    const layers = [...document.querySelectorAll('[data-speed]')];
    let ticking = false;
    const parallax = () => {
        ticking = false;
        layers.forEach(el => {
            const p = el.parentElement, r = p.getBoundingClientRect();
            if (r.bottom < -250 || r.top > innerHeight + 250) return;
            let v = -(r.top + r.height / 2 - innerHeight / 2) * el.dataset.speed;
            if (el.hasAttribute('data-clamp')) {
                const lim = (el.offsetHeight - p.offsetHeight) / 2;
                v = Math.max(-lim, Math.min(lim, v));
            }
            el.style.transform = `translate3d(0,${v.toFixed(1)}px,0)`;
        });
    };
    if (!reduce) {
        addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(parallax); } }, { passive: true });
        addEventListener('resize', parallax);
        parallax();
    }

    /* Video YouTube dimuat saat diklik supaya halaman tetap ringan */
    const playBtn = document.getElementById('video-play');
    if (playBtn) playBtn.addEventListener('click', () => {
        const f = document.createElement('iframe');
        f.src = 'https://www.youtube-nocookie.com/embed/{{ $sekolah['youtube_id'] }}?autoplay=1&rel=0';
        f.title = 'Video profil {{ $sekolah['nama'] }}';
        f.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        f.allowFullscreen = true;
        playBtn.replaceWith(f);
    });

    /* Filter kategori ekskul */
    const chips = document.querySelectorAll('.chip');
    chips.forEach(c => c.addEventListener('click', () => {
        chips.forEach(x => x.setAttribute('aria-pressed', x === c));
        document.querySelectorAll('.card').forEach(card => {
            card.hidden = c.dataset.f !== 'all' && card.dataset.kat !== c.dataset.f;
        });
    }));

    /* Galeri bertumpuk: foto paling depan terlempar keluar, foto di belakangnya maju satu layer */
    const stack = document.getElementById('stack');
    if (!stack) return;
    const cards = [...stack.querySelectorAll('.g-card')], n = cards.length;
    const txt = document.getElementById('g-txt');
    let cur = 0, busy = false;
    const OUT = 'translate3d(-120%,-8%,0) rotate(-14deg)';

    const place = (c, off) => {
        const s = 1 - off * .06, side = off % 2 ? 1 : -1;
        c.style.transform = off > 3
            ? 'translate3d(0,0,0) scale(.8)'
            : `translate3d(${off * 28}px,${off * -9}px,0) rotate(${side * off * 2.2}deg) scale(${s})`;
        c.style.opacity = off > 3 ? 0 : 1;
        c.style.filter = `brightness(${1 - off * .08})`;
        c.style.zIndex = n - off;
        c.classList.toggle('top', off === 0);
    };
    const render = skip => cards.forEach((c, i) => { if (c !== skip) place(c, (i - cur + n) % n); });

    const caption = () => {
        const c = cards[cur];
        txt.classList.add('swap');
        setTimeout(() => {
            document.getElementById('g-judul').textContent = c.dataset.judul;
            document.getElementById('g-ekskul').textContent = c.dataset.ekskul;
            document.getElementById('g-ket').textContent = c.dataset.ket;
            const now = document.getElementById('g-now'); if (now) now.textContent = cur + 1;
            txt.classList.remove('swap');
        }, 240);
    };

    const go = dir => {
        if (busy || n < 2) return;
        busy = true;
        if (dir > 0) {
            const out = cards[cur];
            out.style.zIndex = n + 2; out.style.opacity = 0; out.style.transform = OUT;
            cur = (cur + 1) % n;
            render(out);
            setTimeout(() => {        /* setelah terlempar, foto pindah diam-diam ke tumpukan paling belakang */
                out.style.transition = 'none';
                place(out, n - 1);
                out.offsetWidth;
                out.style.transition = '';
                busy = false;
            }, 620);
        } else {
            const back = cards[(cur - 1 + n) % n];
            back.style.transition = 'none';
            back.style.zIndex = n + 2; back.style.opacity = 0; back.style.transform = OUT;
            back.offsetWidth;
            back.style.transition = '';
            cur = (cur - 1 + n) % n;
            render();
            setTimeout(() => busy = false, 620);
        }
        caption();
    };

    render();
    document.getElementById('g-next')?.addEventListener('click', e => { e.stopPropagation(); go(1); });
    document.getElementById('g-prev')?.addEventListener('click', e => { e.stopPropagation(); go(-1); });
    stack.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
        if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
    });

    /* Klik = foto berikutnya, geser (swipe) = maju/mundur */
    let x0 = null, moved = false;
    stack.addEventListener('pointerdown', e => { x0 = e.clientX; moved = false; });
    stack.addEventListener('pointerup', e => {
        if (x0 === null) return;
        const dx = e.clientX - x0; x0 = null;
        if (Math.abs(dx) > 50) { moved = true; go(dx < 0 ? 1 : -1); }
    });
    stack.addEventListener('click', () => { if (!moved) go(1); });
})();
</script>
</body>
</html>