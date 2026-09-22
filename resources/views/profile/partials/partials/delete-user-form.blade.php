<button
    type="button"
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-full text-sm font-bold transition-colors"
>
    <i class="fas fa-trash"></i> Hapus Akun
</button>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <h2 class="text-lg font-extrabold text-[#10316B]">
                Yakin mau hapus akun ini?
            </h2>
        </div>

        <p class="text-sm text-[#7C8DB5] mb-6">
            Setelah akun dihapus, semua data dan resource-nya akan hilang permanen. Masukkan password buat konfirmasi.
        </p>

        <div>
            <label for="password" class="sr-only">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-red-400 @error('password', 'userDeletion') ring-2 ring-red-400 @enderror"
                placeholder="Password"
            />
            @error('password', 'userDeletion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                Batal
            </button>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-trash"></i> Hapus Akun
            </button>
        </div>
    </form>
</x-modal>