{{--
    Biodata Pembina (dipindah dari menu Kelola Pembina yang sudah dihapus).
    Tampil hanya kalau role = Pembina. Dipakai oleh create & edit.
    Variabel: $ekskuls, $pembina (nullable, hanya saat edit), $assignedEkskulIds (array, hanya saat edit)
--}}
@php
    $pembina = $pembina ?? null;
    $assignedEkskulIds = $assignedEkskulIds ?? [];
@endphp

<div class="md:col-span-2" id="pembina-fields" style="display: none;">
    <div class="pt-2 mt-1 border-t border-gray-100">
        <p class="text-[11px] font-bold text-[#0B409C] uppercase tracking-wider mb-1 mt-4">Biodata Pembina</p>
        <p class="text-xs text-[#7C8DB5] mb-5">Nama lengkap di atas otomatis dipakai sebagai nama pembina. Biodata dan akun login tersimpan sekaligus.</p>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div id="foto-preview" class="w-16 h-16 rounded-2xl bg-[#F2F7FF] border-2 border-dashed border-[#DDE8FB] flex items-center justify-center overflow-hidden shrink-0 text-[#7C8DB5]">
                    @if($pembina && $pembina->foto_url)
                        <img src="{{ $pembina->foto_url }}" class="w-full h-full object-cover" alt="Foto {{ $pembina->nama_pembina }}">
                    @else
                        <i class="fas fa-user text-xl"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <input type="file" name="foto" id="foto-input" accept="image/png,image/jpeg,image/webp"
                           class="w-full text-sm text-[#10316B] file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#0B409C] file:text-white file:text-xs file:font-bold file:cursor-pointer hover:file:opacity-90 @error('foto') ring-2 ring-red-400 rounded-xl @enderror">
                    <p class="text-[11px] text-[#7C8DB5] mt-1.5">
                        JPG, PNG, atau WEBP. Maks 2MB. Foto ini tampil menggantikan inisial di navbar pembina. (opsional)
                        @if($pembina && $pembina->foto_url) Kosongkan jika tidak ingin mengganti foto. @endif
                    </p>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @if($pembina && $pembina->foto_url)
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
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jk" id="jk-select" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('jk') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jk', $pembina->jk ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jk', $pembina->jk ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Agama</label>
                <input type="text" name="agama" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('agama') ring-2 ring-red-400 @enderror" value="{{ old('agama', $pembina->agama ?? '') }}">
                @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nomor HP</label>
                <input type="text" name="nomor_hp" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('nomor_hp') ring-2 ring-red-400 @enderror" value="{{ old('nomor_hp', $pembina->nomor_hp ?? '') }}">
                @error('nomor_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Media Sosial</label>
                <input type="text" name="medsos" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('medsos') ring-2 ring-red-400 @enderror" value="{{ old('medsos', $pembina->medsos ?? '') }}">
                @error('medsos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('alamat') ring-2 ring-red-400 @enderror">{{ old('alamat', $pembina->alamat ?? '') }}</textarea>
                @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Ekskul yang Dibina</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto p-4 bg-[#F2F7FF] rounded-xl @error('ekskul_ids') ring-2 ring-red-400 @enderror">
                @forelse($ekskuls as $ek)
                    <label class="flex items-center gap-2 text-sm text-[#10316B] bg-white rounded-lg px-3 py-2 cursor-pointer">
                        <input type="checkbox" name="ekskul_ids[]" value="{{ $ek->id_ekskul }}" class="rounded border-[#DDE8FB] text-[#0B409C] focus:ring-[#0B409C]" {{ in_array($ek->id_ekskul, old('ekskul_ids', $assignedEkskulIds)) ? 'checked' : '' }}>
                        <span>
                            {{ $ek->nama_ekskul }}
                            @if($ek->pembina && (!$pembina || $ek->id_pembina != $pembina->id_pembina))
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
    </div>
</div>