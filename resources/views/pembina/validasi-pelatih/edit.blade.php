<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail & Validasi Absensi Pelatih — Elevenxkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen flex items-center justify-center p-5"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(174,226,255,0.35), transparent 45%), radial-gradient(circle at 0% 100%, rgba(217,249,223,0.4), transparent 40%);">

<div class="w-full max-w-lg">
    <a href="{{ route('pembina.validasi.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Daftar Validasi
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <div class="w-11 h-11 rounded-2xl bg-periwinkle text-[#3F41B0] flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Detail Laporan Absensi Pelatih</h1>
        <p class="text-sm text-inksoft mb-6">
            Laporan ini diisi oleh Ketua. Kamu hanya bisa memvalidasi (menyetujui/menolak), bukan mengubah isi laporannya.
        </p>

        {{-- Ringkasan laporan (read-only) --}}
        <div class="bg-bgsoft rounded-2xl p-5 mb-5 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-inksoft">Pelatih</span>
                <span class="font-semibold text-ink">{{ $absensiPelatih->pelatih->nama_pelatih ?? '-' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-inksoft">Tanggal</span>
                <span class="font-semibold text-ink">{{ optional($absensiPelatih->tanggal_absensi)->translatedFormat('d M Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-inksoft">Kegiatan</span>
                <span class="font-semibold text-ink">{{ $absensiPelatih->kegiatan ?? '-' }}</span>
            </div>
            <div class="flex justify-between text-sm items-center">
                <span class="text-inksoft">Kehadiran (melatih?)</span>
                @php
                    $kLabel = match($absensiPelatih->status_kehadiran) {
                        'hadir' => 'Hadir / Melatih',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Tidak Melatih',
                        default => '-',
                    };
                    $kClass = match($absensiPelatih->status_kehadiran) {
                        'hadir' => 'bg-mint text-[#1F7A3D]',
                        'izin' => 'bg-sky text-[#1E6FA8]',
                        'sakit' => 'bg-yellow-100 text-yellow-700',
                        'alpha' => 'bg-red-100 text-red-500',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $kClass }}">{{ $kLabel }}</span>
            </div>
            @if ($absensiPelatih->foto_kehadiran)
                <div class="pt-2">
                    <span class="text-inksoft text-sm block mb-1.5">Foto bukti</span>
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($absensiPelatih->foto_kehadiran) }}" target="_blank">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($absensiPelatih->foto_kehadiran) }}"
                             class="w-full max-h-48 object-cover rounded-xl" alt="Foto bukti kehadiran">
                    </a>
                </div>
            @endif
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.validasi.update', $absensiPelatih) }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Status Validasi</label>
                <select name="status_validasi" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    @foreach (['Menunggu', 'Divalidasi', 'Ditolak'] as $status)
                        <option value="{{ $status }}" @selected(old('status_validasi', $absensiPelatih->status_validasi) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Catatan Validasi (opsional)</label>
                <textarea name="catatan_validasi" rows="3" placeholder="Contoh: alasan penolakan, atau catatan tambahan"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender resize-none">{{ old('catatan_validasi', $absensiPelatih->catatan_validasi) }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.validasi.index') }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit"
                        class="flex-1 bg-lavender hover:bg-[#8385f0] text-white font-bold text-sm py-2.5 rounded-xl">
                    Simpan Validasi
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
