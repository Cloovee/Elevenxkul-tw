{{--
    Sidebar rail role PEMBINA — format disamakan dengan sidebar ketua.
    Gunakan: @include('pembina.partials.sidebar', ['active' => 'dashboard'])
    Nilai $active: dashboard | absensi | validasi | pelatih | ketua | nilai | profile

    Catatan: posisi "fixed" ditulis sebagai CSS mentah (bukan class Tailwind)
    supaya tidak bergantung pada proses build/compile Tailwind — jadi selalu
    aktif walau aset belum di-rebuild.

    PENTING soal jarak & floating:
    - Wrapper halaman selalu memakai "flex gap-5 p-5" (gap 1.25rem, padding 1.25rem).
    - Rail di-set fixed persis di top/left 1.25rem (=p-5) supaya sejajar 1:1 dengan
      <aside> placeholder di bawah ini. Kalau nilainya beda (mis. 2rem/2.5rem),
      rail akan "menempel" ke konten karena jarak gap-5 ikut kepakai untuk
      menggeser rail, bukan jadi jarak kosong ke konten. Jaga supaya top/left
      di sini SELALU sama dengan padding wrapper (p-5) di setiap halaman pembina.
    - position: fixed + satuan rem membuat rail terkunci ke viewport: ia tidak
      ikut scroll dan tidak "loncat" posisi saat browser di-zoom, karena zoom
      men-scale seluruh viewport (termasuk konten) secara seragam.
    - Tinggi rail SENGAJA tidak dipatok pakai angka (min-height/height tetap),
      tapi pakai "top" + "bottom" sekaligus supaya browser yang menghitung
      tingginya = 100% tinggi layar dikurangi margin atas-bawah. Jadi rail
      selalu penuh dari atas sampai bawah layar, di ukuran/zoom berapa pun —
      tidak akan pernah menyisakan ruang kosong di bawahnya.

    Hover sidebar (khusus layar >= lg): saat mouse masuk ke rail, rail MELEBAR
    (5rem -> 17rem) menampilkan nama tiap tombol, dan halaman di
    belakangnya di-BLUR lewat elemen .ekk-sidebar-backdrop. Lebar <aside> tetap
    lg:w-20, jadi konten halaman tidak bergeser — rail melebar menimpa konten.
    CSS ditulis mentah (bukan Tailwind) supaya tidak bergantung pada build.
    Untuk menambah menu baru cukup tambahkan satu baris di array $menuItems.
--}}
@php
    $sidebarPembina = auth()->user()?->pembina;
@endphp

