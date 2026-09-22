{{--
    Sidebar rail role PEMBINA — format disamakan dengan sidebar ketua.
    Gunakan: @include('pembina.partials.sidebar', ['active' => 'dashboard'])
    Nilai $active: dashboard | absensi | validasi | pelatih | nilai | profile

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
--}}
@php($sidebarPembina = auth()->user()?->pembina)

<style>
    @media (min-width: 1024px) {
        .ekk-sidebar-rail {
            position: fixed;
            top: 1.25rem;     /* = p-5 pada wrapper halaman */
            bottom: 1.25rem;  /* = p-5 pada wrapper halaman -> rail selalu full-height */
            left: 1.25rem;    /* = p-5 pada wrapper halaman */
            width: 5rem;      /* setara w-20 */
            z-index: 30;
        }
    }
</style>

<aside class="lg:w-20 shrink-0">
    <div class="ekk-sidebar-rail bg-periwinkle rounded-3xl p-3
                flex lg:flex-col items-center gap-2 overflow-x-auto lg:overflow-visible
                shadow-2xl shadow-periwinkle/30 ring-1 ring-white/20">

        <a href="{{ route('pembina.profile.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl bg-white flex items-center justify-center overflow-hidden font-bold text-periwinkle lg:mb-4 shadow-md"
           title="Profil Saya">
            @if($sidebarPembina?->foto_url)
                <img src="{{ $sidebarPembina->foto_url }}" class="w-full h-full object-cover" alt="Foto Profil">
            @else
                {{ $sidebarPembina?->inisial ?? 'P' }}
            @endif
        </a>

        <a href="{{ route('pembina.dashboard') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'dashboard' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </a>

        <a href="{{ route('pembina.absensi.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'absensi' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Absensi Peserta">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
            </svg>
        </a>

        <a href="{{ route('pembina.validasi.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'validasi' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Absensi Pelatih">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

        <a href="{{ route('pembina.pelatih.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'pelatih' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Kelola Pelatih">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </a>

        <a href="{{ route('pembina.nilai.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'nilai' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Nilai Peserta">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.518-4.674z" />
            </svg>
        </a>

        <a href="{{ route('pembina.profile.index') }}"
           class="w-11 h-11 shrink-0 rounded-2xl flex items-center justify-center transition-all {{ $active === 'profile' ? 'bg-white text-periwinkle shadow-lg shadow-black/10' : 'text-white/90 hover:bg-white/25 hover:shadow-md' }}"
           title="Profil">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

        <form method="POST" action="{{ url('/logout') }}" class="lg:mt-auto shrink-0">
            @csrf
            <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-white/90 hover:bg-white/25 hover:shadow-md transition-all" title="Keluar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</aside>