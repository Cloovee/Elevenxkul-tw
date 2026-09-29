@extends('layouts.admin')

@section('title', 'Tambah Pembina')
@section('page-title', 'Data Pembina')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Tambah Biodata Pembina</h3>
    </div>

    <form action="{{ route('admin.pembina.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div id="foto-preview" class="w-16 h-16 rounded-2xl bg-[#F2F7FF] border-2 border-dashed border-[#DDE8FB] flex items-center justify-center overflow-hidden shrink-0 text-[#7C8DB5]">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <div class="flex-1">
                    <input type="file" name="foto" id="foto-input" accept="image/png,image/jpeg,image/webp"
                           class="w-full text-sm text-[#10316B] file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#0B409C] file:text-white file:text-xs file:font-bold file:cursor-pointer hover:file:opacity-90 @error('foto') ring-2 ring-red-400 rounded-xl @enderror">
                    <p class="text-[11px] text-[#7C8DB5] mt-1.5">JPG, PNG, atau WEBP. Maks 2MB. Foto ini yang akan tampil menggantikan inisial di navbar pembina.</p>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Email (Username Login) <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email') }}" required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-[#7C8DB5] mt-1.5">Email ini otomatis jadi akun login pembina (role Pembina).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('password') ring-2 ring-red-400 @enderror" required>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nama Pembina <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pembina" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('nama_pembina') ring-2 ring-red-400 @enderror" value="{{ old('nama_pembina') }}" required>
                @error('nama_pembina')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                <select name="jk" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('jk') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Agama</label>
                <input type="text" name="agama" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('agama') ring-2 ring-red-400 @enderror" value="{{ old('agama') }}">
                @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nomor HP</label>
                <input type="text" name="nomor_hp" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('nomor_hp') ring-2 ring-red-400 @enderror" value="{{ old('nomor_hp') }}">
                @error('nomor_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">MedSos</label>
                <input type="text" name="medsos" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('medsos') ring-2 ring-red-400 @enderror" value="{{ old('medsos') }}">
                @error('medsos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('alamat') ring-2 ring-red-400 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Ekskul yang Dibina</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto p-4 bg-[#F2F7FF] rounded-xl @error('ekskul_ids') ring-2 ring-red-400 @enderror">
                @forelse($ekskuls as $ek)
                    <label class="flex items-center gap-2 text-sm text-[#10316B] bg-white rounded-lg px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="ekskul_ids[]" value="{{ $ek->id_ekskul }}" class="rounded border-[#DDE8FB] text-[#0B409C] focus:ring-[#0B409C]" {{ in_array($ek->id_ekskul, old('ekskul_ids', [])) ? 'checked' : '' }}>
                        <span>
                            {{ $ek->nama_ekskul }}
                            @if($ek->pembina)
                                <span class="block text-[11px] text-amber-600">Sekarang: {{ $ek->pembina->nama_pembina }} (ambil alih kalau dicentang)</span>
                            @endif
                        </span>
                    </label>
                @empty
                    <p class="text-sm text-[#7C8DB5] col-span-2">Belum ada data ekskul.</p>
                @endforelse
            </div>
            @error('ekskul_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @error('ekskul_ids.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.pembina.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('foto-input')?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('foto-preview');
        if (!file || !preview) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            preview.innerHTML = '<img src="' + ev.target.result + '" class="w-full h-full object-cover" alt="Preview foto">';
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection