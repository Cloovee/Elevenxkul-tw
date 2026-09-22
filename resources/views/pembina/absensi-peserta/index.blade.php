<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Peserta — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen">

<div class="flex gap-5 p-5 min-h-screen">

    @include('pembina.partials.sidebar', ['active' => 'absensi'])

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

        {{-- HERO — periwinkle theme, disamakan dengan halaman Kelola Pelatih --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-gradient-to-br from-[#B5BAFF] via-[#E4E5FF] to-white shadow-[0_10px_30px_-18px_rgba(63,65,176,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/40 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="flex items-center justify-between flex-wrap gap-4 relative">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#3F41B0]">Kehadiran</p>
                    <h1 class="font-display text-2xl font-semibold mt-1">Absensi Peserta</h1>
                    <p class="text-sm text-inksoft mt-1 max-w-md">Data absensi tiap ekskul yang kamu bina, dikelompokkan per tanggal submit dari Ketua.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/70 rounded-2xl px-4 py-2.5 text-center">
                        <div class="font-display text-xl font-bold text-[#3F41B0]">{{ $hadirBulanIni }}/{{ $totalBulanIni }}</div>
                        <div class="text-[11px] text-inksoft">Hadir bulan ini</div>
                    </div>
                </div>
            </div>
            <p class="relative text-xs text-inksoft mt-4 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Data ini otomatis terisi dari input absensi peserta yang dilakukan Ketua. Klik nama ekskul untuk melihat riwayat, lalu klik tanggal untuk melihat detail kehadiran.
            </p>
            <a href="{{ route('pembina.validasi.index') }}" class="relative inline-flex items-center gap-1 text-xs font-semibold text-[#3F41B0] mt-2 hover:underline">
                Lihat absensi pelatih
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- DAFTAR EKSKUL — kartu per ekskul, dropdown riwayat tanggal, dropdown detail per tanggal --}}
        <div class="animate-fade-in-up animate-delay-2 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-4">Data Absensi per Ekskul</h3>

            @if ($riwayatPerEkskul->isEmpty())
                <div class="flex flex-col items-center justify-center text-center gap-2 py-12">
                    <div class="w-14 h-14 rounded-2xl bg-lavender/30 text-[#3F41B0] flex items-center justify-center mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6M9 8h1"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-ink">Belum ada ekskul yang kamu bina</p>
                    <p class="text-xs text-inksoft max-w-xs">Hubungi admin jika ini keliru.</p>
                </div>
            @else
                <div class="flex flex-col gap-3" x-data="{ openEkskul: 0 }">
                    @foreach ($riwayatPerEkskul as $i => $item)
                        @php
                            $ekskul = $item['ekskul'];
                            $terbaru = $item['sesi_terbaru'];
                            $initial = collect(explode(' ', $ekskul->nama_ekskul ?? '-'))->map(fn($s) => $s[0] ?? '')->take(2)->implode('');
                        @endphp
                        <div class="border border-[#EFEFF7] rounded-2xl overflow-hidden">

                            {{-- TOMBOL KARTU EKSKUL: nama ekskul | hadir/total peserta | tanggal submit --}}
                            <button type="button"
                                    @click="openEkskul = (openEkskul === {{ $i }} ? null : {{ $i }})"
                                    class="w-full flex items-center justify-between gap-4 px-4 py-3.5 text-left bg-[#EEF0FF] hover:bg-[#E4E5FF] transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-lavender/70 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm truncate">{{ $ekskul->nama_ekskul }}</p>
                                        <p class="text-[11px] text-inksoft">{{ $item['total_sesi'] }} kali submit absensi</p>
                                    </div>
                                </div>

                                <div class="hidden sm:flex items-center gap-8 shrink-0">
                                    <div class="text-center">
                                        <p class="text-sm font-semibold text-ink">
                                            @if ($terbaru)
                                                {{ $terbaru['hadir'] }}/{{ $terbaru['total'] }}
                                            @else
                                                -/{{ $item['total_anggota'] }}
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-inksoft uppercase tracking-wide">Hadir/Peserta</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-semibold text-ink">
                                            {{ $terbaru ? $terbaru['tanggal']->translatedFormat('d M Y') : '-' }}
                                        </p>
                                        <p class="text-[10px] text-inksoft uppercase tracking-wide">Submit terakhir</p>
                                    </div>
                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                     class="shrink-0 transition-transform text-inksoft"
                                     :class="openEkskul === {{ $i }} ? 'rotate-180' : ''">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>

                            {{-- info ringkas khusus mobile (kolom hadir/tanggal disembunyikan di atas) --}}
                            <div class="sm:hidden flex items-center justify-between px-4 py-2 bg-[#EEF0FF] text-xs text-inksoft border-t border-white/60">
                                <span>Hadir/Peserta: <strong class="text-ink">{{ $terbaru ? $terbaru['hadir'].'/'.$terbaru['total'] : '-/'.$item['total_anggota'] }}</strong></span>
                                <span>Submit: <strong class="text-ink">{{ $terbaru ? $terbaru['tanggal']->translatedFormat('d M Y') : '-' }}</strong></span>
                            </div>

                            {{-- DROPDOWN RIWAYAT: semua tanggal submit untuk ekskul ini --}}
                            <div x-show="openEkskul === {{ $i }}" x-transition class="bg-white" x-cloak>
                                @forelse ($item['sesi'] as $j => $sesiItem)
                                    <div x-data="{ openTanggal: false }" class="border-t border-[#F5F5FA]">
                                        <button type="button" @click="openTanggal = !openTanggal"
                                                class="w-full flex items-center justify-between gap-3 px-5 py-3 text-left hover:bg-bgsoft/60 transition-colors">
                                            <span class="text-sm font-medium flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="text-[#1F9D5E] shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                {{ $sesiItem['tanggal']->translatedFormat('d F Y') }}
                                            </span>
                                            <span class="flex items-center gap-3 shrink-0">
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#D9F9DF] text-[#1F7A3D]">{{ $sesiItem['hadir'] }}/{{ $sesiItem['total'] }} hadir</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                                     class="transition-transform text-inksoft" :class="openTanggal ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                                            </span>
                                        </button>

                                        {{-- DETAIL PER PESERTA untuk tanggal ini --}}
                                        <div x-show="openTanggal" x-transition class="px-5 pb-4" x-cloak>
                                            <div class="overflow-x-auto -mx-1 px-1">
                                            <table class="w-full text-sm border-collapse min-w-[520px]">
                                                <thead>
                                                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                                                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Peserta</th>
                                                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Kelas</th>
                                                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Status</th>
                                                        <th class="text-left py-2 px-2 border-b border-[#EFEFF7]">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sesiItem['records'] as $a)
                                                        <tr class="border-b border-[#F5F5FA] last:border-none">
                                                            <td class="py-2 px-2 font-medium">{{ $a->peserta->nama ?? '-' }}</td>
                                                            <td class="py-2 px-2 text-inksoft">{{ $a->peserta->kelas ?? '-' }}</td>
                                                            <td class="py-2 px-2">
                                                                @php
                                                                    $statusClass = match($a->status_kehadiran) {
                                                                        'hadir' => 'bg-[#D9F9DF] text-[#1F7A3D]',
                                                                        'alpha' => 'bg-red-100 text-red-500',
                                                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                                                        'izin' => 'bg-sky text-[#1E6FA8]',
                                                                        default => 'bg-gray-100 text-gray-600',
                                                                    };
                                                                @endphp
                                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $statusClass }}">{{ ucfirst($a->status_kehadiran) }}</span>
                                                            </td>
                                                            <td class="py-2 px-2">
                                                                <form id="delete-absensi-{{ $a->id_absensi }}" method="POST" action="{{ route('pembina.absensi.destroy', $a) }}" class="hidden">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <button type="button"
                                                                        data-delete-form="delete-absensi-{{ $a->id_absensi }}"
                                                                        data-delete-message="Hapus data absensi {{ $a->peserta->nama ?? '' }} pada tanggal ini? Tindakan ini tidak bisa dibatalkan."
                                                                        class="w-[28px] h-[28px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-5 py-6 text-center border-t border-[#F5F5FA]">
                                        <p class="text-xs text-inksoft">Belum ada riwayat absensi yang disubmit Ketua untuk ekskul ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </main>
</div>

@include('pembina.partials.delete-modal')

</body>
</html>