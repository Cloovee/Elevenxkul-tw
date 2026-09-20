{{--
    Sidebar rail role KETUA.
    Format ini dipakai juga oleh role admin & pembina supaya seragam.

    Catatan: posisi "fixed" ditulis sebagai CSS mentah (bukan class Tailwind)
    supaya tidak bergantung pada proses build/compile Tailwind — jadi selalu
    aktif walau aset belum di-rebuild.
--}}
<style>
    @media (min-width: 1024px) {
        .ekk-sidebar-rail {
            position: fixed;
            top: 2rem;      /* selaras dengan py-8 pada wrapper halaman */
            left: 2.5rem;   /* selaras dengan lg:px-10 pada wrapper halaman */
            width: 5rem;    /* setara w-20 */
            min-height: 33.75rem; /* setara min-h-[540px] */
            z-index: 30;
        }
    }
</style>

<aside class="lg:w-20 shrink-0">
    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3
                flex lg:flex-col items-center gap-2 overflow-x-auto lg:overflow-visible
                shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20">

        <a href="{{ route('dashboard.ketua') }}"
           class="w-11 h-11 shrink-0 rounded-2xl bg-white flex items-center justify-center font-bold text-periwinkle lg:mb-4 shadow-md">
            K
        </a>

        <a href="{{ route('dashboard.ketua') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('dashboard.ketua') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </a>

        <a href="{{ route('ketua.absensi-pelatih') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.absensi-pelatih') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Absensi Pelatih">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

        <a href="{{ route('ketua.absensi-peserta') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.absensi-peserta') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Absensi Peserta">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
            </svg>
        </a>

        <a href="{{ route('ketua.kelola-anggota') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.kelola-anggota*') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Kelola Anggota">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('profile.edit') ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Profil">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="lg:mt-auto shrink-0">
            @csrf
            <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Keluar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</aside>
