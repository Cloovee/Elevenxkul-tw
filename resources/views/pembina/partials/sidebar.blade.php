{{--
    Sidebar mengambang yang dipakai di semua halaman pembina.
    Gunakan: @include('pembina.partials.sidebar', ['active' => 'dashboard'])
    Nilai $active: dashboard | absensi | validasi | nilai | profile
--}}
@php
    $navItem = function (string $key, string $route, string $title, string $icon) use ($active) {
        $isActive = $active === $key;
        return compact('route', 'title', 'icon', 'isActive');
    };
@endphp

<aside class="hidden md:flex w-[84px] shrink-0 sticky top-5 h-[calc(100vh-40px)] rounded-3xl
              bg-gradient-to-b from-lavender to-periwinkle
              flex-col items-center py-6 gap-2.5
              shadow-[0_18px_40px_-14px_rgba(94,92,199,0.55)]">

    @php($sidebarPembina = auth()->user()?->pembina)
    <a href="{{ route('pembina.profile.index') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center overflow-hidden transition-all mb-2
              {{ $active === 'profile' ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Profil Saya">
        @if($sidebarPembina?->foto_url)
            <img src="{{ $sidebarPembina->foto_url }}" class="w-full h-full object-cover" alt="Foto Profil">
        @else
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
        @endif
    </a>

    <div class="w-8 h-px bg-white/25 my-1"></div>

    <a href="{{ route('pembina.dashboard') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ $active === 'dashboard' ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
    </a>

    <a href="{{ route('pembina.absensi.index') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ in_array($active, ['absensi', 'validasi']) ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Absensi &amp; Validasi">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.3"/><polyline points="21 3 21 9 15 9"/></svg>
    </a>

    <a href="{{ route('pembina.nilai.index') }}"
       class="w-[46px] h-[46px] rounded-2xl flex items-center justify-center transition-all
              {{ $active === 'nilai' ? 'bg-white text-lavender' : 'text-white/75 hover:bg-white/20 hover:text-white' }}"
       title="Nilai Peserta">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
    </a>

    <div class="flex-1"></div>

    <form method="POST" action="{{ url('/logout') }}">
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
    <a href="{{ route('pembina.profile.index') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ $active === 'profile' ? 'bg-white text-lavender' : 'text-white/75' }}" title="Profil">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
    </a>
    <a href="{{ route('pembina.dashboard') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ $active === 'dashboard' ? 'bg-white text-lavender' : 'text-white/75' }}" title="Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
    </a>
    <a href="{{ route('pembina.absensi.index') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ in_array($active, ['absensi', 'validasi']) ? 'bg-white text-lavender' : 'text-white/75' }}" title="Absensi & Validasi">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.3"/><polyline points="21 3 21 9 15 9"/></svg>
    </a>
    <a href="{{ route('pembina.nilai.index') }}"
       class="w-11 h-11 rounded-2xl flex items-center justify-center transition-all {{ $active === 'nilai' ? 'bg-white text-lavender' : 'text-white/75' }}" title="Nilai">
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
    </a>
    <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/75 hover:bg-white/20 hover:text-white transition-all" title="Keluar">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
    </form>
</nav>