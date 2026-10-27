<x-guest-login-layout title="Login">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-3xl font-display font-bold text-ink mb-1.5">Masuk</h2>
    <p class="text-inksoft mb-8">Masukkan email/username dan kata sandi akunmu.</p>

    <div class="bg-white rounded-3xl ring-1 ring-ink/[0.06] shadow-[0_2px_24px_-4px_rgba(46,43,85,0.08)] p-6 sm:p-8">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-ink mb-1.5">
                    Email / Username
                </label>

                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft/70">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.4 6a1 1 0 001.2 0L21 7.5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <input
                        id="email"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@sekolah.sch.id atau username"
                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-ink/10 bg-bgsoft/60 text-ink placeholder:text-inksoft/60 focus:outline-none focus:ring-2 focus:ring-periwinkle/60 focus:border-periwinkle focus:bg-white transition-all"
                    />
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-ink mb-1.5">
                    Kata Sandi
                </label>

                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft/70">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-12v3H8V7a4 4 0 118 0z" />
                        </svg>
                    </span>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-11 pr-11 py-3.5 rounded-2xl border border-ink/10 bg-bgsoft/60 text-ink placeholder:text-inksoft/60 focus:outline-none focus:ring-2 focus:ring-periwinkle/60 focus:border-periwinkle focus:bg-white transition-all"
                    />

                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-inksoft/70 hover:text-periwinkle transition"
                    >
                        <svg
                            id="eyeIcon"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="w-4 h-4 rounded border-ink/20 text-periwinkle accent-periwinkle focus:ring-periwinkle focus:ring-offset-0"
                    />

                    <span class="ms-2 text-sm text-inksoft">
                        Ingat saya
                    </span>
                </label>
            </div>

            <!-- Tombol Masuk -->
            <button
                type="submit"
                class="w-full py-3 rounded-2xl bg-periwinkle text-white font-semibold shadow-lg shadow-periwinkle/30 hover:bg-[#5B7FC7] hover:-translate-y-0.5 transition-all"
            >
                Masuk
            </button>
        </form>
    </div>

    <!-- Kembali ke landing page publik -->
    <a href="{{ route('landing') }}"
       class="group mt-5 flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-periwinkle/30 bg-white px-5 py-3 text-sm font-semibold text-periwinkle shadow-sm transition-all hover:border-periwinkle hover:bg-periwinkle/5 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-periwinkle/60">
        <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
    </a>


    {{-- Info lupa kata sandi: pojok kanan bawah layar. Fitur "Lupa kata sandi" dihilangkan karena
         reset password dilakukan oleh admin. --}}
    <div id="infoSandi" class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3">
        <div
            id="infoSandiBox"
            role="tooltip"
            class="hidden w-72 max-w-[calc(100vw-2.5rem)] rounded-2xl bg-white p-4 text-sm text-ink shadow-xl shadow-black/20 ring-1 ring-ink/10"
        >
            <p class="font-semibold mb-1">Lupa kata sandi?</p>
            <p class="text-inksoft leading-relaxed">
                Silakan temui <strong>Admin</strong> untuk mengganti kata sandimu.
                Kata sandi akan direset langsung oleh Admin.
            </p>
        </div>

        <button
            id="infoSandiBtn"
            type="button"
            aria-label="Info lupa kata sandi"
            aria-expanded="false"
            aria-controls="infoSandiBox"
            class="flex h-11 w-11 items-center justify-center rounded-full bg-periwinkle text-white shadow-lg shadow-periwinkle/40 hover:bg-[#5B7FC7] focus:outline-none focus:ring-2 focus:ring-white/80 transition"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5" />
                <circle cx="12" cy="7.75" r="0.6" fill="currentColor" />
            </svg>
        </button>
    </div>

    <script>
        (function () {
            const wrap = document.getElementById('infoSandi');
            const btn = document.getElementById('infoSandiBtn');
            const box = document.getElementById('infoSandiBox');

            function toggle(show) {
                box.classList.toggle('hidden', !show);
                btn.setAttribute('aria-expanded', show ? 'true' : 'false');
            }

            btn.addEventListener('click', () => toggle(box.classList.contains('hidden')));
            document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) toggle(false); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggle(false); });
        })();
    </script>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                    />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                `;
            }
        }
    </script>
</x-guest-login-layout>