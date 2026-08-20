@extends('layouts.admin')

@section('title', 'Tambah Pembina')
@section('page-title', 'Tambah Pembina')

@section('content')
<div class="animate-fade-in-up bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
    <form action="{{ route('admin.pembina.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div class="relative w-16 h-16 shrink-0">
                    <div id="foto-preview-fallback" class="w-16 h-16 rounded-2xl bg-gradient-to-br from-lavender to-sky flex items-center justify-center text-white font-bold text-lg">
                        <i class="fas fa-user"></i>
                    </div>
                    <img id="foto-preview" src="" class="hidden w-16 h-16 rounded-2xl object-cover border border-[#E7E7F4]" alt="Pratinjau foto">
                </div>
                <div class="flex-1">
                    <label for="foto" class="inline-flex items-center gap-2 cursor-pointer bg-bgsoft hover:bg-[#E9EAF9] text-ink text-xs font-bold px-4 py-2.5 rounded-xl border border-[#E7E7F4] transition">
                        <i class="fas fa-upload"></i>
                        Unggah Foto Guru
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp" class="hidden">
                    <p class="text-[11px] text-inksoft mt-1.5">JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                    @error('foto')
                        <p class="text-[11px] text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_pembina" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_pembina" id="nama_pembina" value="{{ old('nama_pembina') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('nama_pembina') border-red-500 @enderror">
                @error('nama_pembina')<p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')<p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="nomor_hp" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nomor HP</label>
                <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent">
            </div>

            <div>
                <label for="jk" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Jenis Kelamin</label>
                <select name="jk" id="jk" class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent">
                    <option value="">Pilih</option>
                    <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div>
                <label for="agama" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Agama</label>
                <input type="text" name="agama" id="agama" value="{{ old('agama') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent">
            </div>

            <div class="md:col-span-2">
                <label for="medsos" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Media Sosial</label>
                <input type="text" name="medsos" id="medsos" value="{{ old('medsos') }}"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent">
            </div>

            <div class="md:col-span-2">
                <label for="alamat" class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                    class="w-full px-4 py-2 border border-[#E7E7F4] rounded-xl focus:ring-2 focus:ring-lavender focus:border-transparent">{{ old('alamat') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-[#EFEFF7]">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-lavender to-periwinkle text-white font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-periwinkle/30">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
            <a href="{{ route('admin.pembina.index') }}" class="px-6 py-2.5 bg-bgsoft text-ink font-semibold rounded-xl hover:bg-[#E9EAF9] transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    const inputFoto = document.getElementById('foto');
    if (inputFoto) {
        inputFoto.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const preview = document.getElementById('foto-preview');
            const fallback = document.getElementById('foto-preview-fallback');
            const reader = new FileReader();
            reader.onload = function (ev) {
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
                fallback.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
