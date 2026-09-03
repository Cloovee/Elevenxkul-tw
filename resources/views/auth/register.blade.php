<x-guest-layout>
    <div class="mb-8 hidden lg:block">
        <h2 class="text-2xl sm:text-3xl font-display font-bold text-ink mb-2">Selamat Datang</h2>
        <p class="text-inksoft text-sm">Masuk untuk mengelola kegiatan ekstrakurikuler sekolahmu.</p>
    </div>
    <div class="mb-8 lg:hidden">
        <h2 class="text-2xl font-display font-bold text-ink mb-2">Masuk</h2>
        <p class="text-inksoft text-sm">Masukkan email dan kata sandi akunmu.</p>
    </div>

    <x-auth-session-status class="mb-5 flex items-center gap-2 bg-mint/40 border border-mint text-emerald-700 rounded-xl px-4 py-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-ink mb-1.5">
                Email
            </label>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-inksoft/70">
                    <i class="fas fa-envelope text-sm"></i>
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
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-bgsoft/60 text-ink placeholder:text-inksoft/50 focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle focus:bg-white transition"
                />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-ink mb-1.5">
                Kata Sandi
            </label>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-inksoft/70">
                    <i class="fas fa-lock text-sm"></i>
                </span>

                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-11 pr-11 py-2.5 rounded-xl border border-gray-200 bg-bgsoft/60 text-ink placeholder:text-inksoft/50 focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle focus:bg-white transition"
                />

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-inksoft/70 hover:text-periwinkle transition"
                    tabindex="-1"
                >
                    <i class="fas text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me + Lupa Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-periwinkle shadow-sm focus:ring-periwinkle"
                />

                <span class="ms-2 text-sm text-inksoft">
                    Ingat saya
                </span>
            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm font-semibold text-periwinkle hover:text-[#7b84fc] hover:underline transition"
                >
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Tombol Masuk -->
        <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 rounded-xl bg-gradient-to-r from-[#7b84fc] to-periwinkle text-white font-semibold shadow-lg shadow-periwinkle/30 hover:shadow-xl hover:shadow-periwinkle/40 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed disabled:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2"
        >
            <i class="fas fa-circle-notch fa-spin" x-show="loading" x-cloak></i>
            <span x-text="loading ? 'Memproses...' : 'Masuk'"></span>
        </button>
    </form>
</x-guest-layout>