@extends('layouts.landing')

@section('title', $ekskul->nama_ekskul)
@section('description', \Illuminate\Support\Str::limit($ekskul->deskripsi ?: 'Informasi ekskul ' . $ekskul->nama_ekskul . ' di SMKN 11 Bandung.', 150))

@section('content')
@php
    // Class ditulis lengkap (bukan dirangkai) supaya terbaca oleh Tailwind.
    $gaya = [
        'organisasi' => [
            'header'  => 'bg-linear-to-br from-blue to-navy',
            'inisial' => 'text-white/15',
            'badge'   => 'bg-white/20 text-white',
            'judul'   => 'text-white',
            'sub'     => 'text-white/75',
        ],
        'ekstrakulikuler' => [
            'header'  => 'bg-linear-to-br from-gold to-[#F7C948]',
            'inisial' => 'text-navy/10',
            'badge'   => 'bg-navy/10 text-navy',
            'judul'   => 'text-navy',
            'sub'     => 'text-navy/75',
        ],
        'komunitas' => [
            'header'  => 'bg-linear-to-br from-[#2A5CB8] to-blue',
            'inisial' => 'text-white/15',
            'badge'   => 'bg-white/20 text-white',
            'judul'   => 'text-white',
            'sub'     => 'text-white/75',
        ],
    ];
    $g = $gaya[$ekskul->kategori] ?? $gaya['organisasi'];
    $labelKategori = $kategoriList[$ekskul->kategori] ?? ucfirst($ekskul->kategori);
@endphp

{{-- ================= HEADER EKSKUL ================= --}}
<section class="relative overflow-hidden {{ $g['header'] }}">
    <span class="absolute -right-4 -bottom-16 font-display font-bold text-[16rem] leading-none select-none {{ $g['inisial'] }}">
        {{ strtoupper(mb_substr($ekskul->nama_ekskul, 0, 1)) }}
    </span>

    <div class="relative max-w-6xl mx-auto px-5 py-12 sm:py-16">
        <a href="{{ route('landing') }}#ekskul"
           class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $g['sub'] }} hover:opacity-100 transition">
            &larr; Kembali ke daftar ekskul
        </a>

        <div class="mt-5">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $g['badge'] }}">
                {{ $labelKategori }}
            </span>
            <h1 class="font-display font-bold text-3xl sm:text-5xl mt-3 {{ $g['judul'] }}">
                {{ $ekskul->nama_ekskul }}
            </h1>
            <p class="mt-3 text-sm sm:text-base {{ $g['sub'] }}">
                {{ $ekskul->jumlah_anggota }} siswa aktif bergabung
            </p>
        </div>
    </div>
</section>

{{-- ================= ISI ================= --}}
<section class="max-w-6xl mx-auto px-5 -mt-6 relative">
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Kolom kiri: deskripsi --}}
        <div class="lg:col-span-2 bg-white rounded-3xl ring-1 ring-ink/[0.06] shadow-[0_2px_24px_-6px_rgba(16,49,107,0.12)] p-6 sm:p-8">
            <h2 class="font-display font-bold text-ink text-xl">Tentang Ekskul Ini</h2>

            @if($ekskul->deskripsi)
                <div class="text-ink/80 leading-relaxed mt-4 text-[15px]">
                    {!! nl2br(e($ekskul->deskripsi)) !!}
                </div>
            @else
                <p class="text-inksoft mt-4 text-[15px]">
                    Deskripsi ekskul ini belum ditambahkan. Tanyakan langsung ke pembina atau ketua ekskul untuk info lebih lengkap.
                </p>
            @endif

            <div class="mt-8 rounded-2xl bg-cloud p-5 sm:p-6">
                <p class="font-display font-bold text-ink text-lg">Tertarik bergabung?</p>
                <p class="text-inksoft text-sm mt-1.5 leading-relaxed">
                    Temui ketua atau pembina ekskul ini di sekolah untuk mendaftar. Pendaftaran anggota dicatat langsung oleh pengurus ekskul.
                </p>
            </div>
        </div>

        {{-- Kolom kanan: info singkat --}}
        <aside class="bg-white rounded-3xl ring-1 ring-ink/[0.06] shadow-[0_2px_24px_-6px_rgba(16,49,107,0.12)] p-6 sm:p-8 h-fit">
            <h2 class="font-display font-bold text-ink text-xl">Informasi</h2>

            <dl class="mt-5 space-y-5">
                <div>
                    <dt class="text-xs font-bold text-inksoft uppercase tracking-wide">Pembina</dt>
                    <dd class="mt-2">
                        @if($ekskul->pembina)
                            <div class="flex items-center gap-3">
                                @if($ekskul->pembina->foto_url)
                                    <img src="{{ $ekskul->pembina->foto_url }}" alt="{{ $ekskul->pembina->nama_pembina }}"
                                         class="w-10 h-10 rounded-full object-cover shrink-0">
                                @else
                                    <span class="w-10 h-10 rounded-full bg-blue text-white text-sm font-bold flex items-center justify-center shrink-0">
                                        {{ $ekskul->pembina->inisial }}
                                    </span>
                                @endif
                                <span class="font-semibold text-ink">{{ $ekskul->pembina->nama_pembina }}</span>
                            </div>
                        @else
                            <span class="text-inksoft text-sm">Belum ditentukan</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-inksoft uppercase tracking-wide">Pelatih</dt>
                    <dd class="mt-1.5 font-semibold text-ink">
                        {{ $ekskul->pelatih->nama_pelatih ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-inksoft uppercase tracking-wide">Ketua</dt>
                    <dd class="mt-1.5 font-semibold text-ink">
                        {{ $ekskul->ketua->nama_siswa ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-inksoft uppercase tracking-wide">Anggota Aktif</dt>
                    <dd class="mt-1.5 font-semibold text-ink">{{ $ekskul->jumlah_anggota }} siswa</dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-inksoft uppercase tracking-wide">Kategori</dt>
                    <dd class="mt-1.5 font-semibold text-ink">{{ $labelKategori }}</dd>
                </div>
            </dl>
        </aside>
    </div>
</section>

{{-- ================= EKSKUL LAINNYA ================= --}}
@if($lainnya->count())
    <section class="max-w-6xl mx-auto px-5 mt-16">
        <h2 class="font-display font-bold text-ink text-xl sm:text-2xl">Ekskul Lainnya</h2>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
            @foreach($lainnya as $item)
                @php $gi = $gaya[$item->kategori] ?? $gaya['organisasi']; @endphp

                <a href="{{ route('landing.ekskul', $item->id_ekskul) }}"
                   class="group flex items-center gap-4 bg-white rounded-2xl p-4 ring-1 ring-ink/[0.06] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-ink/10 transition-all">
                    <span class="w-14 h-14 rounded-xl {{ $gi['header'] }} flex items-center justify-center shrink-0 font-display font-bold text-2xl {{ $gi['judul'] }}">
                        {{ strtoupper(mb_substr($item->nama_ekskul, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-display font-bold text-ink group-hover:text-blue transition truncate">{{ $item->nama_ekskul }}</p>
                        <p class="text-xs text-inksoft mt-0.5">{{ $kategoriList[$item->kategori] ?? ucfirst($item->kategori) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif

@endsection