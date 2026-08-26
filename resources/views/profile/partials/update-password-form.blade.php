<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 gap-5">
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('current_password', 'updatePassword') ring-2 ring-red-400 @enderror"
                autocomplete="current-password">
            @error('current_password', 'updatePassword')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Password Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('password', 'updatePassword') ring-2 ring-red-400 @enderror"
                autocomplete="new-password">
            @error('password', 'updatePassword')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('password_confirmation', 'updatePassword') ring-2 ring-red-400 @enderror"
                autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-save"></i> Simpan
        </button>

        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="inline-flex items-center gap-1.5 text-green-600 text-xs font-bold bg-green-50 px-3 py-1.5 rounded-full">
                <i class="fas fa-check-circle"></i> Tersimpan.
            </p>
        @endif
    </div>
</form>