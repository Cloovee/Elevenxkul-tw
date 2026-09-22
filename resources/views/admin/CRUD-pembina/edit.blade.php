@extends('layouts.admin')

@section('title', 'Edit Pembina')
@section('page-title', 'Data Pembina')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Edit Biodata Pembina</h3>
    </div>

    <!-- Info akun, read-only -->
    <div class="mb-6 p-4 bg-[#F2F7FF] rounded-xl flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Akun Login Terkait</p>
            @if($pembina->user)
                <p class="text-sm text-[#10316B] font-bold">{{ $pembina->user->name }} <span class="font-normal text-[#7C8DB5]">({{ $pembina->user->email }})</span></p>
            @else
                <p class="text-sm text-amber-600 font-bold"><i class="fas fa-triangle-exclamation mr-1"></i> Akun sudah dihapus</p>
            @endif
        </div>
        <a href="{{ route('admin.user.index') }}" class="text-xs font-bold text-[#0B409C] hover:underline whitespace-nowrap">
            Kelola di User &rarr;
        </a>
    </div>

    <form action="{{ route('admin.pembina.update', $pembina->id_pembina) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div id="foto-preview" class="w-16 h-16 rounded-2xl bg-[#F2F7FF] border-2 border-dashed border-[#DDE8FB] flex items-center justify-center overflow-hidden shrink-0 text-[#7C8DB5]">
                    @if($pembina->foto_url)
                        <img src="{{ $pembina->foto_url }}" class="w-full h-full object-cover" alt="Foto {{ $pembina->nama_pembina }}">
                    @else
                        <i class="fas fa-user text-xl"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <input type="file" name="foto" id="foto-input" accept="image/png,image/jpeg,image/webp"
                           class="w-full text-sm text-[#10316B] file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#0B409C] file:text-white file:text-xs file:font-bold file:cursor-pointer hover:file:opacity-90 @error('foto') ring-2 ring-red-400 rounded-xl @enderror">
                    <p class="text-[11px] text-[#7C8DB5] mt-1.5">JPG, PNG, atau WEBP. Maks 2MB. Kosongkan jika tidak ingin mengganti foto.</p>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    @if($pembina->foto_url)
                        <label class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold text-red-500 cursor-pointer">
                            <input type="checkbox" name="hapus_foto" value="1" class="rounded border-red-300 text-red-500 focus:ring-red-400">
                            Hapus foto (kembali ke avatar inisial)
                        </label>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nama Pembina <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pembina" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('nama_pembina') ring-2 ring-red-400 @enderror" value="{{ old('nama_pembina', $pembina->nama_pembina) }}" required>
                @error('nama_pembina')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                <select name="jk" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('jk') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jk', $pembina->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk', $pembina->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Agama</label>
                <input type="text" name="agama" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('agama') ring-2 ring-red-400 @enderror" value="{{ old('agama', $pembina->agama) }}">
                @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nomor HP</label>
                <input type="text" name="nomor_hp" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('nomor_hp') ring-2 ring-red-400 @enderror" value="{{ old('nomor_hp', $pembina->nomor_hp) }}">
                @error('nomor_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Email <span class="text-xs normal-case font-normal text-[#7C8DB5]">(kontak, boleh beda dari email akun)</span></label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email', $pembina->email) }}">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">MedSos</label>
                <input type="text" name="medsos" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('medsos') ring-2 ring-red-400 @enderror" value="{{ old('medsos', $pembina->medsos) }}">
                @error('medsos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('alamat') ring-2 ring-red-400 @enderror">{{ old('alamat', $pembina->alamat) }}</textarea>
                @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
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