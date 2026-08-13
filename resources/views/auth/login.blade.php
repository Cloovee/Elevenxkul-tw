
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-2xl font-bold text-gray-800 mb-2">Masuk</h2>
    <p class="text-gray-500 mb-8">Masukkan email dan kata sandi akunmu.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-800 mb-1.5">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="nama@sekolah.sch.id"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-800 mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember me + lupa password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-periwinkle shadow-sm focus:ring-periwinkle" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-periwinkle hover:underline" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-2.5 rounded-xl bg-periwinkle text-white font-semibold hover:bg-periwinkle/90 transition"
        >
            Masuk
        </button>
    </form>
</x-guest-layout>