{{--
    Sidebar mengambang untuk area Ketua — disamakan dengan gaya sidebar Pembina.
--}}
<aside class="hidden md:flex w-[84px] shrink-0 sticky top-5 h-[calc(100vh-40px)] rounded-3xl
              bg-gradient-to-b from-lavender to-periwinkle
              flex-col items-center py-6 gap-2.5
              shadow-[0_18px_40px_-14px_rgba(94,92,199,0.55)]">

    <a href="{{ route('dashboard.ketua') }}"
       class="w-[46px] h-[46px] rounded-2xl bg-white flex items-center justify-center font-display font-bold text-lavender mb-2 shadow-md">
        K
    </a>

    <div class="w-8 h-px bg-white/25 my-1"></div>

    <a href="{{ route('dashboard.ketua') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ request()->routeIs('dashboard.ketua') ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
    </a>

    <a href="{{ route('ketua.absensi-pelatih') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ request()->routeIs('ketua.absensi-pelatih') ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Absensi Pelatih">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
    </a>

    <a href="{{ route('ketua.absensi-peserta') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ request()->routeIs('ketua.absensi-peserta') ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Absensi Peserta">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </a>

    <a href="{{ route('ketua.kelola-anggota') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ request()->routeIs('ketua.kelola-anggota*') ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Kelola Anggota">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23Z"/></svg>
    </a>

    <a href="{{ route('profile.edit') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ request()->routeIs('profile.edit') ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Profil">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
    </a>

    <div class="flex-1"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center text-white/75 hover:bg-white/20 hover:text-white transition-all mb-1" title="Keluar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
    </form>
</aside>

{{-- ================= BOTTOM NAV (mobile only) ================= --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-gradient-to-r from-lavender to-periwinkle
            flex items-center justify-around px-2 py-2.5
            shadow-[0_-10px_30px_-10px_rgba(94,92,199,0.55)]">
    <a href="{{ route('dashboard.ketua') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('dashboard.ketua') ? 'bg-white text-lavender' : 'text-white/75' }}" title="Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
    </a>
    <a href="{{ route('ketua.absensi-pelatih') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.absensi-pelatih') ? 'bg-white text-lavender' : 'text-white/75' }}" title="Absensi Pelatih">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
    </a>
    <a href="{{ route('ketua.absensi-peserta') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.absensi-peserta') ? 'bg-white text-lavender' : 'text-white/75' }}" title="Absensi Peserta">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </a>
    <a href="{{ route('ketua.kelola-anggota') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('ketua.kelola-anggota*') ? 'bg-white text-lavender' : 'text-white/75' }}" title="Kelola Anggota">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23Z"/></svg>
    </a>
    <a href="{{ route('profile.edit') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('profile.edit') ? 'bg-white text-lavender' : 'text-white/75' }}" title="Profil">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/75 hover:bg-white/20 hover:text-white transition-all" title="Keluar">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
    </form>
</nav>
