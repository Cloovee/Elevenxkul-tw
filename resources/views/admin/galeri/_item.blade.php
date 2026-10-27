{{-- Satu kartu foto galeri. Variabel: $foto --}}
<div class="gl-item">
    <div class="gl-img">
        <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" loading="lazy">
        <span class="gl-no">#{{ $foto->urutan }}</span>
    </div>
    <div class="gl-meta">
        <p class="gl-title" title="{{ $foto->judul }}">{{ $foto->judul }}</p>
        @if($foto->keterangan)<p class="gl-ket">{{ $foto->keterangan }}</p>@endif
        <div class="gl-act">
            <a href="{{ route('admin.galeri.edit', $foto->id_galeri) }}" class="gl-mini gl-edit"><i class="fas fa-pen"></i> Edit</a>
            <form action="{{ route('admin.galeri.destroy', $foto->id_galeri) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="gl-mini gl-del"><i class="fas fa-trash"></i> Hapus</button>
            </form>
        </div>
    </div>
</div>
