{{--
    Penanda ekskul yang sedang dibuka (ADMIN saja) + tautan kembali ke daftar seluruh ekskul.
    Pengganti dropdown filter di halaman absensi pelatih / peserta.
    Variabel: $routeName (nama route halaman daftar/absensi ini)
--}}
@if (\App\Support\EkskulAktif::admin())
    @php $ekskulDibuka = \App\Support\EkskulAktif::untuk(auth()->user()); @endphp
    @if ($ekskulDibuka)
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white rounded-2xl ring-1 ring-black/5 shadow-sm px-4 py-3">
            <p class="text-sm text-inksoft">Ekskul: <span class="font-semibold text-ink">{{ $ekskulDibuka->nama_ekskul }}</span></p>
            <a href="{{ route($routeName) }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 transition">
                &larr; Ganti ekskul
            </a>
        </div>
    @endif
@endif
