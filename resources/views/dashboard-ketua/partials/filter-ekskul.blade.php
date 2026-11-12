{{--
    Filter ekskul untuk ADMIN di halaman role Ketua (Ketua tidak melihat ini: ekskulnya sudah tetap).
    Form GET; parameter lain di URL (tanggal, bulan, kelas, ...) ikut dibawa lewat input hidden.

    Variabel:
      $mode : 'pilih' -> wajib memilih satu ekskul ("Pilih ekskul...")
              'semua' -> boleh kosong = semua ekskul ("Semua ekskul")
--}}
@if (\App\Support\EkskulAktif::admin())
    @php
        $mode = $mode ?? 'semua';
        $ekskulPilihan = \App\Support\EkskulAktif::pilihan();
        $ekskulTerpilih = \App\Support\EkskulAktif::id(auth()->user());
    @endphp
    <form method="GET" action="{{ url()->current() }}"
          class="flex flex-col sm:flex-row sm:items-end gap-3 bg-white rounded-2xl ring-1 ring-black/5 shadow-sm px-4 py-3">
        @foreach (request()->except(['ekskul', 'page']) as $nama => $nilai)
            @if (is_scalar($nilai))
                <input type="hidden" name="{{ $nama }}" value="{{ $nilai }}">
            @endif
        @endforeach
        <div class="w-full sm:w-72">
            <label for="filter-ekskul-admin" class="block text-xs font-medium text-inksoft mb-1">Ekskul</label>
            <select name="ekskul" id="filter-ekskul-admin" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition">
                <option value="">{{ $mode === 'pilih' ? 'Pilih ekskul...' : 'Semua ekskul' }}</option>
                @foreach ($ekskulPilihan as $e)
                    <option value="{{ $e->id_ekskul }}" {{ (int) $ekskulTerpilih === (int) $e->id_ekskul ? 'selected' : '' }}>{{ $e->nama_ekskul }}</option>
                @endforeach
            </select>
        </div>
        @if ($ekskulTerpilih)
            <a href="{{ url()->current() }}?{{ http_build_query(request()->except(['ekskul', 'page'])) }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200 transition">
                Reset
            </a>
        @endif
        @if ($mode === 'pilih' && ! $ekskulTerpilih)
            <p class="sm:ml-auto text-xs text-amber-600 font-medium self-center">Pilih ekskul dulu untuk melanjutkan.</p>
        @elseif ($mode === 'semua' && ! $ekskulTerpilih)
            <p class="sm:ml-auto text-xs text-inksoft self-center">Menampilkan semua ekskul.</p>
        @endif
    </form>
@endif
