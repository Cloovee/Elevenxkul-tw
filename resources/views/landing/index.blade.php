@extends('layouts.landing')

@section('title', 'Daftar Ekstrakurikuler')

@section('content')
@php
    // Warna per kategori. Class ditulis lengkap (bukan dirangkai) supaya terbaca oleh Tailwind.
    $gaya = [
        'organisasi' => [
            'header'  => 'bg-linear-to-br from-blue to-navy',
            'inisial' => 'text-white/20',
            'badge'   => 'bg-white/20 text-white',
        ],
        'ekstrakulikuler' => [
            'header'  => 'bg-linear-to-br from-gold to-[#F7C948]',
            'inisial' => 'text-navy/15',
            'badge'   => 'bg-navy/10 text-navy',
        ],
        'komunitas' => [
            'header'  => 'bg-linear-to-br from-[#2A5CB8] to-blue',
            'inisial' => 'text-white/20',
            'badge'   => 'bg-white/20 text-white',
        ],
    ];
    $gayaDefault = $gaya['organisasi'];
@endphp

{{-- ================= HERO ================= --}}
<section class="relative overflow-hidden bg-navy">
    <img src="{{ asset('images/smkn11foto.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-navy/80"></div>

    <div class="relative max-w-6xl mx-auto px-5 py-16 sm:py-24 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full bg-gold text-navy text-xs font-bold tracking-wide">
            EKSTRAKURIKULER SMKN 11 BANDUNG
        </span>

        <h1 class="font-display font-bold text-white text-3xl sm:text-5xl leading-tight mt-5">
            Temukan Ekskul yang <br class="hidden sm:block">
            <span class="text-gold">Cocok dengan Minatmu</span>
        </h1>

        <p class="text-white/80 max-w-xl mx-auto mt-5 text-sm sm:text-base leading-relaxed">
            Kembangkan bakat, cari teman baru, dan raih prestasi bersama. Lihat daftar ekskul di bawah,
            lalu klik salah satunya untuk tahu kegiatan dan siapa pembinanya.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-8">
            <a href="#ekskul"
               class="w-full sm:w-auto px-7 py-3 rounded-xl bg-gold text-navy font-bold shadow-lg shadow-black/20 hover:-translate-y-0.5 transition">
                Lihat Daftar Ekskul
            </a>
        </div>

        <div class="flex items-center justify-center gap-8 sm:gap-14 mt-12">
            <div>
                <p class="font-display font-bold text-gold text-3xl sm:text-4xl">{{ $totalEkskul }}</p>
                <p class="text-white/70 text-xs sm:text-sm mt-1">Pilihan Ekskul</p>
            </div>
            <div class="w-px h-10 bg-white/20"></div>
            <div>
                <p class="font-display font-bold text-gold text-3xl sm:text-4xl">{{ $totalAnggota }}</p>
                <p class="text-white/70 text-xs sm:text-sm mt-1">Siswa Aktif Bergabung</p>
            </div>
        </div>
    </div>
</section>

{{-- ================= DAFTAR EKSKUL ================= --}}
<section id="ekskul" class="max-w-6xl mx-auto px-5 pt-14 scroll-mt-20">

    <div class="text-center mb-8">
        <h2 class="font-display font-bold text-ink text-2xl sm:text-3xl">Daftar Ekstrakurikuler</h2>
        <p class="text-inksoft mt-2 text-sm sm:text-base">Klik salah satu ekskul untuk melihat detail informasinya.</p>
    </div>

    {{-- Pencarian + filter kategori --}}
    <form method="GET" action="{{ route('landing') }}#ekskul" class="flex flex-col gap-4 mb-8">
        @if($kategoriAktif)
            <input type="hidden" name="kategori" value="{{ $kategoriAktif }}">
        @endif

        <div class="relative max-w-md w-full mx-auto">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-inksoft/70">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                </svg>
            </span>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari ekskul..."
                   class="w-full pl-11 pr-4 py-3 rounded-2xl border border-ink/10 bg-white text-ink placeholder:text-inksoft/60 focus:outline-none focus:ring-2 focus:ring-blue/40 focus:border-blue transition">
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('landing', array_filter(['q' => $search])) }}#ekskul"
               class="px-4 py-2 rounded-full text-sm font-semibold transition {{ ! $kategoriAktif ? 'bg-blue text-white shadow-md shadow-blue/25' : 'bg-white text-inksoft hover:text-blue ring-1 ring-ink/10' }}">
                Semua
            </a>
            @foreach($kategoriList as $key => $label)
                <a href="{{ route('landing', array_filter(['kategori' => $key, 'q' => $search])) }}#ekskul"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition {{ $kategoriAktif === $key ? 'bg-blue text-white shadow-md shadow-blue/25' : 'bg-white text-inksoft hover:text-blue ring-1 ring-ink/10' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </form>

    @if($ekskuls->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ekskuls as $ekskul)
                @php $g = $gaya[$ekskul->kategori] ?? $gayaDefault; @endphp

                <a href="{{ route('landing.ekskul', $ekskul->id_ekskul) }}"
                   class="group flex flex-col bg-white rounded-3xl overflow-hidden ring-1 ring-ink/[0.06] shadow-[0_2px_24px_-6px_rgba(16,49,107,0.12)] hover:-translate-y-1 hover:shadow-[0_12px_32px_-8px_rgba(16,49,107,0.25)] transition-all">

                    {{-- Header berwarna + huruf awal (pengganti foto) --}}
                    <div class="relative h-36 {{ $g['header'] }} overflow-hidden">
                        <span class="absolute -right-2 -bottom-8 font-display font-bold text-[9rem] leading-none select-none {{ $g['inisial'] }}">
                            {{ strtoupper(mb_substr($ekskul->nama_ekskul, 0, 1)) }}
                        </span>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold {{ $g['badge'] }}">
                            {{ $kategoriList[$ekskul->kategori] ?? ucfirst($ekskul->kategori) }}
                        </span>
                    </div>

                    <div class="flex flex-col flex-1 p-5">
                        <h3 class="font-display font-bold text-ink text-lg group-hover:text-blue transition">
                            {{ $ekskul->nama_ekskul }}
                        </h3>

                        <p class="text-inksoft text-sm mt-2 leading-relaxed flex-1">
                            {{ $ekskul->deskripsi ? \Illuminate\Support\Str::limit($ekskul->deskripsi, 110) : 'Klik untuk melihat informasi lengkap ekskul ini.' }}
                        </p>

                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-ink/5 text-xs">
                            <span class="text-inksoft">
                                <span class="font-bold text-ink">{{ $ekskul->jumlah_anggota }}</span> anggota aktif
                            </span>
                            <span class="font-semibold text-blue group-hover:translate-x-0.5 transition">
                                Lihat detail &rarr;
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $ekskuls->fragment('ekskul')->links() }}
        </div>
    @else
        <div class="text-center bg-white rounded-3xl ring-1 ring-ink/[0.06] py-16 px-6">
            <p class="font-display font-bold text-ink text-lg">Ekskul tidak ditemukan</p>
            <p class="text-inksoft text-sm mt-2">Coba kata kunci lain atau pilih kategori "Semua".</p>
            <a href="{{ route('landing') }}#ekskul"
               class="inline-block mt-5 px-5 py-2.5 rounded-xl bg-blue text-white text-sm font-semibold hover:bg-navy transition">
                Reset pencarian
            </a>
        </div>
    @endif
</section>

@endsection