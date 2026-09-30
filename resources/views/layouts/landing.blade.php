@php
    $kat = ['organisasi' => 'Organisasi', 'ekstrakulikuler' => 'Ekstrakurikuler', 'komunitas' => 'Komunitas'];
    $foto = asset('images/smkn11foto.jpg');
    $logo = asset('images/smkn11logo.png');
    $q = urlencode($sekolah['peta_query']);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <script>try{if(localStorage.getItem('rail-folded')==='1')document.documentElement.classList.add('rail-folded')}catch(e){}</script>
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
        html{scroll-behavior:smooth;scroll-padding-top:1.5rem;-webkit-text-size-adjust:100%;text-size-adjust:100%}
        body{font-family:var(--body);color:var(--navy);background:var(--cloud);line-height:1.65;overflow-x:hidden;-webkit-font-smoothing:antialiased}
        img{display:block;max-width:100%}
        [hidden]{display:none!important}
        .sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
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
        .cta{display:flex;flex-wrap:wrap;gap:.7rem}
        .btn-flat{box-shadow:none;background:var(--cloud)}
        .btn-flat:hover{background:var(--navy)}

        /* ---------- Latar lembut (sama seperti layout admin) ---------- */
        .blob{position:absolute;border-radius:50%;filter:blur(64px);pointer-events:none;z-index:0}

        /* ---------- Sidebar rail (gaya sama dengan sidebar Admin/Pembina/Ketua) ---------- */
        :root{--rail:0px}
        @media(min-width:1024px){:root{--rail:9rem}}
        @media(max-width:1023px){html{scroll-padding-top:5.5rem}}
        .rail{position:fixed;z-index:60;top:.75rem;left:.75rem;right:.75rem;display:flex;align-items:center;gap:.5rem;padding:.6rem;border-radius:1.5rem;background:var(--blue);box-shadow:0 25px 50px -12px rgba(11,64,156,.35);outline:1px solid rgba(255,255,255,.2);overflow-x:auto;scrollbar-width:none;user-select:none}
        .rail::-webkit-scrollbar{display:none}
        .r-link{flex:none;display:flex;align-items:center;height:2.75rem;border-radius:1rem;color:rgba(255,255,255,.9);background:none;border:0;font:inherit;cursor:pointer;overflow:hidden;white-space:nowrap;transition:width .32s cubic-bezier(.4,0,.2,1),margin .32s cubic-bezier(.4,0,.2,1),padding .32s cubic-bezier(.4,0,.2,1),background .2s,box-shadow .2s}
        .r-link:hover{background:rgba(255,255,255,.25);box-shadow:0 4px 8px -2px rgba(0,0,0,.15)}
        .r-link[aria-current=page]{background:#fff;color:var(--blue);box-shadow:0 10px 15px -3px rgba(0,0,0,.1)}
        .r-ico{width:2.75rem;height:2.75rem;flex:none;display:grid;place-items:center}
        .r-ico svg{width:1.25rem;height:1.25rem;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;transition:transform .2s}
        .r-link:hover .r-ico svg{transform:scale(1.15)}
        .r-logo{width:2.75rem;height:2.75rem;border-radius:1rem;background:#fff;display:grid;place-items:center;box-shadow:0 4px 8px -2px rgba(0,0,0,.2)}
        .r-logo img{width:1.7rem}
        .r-txt{display:none;font-weight:600;font-size:.92rem;padding-right:.75rem}
        .r-end{margin-left:auto}
        .r-back{display:none}
        @media(min-width:1024px){
            .rail{top:2rem;bottom:2rem;left:2.5rem;right:auto;width:5rem;padding:.75rem;flex-direction:column;align-items:stretch;overflow:hidden;overscroll-behavior:contain;transition:width .32s cubic-bezier(.4,0,.2,1),box-shadow .32s}
            .rail:hover,.rail:focus-within{width:17rem;box-shadow:0 30px 60px -15px rgba(16,49,107,.55)}
            .r-link{width:2.75rem;margin-left:.375rem}
            .rail:hover .r-link,.rail:focus-within .r-link{width:100%;margin-left:0;padding-left:.375rem}
            .r-top{margin-bottom:1rem}.r-top .r-txt{margin-left:.75rem}
            .r-end{margin-left:.375rem;margin-top:auto}
            .rail:hover .r-end,.rail:focus-within .r-end{margin-left:0}
            .r-txt{display:block;opacity:0;transform:translateX(-8px);transition:opacity .2s,transform .25s}
            .rail:hover .r-txt,.rail:focus-within .r-txt{opacity:1;transform:none;transition-delay:.12s}
            .r-back{display:block;position:fixed;inset:0;z-index:59;background:rgba(16,49,107,.18);backdrop-filter:blur(6px);opacity:0;visibility:hidden;pointer-events:none;transition:opacity .3s,visibility .3s}
            .rail:hover~.r-back,.rail:focus-within~.r-back{opacity:1;visibility:visible}
        }
        .content{padding-left:var(--rail)}
        @media(max-width:1023px){
            .rail{gap:.25rem;padding:.4rem;border-radius:1.25rem}
            .r-link{flex:1 1 0;min-width:0;max-width:2.75rem;justify-content:center}
            .r-ico{width:100%}
        }
        @media(max-width:420px){.r-top{display:none}}

        /* ---------- Lipat sidebar: jadi satu ikon melayang di pojok kiri bawah ---------- */
        .rail{transition:width .32s cubic-bezier(.4,0,.2,1),box-shadow .32s,transform .55s var(--ease),opacity .35s}
        @media(min-width:1024px){.rail{transform-origin:0 100%}}
        html.rail-folded{--rail:0px}
        html.rail-folded .rail{opacity:0;pointer-events:none;transform:translateY(-130%)}
        @media(min-width:1024px){html.rail-folded .rail{transform:scale(.06)}}
        .rail-fab{position:fixed;z-index:61;left:1rem;bottom:1rem;width:3.5rem;height:3.5rem;border:0;border-radius:1.25rem;background:var(--blue);color:#fff;display:grid;place-items:center;cursor:pointer;box-shadow:0 20px 40px -12px rgba(11,64,156,.65);outline:1px solid rgba(255,255,255,.25);opacity:0;transform:scale(.4);pointer-events:none;transition:opacity .3s,transform .55s var(--ease),background .25s}
        .rail-fab svg{width:1.5rem;height:1.5rem;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
        html.rail-folded .rail-fab{opacity:1;transform:none;pointer-events:auto;transition-delay:.18s,.18s,0s}
        html.rail-folded .rail-fab:hover{background:var(--navy);transform:translateY(-3px);transition-delay:0s}
        html.rail-folded .rail-fab:active{transform:scale(.94)}
        @media(min-width:1024px){.rail-fab{left:2.5rem;bottom:2rem}}
        /* konten ikut bergeser halus saat sidebar dilipat/dibuka */
        .content,.hero-top,.cf,.cf-meta,footer{transition:padding .5s var(--ease),margin .5s var(--ease)}
        @media(min-width:1024px){.hero{padding-top:3rem}}

        /* ---------- Hero: carousel ekskul (coverflow 3D) ---------- */
        .hero{position:relative;min-height:100vh;min-height:100svh;display:flex;flex-direction:column;color:#fff;overflow:hidden;isolation:isolate;background:var(--navy);padding:6.5rem 0 4.5rem}
        /* Latar hero: foto sekolah dengan opasitas 70% (ubah --hero-bg-opacity kalau mau lebih pekat/transparan) */
        :root{--hero-bg-opacity:.7}
        .hero-bg{position:absolute;left:0;top:-20%;width:100%;height:140%;z-index:-2;background:url('{{ $foto }}') center/cover no-repeat;opacity:var(--hero-bg-opacity);will-change:transform}
        .hero::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(rgba(10,30,72,.55),rgba(10,30,72,.32) 45%,rgba(8,26,66,.82))}
        .hero-top{padding-inline:calc(var(--rail) + 1.5rem) 1.5rem;position:relative;z-index:5;text-align:center}
        .hero h1{font-size:clamp(1.6rem,3vw,2.5rem);font-weight:700;max-width:26ch;margin-inline:auto;color:#fff;text-shadow:0 2px 16px rgba(0,0,0,.45)}
        .hero .sub{margin:.6rem auto 0;max-width:52ch;color:rgba(255,255,255,.9);text-shadow:0 1px 10px rgba(0,0,0,.45)}
        .cf{position:relative;flex:1;display:grid;place-items:center;margin-left:var(--rail);perspective:1500px;touch-action:pan-y;user-select:none;--ch:clamp(340px,66svh,640px);--cw:calc(var(--ch)*.58);min-height:calc(var(--ch) + 3rem)}
        .cf:focus-visible{outline-offset:-8px}
        @supports not (height:100svh){.cf{--ch:clamp(340px,66vh,640px)}}
        .cf-card{position:absolute;left:50%;top:50%;width:var(--cw);height:var(--ch);margin:calc(var(--ch)/-2) 0 0 calc(var(--cw)/-2);border-radius:1.1rem;overflow:hidden;background:var(--blue);box-shadow:0 30px 50px -20px rgba(0,0,0,.6);cursor:pointer;will-change:transform,opacity}
        .cf-card.on{cursor:default}
        .cf-card img{width:100%;height:100%;object-fit:cover;pointer-events:none}
        .cf-card::after{content:"";position:absolute;inset:0;background:linear-gradient(transparent 40%,rgba(8,26,66,.8));opacity:0;transition:opacity .6s}
        .cf-card.on::after{opacity:1}
        .cf-ph{position:absolute;inset:0;display:grid;place-items:center;font:700 5rem var(--display);color:rgba(255,255,255,.85);background:linear-gradient(160deg,var(--blue),var(--navy))}
        .cf-cap{position:absolute;z-index:15;left:50%;bottom:calc(50% - var(--ch)/2 + 1.4rem);width:calc(var(--cw) - 2.4rem);transform:translateX(-50%);pointer-events:none;opacity:var(--o,0);transition:opacity .25s,transform .45s var(--ease)}
        .cf-cap.swap{opacity:0;transform:translate(-50%,10px)}
        .cf-title{font:700 clamp(1.25rem,2.1vw,1.9rem) var(--display);text-transform:uppercase;letter-spacing:.04em;line-height:1.1;text-shadow:0 2px 18px rgba(0,0,0,.5)}
        .cf-line{width:2.2rem;height:2px;background:#fff;margin:.9rem 0 .6rem}
        .cf-sub{font-size:.95rem;text-shadow:0 1px 10px rgba(0,0,0,.5)}
        .cf-title,.cf-sub{overflow-wrap:anywhere}
        .cf-go{pointer-events:auto;margin-top:.9rem;padding:.55rem 1.2rem;font-size:.85rem}
        .cf-arrow{position:absolute;top:50%;translate:0 -50%;z-index:20;width:3rem;height:3rem;border:0;border-radius:50%;background:rgba(255,255,255,.14);backdrop-filter:blur(6px);color:#fff;cursor:pointer;display:grid;place-items:center;opacity:var(--o,0);transition:background .3s,color .3s,transform .3s var(--ease)}
        .cf-arrow svg{width:22px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
        .cf-arrow:hover{background:var(--gold);color:var(--navy)}.cf-arrow:active{transform:scale(.92)}
        .cf-arrow.pv{left:1rem}.cf-arrow.nx{right:1rem}
        .cf-meta{opacity:var(--o,0);margin-left:var(--rail);text-align:center;font:600 1rem var(--display);position:relative;z-index:5}
        .cf-meta span{color:rgba(255,255,255,.6);font-weight:500}
        .folded .cf-arrow,.folded .cf-go{pointer-events:none}
        @media(max-width:600px){
            .hero{padding:5.6rem 0 4rem}
            .hero-top{padding-inline:calc(var(--rail) + 1.1rem) 1.1rem}
            .cf{--ch:clamp(300px,58svh,520px);--cw:calc(var(--ch)*.68)}
            .cf-cap{width:calc(var(--cw) - 1.8rem);bottom:calc(50% - var(--ch)/2 + 1.1rem)}
            .cf-sub{font-size:.85rem}
        }
        .cf-empty{margin:auto;padding:2rem;text-align:center;color:rgba(255,255,255,.85)}
        .stats{position:relative;z-index:2;margin-top:-3rem;display:grid;grid-template-columns:repeat(4,1fr);padding:1.6rem 1rem}
        .stat{text-align:center;padding:.2rem 1rem}
        .stat+.stat{border-left:1px solid var(--line)}
        .stat b{display:block;font:700 2.1rem var(--display);color:var(--blue);line-height:1.1}
        .stat span{font-size:.85rem;color:var(--mute)}
        @media(max-width:860px){.stats{grid-template-columns:repeat(2,1fr);row-gap:1.2rem}.stat:nth-child(3){border-left:0}.stat:nth-child(n+3){border-top:1px solid var(--line);padding-top:1.2rem}.cf-arrow{width:2.6rem;height:2.6rem}.cf-arrow.pv{left:.4rem}.cf-arrow.nx{right:.4rem}}
        @keyframes flash{0%,60%{box-shadow:0 0 0 4px var(--gold),var(--card-hover)}100%{box-shadow:var(--card)}}

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
        .head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:end;gap:1.2rem;margin-bottom:1.4rem}
        .toolbar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.8rem 1.2rem;margin-bottom:1.4rem}
        .search{position:relative;flex:1 1 240px;max-width:360px}
        .search svg{position:absolute;left:1rem;top:50%;translate:0 -50%;width:1.05rem;height:1.05rem;fill:none;stroke:var(--mute);stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;pointer-events:none}
        .search input{width:100%;-webkit-appearance:none;appearance:none;font:500 1rem var(--body);color:var(--navy);padding:.7rem 1rem .7rem 2.6rem;border-radius:999px;border:1px solid var(--line);background:#fff;box-shadow:var(--card)}
        .search input::placeholder{color:var(--mute);opacity:.8}
        .chips{display:flex;flex-wrap:wrap;gap:.45rem}
        .chip{flex:none;border:0;background:#fff;color:var(--mute);font:600 .85rem var(--body);padding:.55rem 1.15rem;border-radius:999px;cursor:pointer;box-shadow:var(--card);transition:background .25s,color .25s,transform .3s var(--ease)}
        .chip:hover{transform:translateY(-2px);color:var(--navy)}
        .chip[aria-pressed=true]{background:var(--blue);color:#fff}
        /* Kartu ringkas: 2 kolom di ponsel, 3-4 kolom di layar lebar. Jumlah yang tampil dibatasi lewat tombol "Tampilkan lebih banyak". */
        .cards{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.8rem;align-items:stretch}
        .cards>.empty{grid-column:1/-1}
        @media(min-width:600px){.cards{grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem}}
        .card{display:flex;flex-direction:column;min-width:0;background:#fff;border-radius:1.4rem;padding:.5rem;box-shadow:var(--card);border:1px solid #F3F6FC;cursor:pointer;transition:transform .5s var(--ease),box-shadow .5s var(--ease)}
        .card:hover{transform:translateY(-6px);box-shadow:var(--card-hover)}
        .card:focus-visible{transform:translateY(-6px);box-shadow:var(--card-hover)}
        .cover{position:relative;flex:none;aspect-ratio:4/3;border-radius:1rem;overflow:hidden;background:var(--cloud)}
        .cover img{width:100%;height:100%;object-fit:cover;transition:transform .8s var(--ease)}
        .card:hover .cover img{transform:scale(1.08)}
        .ph{position:absolute;inset:0;display:grid;place-items:center;font:700 3.5rem var(--display);color:var(--blue);background:linear-gradient(135deg,#E4EEFF,#F7FAFF)}
        .tag{position:absolute;left:.6rem;top:.6rem;max-width:calc(100% - 1.2rem);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:.22rem .7rem;border-radius:999px;font-size:.68rem;font-weight:700;background:var(--gold);color:var(--navy)}
        .tag.k-organisasi{background:var(--blue);color:#fff}.tag.k-komunitas{background:var(--navy);color:#fff}
        .card-body{display:flex;flex-direction:column;flex:1;padding:.75rem .45rem .45rem}
        .card h3{font-size:1.05rem;line-height:1.2;overflow-wrap:anywhere}
        .desc{margin-top:.35rem;font-size:.82rem;line-height:1.5;color:var(--mute);display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;line-clamp:2;overflow:hidden;overflow-wrap:anywhere}
        .count{margin-top:auto;padding-top:.7rem;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:baseline;gap:.2rem .6rem;font-size:.8rem;color:var(--mute)}
        .count b{font:700 1.1rem var(--display);color:var(--blue)}
        .count .go{font-weight:700;color:var(--blue)}
        .card.flash{animation:flash 1.6s ease}
        .enter{animation:enter .55s var(--ease) both}
        @keyframes enter{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
        .empty{padding:3rem 1.5rem;text-align:center;color:var(--mute);background:#fff;border-radius:1.8rem;box-shadow:var(--card)}
        .more-wrap{display:flex;flex-direction:column;align-items:center;gap:.8rem;margin-top:1.8rem}
        .more-info{font-size:.85rem;color:var(--mute)}
        .more-info:empty{display:none}
        .meta{margin-top:1rem;padding:.9rem 1rem;border-radius:1.1rem;background:var(--cloud);display:grid;gap:.35rem;font-size:.85rem}
        .meta div{display:flex;gap:.6rem}
        .meta dt{width:4.2rem;flex:none;color:var(--mute)}
        .meta dd{font-weight:700;min-width:0;overflow-wrap:anywhere}
        @media(max-width:600px){
            .search{max-width:none;flex-basis:100%}
            /* chip kategori jadi satu baris yang bisa digeser, hemat tinggi layar */
            .chips{flex:0 0 auto;flex-wrap:nowrap;overflow-x:auto;width:calc(100% + 8vw);margin-inline:-4vw;padding:.2rem 4vw .7rem;scrollbar-width:none;-webkit-overflow-scrolling:touch}
            .chips::-webkit-scrollbar{display:none}
            .toolbar{margin-bottom:.6rem}
            .card{border-radius:1.2rem;padding:.4rem}
            .cover{border-radius:.85rem}
            .card-body{padding:.65rem .35rem .35rem}
            .card h3{font-size:.98rem}
            .ph{font-size:3rem}
        }

        /* ---------- Detail ekskul (pop-up) ---------- */
        .dlg{position:fixed;inset:0;margin:auto;width:min(92vw,520px);max-width:none;max-height:min(90vh,760px);max-height:min(90svh,760px);padding:0;border:0;border-radius:1.8rem;background:#fff;color:var(--navy);box-shadow:0 40px 80px -20px rgba(16,49,107,.55);overflow:hidden}
        .dlg[open]{display:flex;flex-direction:column;animation:dlgIn .4s var(--ease)}
        .dlg::backdrop{background:rgba(16,49,107,.45);backdrop-filter:blur(4px)}
        @keyframes dlgIn{from{opacity:0;transform:translateY(24px) scale(.97)}to{opacity:1;transform:none}}
        html:has(.dlg[open]){overflow:hidden}
        .dlg-x{position:absolute;z-index:2;top:.8rem;right:.8rem;width:2.5rem;height:2.5rem;border:0;border-radius:50%;background:rgba(255,255,255,.92);color:var(--navy);cursor:pointer;display:grid;place-items:center;box-shadow:0 4px 12px -2px rgba(0,0,0,.25);transition:background .25s,color .25s}
        .dlg-x:hover{background:var(--navy);color:#fff}
        .dlg-x svg{width:1.1rem;height:1.1rem;fill:none;stroke:currentColor;stroke-width:2.6;stroke-linecap:round}
        .dlg-cover{position:relative;flex:none;aspect-ratio:16/9;max-height:36vh;max-height:36svh;background:var(--cloud)}
        .dlg-cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
        .dlg-cover .tag{left:1rem;top:1rem;font-size:.75rem}
        .dlg-body{flex:1;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding:1.4rem 1.5rem 1.6rem}
        .dlg-body h3{font-size:1.6rem;overflow-wrap:anywhere}
        .dlg-desc{margin-top:.6rem;color:var(--mute);font-size:.95rem;white-space:pre-line;overflow-wrap:anywhere}
        .dlg-body .count{margin-top:1rem;padding-top:0;justify-content:flex-start;font-size:.9rem}
        @media(max-width:560px){
            .dlg{width:100%;margin:auto 0 0;border-radius:1.6rem 1.6rem 0 0;max-height:88vh;max-height:88svh}
            .dlg-body{padding:1.1rem 1.1rem calc(1.4rem + env(safe-area-inset-bottom,0px))}
            .dlg-body h3{font-size:1.4rem}
        }

        /* ---------- Pembina ---------- */
        .plist{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,250px),1fr));gap:1rem;margin-top:2rem}
        @media(max-width:560px){.plist{gap:.7rem;margin-top:1.4rem}.person{padding:.8rem 1rem}.avatar{width:50px;height:50px}}
        .person{display:flex;align-items:center;gap:1rem;padding:1rem 1.2rem;border-radius:1.5rem;background:#fff;box-shadow:var(--card);border:1px solid #F3F6FC;transition:transform .45s var(--ease),background .35s,color .35s}
        .person:hover{transform:translateY(-5px);background:var(--navy);color:#fff}
        .avatar{width:58px;height:58px;flex:none;border-radius:50%;object-fit:cover;display:grid;place-items:center;background:var(--blue);color:#fff;font:600 1.1rem var(--display);transition:transform .5s var(--ease)}
        .person:hover .avatar{transform:scale(1.08)}
        .person h3{font-size:1.02rem;font-weight:600}
        .person p{font-size:.8rem;color:var(--mute);margin-top:.15rem;transition:color .3s}
        .person>div:last-child{min-width:0}
        .person{min-width:0}
        .person h3,.person p{overflow-wrap:anywhere}
        .person:hover p{color:rgba(255,255,255,.75)}

        /* ---------- Pita parallax ---------- */
        .band{position:relative;border-radius:2.2rem;overflow:hidden;color:#fff;isolation:isolate;padding:clamp(3.5rem,8vw,6rem) 1.5rem;text-align:center}
        .band img{position:absolute;left:0;top:-20%;width:100%;height:140%;object-fit:cover;z-index:-2;will-change:transform}
        .band::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(rgba(16,49,107,.8),rgba(11,64,156,.78))}
        .band h2{max-width:22ch;margin:0 auto}
        .band .cta{justify-content:center;margin-top:1.8rem}

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
        @media(max-width:860px){.mapgrid{grid-template-columns:1fr}.map{min-height:320px}.map iframe{min-height:308px}}

        /* ---------- Footer ---------- */
        footer{--ft:#081F40;background:var(--ft);color:rgba(255,255,255,.86);padding:clamp(2.8rem,6vw,4.2rem) 0 2rem var(--rail);font-size:1rem}
        .ft-grid{display:grid;grid-template-columns:1.15fr .85fr 1fr;gap:clamp(2rem,5vw,4rem)}
        .ft-brand{display:flex;align-items:center;gap:1rem}
        .ft-brand img{width:3.4rem;height:auto;flex:none}
        .ft-brand h3{font-size:1.65rem;color:#fff;letter-spacing:0}
        .ft-brand p{color:rgba(255,255,255,.8);line-height:1.3}
        .ft-desc{margin-top:1.4rem;text-align:justify;color:rgba(255,255,255,.8);line-height:1.75}
        .ft-social{display:flex;gap:1.1rem;margin-top:1.4rem}
        .ft-social a{width:2.1rem;height:2.1rem;display:grid;place-items:center;color:rgba(255,255,255,.85);border-radius:50%;transition:color .25s,transform .35s var(--ease)}
        .ft-social a:hover{color:var(--gold);transform:translateY(-3px)}
        .ft-social svg,.ft-ct svg{width:1.5rem;height:1.5rem;fill:currentColor;flex:none}
        footer h4{font-family:var(--display);font-weight:600;font-size:1.65rem;line-height:1.15;color:#fff;margin-bottom:1.3rem}
        .ft-links{list-style:none;display:grid;gap:.85rem}
        .ft-links a{color:rgba(255,255,255,.86);transition:color .25s,padding-left .3s var(--ease)}
        .ft-links a:hover{color:var(--gold);padding-left:.35rem}
        .ft-ct{list-style:none;display:grid;gap:1.1rem}
        .ft-ct li{display:flex;align-items:flex-start;gap:1rem}
        .ft-ct svg{width:1.4rem;height:1.4rem;margin-top:.15rem;color:#fff}
        .ft-ct a:hover{color:var(--gold)}
        .ft-bar{margin-top:clamp(2rem,4vw,2.8rem);padding-top:1.9rem;border-top:1px solid rgba(255,255,255,.16);color:rgba(255,255,255,.8)}
        .ft-bar b{color:#fff;font-weight:700}
        @media(max-width:960px){.ft-grid{grid-template-columns:1fr 1fr}.ft-col-brand{grid-column:1/-1}}
        @media(max-width:600px){.ft-grid{grid-template-columns:1fr}footer h4,.ft-brand h3{font-size:1.4rem}}

        /* ---------- Efek muncul saat di-scroll (kelas ditambahkan lewat JS) ---------- */
        .reveal{opacity:0;transform:translate3d(0,38px,0);transition:opacity .9s var(--ease),transform 1s var(--ease);transition-delay:var(--rd,0ms);will-change:opacity,transform}
        .reveal[data-r=zoom]{transform:scale(.93)}
        .reveal[data-r=left]{transform:translate3d(-56px,0,0)}
        .reveal[data-r=right]{transform:translate3d(56px,0,0)}
        @media(max-width:860px){.reveal[data-r=left],.reveal[data-r=right]{transform:translate3d(0,38px,0)}}
        .reveal.in{opacity:1;transform:none}

        @media(prefers-reduced-motion:reduce){
            html{scroll-behavior:auto}
            *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
            .rise{opacity:1}
            .reveal{opacity:1;transform:none}
        }
    </style>
</head>
<body>

<nav class="rail" id="rail" aria-label="Menu utama">
        <a href="#beranda" class="r-link r-top" aria-label="{{ $sekolah['nama'] }}">
            <span class="r-ico"><span class="r-logo"><img src="{{ $logo }}" alt=""></span></span>
            <span class="r-txt">SMKN 11 Bandung</span>
        </a>
        <a href="#beranda" class="r-link" aria-label="Beranda"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></span><span class="r-txt">Beranda</span></a>
        <a href="#sekolah" class="r-link" aria-label="Sekolah"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span><span class="r-txt">Sekolah</span></a>
        <a href="#ekskul" class="r-link" aria-label="Ekskul"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></span><span class="r-txt">Ekskul</span></a>
        @if($pembina->isNotEmpty())
        <a href="#pembina" class="r-link" aria-label="Pembina"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span><span class="r-txt">Pembina</span></a>
        @endif
        <a href="#galeri" class="r-link" aria-label="Galeri"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span><span class="r-txt">Galeri</span></a>
        <a href="#lokasi" class="r-link" aria-label="Lokasi"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span><span class="r-txt">Lokasi</span></a>
        @auth
        <a href="{{ route('dashboard') }}" class="r-link r-end" aria-label="Dashboard"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></span><span class="r-txt">Dashboard</span></a>
        @else
        <a href="{{ route('login') }}" class="r-link r-end" aria-label="Masuk"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></span><span class="r-txt">Masuk</span></a>
        @endauth
        <button type="button" class="r-link r-fold" id="rail-fold" aria-label="Lipat menu"><span class="r-ico"><svg viewBox="0 0 24 24"><path d="M11 17l-5-5 5-5M18 17l-5-5 5-5"/></svg></span><span class="r-txt">Lipat menu</span></button>
</nav>
<div class="r-back" aria-hidden="true"></div>
<button type="button" class="rail-fab" id="rail-fab" aria-label="Buka menu" aria-controls="rail" aria-expanded="false" title="Buka menu" inert><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>

<main>
    {{-- ================= HERO: carousel ekskul ================= --}}
    <section class="hero" id="beranda">
        @php
            $slides = $ekskuls->map(fn ($e) => [
                'e' => $e,
                'img' => $e->cover_url,
                'sub' => \Illuminate\Support\Str::limit(strip_tags((string) $e->deskripsi), 70) ?: ($kat[$e->kategori] ?? ucfirst($e->kategori)),
            ]);
            $first = $slides->first();
        @endphp
        <div class="hero-bg" data-speed="0.25" data-clamp aria-hidden="true"></div>

        <div class="hero-top">
            <h1 class="rise" style="--d:0">Ayo kembangkan bakatmu di 11 melalui ElevenXkul</h1>
            <p class="sub rise" style="--d:1">Klik foto atau tekan panah untuk menjelajahi ekstrakurikuler {{ $sekolah['nama'] }}.</p>
        </div>

        @if($slides->isEmpty())
            <p class="cf-empty">Belum ada ekskul. Data akan tampil di sini setelah admin menambahkannya.</p>
        @else
        <div class="cf" id="cf" tabindex="0" aria-roledescription="carousel" aria-label="Daftar ekskul">
            @foreach($slides as $s)
                <figure class="cf-card" data-nama="{{ $s['e']->nama_ekskul }}" data-sub="{{ $s['sub'] }}" data-href="#ekskul-{{ $s['e']->id_ekskul }}">
                    @if($s['img'])<img src="{{ $s['img'] }}" alt="Kegiatan {{ $s['e']->nama_ekskul }}" draggable="false" loading="{{ $loop->index < 5 ? 'eager' : 'lazy' }}">
                    @else<span class="cf-ph" aria-hidden="true">{{ strtoupper(mb_substr($s['e']->nama_ekskul, 0, 1)) }}</span>@endif
                </figure>
            @endforeach

            <div class="cf-cap" id="cf-cap" aria-live="polite">
                <h2 class="cf-title" id="cf-title">{{ $first['e']->nama_ekskul }}</h2>
                <div class="cf-line"></div>
                <p class="cf-sub" id="cf-sub">{{ $first['sub'] }}</p>
                <a class="btn btn-gold cf-go" id="cf-go" href="#ekskul-{{ $first['e']->id_ekskul }}">Lihat detail</a>
            </div>

            @if($slides->count() > 1)
            <button class="cf-arrow pv" id="cf-prev" aria-label="Ekskul sebelumnya"><svg viewBox="0 0 24 24"><path d="M15 5l-7 7 7 7"/></svg></button>
            <button class="cf-arrow nx" id="cf-next" aria-label="Ekskul berikutnya"><svg viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg></button>
            @endif
        </div>
        <p class="cf-meta"><b id="cf-now">1</b> <span>/ {{ $slides->count() }}</span></p>
        @endif
    </section>

    <div class="content">
    <div class="wrap">
        <div class="panel stats">
            <div class="stat"><b>{{ $stat['ekskul'] }}</b><span>Ekskul</span></div>
            <div class="stat"><b>{{ $stat['anggota'] }}</b><span>Anggota aktif</span></div>
            <div class="stat"><b>{{ $stat['pembina'] }}</b><span>Pembina</span></div>
            <div class="stat"><b>{{ $stat['pelatih'] }}</b><span>Pelatih</span></div>
        </div>
    </div>

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
                    <p class="lead">Cari atau saring ekskul, lalu ketuk kartunya untuk melihat deskripsi lengkap dan pengurusnya.</p>
                </div>
            </div>

            @if($ekskuls->isNotEmpty())
            <div class="toolbar">
                <label class="search">
                    <span class="sr">Cari ekskul</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                    <input type="search" id="ek-q" placeholder="Cari ekskul, pembina, pelatih..." autocomplete="off" enterkeyhint="search">
                </label>
                <div class="chips" role="group" aria-label="Filter kategori">
                    <button type="button" class="chip" data-f="all" aria-pressed="true">Semua</button>
                    @foreach($kat as $k => $label)
                        @if($ekskuls->contains('kategori', $k))
                            <button type="button" class="chip" data-f="{{ $k }}" aria-pressed="false">{{ $label }}</button>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <div class="cards" id="ek-grid">
                @forelse($ekskuls as $e)
                    @php $sampul = $e->cover_url; $labelKat = $kat[$e->kategori] ?? ucfirst($e->kategori); @endphp
                    <article class="card" id="ekskul-{{ $e->id_ekskul }}" tabindex="0" role="button" aria-haspopup="dialog"
                        data-kat="{{ $e->kategori }}"
                        data-tag="{{ $labelKat }}"
                        data-nama="{{ $e->nama_ekskul }}"
                        data-desc="{{ $e->deskripsi }}"
                        data-img="{{ $sampul }}"
                        data-pembina="{{ $e->pembina->nama_pembina ?? 'Belum ditentukan' }}"
                        data-pelatih="{{ $e->pelatih->nama_pelatih ?? 'Belum ada' }}"
                        data-ketua="{{ $e->ketua->nama_siswa ?? 'Belum ditentukan' }}"
                        data-anggota="{{ $e->anggota_aktif }}">
                        <div class="cover">
                            @if($sampul)
                                <img src="{{ $sampul }}" alt="Kegiatan {{ $e->nama_ekskul }}" loading="lazy">
                            @else
                                <span class="ph" aria-hidden="true">{{ strtoupper(mb_substr($e->nama_ekskul, 0, 1)) }}</span>
                            @endif
                            <span class="tag k-{{ $e->kategori }}">{{ $labelKat }}</span>
                        </div>
                        <div class="card-body">
                            <h3>{{ $e->nama_ekskul }}</h3>
                            <p class="desc">{{ $e->deskripsi ?: 'Deskripsi belum ditambahkan.' }}</p>
                            <p class="count"><span><b>{{ $e->anggota_aktif }}</b> anggota</span><span class="go">Detail &rarr;</span></p>
                        </div>
                    </article>
                @empty
                    <p class="empty">Belum ada ekskul. Data akan tampil di sini setelah admin menambahkannya.</p>
                @endforelse
            </div>

            @if($ekskuls->isNotEmpty())
            <p class="empty" id="ek-none" hidden>Tidak ada ekskul yang cocok dengan pencarianmu. Coba kata kunci atau kategori lain.</p>
            <div class="more-wrap">
                <p class="more-info" id="ek-info" aria-live="polite"></p>
                <button type="button" class="btn btn-soft" id="ek-more" hidden></button>
            </div>
            @endif
        </div>
    </section>

    {{-- ================= PEMBINA ================= --}}
    @if($pembina->isNotEmpty())
    <section class="sec" id="pembina" style="padding-top:0">
        <div class="wrap">
            <h2>Pembina ekskul</h2>
            <p class="lead">Guru yang mendampingi dan bertanggung jawab atas kegiatan ekskul.</p>
            <div class="plist" id="pb-grid">
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
            <div class="more-wrap">
                <p class="more-info" id="pb-info" aria-live="polite"></p>
                <button type="button" class="btn btn-soft" id="pb-more" hidden></button>
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
                        <a class="btn btn-soft btn-flat" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ $q }}">Buka di Google Maps</a>
                    </div>
                </div>
                <div class="map">
                    <iframe title="Peta {{ $sekolah['nama'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                        src="https://www.google.com/maps?q={{ $q }}&output=embed"></iframe>
                </div>
            </div>
        </div>
    </section>
    </div>
</main>

<footer>
    <div class="wrap">
        <div class="ft-grid">
            {{-- Kolom 1: identitas sekolah --}}
            <div class="ft-col-brand">
                <div class="ft-brand">
                    <img src="{{ $logo }}" alt="Logo {{ $sekolah['nama'] }}">
                    <div>
                        <h3>{{ $sekolah['singkat'] }}</h3>
                        <p>{{ $sekolah['tagline'] }}</p>
                    </div>
                </div>
                <p class="ft-desc">{{ $sekolah['deskripsi'] }}</p>
                <div class="ft-social" aria-label="Media sosial">
                    <a href="{{ $sekolah['sosmed']['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="{{ $sekolah['sosmed']['tiktok'] }}" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>
                    <a href="{{ $sekolah['sosmed']['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                    <a href="{{ $sekolah['sosmed']['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                </div>
            </div>

            {{-- Kolom 2: tautan cepat (mengarah ke bagian yang ada di halaman ini) --}}
            <nav aria-label="Tautan cepat">
                <h4>Quick Links</h4>
                <ul class="ft-links">
                    <li><a href="#beranda">Home</a></li>
                    <li><a href="#sekolah">Profil Sekolah</a></li>
                    <li><a href="#ekskul">Ekstrakurikuler</a></li>
                    <li><a href="#galeri">Galeri</a></li>
                    <li><a href="#lokasi">Kontak</a></li>
                </ul>
            </nav>

            {{-- Kolom 3: kontak --}}
            <div>
                <h4>Kontak Kami</h4>
                <ul class="ft-ct">
                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 119.5 9 2.5 2.5 0 0112 11.5z"/></svg>
                        <span>{{ $sekolah['alamat_footer'] }}</span>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.36 11.36 0 003.58.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.58a1 1 0 01-.25 1.02l-2.2 2.19z"/></svg>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $sekolah['telepon']) }}">{{ $sekolah['telepon'] }}</a>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:{{ $sekolah['email'] }}">{{ $sekolah['email'] }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <p class="ft-bar">&copy; {{ date('Y') }} <b>{{ $sekolah['singkat'] }}.</b> All rights reserved.</p>
    </div>
</footer>

{{-- Pop-up detail ekskul (diisi lewat JS dari atribut data-* pada kartu) --}}
<dialog class="dlg" id="ek-dlg" aria-labelledby="dlg-title">
    <button type="button" class="dlg-x" aria-label="Tutup"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
    <div class="dlg-cover">
        <img id="dlg-img" alt="" hidden>
        <span class="ph" id="dlg-ph" aria-hidden="true"></span>
        <span class="tag" id="dlg-tag"></span>
    </div>
    <div class="dlg-body">
        <h3 id="dlg-title"></h3>
        <p class="dlg-desc" id="dlg-desc"></p>
        <dl class="meta">
            <div><dt>Pembina</dt><dd id="dlg-pembina"></dd></div>
            <div><dt>Pelatih</dt><dd id="dlg-pelatih"></dd></div>
            <div><dt>Ketua</dt><dd id="dlg-ketua"></dd></div>
        </dl>
        <p class="count"><span><b id="dlg-n">0</b> anggota aktif</span></p>
    </div>
</dialog>

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

    /* ===== Pager: batasi kartu yang tampil supaya halaman tidak memanjang =====
       Jumlah awal = jumlah kolom x 3 baris (minimal 6), lalu tombol menambah per batch yang sama. */
    const makePager = ({ grid, items, btn, info, none, unit, rows = 3, min = 6 }) => {
        let pages = 1, pred = () => true;
        const cols = () => getComputedStyle(grid).gridTemplateColumns.split(' ').filter(Boolean).length || 1;
        const step = () => Math.max(min, cols() * rows);
        const render = anim => {
            const lim = step() * pages; let seen = 0, shown = 0;
            items.forEach(el => {
                const vis = pred(el) && seen++ < lim;
                if (vis) shown++;
                if (vis && el.hidden && anim) { el.classList.remove('enter'); void el.offsetWidth; el.classList.add('enter'); }
                el.hidden = !vis;
            });
            if (none) none.hidden = seen > 0;
            if (info) info.textContent = seen ? `Menampilkan ${shown} dari ${seen} ${unit}` : '';
            if (shown < seen) { btn.hidden = false; btn.dataset.mode = 'more'; btn.textContent = `Tampilkan lebih banyak (${seen - shown} lagi)`; }
            else if (seen > step()) { btn.hidden = false; btn.dataset.mode = 'less'; btn.textContent = 'Tampilkan lebih sedikit'; }
            else btn.hidden = true;
        };
        btn.addEventListener('click', () => {
            if (btn.dataset.mode === 'more') { pages++; render(true); }
            else { pages = 1; render(); (grid.closest('section') || grid).scrollIntoView({ block: 'start' }); }
        });
        addEventListener('resize', () => render());
        render();
        return {
            filter(fn) { pred = fn; pages = 1; render(); },
            reveal(el) { pred = () => true; pages = Math.max(pages, Math.ceil((items.indexOf(el) + 1) / step())); render(); }
        };
    };

    /* Pembina */
    const pbGrid = document.getElementById('pb-grid');
    if (pbGrid) makePager({ grid: pbGrid, items: [...pbGrid.querySelectorAll('.person')], btn: document.getElementById('pb-more'), info: document.getElementById('pb-info'), unit: 'pembina' });

    /* Ekskul: pencarian + filter kategori + pager */
    const ekGrid = document.getElementById('ek-grid');
    const ekItems = ekGrid ? [...ekGrid.querySelectorAll('.card')] : [];
    const chips = [...document.querySelectorAll('.chip')], ekQ = document.getElementById('ek-q');
    let ekPager = null, ekCat = 'all', ekTerm = '';
    if (ekItems.length) {
        ekItems.forEach(c => { const d = c.dataset; d.q = [d.nama, d.tag, d.pembina, d.pelatih, d.ketua].join(' ').toLowerCase(); });
        ekPager = makePager({ grid: ekGrid, items: ekItems, btn: document.getElementById('ek-more'), info: document.getElementById('ek-info'), none: document.getElementById('ek-none'), unit: 'ekskul' });
        const apply = () => ekPager.filter(c => (ekCat === 'all' || c.dataset.kat === ekCat) && (!ekTerm || c.dataset.q.includes(ekTerm)));
        chips.forEach(c => c.addEventListener('click', () => {
            ekCat = c.dataset.f;
            chips.forEach(x => x.setAttribute('aria-pressed', x === c));
            apply();
        }));
        ekQ?.addEventListener('input', () => { ekTerm = ekQ.value.trim().toLowerCase(); apply(); });
    }

    /* Pop-up detail ekskul */
    const dlg = document.getElementById('ek-dlg');
    if (dlg && ekItems.length) {
        const $ = id => document.getElementById(id);
        const openDetail = c => {
            const d = c.dataset, img = $('dlg-img'), ph = $('dlg-ph'), tag = $('dlg-tag');
            $('dlg-title').textContent = d.nama;
            $('dlg-desc').textContent = d.desc || 'Deskripsi belum ditambahkan.';
            $('dlg-pembina').textContent = d.pembina;
            $('dlg-pelatih').textContent = d.pelatih;
            $('dlg-ketua').textContent = d.ketua;
            $('dlg-n').textContent = d.anggota;
            tag.textContent = d.tag; tag.className = 'tag k-' + d.kat;
            if (d.img) { img.src = d.img; img.alt = 'Kegiatan ' + d.nama; img.hidden = false; ph.hidden = true; }
            else { img.hidden = true; img.removeAttribute('src'); ph.hidden = false; ph.textContent = d.nama.charAt(0).toUpperCase(); }
            dlg.showModal();
            dlg.querySelector('.dlg-body').scrollTop = 0;
        };
        ekItems.forEach(c => {
            c.addEventListener('click', () => openDetail(c));
            c.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openDetail(c); } });
        });
        dlg.addEventListener('click', e => { if (e.target === dlg) dlg.close(); });
        dlg.querySelector('.dlg-x').addEventListener('click', () => dlg.close());
    }

    /* ===== Carousel ekskul (coverflow) =====
       - Saat halaman dibuka, kartu bertumpuk lalu menyebar pelan selama INTRO ms.
       - Saat di-scroll ke bawah, kartu melipat kembali jadi satu kartu sambil mundur ke belakang
         (mengecil, miring, dan memudar), mengikuti posisi scroll (scroll ke atas = maju & menyebar lagi).
       - Panah next/prev menggeser semua kartu satu posisi (kartu paling kiri pindah ke paling kanan). */
    const cf = document.getElementById('cf');
    if (cf) {
        const INTRO = 1800;                       /* durasi menyebar (ms) */
        const RECEDE = 900, TILT = 12;            /* efek mundur saat scroll: jarak ke belakang (px) & kemiringan (derajat) */
        const hero = document.getElementById('beranda');
        const cards = [...cf.querySelectorAll('.cf-card')], n = cards.length;
        const cap = document.getElementById('cf-cap');
        const XD = [0, 1.04, 1.9, 2.6], XM = [0, .8, 1.4, 1.9], R = [0, 26, 34, 40], Z = [0, -90, -180, -260], S = [1, .86, .72, .6], B = [1, .62, .45, .35];
        const curve = (arr, a) => { const i = Math.min(Math.floor(a), 2), t = Math.min(a, 3) - i; return arr[i] + (arr[i + 1] - arr[i]) * t; };
        const easeIO = t => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
        const idx = () => ((Math.round(target) % n) + n) % n;

        let target = 0, pos = 0, spread = reduce ? 1 : 0, fold = 0, t0 = Infinity, raf = 0, last = 0;

        const offset = i => { let d = i - pos; return d - n * Math.round(d / n); };

        const draw = () => {
            const F = easeIO(spread) * (1 - fold), w = cards[0].offsetWidth, X = innerWidth < 700 ? XM : XD;
            cards.forEach((el, i) => {
                const d = offset(i), a = Math.abs(d), sg = Math.sign(d);
                el.style.transform = `translate3d(${(sg * curve(X, a) * w * F).toFixed(1)}px,0,${(curve(Z, a) * F - RECEDE * fold).toFixed(1)}px) rotateX(${(TILT * fold).toFixed(2)}deg) rotateY(${(-sg * curve(R, a) * F).toFixed(2)}deg) scale(${(1 + (curve(S, a) - 1) * F).toFixed(4)})`;
                el.style.opacity = (a <= 2 ? 1 : Math.max(0, 3 - a)) * (1 - .7 * fold);
                el.style.filter = `brightness(${((1 + (curve(B, a) - 1) * F) * (1 - .35 * fold)).toFixed(3)})`;
                el.style.zIndex = Math.max(0, Math.round(10 - a * 3));
                el.style.pointerEvents = (a > 2 || F < .9) ? 'none' : 'auto';
                el.classList.toggle('on', a < .5);
            });
            const o = Math.min(1, Math.max(0, (F - .7) / .3));
            hero.style.setProperty('--o', o.toFixed(3));
            hero.classList.toggle('folded', o < .5);
        };

        const frame = ts => {
            raf = 0;
            const dt = Math.min(ts - (last || ts), 50); last = ts;
            let busy = false;
            if (spread < 1) { spread = Math.min(1, Math.max(0, (ts - t0) / INTRO)); busy = true; }
            const diff = target - pos;
            if (Math.abs(diff) > .001) { pos += diff * (1 - Math.exp(-dt / 110)); busy = true; } else pos = target;
            draw();
            if (busy) raf = requestAnimationFrame(frame); else last = 0;
        };
        const kick = () => { if (!raf) raf = requestAnimationFrame(frame); };

        const caption = () => {
            const c = cards[idx()];
            cap.classList.add('swap');
            setTimeout(() => {
                document.getElementById('cf-title').textContent = c.dataset.nama;
                document.getElementById('cf-sub').textContent = c.dataset.sub;
                document.getElementById('cf-go').setAttribute('href', c.dataset.href);
                const now = document.getElementById('cf-now'); if (now) now.textContent = idx() + 1;
                cap.classList.remove('swap');
            }, 240);
        };
        const go = delta => { if (n < 2 || !delta) return; target += delta; caption(); kick(); };

        draw();                                             /* posisi awal: semua kartu menumpuk */
        if (!reduce) {
            const imgs = [...cf.querySelectorAll('img')].slice(0, 5).map(i => i.decode ? i.decode().catch(() => {}) : Promise.resolve());
            Promise.race([Promise.all(imgs), new Promise(r => setTimeout(r, 1200))]).then(() => { t0 = performance.now() + 250; kick(); });

            const onScroll = () => {
                const f = Math.min(1, Math.max(0, scrollY / (hero.offsetHeight * .6)));
                if (f !== fold) { fold = f; kick(); }
            };
            addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }
        addEventListener('resize', kick);

        document.getElementById('cf-next')?.addEventListener('click', () => go(1));
        document.getElementById('cf-prev')?.addEventListener('click', () => go(-1));
        cf.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
        });
        let x0 = null, swiped = false;
        cf.addEventListener('pointerdown', e => { x0 = e.clientX; swiped = false; });
        cf.addEventListener('pointerup', e => {
            if (x0 === null) return;
            const dx = e.clientX - x0; x0 = null;
            if (Math.abs(dx) > 50) { swiped = true; go(dx < 0 ? 1 : -1); }
        });
        cards.forEach((el, i) => el.addEventListener('click', () => { if (!swiped) go(Math.round(offset(i))); }));

        /* "Lihat detail": buka bagian ekskul, pastikan kartunya tampil (reset filter/pencarian), lalu sorot */
        document.getElementById('cf-go')?.addEventListener('click', e => {
            e.preventDefault();
            const t = document.querySelector(document.getElementById('cf-go').getAttribute('href'));
            if (!t) return;
            if (ekPager) {
                ekCat = 'all'; ekTerm = ''; if (ekQ) ekQ.value = '';
                chips.forEach(x => x.setAttribute('aria-pressed', x.dataset.f === 'all'));
                ekPager.reveal(t);
            }
            t.scrollIntoView({ block: 'center' });
            t.classList.remove('flash'); void t.offsetWidth; t.classList.add('flash');
        });
    }

    /* ===== Efek muncul saat scroll: tiap elemen memudar & bergeser masuk begitu terlihat ===== */
    if (!reduce && 'IntersectionObserver' in window) {
        const groups = [
            ['up',    'section.sec h2, section.sec .lead, .toolbar, .empty, footer .wrap'],
            ['zoom',  '.stats, .band, .gal-panel'],
            ['left',  '.school .video, .mapgrid .addr'],
            ['right', '.school .school-info, .mapgrid .map']
        ];
        const els = [];
        groups.forEach(([type, sel]) => document.querySelectorAll(sel).forEach(el => {
            el.classList.add('reveal'); el.dataset.r = type; els.push(el);
        }));
        els.forEach(el => {          /* jeda bertahap antar elemen bersaudara (mis. kartu dalam satu baris) */
            const sib = [...el.parentElement.children].filter(c => c.classList.contains('reveal'));
            el.style.setProperty('--rd', (sib.indexOf(el) % 4) * 90 + 'ms');
        });
        const io = new IntersectionObserver(es => es.forEach(e => {
            const el = e.target;
            if (e.isIntersecting) {
                io.unobserve(el);
                el.classList.add('in');
                /* selesai animasi -> lepas kelas supaya efek hover bawaan elemen kembali normal */
                setTimeout(() => el.classList.remove('reveal', 'in'), 1300 + parseInt(el.style.getPropertyValue('--rd')) || 1300);
            } else if (e.boundingClientRect.top < 0) {   /* sudah terlewat (mis. halaman dibuka di tengah) */
                io.unobserve(el); el.classList.remove('reveal');
            }
        }), { threshold: .12, rootMargin: '0px 0px -6% 0px' });
        els.forEach(el => io.observe(el));
    }

    /* ===== Lipat / buka sidebar (status disimpan di localStorage) ===== */
    const rail = document.getElementById('rail'), fab = document.getElementById('rail-fab'), htmlEl = document.documentElement;
    const setFold = (f, save = true) => {
        htmlEl.classList.toggle('rail-folded', f);
        rail.toggleAttribute('inert', f);
        rail.setAttribute('aria-hidden', f);
        fab.toggleAttribute('inert', !f);
        fab.setAttribute('aria-expanded', !f);
        if (save) { try { localStorage.setItem('rail-folded', f ? '1' : '0'); } catch (e) {} }
    };
    setFold(htmlEl.classList.contains('rail-folded'), false);
    document.getElementById('rail-fold')?.addEventListener('click', () => { setFold(true); fab.focus({ preventScroll: true }); });
    fab.addEventListener('click', () => setFold(false));

    /* Tombol sidebar aktif mengikuti bagian halaman yang sedang dilihat */
    const rl = [...document.querySelectorAll('.rail a[href^="#"]')];
    const spy = new IntersectionObserver(es => es.forEach(e => {
        if (!e.isIntersecting) return;
        rl.forEach(a => a.getAttribute('href') === '#' + e.target.id ? a.setAttribute('aria-current', 'page') : a.removeAttribute('aria-current'));
    }), { rootMargin: '-45% 0px -50% 0px' });
    rl.forEach(a => { const t = document.querySelector(a.getAttribute('href')); if (t) spy.observe(t); });

    /* Galeri bertumpuk: foto paling depan terlempar keluar, foto di belakangnya maju satu layer */
    const stack = document.getElementById('stack');
    if (!stack) return;
    const cards = [...stack.querySelectorAll('.g-card')], n = cards.length;
    const txt = document.getElementById('g-txt');
    let cur = 0, busy = false;
    const OUT = 'translate3d(-120%,-8%,0) rotate(-14deg)';

    const place = (c, off) => {
        const s = 1 - off * .06, side = off % 2 ? 1 : -1, k = Math.max(.35, Math.min(1, stack.offsetWidth / 520));
        c.style.transform = off > 3
            ? 'translate3d(0,0,0) scale(.8)'
            : `translate3d(${(off * 28 * k).toFixed(1)}px,${(off * -9 * k).toFixed(1)}px,0) rotate(${side * off * 2.2}deg) scale(${s})`;
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
    addEventListener('resize', () => { if (!busy) render(); });
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