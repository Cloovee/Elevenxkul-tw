{{-- Form bersama untuk tambah & edit foto galeri. Variabel: $galeri (opsional), $ekskuls, $urutanBerikut (opsional) --}}
@php $isEdit = isset($galeri); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="md:col-span-2">
        <label for="judul" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
            Judul Foto <span class="text-red-500">*</span>
        </label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $galeri->judul ?? '') }}"
            placeholder="Contoh: Latihan rutin Paskibra"
            class="w-full px-4 py-3 bg-[#f4f7fe] border @error('judul') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] placeholder-[#a3aed1] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
        @error('judul')
            <p class="mt-1.5 text-xs text-red-500 font-semibold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="id_ekskul" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">Ekskul Terkait</label>
        <select name="id_ekskul" id="id_ekskul"
            class="w-full px-4 py-3 bg-[#f4f7fe] border border-transparent rounded-xl text-sm text-[#2b3674] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
            <option value="">-- Umum (tanpa ekskul) --</option>
            @foreach($ekskuls as $e)
                <option value="{{ $e->id_ekskul }}" {{ (string) old('id_ekskul', $galeri->id_ekskul ?? '') === (string) $e->id_ekskul ? 'selected' : '' }}>
                    {{ $e->nama_ekskul }}
                </option>
            @endforeach
        </select>
        <p class="mt-1.5 text-xs text-[#a3aed1]">Foto pertama sebuah ekskul dipakai sebagai sampul kartu ekskul di landing page.</p>
    </div>

    <div>
        <label for="urutan" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">Nomor Urutan</label>
        <input type="number" min="0" name="urutan" id="urutan" value="{{ old('urutan', $galeri->urutan ?? ($urutanBerikut ?? 0)) }}"
            class="w-full px-4 py-3 bg-[#f4f7fe] border border-transparent rounded-xl text-sm text-[#2b3674] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
        <p class="mt-1.5 text-xs text-[#a3aed1]">Angka terkecil tampil paling depan di tumpukan galeri.</p>
        @error('urutan')
            <p class="mt-1.5 text-xs text-red-500 font-semibold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="keterangan" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">Keterangan</label>
        <textarea name="keterangan" id="keterangan" rows="3" placeholder="Cerita singkat tentang kegiatan di foto ini"
            class="w-full px-4 py-3 bg-[#f4f7fe] border border-transparent rounded-xl text-sm text-[#2b3674] placeholder-[#a3aed1] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">{{ old('keterangan', $galeri->keterangan ?? '') }}</textarea>
        @error('keterangan')
            <p class="mt-1.5 text-xs text-red-500 font-semibold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="foto" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
            Foto @unless($isEdit)<span class="text-red-500">*</span>@endunless
        </label>

        @if($isEdit)
            <img id="foto-preview" src="{{ $galeri->foto_url }}" alt="{{ $galeri->judul }}" class="w-full max-w-sm aspect-[4/3] object-cover rounded-2xl mb-3 border border-[#E8F0FE]">
        @else
            <img id="foto-preview" src="" alt="" class="hidden w-full max-w-sm aspect-[4/3] object-cover rounded-2xl mb-3 border border-[#E8F0FE]">
        @endif

        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
            class="block w-full text-sm text-[#5B6E96] file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-[#0B409C] file:text-white hover:file:opacity-90 file:cursor-pointer"
            onchange="const f=this.files[0],p=document.getElementById('foto-preview'); if(f){p.src=URL.createObjectURL(f);p.classList.remove('hidden');}">
        <p class="mt-1.5 text-xs text-[#a3aed1]">
            JPG, PNG, atau WEBP, maksimal 4 MB. Foto landscape (4:3) tampil paling rapi.
            @if($isEdit) Kosongkan kalau tidak ingin mengganti foto. @endif
        </p>
        @error('foto')
            <p class="mt-1.5 text-xs text-red-500 font-semibold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
        @enderror
    </div>
</div>