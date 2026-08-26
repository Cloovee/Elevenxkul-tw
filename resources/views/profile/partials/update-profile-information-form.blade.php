<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 gap-5">
        <div>
            <label for="name" class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Nama</label>
            <input id="name" name="name" type="text"
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('name') ring-2 ring-red-400 @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Email</label>
            <input id="email" name="email" type="email"
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('email') ring-2 ring-red-400 @enderror"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="mt-2 inline-flex items-center gap-1.5 text-amber-600 text-xs font-bold bg-amber-50 px-3 py-1.5 rounded-full">
                    <i class="fas fa-triangle-exclamation"></i>
                    Emailmu belum terverifikasi.
                    <button form="send-verification" class="underline hover:no-underline">
                        Kirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-bold text-green-600">
                        Link verifikasi baru sudah dikirim ke emailmu.
                    </p>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-save"></i> Simpan
        </button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="inline-flex items-center gap-1.5 text-green-600 text-xs font-bold bg-green-50 px-3 py-1.5 rounded-full">
                <i class="fas fa-check-circle"></i> Tersimpan.
            </p>
        @endif
    </div>
</form>