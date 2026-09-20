<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Absensi Pelatih — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('pembina.partials.sidebar', ['active' => 'validasi'])

                <main class="flex-1 min-w-0 space-y-5">

        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 bg-red-50 text-red-600 text-sm font-semibold px-4 py-3 rounded-2xl animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO — sky theme --}}
        <div class="animate-fade-in-up rounded-3xl p-7 bg-[#D6E4FF] shadow-[0_10px_30px_-18px_rgba(30,111,168,0.35)] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-56 h-56 rounded-full bg-white/40 -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="flex items-center justify-between flex-wrap gap-4 relative">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#0B409C]">Kehadiran Pelatih</p>
                    <h1 class="font-display text-2xl font-semibold mt-1">Validasi Absensi Pelatih</h1>
                    <p class="text-sm text-inksoft mt-1 max-w-md">Setujui atau tolak laporan kehadiran pelatih di ekskul yang kamu bina.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/70 rounded-2xl px-4 py-2.5 text-center">
                        <div class="font-display text-xl font-bold text-[#0B409C]">{{ $pendingCount }}</div>
                        <div class="text-[11px] text-inksoft">Menunggu tindakan</div>
                    </div>
                </div>
            </div>
            <p class="relative text-xs text-inksoft mt-4 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Laporan kehadiran pelatih diisi oleh Ketua. Tugas kamu di sini hanya menyetujui atau menolaknya.
            </p>
            <a href="{{ route('pembina.absensi.index') }}" class="relative inline-flex items-center gap-1 text-xs font-semibold text-[#0B409C] mt-2 hover:underline">
                Lihat absensi peserta
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- RINGKASAN KEHADIRAN — hadir / izin / sakit / alpha --}}
        <div class="animate-fade-in-up animate-delay-1 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
                $kehadiranLabel = ['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Tidak Melatih'];
                $kehadiranColor = [
                    'hadir' => 'bg-emerald-50 text-emerald-700',
                    'izin' => 'bg-sky text-[#0B409C]',
                    'sakit' => 'bg-yellow-100 text-yellow-700',
                    'alpha' => 'bg-red-100 text-red-500',
                ];
            @endphp
            @foreach ($kehadiranLabel as $key => $label)
                <div class="bg-white rounded-2xl p-4 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.25)] flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold text-inksoft">{{ $label }}</p>
                        <p class="font-display text-lg font-bold text-ink">{{ $kehadiranSummary[$key] ?? 0 }}</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $kehadiranColor[$key] }}">{{ strtoupper($key) }}</span>
                </div>
            @endforeach
        </div>

        {{-- FILTER STATUS VALIDASI --}}
        <div class="animate-fade-in-up animate-delay-1 flex flex-wrap gap-2">
            @php
                $tabs = [
                    null => 'Semua',
                    'Menunggu' => 'Menunggu ('.$pendingCount.')',
                    'Divalidasi' => 'Divalidasi ('.$divalidasiCount.')',
                    'Ditolak' => 'Ditolak ('.$ditolakCount.')',
                ];
            @endphp
            @foreach ($tabs as $value => $label)
                <a href="{{ route('pembina.validasi.index', $value ? ['status' => $value] : []) }}"
                   class="text-xs font-semibold px-3.5 py-2 rounded-xl transition-colors {{ $statusAktif === $value ? 'bg-[#0B409C] text-white' : 'bg-white text-inksoft hover:bg-sky/40' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- TABLE --}}
        <div class="animate-fade-in-up animate-delay-2 bg-white rounded-3xl p-6 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
            <h3 class="font-bold text-base mb-3">Daftar Laporan</h3>
            <div class="overflow-x-auto -mx-2 px-2">
            <table class="w-full text-sm border-collapse min-w-[640px]">
                <thead>
                    <tr class="text-inksoft text-[11px] uppercase tracking-wide">
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Pelatih</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Kegiatan</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Tanggal</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Melatih?</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Validasi</th>
                        <th class="text-left py-2 px-2 border-b border-[#E8F0FE]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $v)
                        <tr class="border-b border-[#F2F7FF] last:border-none hover:bg-bgsoft/60 transition-colors">
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2.5 font-semibold">
                                    <div class="w-[30px] h-[30px] rounded-lg bg-lavender text-white text-xs font-bold flex items-center justify-center">
                                        {{ collect(explode(' ', $v->pelatih->nama_pelatih ?? '-'))->map(fn($s) => $s[0] ?? '')->take(2)->implode('') }}
                                    </div>
                                    {{ $v->pelatih->nama_pelatih ?? '-' }}
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-inksoft">{{ $v->kegiatan ?? '-' }}</td>
                            <td class="py-2.5 px-2 text-inksoft">{{ optional($v->tanggal_absensi)->translatedFormat('d M Y') }}</td>
                            <td class="py-2.5 px-2">
                                @php
                                    $kClass = match($v->status_kehadiran) {
                                        'hadir' => 'bg-emerald-50 text-emerald-700',
                                        'izin' => 'bg-sky text-[#0B409C]',
                                        'sakit' => 'bg-yellow-100 text-yellow-700',
                                        'alpha' => 'bg-red-100 text-red-500',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $kLabel = match($v->status_kehadiran) {
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Tidak Melatih',
                                        default => '-',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $kClass }}">{{ $kLabel }}</span>
                                @if ($v->foto_kehadiran)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($v->foto_kehadiran) }}" target="_blank" class="block text-[10px] text-[#0B409C] font-semibold mt-1 hover:underline">Lihat foto</a>
                                @endif
                            </td>
                            <td class="py-2.5 px-2">
                                @php
                                    $vClass = match($v->status_validasi) {
                                        'Divalidasi' => 'bg-emerald-50 text-emerald-700',
                                        'Ditolak' => 'bg-red-100 text-red-500',
                                        default => 'bg-sky text-[#0B409C]',
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $vClass }}">{{ $v->status_validasi }}</span>
                            </td>
                            <td class="py-2.5 px-2">
                                <div class="flex gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('pembina.validasi.setujui', $v) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-[30px] h-[30px] rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center disabled:opacity-30"
                                                {{ $v->status_validasi !== 'Menunggu' ? 'disabled' : '' }} title="Setujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('pembina.validasi.tolak', $v) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-[30px] h-[30px] rounded-lg bg-red-100 text-red-500 flex items-center justify-center disabled:opacity-30"
                                                {{ $v->status_validasi !== 'Menunggu' ? 'disabled' : '' }} title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('pembina.validasi.edit', $v) }}" title="Lihat detail &amp; validasi"
                                       class="w-[30px] h-[30px] rounded-lg bg-sky text-[#0B409C] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form id="delete-validasi-{{ $v->id_absensi }}" method="POST" action="{{ route('pembina.validasi.destroy', $v) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" title="Hapus"
                                            data-delete-form="delete-validasi-{{ $v->id_absensi }}"
                                            data-delete-message="Hapus laporan absensi pelatih {{ $v->pelatih->nama_pelatih ?? '' }} ini? Tindakan ini tidak bisa dibatalkan."
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
                                    <div class="w-14 h-14 rounded-2xl bg-sky/60 text-[#0B409C] flex items-center justify-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-ink">Belum ada laporan untuk divalidasi</p>
                                    <p class="text-xs text-inksoft max-w-xs">Laporan absensi pelatih dari Ketua akan muncul di sini untuk kamu setujui atau tolak.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="mt-4">
                {{ $laporan->links() }}
            </div>
        </div>

                    </main>
            </div>
        </div>
    </div>

@include('pembina.partials.delete-modal')

</body>
</html>