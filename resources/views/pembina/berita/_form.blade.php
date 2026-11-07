{{--
    Isian form berita (dipakai create & edit).
    Variabel: $ekskuls (hanya create), $berita (hanya edit).
--}}
@if ($errors->any())
    <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@isset($ekskuls)
    <div>
        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Kirim ke Ekskul <span class="text-red-500">*</span></label>
        <select name="id_ekskul" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
            @if($ekskuls->count() !== 1)
                <option value="">-- Pilih Ekskul --</option>
            @endif
            @if($ekskuls->count() > 1)
                <option value="semua" {{ old('id_ekskul') === 'semua' ? 'selected' : '' }}>Semua ekskul yang saya bina</option>
            @endif
            @forelse($ekskuls as $e)
                <option value="{{ $e->id_ekskul }}" {{ (string) old('id_ekskul', $ekskuls->count() === 1 ? $e->id_ekskul : '') === (string) $e->id_ekskul ? 'selected' : '' }}>
                    {{ $e->nama_ekskul }}
                </option>
            @empty
                <option value="" disabled>Kamu belum membina ekskul manapun</option>
            @endforelse
        </select>
        <p class="mt-1 text-xs text-inksoft">Berita ini muncul di aplikasi ketua ekskul tersebut.</p>
    </div>
@else
    <div>
        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Ekskul</label>
        <p class="px-3.5 py-2.5 rounded-xl bg-bgsoft text-sm font-semibold">{{ $berita->ekskul->nama_ekskul ?? '-' }}</p>
    </div>
@endisset

<div>
    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Judul <span class="text-red-500">*</span></label>
    <input type="text" name="judul" required maxlength="150" value="{{ old('judul', $berita->judul ?? '') }}"
           placeholder="Contoh: Jadwal latihan minggu ini berubah"
           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
</div>

<div>
    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Isi Berita <span class="text-red-500">*</span></label>
    <textarea name="isi" rows="7" required maxlength="5000"
              placeholder="Tulis pengumuman untuk ketua..."
              class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">{{ old('isi', $berita->isi ?? '') }}</textarea>
</div>

<label class="flex items-center gap-2.5 cursor-pointer select-none">
    <input type="hidden" name="penting" value="0">
    <input type="checkbox" name="penting" value="1" {{ old('penting', ($berita->penting ?? false) ? '1' : '0') == '1' ? 'checked' : '' }}
           class="w-4 h-4 rounded border-[#E7E7F4] text-lavender focus:ring-lavender">
    <span class="text-sm font-semibold">Tandai sebagai <span class="text-amber-600">Penting</span></span>
</label>
