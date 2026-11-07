<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Ketua - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden font-body bg-bgsoft text-ink min-h-screen">

<div class="flex flex-col lg:flex-row gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'berita'])

    <main class="flex-1 min-w-0 flex flex-col gap-5 pb-24 md:pb-0">

        @if (session('success'))
            <div class="bg-mint text-[#1F7A3D] text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        {{-- HERO --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-gradient-to-br from-[#B5BAFF] via-[#E4E5FF] to-white shadow-[0_10px_30px_-18px_rgba(63,65,176,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/40 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="flex items-center justify-between flex-wrap gap-4 relative">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#3F41B0]">Pengumuman</p>
                    <h1 class="font-display text-2xl font-semibold mt-1">Berita untuk Ketua</h1>
                    <p class="text-sm text-inksoft mt-1 max-w-md">Tulis berita atau pengumuman. Ketua ekskul yang kamu bina langsung menerimanya di aplikasi, lengkap dengan titik notifikasi.</p>
                </div>
                <div class="bg-white/70 rounded-2xl px-4 py-2.5 text-center">
                    <div class="font-display text-xl font-bold text-[#3F41B0]">{{ $berita->total() }}</div>
                    <div class="text-[11px] text-inksoft">Berita terkirim</div>
                </div>
            </div>

            @if($adaEkskulTanpaPembina)
                <p class="relative text-xs text-amber-700 bg-amber-50 mt-4 px-3 py-2 rounded-xl max-w-lg">
                    Kamu belum ditugaskan membina ekskul manapun. Hubungi Admin agar kamu dikaitkan ke sebuah ekskul dulu.
                </p>
            @endif
        </div>

        {{-- DAFTAR --}}
        <div class="animate-fade-in-up animate-delay-1 bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">

            <div class="flex flex-wrap justify-between items-center gap-4 mb-5">
                <form method="GET" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" placeholder="Cari judul / isi berita..." value="{{ request('search') }}"
                           class="px-4 py-2 bg-bgsoft border-none rounded-full text-sm text-ink placeholder-inksoft focus:ring-2 focus:ring-lavender w-56">
                    @include('pembina.partials.filter-ekskul')
                    <button type="submit" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-ink rounded-full text-sm font-bold transition-colors">Cari</button>
                    @if(request('search') || $filterEkskul)
                        <a href="{{ route('pembina.berita.index') }}" class="px-4 py-2 bg-bgsoft hover:bg-[#e9edfb] text-inksoft rounded-full text-sm font-bold transition-colors">Reset</a>
                    @endif
                </form>

                <a href="{{ route('pembina.berita.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-lavender text-white rounded-full text-sm font-bold shadow-md shadow-lavender/30 hover:bg-[#8385f0] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tulis Berita
                </a>
            </div>

            <div class="flex flex-col divide-y divide-gray-100">
                @forelse($berita as $b)
                    <article class="py-4 flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                @if($b->penting)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">PENTING</span>
                                @endif
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-periwinkle/20 text-[#5E5CC7]">{{ $b->ekskul->nama_ekskul ?? '-' }}</span>
                                <span class="text-[11px] text-inksoft">{{ $b->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <h2 class="font-display text-base font-semibold text-ink">{{ $b->judul }}</h2>
                            <p class="text-sm text-inksoft mt-1 line-clamp-2 whitespace-pre-line">{{ $b->isi }}</p>
                        </div>

                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('pembina.berita.edit', $b->id_berita) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            </a>
                            <form action="{{ route('pembina.berita.destroy', $b->id_berita) }}" method="POST" onsubmit="return confirm('Yakin hapus berita ini? Berita juga hilang dari aplikasi ketua.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-12 text-inksoft">
                        <p class="font-bold text-sm">Belum ada berita.</p>
                        <p class="text-xs mt-1">Klik "Tulis Berita" untuk mengirim pengumuman ke ketua ekskul kamu.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $berita->links() }}
            </div>
        </div>
    </main>
</div>

</body>
</html>
