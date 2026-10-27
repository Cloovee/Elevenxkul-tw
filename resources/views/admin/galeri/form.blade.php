{{-- Form bersama tambah & edit foto galeri. Variabel: $ekskuls, $galeri (edit), $terpilih (tambah) --}}
@php $isEdit = isset($galeri); @endphp

<div class="gl-form">
    <div class="gl-full">
        <label for="id_ekskul" class="gl-label">Ekstrakurikuler <b>*</b></label>
        <select name="id_ekskul" id="id_ekskul" class="gl-input @error('id_ekskul') err @enderror" required>
            <option value="">-- Pilih ekskul --</option>
            @foreach($ekskuls as $e)
                <option value="{{ $e->id_ekskul }}" {{ (string) old('id_ekskul', $galeri->id_ekskul ?? ($terpilih ?? '')) === (string) $e->id_ekskul ? 'selected' : '' }}>
                    {{ $e->nama_ekskul }}
                </option>
            @endforeach
        </select>
        <p class="gl-hint">Foto akan masuk ke kelompok ekskul ini di galeri landing page (contoh: Basket, Japanese Club).</p>
        @error('id_ekskul')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
    </div>

    <div class="{{ $isEdit ? '' : 'gl-full' }}">
        <label for="judul" class="gl-label">Judul Foto @if($isEdit)<b>*</b>@endif</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $galeri->judul ?? '') }}"
            placeholder="{{ $isEdit ? 'Contoh: Latihan rutin hari Jumat' : 'Kosongkan untuk otomatis: Kegiatan <nama ekskul>' }}"
            class="gl-input @error('judul') err @enderror" @if($isEdit) required @endif>
        @error('judul')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
    </div>

    @if($isEdit)
    <div>
        <label for="urutan" class="gl-label">Nomor Urutan</label>
        <input type="number" min="0" name="urutan" id="urutan" value="{{ old('urutan', $galeri->urutan) }}" class="gl-input @error('urutan') err @enderror">
        <p class="gl-hint">Angka terkecil tampil paling depan di dalam galeri ekskul ini.</p>
        @error('urutan')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
    </div>
    @endif

    <div class="gl-full">
        <label for="keterangan" class="gl-label">Keterangan</label>
        <textarea name="keterangan" id="keterangan" rows="3" placeholder="Cerita singkat tentang kegiatan di foto ini (boleh dikosongkan)"
            class="gl-input @error('keterangan') err @enderror">{{ old('keterangan', $galeri->keterangan ?? '') }}</textarea>
        @error('keterangan')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
    </div>

    <div class="gl-full">
        @if($isEdit)
            <label class="gl-label">Foto</label>
            <img id="gl-cur" src="{{ $galeri->foto_url }}" alt="{{ $galeri->judul }}" class="gl-cur">
            <label class="gl-drop" id="gl-drop">
                <i class="fas fa-arrow-up-from-bracket"></i>
                <b>Ganti foto (opsional)</b>
                <span class="gl-hint" style="margin:0">Klik untuk memilih. Kosongkan kalau tidak ingin mengganti.</span>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp">
            </label>
            @error('foto')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        @else
            <label class="gl-label">Foto Kegiatan <b>*</b></label>
            <label class="gl-drop" id="gl-drop">
                <i class="fas fa-images"></i>
                <b>Klik atau seret foto ke sini</b>
                <span class="gl-hint" style="margin:0">Bisa pilih banyak foto sekaligus (maks. 20). JPG, PNG, WEBP, maks. 4 MB per foto.</span>
                <input type="file" name="foto[]" id="foto" accept="image/jpeg,image/png,image/webp" multiple>
            </label>
            <div class="gl-prev" id="gl-prev"></div>
            @error('foto')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            @error('foto.*')<p class="gl-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        @endif
    </div>
</div>

<script>
    (function () {
        const inp = document.getElementById('foto'), drop = document.getElementById('gl-drop');
        const prev = document.getElementById('gl-prev'), cur = document.getElementById('gl-cur');
        if (!inp) return;
        inp.addEventListener('change', () => {
            const files = [...inp.files];
            if (prev) { prev.innerHTML = ''; files.forEach(f => { const i = document.createElement('img'); i.src = URL.createObjectURL(f); i.alt = f.name; prev.appendChild(i); }); }
            if (cur && files[0]) cur.src = URL.createObjectURL(files[0]);
        });
        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('on'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('on'); }));
        drop.addEventListener('drop', e => { if (e.dataTransfer.files.length) { inp.files = e.dataTransfer.files; inp.dispatchEvent(new Event('change')); } });
    })();
</script>
