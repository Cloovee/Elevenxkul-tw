<x-guest-login-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-3xl font-display font-bold text-ink mb-1.5">Masuk</h2>
    <p class="text-inksoft mb-8">Masukkan email dan kata sandi akunmu.</p>

    <div class="bg-white rounded-3xl ring-1 ring-ink/[0.06] shadow-[0_2px_24px_-4px_rgba(46,43,85,0.08)] p-6 sm:p-8">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-ink mb-1.5">
                    Email
                </label>

                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft/70">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.4 6a1 1 0 001.2 0L21 7.5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@sekolah.sch.id"
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

            <!-- Remember Me + Lupa Password -->
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

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-periwinkle hover:text-[#8F92F5] hover:underline"
                    >
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <!-- Tombol Masuk -->
            <button
                type="submit"
                class="w-full py-3 rounded-2xl bg-periwinkle text-white font-semibold shadow-lg shadow-periwinkle/30 hover:bg-[#9FA1FF] hover:-translate-y-0.5 transition-all"
            >
                Masuk
            </button>
        </form>
    </div>


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