{{--
    Sidebar rail role KETUA (dipakai juga oleh halaman profil).

    - Posisi FIXED terkunci ke viewport (top/left/bottom), tidak ikut scroll dan
      tidak bisa digeser. Tinggi = 100% layar dikurangi margin atas-bawah (top + bottom
      sekaligus), jadi selalu mengikuti ukuran layar.
    - Top/left/bottom di CSS harus SELALU sama dengan padding wrapper halaman ketua:
      py-8 (2rem) dan lg:px-10 (2.5rem).
    - Hover (>= lg): rail melebar 5rem -> 17rem menampilkan nama tombol, dan halaman
      di belakangnya di-blur lewat .ekk-sidebar-backdrop. Lebar <aside> tetap lg:w-20
      sehingga konten tidak bergeser.
    - CSS ditulis mentah (bukan Tailwind) supaya tidak bergantung pada build.
    - Tambah menu baru: tambahkan satu baris di array $menuItems.
--}}
<style>
    @media (min-width: 1024px) {
        .ekk-sidebar-rail {
            position: fixed;
            top: 2rem;        /* = py-8 pada wrapper halaman ketua */
            bottom: 2rem;     /* = py-8 -> rail selalu full-height mengikuti layar */
            left: 2.5rem;     /* = lg:px-10 pada wrapper halaman ketua */
            width: 5rem;      /* setara w-20 (kondisi tertutup) */
            z-index: 30;
            align-items: stretch;
            overflow-x: hidden;
            overflow-y: auto;            /* hanya jika layar sangat pendek */
            overscroll-behavior: contain;
            scrollbar-width: none;
            user-select: none;
            -webkit-user-select: none;
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

    .ekk-sidebar-rail::-webkit-scrollbar { display: none; }
    .ekk-sidebar-rail a, .ekk-sidebar-rail img { -webkit-user-drag: none; }

    /* Mobile: rail horizontal, label disembunyikan */
    .ekk-nav-ico { width: 2.75rem; height: 2.75rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    @media (max-width: 1023.98px) { .ekk-nav-label { display: none; } }

    /* Jarak antara foto profil (kotak penuh 2.75rem) dan teks di sebelahnya */
    .ekk-nav-label-avatar { margin-left: .75rem; }

    .ekk-nav-link .ekk-nav-icon { transition: transform .2s ease; }
    .ekk-nav-link:hover .ekk-nav-icon { transform: scale(1.15); }

    @media (prefers-reduced-motion: reduce) {
        .ekk-sidebar-rail, .ekk-sidebar-rail *, .ekk-sidebar-backdrop { transition: none !important; }
    }
</style>

@php
    // 'active' = pola nama route untuk menandai tombol aktif
    $menuItems = [
        ['active' => 'dashboard.ketua',        'route' => 'dashboard.ketua',          'label' => 'Dashboard',
         'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['active' => 'ketua.absensi-pelatih',  'route' => 'ketua.absensi-pelatih',    'label' => 'Absensi Pelatih',
         'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['active' => 'ketua.absensi-peserta',  'route' => 'ketua.absensi-peserta',    'label' => 'Absensi Peserta',
         'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4'],
        ['active' => 'ketua.riwayat-absensi',  'route' => 'ketua.riwayat-absensi',    'label' => 'Riwayat Absensi',
         'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['active' => 'ketua.kelola-anggota*',  'route' => 'ketua.kelola-anggota',     'label' => 'Kelola Anggota',
         'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['active' => 'profile.edit',           'route' => 'profile.edit',             'label' => 'Profil',
         'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<aside class="lg:w-20 shrink-0">
    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3
                flex lg:flex-col items-center gap-2 overflow-x-auto lg:overflow-visible
                shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20">

        {{-- Avatar Ketua --}}
        <a href="{{ route('dashboard.ketua') }}" aria-label="Ketua" draggable="false"
           class="ekk-nav-link shrink-0 flex items-center rounded-2xl text-white lg:mb-4 hover:bg-white/15 transition-colors">
            <span class="ekk-nav-ico">
                <span class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center font-bold text-periwinkle shadow-md">K</span>
            </span>
            <span class="ekk-nav-label ekk-nav-label-avatar">
                <span class="ekk-nav-title">Ketua</span>
            </span>
        </a>

        @foreach($menuItems as $item)
            @php
                $isActive = request()->routeIs($item['active']);
            @endphp
            <a href="{{ route($item['route']) }}" draggable="false"
               aria-label="{{ $item['label'] }}"
               @if($isActive) aria-current="page" @endif
               class="ekk-nav-link shrink-0 rounded-2xl flex items-center transition-all {{ $isActive ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}">
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
        <form method="POST" action="{{ route('logout') }}" class="lg:mt-auto shrink-0 flex lg:block">
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
    </div>
    {{-- Backdrop blur: harus SAUDARA (sibling) langsung setelah rail agar bisa dipicu lewat :hover --}}
    <div class="ekk-sidebar-backdrop" aria-hidden="true"></div>
</aside>