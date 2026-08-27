<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembina — Elevenxkul</title>
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
                <p class="text-inksoft text-sm mt-1">Kelola informasi akun dan keamanan password kamu.</p>
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

                {{-- Informasi Akun --}}
                <div class="prof-in bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:60ms">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-2xl bg-periwinkle/30 text-[#3F41B0] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold">Informasi Akun</h2>
                            <p class="text-xs text-inksoft mt-0.5 flex items-center gap-1">
                                Nama & foto profil diubah lewat panel di kanan
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="hidden lg:inline"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </p>
                        </div>
                    </div>

                    @if ($errors->has('name') || $errors->has('email'))
                        <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <ul class="list-disc list-inside space-y-0.5">
                                @if($errors->has('name')) <li>{{ $errors->first('name') }}</li> @endif
                                @if($errors->has('email')) <li>{{ $errors->first('email') }}</li> @endif
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembina.profile.update') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                        @csrf
                        @method('PATCH')

                        {{-- name is edited from the right-side widget; carried here so this form's
                             own submission never blanks it out --}}
                        <input type="hidden" name="name" value="{{ old('name', $pembina->nama_pembina ?? $user->name) }}">

                        <div>
                            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Email / Username</label>
                            <div class="relative" style="position:relative;">
                                <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                                </span>
                                <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                                       class="field-input w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:14px;">
                            </div>
                        </div>

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
                                    class="bg-gradient-to-r from-lavender to-mint text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 hover:-translate-y-0.5 transition-all shrink-0">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Ganti Password --}}
                <div class="prof-in bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]" style="--d:120ms">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-2xl bg-sky/60 text-[#1E6FA8] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold">Ganti Password</h2>
                            <p class="text-xs text-inksoft mt-0.5">Minimal 8 karakter, gunakan kombinasi yang kuat.</p>
                        </div>
                    </div>

                    @if ($errors->has('password_lama') || $errors->has('password_baru'))
                        <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <ul class="list-disc list-inside space-y-0.5">
                                @if($errors->has('password_lama')) <li>{{ $errors->first('password_lama') }}</li> @endif
                                @if($errors->has('password_baru')) <li>{{ $errors->first('password_baru') }}</li> @endif
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembina.profile.password') }}" class="flex flex-col gap-4" id="password-form">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Password Lama</label>
                            <div class="relative" style="position:relative;">
                                <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                </span>
                                <input type="password" name="password_lama" required
                                       class="field-input pw-input w-full pl-10 pr-11 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:44px;">
                                <button type="button" data-pw-toggle class="pw-toggle text-inksoft/60 hover:text-ink transition-colors" style="position:absolute; right:12px; top:50%; transform:translateY(-50%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Password Baru</label>
                                <div class="relative" style="position:relative;">
                                    <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    </span>
                                    <input type="password" name="password_baru" id="password_baru" required minlength="8"
                                           class="field-input pw-input w-full pl-10 pr-11 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:44px;">
                                    <button type="button" data-pw-toggle class="pw-toggle text-inksoft/60 hover:text-ink transition-colors" style="position:absolute; right:12px; top:50%; transform:translateY(-50%);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <div class="flex gap-1 mt-1.5" id="pw-strength">
                                    <span class="h-1 flex-1 rounded-full bg-[#EFEFF7]" data-bar></span>
                                    <span class="h-1 flex-1 rounded-full bg-[#EFEFF7]" data-bar></span>
                                    <span class="h-1 flex-1 rounded-full bg-[#EFEFF7]" data-bar></span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Konfirmasi Password Baru</label>
                                <div class="relative" style="position:relative;">
                                    <span class="field-icon text-inksoft/60" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    </span>
                                    <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" required minlength="8"
                                           class="field-input pw-input w-full pl-10 pr-11 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:border-lavender focus:ring-2 focus:ring-lavender/15 transition-colors" style="padding-left:40px; padding-right:44px;">
                                    <button type="button" data-pw-toggle class="pw-toggle text-inksoft/60 hover:text-ink transition-colors" style="position:absolute; right:12px; top:50%; transform:translateY(-50%);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <p class="text-[11px] mt-1.5 h-4" id="pw-match-hint"></p>
                            </div>
                        </div>

                        <button type="submit"
                                class="self-start mt-1 bg-gradient-to-r from-lavender to-mint text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 hover:-translate-y-0.5 transition-all">
                            Perbarui Password
                        </button>
                    </form>
                </div>
            </div>

            {{-- ================= KANAN: WIDGET FIKS — foto + nama ================= --}}
            <div class="order-1 lg:order-2 w-full lg:w-[360px] lg:fixed lg:top-5 lg:right-5 lg:z-20">
                <div class="prof-in relative bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] overflow-hidden" style="--d:0ms">

                    <div class="flex flex-col items-center text-center px-8 pt-8 pb-8">

                        @error('foto')
                            <div class="w-full bg-red-50 text-red-500 text-xs font-semibold px-3 py-2 rounded-xl mb-4">{{ $message }}</div>
                        @enderror

                        <form method="POST" action="{{ route('pembina.profile.update') }}" enctype="multipart/form-data" class="w-full flex flex-col items-center">
                            @csrf
                            @method('PATCH')

                            {{-- carried so this focused form still submits a complete, valid payload --}}
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <input type="hidden" name="nomor_hp" value="{{ $pembina->nomor_hp ?? '' }}">
                            <input type="hidden" name="alamat" value="{{ $pembina->alamat ?? '' }}">

                            {{-- Foto --}}
                            <label for="foto" class="relative w-28 h-28 mb-3 cursor-pointer group block" style="position:relative;">
                                <div class="relative w-full h-full">
                                    <div id="foto-preview-fallback" class="w-full h-full rounded-full border-4 border-white shadow-[0_10px_30px_-12px_rgba(46,43,85,0.4)] bg-gradient-to-br from-lavender via-periwinkle to-sky flex items-center justify-center {{ $pembina?->foto_url ? 'hidden' : '' }}">
                                        <span class="font-display text-3xl font-bold text-white">{{ $pembina->inisial ?? 'P' }}</span>
                                    </div>
                                    <img id="foto-preview"
                                         src="{{ $pembina?->foto_url }}"
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-[0_10px_30px_-12px_rgba(46,43,85,0.4)] {{ $pembina?->foto_url ? '' : 'hidden' }}"
                                         alt="Foto Profil {{ $pembina->nama_pembina ?? '' }}">

                                    <div class="absolute inset-0 rounded-full bg-ink/0 group-hover:bg-ink/25 transition-colors flex items-center justify-center">
                                        <span class="opacity-0 group-hover:opacity-100 transition-opacity text-white text-[10px] font-bold tracking-wide">GANTI FOTO</span>
                                    </div>
                                </div>

                                <span class="w-8 h-8 rounded-full bg-lavender text-white flex items-center justify-center shadow-[0_6px_14px_-6px_rgba(46,43,85,0.5)] ring-2 ring-white group-hover:scale-110 transition-transform" style="position:absolute; bottom:2px; right:2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp" class="hidden">
                            <p class="text-[11px] text-inksoft mt-1 mb-5">JPG, PNG, atau WEBP · maks. 2MB</p>

                            {{-- Nama — inline editable, gaya judul --}}
                            <input type="text" name="name"
                                   value="{{ old('name', $pembina->nama_pembina ?? $user->name) }}"
                                   placeholder="Nama lengkap"
                                   class="w-full text-center font-display text-xl font-bold text-ink bg-transparent border-b-2 border-transparent hover:border-[#E7E7F4] focus:border-lavender outline-none pb-1 transition-colors">

                            <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-mint/60 text-[#1F7A3D] text-[11px] font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                Pembina Ekstrakulikuler
                            </span>

                            <button type="submit" id="foto-nama-submit"
                                    class="mt-5 w-full bg-gradient-to-r from-lavender to-mint text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:opacity-90 hover:-translate-y-0.5 transition-all">
                                Simpan Foto & Nama
                            </button>
                        </form>

                        <div class="w-full mt-6 pt-6 border-t border-[#EFEFF7] flex flex-col gap-3 text-left">
                            <div class="flex items-center gap-3 bg-bgsoft rounded-2xl px-4 py-3">
                                <div class="w-8 h-8 rounded-xl bg-mint text-[#1F7A3D] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                                </div>
                                <div class="text-xs min-w-0">
                                    <div class="text-inksoft">Email</div>
                                    <div class="font-semibold truncate">{{ $user->email }}</div>
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

<script>
    // Preview foto
    const inputFoto = document.getElementById('foto');
    if (inputFoto) {
        inputFoto.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('foto-preview');
            const fallback = document.getElementById('foto-preview-fallback');
            const reader = new FileReader();

            reader.onload = function (ev) {
                if (preview) {
                    preview.src = ev.target.result;
                    preview.classList.remove('hidden');
                }
                if (fallback) {
                    fallback.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // Toggle tampil/sembunyi password
    document.querySelectorAll('[data-pw-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.previousElementSibling && btn.previousElementSibling.matches('.pw-input')
                ? btn.previousElementSibling
                : btn.parentElement.querySelector('.pw-input');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.classList.toggle('text-lavender');
        });
    });

    // Indikator kekuatan & kecocokan password baru
    const pwBaru = document.getElementById('password_baru');
    const pwKonfirmasi = document.getElementById('password_baru_confirmation');
    const bars = document.querySelectorAll('#pw-strength [data-bar]');
    const matchHint = document.getElementById('pw-match-hint');
    const strengthColors = ['bg-red-400', 'bg-amber-400', 'bg-[#3FBE6A]'];

    function nilaiKekuatan(val) {
        let skor = 0;
        if (val.length >= 8) skor++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) skor++;
        if (/[^A-Za-z0-9]/.test(val) && val.length >= 10) skor++;
        return Math.max(skor, val.length > 0 ? 1 : 0);
    }

    function perbaruiKekuatan() {
        if (!pwBaru) return;
        const skor = nilaiKekuatan(pwBaru.value);
        bars.forEach(function (bar, i) {
            bar.className = 'h-1 flex-1 rounded-full transition-colors ' + (i < skor ? strengthColors[Math.min(skor, 3) - 1] : 'bg-[#EFEFF7]');
        });
    }

    function perbaruiKecocokan() {
        if (!pwBaru || !pwKonfirmasi || !matchHint) return;
        if (!pwKonfirmasi.value) {
            matchHint.textContent = '';
            return;
        }
        if (pwBaru.value === pwKonfirmasi.value) {
            matchHint.textContent = 'Password cocok';
            matchHint.className = 'text-[11px] mt-1.5 h-4 text-[#1F7A3D] font-semibold';
        } else {
            matchHint.textContent = 'Password belum sama';
            matchHint.className = 'text-[11px] mt-1.5 h-4 text-red-500 font-semibold';
        }
    }

    if (pwBaru) pwBaru.addEventListener('input', function () { perbaruiKekuatan(); perbaruiKecocokan(); });
    if (pwKonfirmasi) pwKonfirmasi.addEventListener('input', perbaruiKecocokan);
</script>

</body>
</html>