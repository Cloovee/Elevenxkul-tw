{{--
    Pilihan ekskul yang dibina (boleh lebih dari satu).
    Variabel: $ekskuls (koleksi Ekskul with pembina), $terpilih (array id_ekskul), $idPembinaSaatIni (nullable)
    Ekskul yang sudah dibina pembina lain tampil disabled agar tidak bentrok.
--}}
@php
    $terpilih = collect($terpilih ?? [])->map(fn ($v) => (int) $v)->all();
    $idPembinaSaatIni = $idPembinaSaatIni ?? null;
@endphp

<div>
    <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Ekskul yang Dibina</label>

    @if($ekskuls->isEmpty())
        <p class="text-amber-600 text-xs bg-amber-50 px-3 py-2 rounded-lg">
            <i class="fas fa-triangle-exclamation mr-1"></i>
            Belum ada data ekskul. Tambahkan dulu lewat menu <strong>Kelola Ekskul</strong>.
        </p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 bg-[#F2F7FF] rounded-xl @error('ekskul') ring-2 ring-red-400 @enderror">
            @foreach($ekskuls as $e)
                @php
                    $dibinaLain = $e->id_pembina && (int) $e->id_pembina !== (int) $idPembinaSaatIni;
                @endphp
                <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-white text-sm {{ $dibinaLain ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-[#DDE8FB]' }}">
                    <input type="checkbox" name="ekskul[]" value="{{ $e->id_ekskul }}"
                           class="rounded border-[#DDE8FB] text-[#0B409C] focus:ring-[#0B409C]"
                           {{ in_array((int) $e->id_ekskul, $terpilih, true) ? 'checked' : '' }}
                           {{ $dibinaLain ? 'disabled' : '' }}>
                    <span class="text-[#10316B] font-semibold">{{ $e->nama_ekskul }}</span>
                    @if($dibinaLain)
                        <span class="ml-auto text-[10px] text-[#7C8DB5] whitespace-nowrap">dibina {{ $e->pembina->nama_pembina ?? 'pembina lain' }}</span>
                    @endif
                </label>
            @endforeach
        </div>
        <p class="text-xs text-[#7C8DB5] mt-2">Centang satu atau lebih ekskul. Ekskul yang sudah punya pembina lain tidak bisa dipilih.</p>
    @endif
    @error('ekskul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    @error('ekskul.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
