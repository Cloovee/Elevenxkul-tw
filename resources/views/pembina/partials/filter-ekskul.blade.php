{{--
    Dropdown filter berdasarkan nama ekskul (auto-submit ke form GET pembungkusnya).
    Pakai di dalam <form method="GET">.
    Variabel: $ekskulList (koleksi Ekskul milik pembina), $filterEkskul (id ekskul terpilih / null)
--}}
<select name="ekskul" onchange="this.form.submit()" aria-label="Filter ekskul"
        class="py-2 pl-4 pr-9 bg-bgsoft border-none rounded-full text-sm font-semibold text-ink focus:ring-2 focus:ring-lavender cursor-pointer max-w-[220px]">
    <option value="">Semua Ekskul</option>
    @foreach ($ekskulList as $e)
        <option value="{{ $e->id_ekskul }}" {{ (int) $filterEkskul === (int) $e->id_ekskul ? 'selected' : '' }}>
            {{ $e->nama_ekskul }}
        </option>
    @endforeach
</select>
