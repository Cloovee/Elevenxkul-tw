<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/smkn11logo.png') }}" type="image/png">
    <title>Profil Pembina — ElevenXkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes profIn {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .prof-in {
            opacity: 0;
            animation: profIn .55s cubic-bezier(.16,.84,.44,1) forwards;
            animation-delay: var(--d, 0ms);
        }
        .field-icon svg { pointer-events: none; }
        .pw-toggle:focus-visible,
        .field-input:focus-visible { outline: none; }
        @media (prefers-reduced-motion: reduce) {
            .prof-in { animation: none !important; opacity: 1 !important; }
        }
    </style>
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(174,226,255,0.35), transparent 45%), radial-gradient(circle at 0% 100%, rgba(217,249,223,0.4), transparent 40%);">

<div class="flex gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'profile'])

    <main class="flex-1 min-w-0 flex flex-col gap-5 pb-24 md:pb-0">

        @if (session('success'))
            <div class="prof-in flex items-start gap-3 bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl" style="--d:0ms">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HEADER --}}
        @php
            $adaFoto   = (bool) ($pembina?->foto_url);
            $adaHp     = trim((string) ($pembina->nomor_hp ?? '')) !== '';
            $adaAlamat = trim((string) ($pembina->alamat ?? '')) !== '';
            $totalIsi  = (int) $adaFoto + (int) $adaHp + (int) $adaAlamat;
            $persen    = (int) round(($totalIsi / 3) * 100);
            $keliling  = round(2 * pi() * 15.5, 1); // ~97.4
            $offset    = round($keliling * (1 - $persen / 100), 1);
        @endphp
        <div class="prof-in flex items-center justify-between flex-wrap gap-3" style="--d:0ms">
            <div>
                <span class="text-[11px] font-bold tracking-[0.14em] uppercase text-lavender">Pengaturan Akun</span>
                <h1 class="font-display text-2xl md:text-[26px] font-bold tracking-tight leading-tight mt-0.5">Profil Saya</h1>
                <p class="text-inksoft text-sm mt-1">Data diri kamu ditampilkan di bawah. Kamu hanya bisa mengubah nomor HP dan alamat; data lainnya diatur oleh admin.</p>
            </div>

            <div class="flex items-center gap-3 bg-white rounded-2xl pl-3.5 pr-5 py-2.5 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
                <div class="relative w-10 h-10 shrink-0">
                    <svg viewBox="0 0 36 36" class="w-10 h-10 -rotate-90">
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="#EFEFF7" stroke-width="4"></circle>
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="currentColor" stroke-width="4"
                                stroke-linecap="round" class="text-lavender transition-all duration-700"
                                stroke-dasharray="{{ $keliling }}" stroke-dashoffset="{{ $offset }}"></circle>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-[9px] font-bold text-lavender">{{ $persen }}%</span>
                </div>
                <div class="text-xs leading-tight">
                    <div class="font-semibold text-ink">{{ $persen == 100 ? 'Profil lengkap' : 'Profil belum lengkap' }}</div>
                    <div class="text-inksoft">{{ $totalIsi }} dari 3 data terisi</div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- ================= KIRI: FORM ================= --}}
            <div class="order-2 lg:order-1 w-full lg:mr-[380px] flex flex-col gap-5">

                {{-- Ekskul yang dibina (tag -> halaman penilaian) --}}
                <div class="prof-in bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:30ms">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-mint text-[#1F7A3D] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82Z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold">Ekskul yang Dibina</h2>
                            <p class="text-xs text-inksoft mt-0.5">Klik salah satu untuk membuka halaman penilaian ekskul tersebut</p>
                        </div>
                    </div>

                    @if ($ekskuls->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($ekskuls as $e)
                                <a href="{{ route('pembina.nilai.index', ['ekskul' => $e->id_ekskul]) }}"
                                   title="Buka penilaian {{ $e->nama_ekskul }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-periwinkle/30 text-[#3F41B0] text-xs font-bold hover:bg-lavender hover:text-white transition-colors">
                                    {{ $e->nama_ekskul }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-inksoft bg-bgsoft rounded-2xl px-4 py-3">Kamu belum ditugaskan membina ekskul manapun. Hubungi admin untuk dikaitkan ke sebuah ekskul.</p>
                    @endif
                </div>

                {{-- Data diri (diisi oleh admin, hanya tampilan) --}}
                @php
                    $dataDiri = [
                        ['Nama Lengkap',   $pembina->nama_pembina ?? $user->name],
                        ['Jenis Kelamin',  match ($pembina->jk ?? null) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => null }],
                        ['Agama',          $pembina->agama ?? null],
                        ['Nomor HP',       $pembina->nomor_hp ?? null],
                        ['Email',          $pembina->email ?? $user->email],
                        ['Username',       $user->username ?? null],
                        ['Media Sosial',   $pembina->medsos ?? null],
                        ['Alamat',         $pembina->alamat ?? null],
                    ];
                @endphp
                <div class="prof-in bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:45ms">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-2xl bg-sky text-[#1E6FA8] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M15 8h2"/><path d="M15 12h2"/><path d="M7 16h10"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold">Data Diri</h2>
                            <p class="text-xs text-inksoft mt-0.5">Diisi oleh admin saat akun pembina dibuat</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($dataDiri as [$label, $nilai])
                            <div class="bg-bgsoft rounded-2xl px-4 py-3 {{ $label === 'Alamat' ? 'sm:col-span-2' : '' }}">
                                <div class="text-[11px] font-bold text-inksoft uppercase tracking-wide">{{ $label }}</div>
                                <div class="text-sm font-semibold text-ink mt-0.5 break-words">{{ filled($nilai) ? $nilai : '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Informasi Akun --}}
                <div class="prof-in bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:60ms">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-2xl bg-periwinkle/30 text-[#3F41B0] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold">Ubah Kontak</h2>
                            <p class="text-xs text-inksoft mt-0.5">Nomor HP & alamat bisa kamu perbarui sendiri</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pembina.profile.update') }}" class="flex flex-col gap-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nomor HP</label>
                                <div class="relative" style="position:relative;">
                                    <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </span>
                                    <input type="text" name="nomor_hp" placeholder="08xx-xxxx-xxxx" value="{{ old('nomor_hp', $pembina->nomor_hp ?? '') }}"
                                           class="field-input w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:14px;">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Alamat</label>
                                <div class="relative" style="position:relative;">
                                    <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </span>
                                    <input type="text" name="alamat" placeholder="Kota, provinsi" value="{{ old('alamat', $pembina->alamat ?? '') }}"
                                           class="field-input w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:14px;">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-1 pt-4 border-t border-[#EFEFF7]">
                            <p class="text-xs text-inksoft">Perubahan tersimpan otomatis ke akunmu.</p>
                            <button type="submit"
                                    class="bg-lavender text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:bg-[#8385f0] hover:-translate-y-0.5 transition-all shrink-0">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- ================= KANAN: WIDGET FIKS — foto + nama ================= --}}
            <div class="order-1 lg:order-2 w-full lg:w-[360px] lg:fixed lg:top-5 lg:right-5 lg:z-20">
                <div class="prof-in relative bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] overflow-hidden" style="--d:0ms">

                    <div class="flex flex-col items-center text-center px-8 pt-8 pb-8">

                        {{-- Foto — tampilan saja, tidak bisa diubah oleh pembina --}}
                        <div class="relative w-28 h-28 mb-3 block" style="position:relative;">
                            <div class="relative w-full h-full">
                                <div id="foto-preview-fallback" class="w-full h-full rounded-full border-4 border-white shadow-[0_10px_30px_-12px_rgba(46,43,85,0.4)] bg-gradient-to-br from-lavender via-periwinkle to-sky flex items-center justify-center {{ $pembina?->foto_url ? 'hidden' : '' }}">
                                    <span class="font-display text-3xl font-bold text-white">{{ $pembina->inisial ?? 'P' }}</span>
                                </div>
                                @if ($pembina?->foto_url)
                                    <img src="{{ $pembina->foto_url }}"
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-[0_10px_30px_-12px_rgba(46,43,85,0.4)]"
                                         alt="Foto Profil {{ $pembina->nama_pembina ?? '' }}">
                                @endif
                            </div>
                        </div>

                        {{-- Nama — tampilan saja, tidak bisa diubah oleh pembina --}}
                        <h2 class="w-full text-center font-display text-xl font-bold text-ink pb-1">
                            {{ $pembina->nama_pembina ?? $user->name }}
                        </h2>

                        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-mint/60 text-[#1F7A3D] text-[11px] font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                            Pembina Ekstrakulikuler
                        </span>

                        <p class="text-[11px] text-inksoft mt-4">Foto, nama & data diri lainnya hanya bisa diubah oleh admin.</p>

                        <div class="w-full mt-6 pt-6 border-t border-[#EFEFF7] flex flex-col gap-3 text-left">
                            <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-4 py-3">
                                <div class="w-8 h-8 rounded-xl bg-mint text-[#1F7A3D] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                                </div>
                                <div class="text-xs min-w-0">
                                    <div class="text-inksoft">Email</div>
                                    <div class="font-semibold truncate">{{ $pembina->email ?? $user->email }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-4 py-3">
                                <div class="w-8 h-8 rounded-xl bg-sky text-[#1E6FA8] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </div>
                                <div class="text-xs min-w-0">
                                    <div class="text-inksoft">Nomor HP</div>
                                    <div class="font-semibold truncate">{{ $pembina->nomor_hp ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-4 py-3">
                                <div class="w-8 h-8 rounded-xl bg-periwinkle/40 text-[#3F41B0] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div class="text-xs min-w-0">
                                    <div class="text-inksoft">Alamat</div>
                                    <div class="font-semibold truncate">{{ $pembina->alamat ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>