<style>
    @media (min-width: 1024px) {
        .ekk-sidebar-rail {
            position: fixed;
            top: 1.25rem;     /* = p-5 pada wrapper halaman */
            bottom: 1.25rem;  /* = p-5 pada wrapper halaman -> rail selalu full-height */
            left: 1.25rem;    /* = p-5 pada wrapper halaman */
            width: 5rem;      /* setara w-20 (kondisi tertutup) */
            z-index: 30;
            align-items: stretch;
            transition: width .32s cubic-bezier(.4, 0, .2, 1), box-shadow .32s ease;
        }
        .ekk-sidebar-rail:hover,
        .ekk-sidebar-rail:focus-within {
            width: 17rem;     /* kondisi melebar */
            box-shadow: 0 30px 60px -15px rgba(31, 32, 80, .55);
        }

        /* ===== Latar belakang blur ===== */
        .ekk-sidebar-backdrop {
            position: fixed;
            inset: 0;
            z-index: 29;
            background: rgba(31, 32, 80, .18);
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none; /* supaya mouse tetap "di dalam" rail & tidak berkedip */
            transition: opacity .3s ease, visibility .3s;
        }
        .ekk-sidebar-rail:hover ~ .ekk-sidebar-backdrop,
        .ekk-sidebar-rail:focus-within ~ .ekk-sidebar-backdrop {
            opacity: 1;
            visibility: visible;
        }

        /* ===== Tombol menu (ikon + label) ===== */
        .ekk-sidebar-rail .ekk-nav-link {
            width: 2.75rem;
            height: 2.75rem;
            margin-left: .375rem;     /* rail inner 3.5rem -> ikon 2.75rem di tengah */
            padding-left: 0;
            justify-content: flex-start;
            overflow: hidden;
            white-space: nowrap;
            text-align: left;
            transition: width .32s cubic-bezier(.4, 0, .2, 1), margin-left .32s cubic-bezier(.4, 0, .2, 1),
                        padding-left .32s cubic-bezier(.4, 0, .2, 1), background-color .2s ease, box-shadow .2s ease;
        }
        .ekk-sidebar-rail:hover .ekk-nav-link,
        .ekk-sidebar-rail:focus-within .ekk-nav-link {
            width: 100%;
            margin-left: 0;
            padding-left: .375rem;    /* ikon tetap di posisi yang sama saat melebar */
        }

        .ekk-nav-label {
            display: block;
            min-width: 0;
            padding-right: .75rem;
            opacity: 0;
            transform: translateX(-8px);
            transition: opacity .2s ease, transform .25s ease;
        }
        .ekk-sidebar-rail:hover .ekk-nav-label,
        .ekk-sidebar-rail:focus-within .ekk-nav-label {
            opacity: 1;
            transform: translateX(0);
            transition-delay: .12s;   /* muncul setelah rail cukup lebar */
        }
        .ekk-nav-title { display: block; font-size: .92rem; font-weight: 600; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; }
    }

    /* Mobile: rail horizontal, label disembunyikan */
    .ekk-nav-ico { width: 2.75rem; height: 2.75rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    @media (max-width: 1023.98px) { .ekk-nav-label { display: none; } }

    .ekk-nav-link .ekk-nav-icon { transition: transform .2s ease; }
    .ekk-nav-link:hover .ekk-nav-icon { transform: scale(1.15); }

    @media (prefers-reduced-motion: reduce) {
        .ekk-sidebar-rail, .ekk-sidebar-rail *, .ekk-sidebar-backdrop { transition: none !important; }
    }
</style>

@php
    // key = nilai $active, label = nama tombol
    $menuItems = [
        ['key' => 'dashboard', 'route' => 'pembina.dashboard',       'label' => 'Dashboard',
         'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['key' => 'absensi',   'route' => 'pembina.absensi.index',   'label' => 'Absensi Peserta',
         'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['key' => 'validasi',  'route' => 'pembina.validasi.index',  'label' => 'Absensi Pelatih',
         'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['key' => 'pelatih',   'route' => 'pembina.pelatih.index',   'label' => 'Kelola Pelatih',
         'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['key' => 'ketua',     'route' => 'pembina.ketua.index',     'label' => 'Kelola Ketua',
         'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['key' => 'nilai',     'route' => 'pembina.nilai.index',     'label' => 'Nilai Peserta',
         'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        ['key' => 'profile',   'route' => 'pembina.profile.index',   'label' => 'Profil',
         'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<aside class="lg:w-20 shrink-0">
    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3
                flex lg:flex-col items-center gap-2 overflow-x-auto lg:overflow-visible
                shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20">

        {{-- Avatar / Profil Saya --}}
        <a href="{{ route('pembina.profile.index') }}" aria-label="Profil Saya"
           class="ekk-nav-link shrink-0 flex items-center rounded-2xl text-white lg:mb-4 hover:bg-white/15 transition-colors">
            <span class="ekk-nav-ico">
                <span class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center overflow-hidden font-bold text-periwinkle shadow-md">
                    @if($sidebarPembina?->foto_url)
                        <img src="{{ $sidebarPembina->foto_url }}" class="w-full h-full object-cover" alt="Foto Profil">
                    @else
                        {{ $sidebarPembina?->inisial ?? 'P' }}
                    @endif
                </span>
            </span>
            <span class="ekk-nav-label">
                <span class="ekk-nav-title">Profil Saya</span>
            </span>
        </a>

        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}"
               aria-label="{{ $item['label'] }}"
               @if($active === $item['key']) aria-current="page" @endif
               class="ekk-nav-link shrink-0 rounded-2xl flex items-center transition-all {{ $active === $item['key'] ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}">
                <span class="ekk-nav-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ekk-nav-icon w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                </span>
                <span class="ekk-nav-label">
                    <span class="ekk-nav-title">{{ $item['label'] }}</span>
                </span>
            </a>
        @endforeach

        {{-- Keluar --}}
        <form method="POST" action="{{ url('/logout') }}" class="lg:mt-auto shrink-0 flex lg:block">
            @csrf
            <button type="submit" aria-label="Keluar"
                    class="ekk-nav-link rounded-2xl flex items-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all lg:w-full">
                <span class="ekk-nav-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ekk-nav-icon w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </span>
                <span class="ekk-nav-label">
                    <span class="ekk-nav-title">Keluar</span>
                </span>
            </button>
        </form>

        {{-- Backdrop blur: harus SAUDARA (sibling) langsung setelah rail agar bisa dipicu lewat :hover --}}
    </div>
    <div class="ekk-sidebar-backdrop" aria-hidden="true"></div>
</aside>