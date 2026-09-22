<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absensi Peserta — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-sky/10 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('pembina.partials.sidebar', ['active' => 'absensi'])

                <main class="flex-1 min-w-0 space-y-5">

<div class="w-full max-w-lg">
    <a href="{{ route('pembina.absensi.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Dashboard
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <div class="w-11 h-11 rounded-2xl bg-sky text-[#0B409C] flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Edit Absensi Peserta</h1>
        <p class="text-sm text-inksoft mb-6">
            Perbarui data kehadiran untuk <span class="font-semibold text-ink">{{ $absensiPeserta->peserta->nama }}</span>.
        </p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.absensi.update', $absensiPeserta) }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Peserta</label>
                <select name="id_anggota" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDE8FB] text-sm focus:outline-none focus:border-lavender">
                    @foreach ($pesertas as $p)
                        <option value="{{ $p->id_anggota }}" @selected(old('id_anggota', $absensiPeserta->id_anggota) == $p->id_anggota)>{{ $p->nama }} @if($p->kelas) ({{ $p->kelas }}) @endif</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal_absensi" required
                           value="{{ old('tanggal_absensi', optional($absensiPeserta->tanggal_absensi)->toDateString()) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDE8FB] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status_kehadiran" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDE8FB] text-sm focus:outline-none focus:border-lavender">
                        @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status_kehadiran', $absensiPeserta->status_kehadiran) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Catatan Kegiatan (opsional)</label>
                <textarea name="deskripsi_kegiatan" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDE8FB] text-sm focus:outline-none focus:border-lavender">{{ old('deskripsi_kegiatan', $absensiPeserta->deskripsi_kegiatan) }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.absensi.index') }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#DDE8FB] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit"
                        class="flex-1 bg-lavender hover:bg-[#0B409C] text-white font-bold text-sm py-2.5 rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

                </main>
            </div>
        </div>
    </div>

</body>
</html>