<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Peserta - Xkul11</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden font-body bg-bgsoft text-ink min-h-screen">

@php
    $batas = config('penilaian.batas');
    $bobot = config('penilaian.bobot');
    $minimum = \App\Support\Penilaian::nilaiMinimum();
    $angka = fn ($v) => $v === null ? '' : rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');
    $queryExport = array_filter([
        'ekskul' => $filterEkskul,
        'tahun_ajaran' => $tahunAjaran,
        'semester' => $semester,
    ]);
    // Sedang mencari / menyaring jurusan?
    $adaPencarian = $cari !== '' || $filterJurusan !== '';
    // Link "Reset": hapus pencarian & jurusan, tetap di ekskul/semester yang sama.
    $urlReset = route('pembina.nilai.index', $queryExport);
@endphp

<div class="flex flex-col lg:flex-row gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'nilai'])

    <main class="flex-1 min-w-0 flex flex-col gap-5 pb-24 md:pb-0">

        @if (session('success'))
            <div class="bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <ul class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HERO — periwinkle theme --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-gradient-to-br from-[#B5BAFF] via-[#E4E5FF] to-white shadow-[0_10px_30px_-18px_rgba(63,65,176,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/30 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-wide text-[#3F41B0]">Penilaian</p>
                <h1 class="font-display text-2xl font-semibold mt-1">Grade Peserta</h1>
                <p class="text-sm text-inksoft mt-1 max-w-xl">Grade dihitung dari <b>kehadiran</b> (otomatis dari absensi) dan <b>keaktifan</b> (kamu isi angkanya 0&ndash;100, sistem mengubahnya jadi grade). Peserta yang tidak pernah hadir tidak diberi nilai.</p>
            </div>
        </div>

        {{-- FILTER + EXPORT --}}
        <div class="animate-fade-in-up animate-delay-1 bg-white rounded-3xl px-6 py-4 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('pembina.nilai.index') }}" class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-bold uppercase tracking-wide text-inksoft">Filter</span>
                @include('pembina.partials.filter-ekskul')

                {{-- Filter jurusan (program keahlian kelas siswa) --}}
                <select name="jurusan" onchange="this.form.submit()" aria-label="Filter jurusan"
                        class="py-2 pl-4 pr-9 bg-bgsoft border-none rounded-full text-sm font-semibold text-ink focus:ring-2 focus:ring-lavender cursor-pointer max-w-[200px]">
                    <option value="">Semua Jurusan</option>
                    @foreach ($opsiJurusan as $j)
                        <option value="{{ $j }}" @selected($filterJurusan === $j)>{{ $j }}</option>
                    @endforeach
                </select>

                <select name="tahun_ajaran" onchange="this.form.submit()" aria-label="Tahun ajaran"
                        class="py-2 pl-4 pr-9 bg-bgsoft border-none rounded-full text-sm font-semibold text-ink focus:ring-2 focus:ring-lavender cursor-pointer">
                    @foreach ($opsiTahunAjaran as $ta)
                        <option value="{{ $ta }}" @selected($ta === $tahunAjaran)>TA {{ $ta }}</option>
                    @endforeach
                </select>

                <select name="semester" onchange="this.form.submit()" aria-label="Semester"
                        class="py-2 pl-4 pr-9 bg-bgsoft border-none rounded-full text-sm font-semibold text-ink focus:ring-2 focus:ring-lavender cursor-pointer">
                    <option value="1" @selected($semester === 1)>Semester 1</option>
                    <option value="2" @selected($semester === 2)>Semester 2</option>
                </select>

                {{-- Cari peserta: nama / NIS / NISN --}}
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft pointer-events-none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="search" name="search" value="{{ $cari }}" placeholder="Cari nama / NIS / NISN" autocomplete="off"
                           class="pl-10 pr-4 py-2 bg-bgsoft border-none rounded-full text-sm text-ink placeholder-inksoft focus:ring-2 focus:ring-lavender w-72 max-w-full">
                </div>
                <button type="submit" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-ink rounded-full text-sm font-bold transition-colors">Cari</button>
                @if ($adaPencarian)
                    <a href="{{ $urlReset }}" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-inksoft rounded-full text-sm font-bold transition-colors">Reset</a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wide text-inksoft hidden sm:inline">Export</span>
                <a href="{{ route('pembina.nilai.export', $queryExport + ['format' => 'xlsx']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-mint text-[#1F7A3D] hover:brightness-95 rounded-full text-sm font-bold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Excel
                </a>
                <a href="{{ route('pembina.nilai.export', $queryExport + ['format' => 'pdf']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-lavender text-white hover:bg-[#8385f0] rounded-full text-sm font-bold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    PDF
                </a>
            </div>
        </div>

        {{-- NOTIFIKASI: tahun ajaran yang dipilih belum punya data anggota --}}
        @if ($anggotaBelumTersedia)
            <div class="flex items-start gap-3 bg-amber-50 text-amber-700 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>
                    Data anggota untuk TA {{ $tahunAjaran }} belum tersedia. Daftar di bawah masih menampilkan anggota TA {{ $tahunAjaranAnggota }} (tahun ajaran aktif saat ini).
                </span>
            </div>
        @endif

        {{-- ATURAN SINGKAT --}}
        <div class="animate-fade-in-up animate-delay-1 rounded-2xl bg-white/70 px-5 py-3 text-xs text-inksoft leading-relaxed">
            <b class="text-ink">Cara hitung:</b>
            Grade <b>A</b> = {{ $batas['A'] }} ke atas, <b>B</b> = {{ $batas['B'] }}–{{ $batas['A'] - 1 }}, <b>C</b> = {{ $batas['C'] }}–{{ $batas['B'] - 1 }}
            (nilai {{ $batas['C'] }} paling rendah).
            Kehadiran = persentase hadir pada semester ini.
            Grade akhir = gabungan grade kehadiran ({{ $bobot['kehadiran'] }}%) dan keaktifan ({{ $bobot['keaktifan'] }}%).
            Peserta yang <b>tidak pernah hadir</b> pada semester ini <b>tidak diberi nilai</b> (tanpa grade).
        </div>

        {{-- FORM BERI NILAI --}}
        <div id="beri-nilai" class="scroll-mt-5 animate-fade-in-up animate-delay-2 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-1">Beri Nilai &mdash; Semester {{ $semester }} TA {{ $tahunAjaran }}</h3>
            <p class="text-xs text-inksoft mb-3">Isi nilai keaktifan ({{ $minimum }}&ndash;100). Menyimpan lagi untuk peserta yang sama memperbarui nilai semester ini.
                @if ($daftarPeserta->total() > 0)
                    <span class="font-semibold text-ink">{{ $daftarPeserta->total() }} peserta{{ $adaPencarian ? ' ditemukan' : '' }}.</span>
                @endif
            </p>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[860px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kehadiran</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Nilai keaktifan</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Grade</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Catatan</th>
                        <th class="py-2 px-2 border-b border-[#EFEFF7]"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarPeserta as $p)
                        @php
                            $k = $kehadiran[$p->id_anggota];
                            $n = $nilaiSemester->get($p->id_anggota);
                            $adaAbsensi = $k['total'] > 0;
                            // Tidak pernah hadir sama sekali -> tidak dinilai (tanpa grade).
                            $pernahHadir = \App\Support\Penilaian::pernahHadir($k);
                            $tanpaHadir = $adaAbsensi && ! $pernahHadir;
                            $formId = 'form-nilai-'.$p->id_anggota;
                        @endphp
                        <tr class="js-baris border-b border-[#F5F5FA] last:border-none hover:bg-bgsoft/60 transition-colors"
                            data-grade-kehadiran="{{ $pernahHadir ? $k['grade'] : '' }}">
                            <td class="py-2.5 px-2">
                                <form id="{{ $formId }}" method="POST" action="{{ route('pembina.nilai.simpan', $p) }}" class="hidden">
                                    @csrf
                                    @if($filterEkskul)
                                        <input type="hidden" name="ekskul" value="{{ $filterEkskul }}">
                                    @endif
                                    <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                                    <input type="hidden" name="semester" value="{{ $semester }}">
                                    {{-- Supaya setelah menyimpan tetap di pencarian, jurusan, dan halaman yang sama --}}
                                    @if($cari !== '')
                                        <input type="hidden" name="search" value="{{ $cari }}">
                                    @endif
                                    @if($filterJurusan !== '')
                                        <input type="hidden" name="jurusan" value="{{ $filterJurusan }}">
                                    @endif
                                    @if($daftarPeserta->currentPage() > 1)
                                        <input type="hidden" name="page" value="{{ $daftarPeserta->currentPage() }}">
                                    @endif
                                </form>
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-periwinkle text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $p->nama))->map(fn($s) => $s[0] ?? '')->take(2)->implode('') }}
                                    </div>
                                    <div>
                                        <div>{{ $p->nama }}</div>
                                        <div class="text-[11px] font-normal text-inksoft">{{ $p->ekskul->nama_ekskul ?? '-' }}@if($p->kelas) &middot; {{ $p->kelas }}@endif</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-2">
                                @if($tanpaHadir)
                                    {{-- Ada absensi tapi tidak pernah hadir: tanpa grade --}}
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 h-7 rounded-lg bg-red-50 text-red-600 text-[11px] font-bold">Tidak pernah hadir</span>
                                        <span class="font-semibold">{{ $angka($k['persen']) }}%</span>
                                    </div>
                                    <div class="text-[11px] text-inksoft mt-0.5">
                                        Hadir {{ $k['hadir'] }}/{{ $k['total'] }}
                                        &middot; I {{ $k['izin'] }} &middot; S {{ $k['sakit'] }} &middot; A {{ $k['alpha'] }}
                                    </div>
                                @elseif($adaAbsensi)
                                    <div class="flex items-center gap-2">
                                        @include('pembina.nilai-peserta._grade', ['g' => $k['grade']])
                                        <span class="font-semibold">{{ $angka($k['persen']) }}%</span>
                                    </div>
                                    <div class="text-[11px] text-inksoft mt-0.5">
                                        Hadir {{ $k['hadir'] }}/{{ $k['total'] }}
                                        &middot; I {{ $k['izin'] }} &middot; S {{ $k['sakit'] }} &middot; A {{ $k['alpha'] }}
                                    </div>
                                @else
                                    <span class="text-xs text-amber-600 font-semibold">Belum ada absensi semester ini</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-2">
                                <input type="number" form="{{ $formId }}" name="nilai_keaktifan"
                                       min="{{ $minimum }}" max="100" step="0.01" required
                                       placeholder="{{ $pernahHadir ? $minimum.'-100' : '—' }}"
                                       value="{{ $pernahHadir ? $angka($n?->nilai_keaktifan) : '' }}"
                                       @disabled(! $pernahHadir)
                                       class="js-keaktifan w-24 text-center px-2.5 py-1.5 rounded-lg border border-[#E7E7F4] focus:outline-none focus:border-lavender disabled:bg-bgsoft disabled:cursor-not-allowed">
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="js-hasil flex items-center gap-1.5">
                                    @if(! $pernahHadir)
                                        <span class="text-xs font-semibold text-inksoft">Tanpa grade</span>
                                    @elseif($n && $n->grade_akhir)
                                        @include('pembina.nilai-peserta._grade', ['g' => $n->grade_akhir])
                                        <span class="text-[11px] text-inksoft">Keaktifan {{ $n->grade_keaktifan }}</span>
                                    @else
                                        <span class="text-inksoft">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-2">
                                <input type="text" form="{{ $formId }}" name="catatan_pembina" maxlength="1000"
                                       placeholder="Opsional" value="{{ $pernahHadir ? $n?->catatan_pembina : '' }}"
                                       @disabled(! $pernahHadir)
                                       class="w-40 px-2.5 py-1.5 rounded-lg border border-[#E7E7F4] text-xs focus:outline-none focus:border-lavender disabled:bg-bgsoft disabled:cursor-not-allowed">
                            </td>
                            <td class="py-2.5 px-2 text-right">
                                <button type="submit" form="{{ $formId }}" @disabled(! $pernahHadir)
                                        @if(! $pernahHadir) title="{{ $tanpaHadir ? 'Tidak pernah hadir: tidak diberi nilai' : 'Belum ada absensi semester ini' }}" @endif
                                        class="bg-lavender hover:bg-[#8385f0] text-white font-bold text-xs px-3.5 py-1.5 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed">
                                    {{ ($n && $pernahHadir) ? 'Perbarui' : 'Simpan' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-lavender/30 text-[#3F41B0] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
                                    </div>
                                    @if($adaPencarian)
                                        <p class="text-sm font-semibold text-ink">Peserta tidak ditemukan</p>
                                        <p class="text-xs text-inksoft max-w-xs">Tidak ada peserta yang cocok dengan pencarian{{ $cari !== '' ? ' "'.$cari.'"' : '' }}{{ $filterJurusan !== '' ? ' di jurusan '.$filterJurusan : '' }}.</p>
                                        <a href="{{ $urlReset }}" class="mt-1 px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-ink rounded-full text-xs font-bold transition-colors">Reset pencarian</a>
                                    @else
                                        <p class="text-sm font-semibold text-ink">Belum ada data peserta aktif</p>
                                        <p class="text-xs text-inksoft max-w-xs">{{ $filterEkskul ? 'Belum ada peserta aktif di ekskul yang dipilih.' : 'Peserta aktif di ekskul yang kamu bina akan tampil di sini.' }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            {{ $daftarPeserta->links('vendor.pagination.tema', ['satuan' => 'peserta']) }}
        </div>

        {{-- RIWAYAT --}}
        <div id="riwayat" class="scroll-mt-5 animate-fade-in-up animate-delay-3 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-3">Riwayat Nilai</h3>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[760px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Periode</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kehadiran</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Keaktifan</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Grade Akhir</th>
                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatNilai as $n)
                        <tr class="border-b border-[#F5F5FA] last:border-none hover:bg-bgsoft/60 transition-colors">
                            <td class="py-2.5 px-2">
                                <div class="font-semibold">{{ $n->peserta->nama ?? '-' }}</div>
                                <div class="text-[11px] text-inksoft">{{ $n->peserta->ekskul->nama_ekskul ?? '-' }}</div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">Sem {{ $n->semester }} &middot; {{ $n->tahun_ajaran }}</td>
                            <td class="py-2.5 px-2">
                                @if($n->grade_kehadiran)
                                    <div class="flex items-center gap-2">
                                        @include('pembina.nilai-peserta._grade', ['g' => $n->grade_kehadiran])
                                        <span class="text-xs text-inksoft">{{ $angka($n->persen_kehadiran) }}% ({{ $n->jumlah_hadir }}/{{ $n->jumlah_pertemuan }})</span>
                                    </div>
                                @else
                                    <span class="text-inksoft">—</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-2">
                                @if($n->grade_keaktifan)
                                    <div class="flex items-center gap-2">
                                        @include('pembina.nilai-peserta._grade', ['g' => $n->grade_keaktifan])
                                        <span class="text-xs text-inksoft">{{ $angka($n->nilai_keaktifan) }}</span>
                                    </div>
                                @else
                                    <span class="text-inksoft">—</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-2">
                                @if($n->grade_akhir)
                                    @include('pembina.nilai-peserta._grade', ['g' => $n->grade_akhir])
                                @else
                                    <span class="text-[11px] text-amber-600 font-semibold">Belum dihitung &mdash; buka Edit lalu simpan</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('pembina.nilai.edit', $n) }}" title="Edit"
                                       class="w-[30px] h-[30px] rounded-lg bg-sky text-[#1E6FA8] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form id="delete-nilai-{{ $n->id_nilai }}" method="POST" action="{{ route('pembina.nilai.destroy', $n) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" title="Hapus"
                                            data-delete-form="delete-nilai-{{ $n->id_nilai }}"
                                            data-delete-message="Hapus nilai semester {{ $n->semester }} milik {{ $n->peserta->nama ?? '' }} ini? Tindakan ini tidak bisa dibatalkan."
                                            class="w-[30px] h-[30px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-lavender/30 text-[#3F41B0] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-ink">Belum ada riwayat nilai</p>
                                    <p class="text-xs text-inksoft max-w-xs">Nilai yang sudah kamu simpan akan muncul di riwayat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            {{ $riwayatNilai->links('vendor.pagination.tema', ['satuan' => 'riwayat']) }}
        </div>

    </main>
</div>

@include('pembina.partials.delete-modal')

{{-- Pratinjau grade langsung saat mengetik nilai keaktifan (aturan sama persis dengan server). --}}
<script>
    (function () {
        const BATAS = @json($batas);
        const POIN = @json(config('penilaian.poin'));
        const BOBOT = @json($bobot);
        const WARNA = {
            A: 'bg-mint text-[#1F7A3D]',
            B: 'bg-sky text-[#1E6FA8]',
            C: 'bg-amber-100 text-amber-700',
        };

        function gradeDariAngka(a) {
            for (const [g, min] of Object.entries(BATAS)) { if (a >= min) return g; }
            return Object.keys(BATAS).pop();
        }

        function gabungkan(k, a) {
            const skor = (POIN[k] * BOBOT.kehadiran + POIN[a] * BOBOT.keaktifan) / (BOBOT.kehadiran + BOBOT.keaktifan);
            const nilai = Object.values(POIN);
            const bulat = Math.max(Math.min(...nilai), Math.min(Math.max(...nilai), Math.floor(skor + 0.5 + 1e-9)));
            return Object.keys(POIN).find(g => POIN[g] === bulat);
        }

        document.querySelectorAll('.js-baris').forEach(function (baris) {
            const input = baris.querySelector('.js-keaktifan');
            const hasil = baris.querySelector('.js-hasil');
            const gk = baris.dataset.gradeKehadiran;
            if (!input || !gk) return;

            input.addEventListener('input', function () {
                const v = parseFloat(input.value);
                if (isNaN(v)) return;
                const ga = gradeDariAngka(v);
                const akhir = gabungkan(gk, ga);
                hasil.innerHTML =
                    '<span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-lg text-xs font-extrabold ' + WARNA[akhir] + '">' + akhir + '</span>' +
                    '<span class="text-[11px] text-inksoft">Keaktifan ' + ga + ' (belum disimpan)</span>';
            });
        });
    })();
</script>

</body>
</html>