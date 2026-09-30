@extends('layouts.admin')

@section('title', 'Perubahan Tahun Ajaran')
@section('page-title', 'Perubahan Tahun Ajaran')

@section('content')
<div class="space-y-5">

    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-[#10316B]">Perubahan Tahun Ajaran</h3>
            <p class="text-xs text-[#7C8DB5] mt-1">Menuju <span class="font-bold text-[#10316B]">{{ $tahunAjaran->tahun_ajaran }}</span> &middot; {{ $siswa->total() }} siswa aktif</p>
        </div>
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if($sudahDiproses)
        <div class="px-4 py-3 rounded-2xl bg-amber-50 text-amber-700 text-sm font-semibold">
            Tahun Ajaran ini sudah pernah difinalisasi sebelumnya. Keputusan di bawah menampilkan hasil terakhir; mengirim ulang form ini akan menimpa hasil sebelumnya untuk siswa yang dipilih.
        </div>
    @endif

    <form method="GET" class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-4 flex items-center gap-2">
        <div class="relative flex-1 max-w-xs">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
            <input type="text" name="search" placeholder="Cari nama siswa..." value="{{ request('search') }}"
                class="w-full pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C]">
        </div>
        <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-filter mr-1"></i> Cari
        </button>
    </form>

    <form method="POST" action="{{ route('admin.tahun-ajaran.preview', $tahunAjaran->id_tahun_ajaran) }}"
          class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">
        @csrf

        @if($siswa->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="px-3 py-3">NIS</th>
                            <th class="px-3 py-3">Nama</th>
                            <th class="px-3 py-3">Kelas Saat Ini</th>
                            <th class="px-3 py-3">Keputusan</th>
                            <th class="px-3 py-3">Kelas Tujuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink/5">
                    @foreach($siswa as $s)
                        @php $riwayat = $riwayatSebelumnya->get($s->id_siswa); @endphp
                        <tr>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $s->NIS }}</td>
                            <td class="px-3 py-3 font-semibold text-[#10316B] whitespace-nowrap">{{ $s->nama_siswa }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-3 py-3">
                                <select name="keputusan[{{ $s->id_siswa }}]" onchange="toggleKelasTujuan({{ $s->id_siswa }}, this.value)"
                                    class="js-keputusan px-3 py-1.5 bg-[#F2F7FF] border-none rounded-full text-xs font-semibold text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                                    <option value="naik_kelas" @selected(!$riwayat || $riwayat->keputusan === 'naik_kelas')>Naik Kelas</option>
                                    <option value="tidak_naik" @selected($riwayat && $riwayat->keputusan === 'tidak_naik')>Tidak Naik</option>
                                    <option value="lulus" @selected($riwayat && $riwayat->keputusan === 'lulus')>Lulus</option>
                                    <option value="keluar" @selected($riwayat && $riwayat->keputusan === 'keluar')>Keluar</option>
                                </select>
                            </td>
                            <td class="px-3 py-3">
                                <select id="kelas-tujuan-{{ $s->id_siswa }}" name="kelas_tujuan[{{ $s->id_siswa }}]"
                                    class="px-3 py-1.5 bg-[#F2F7FF] border-none rounded-full text-xs font-semibold text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                                    <option value="">— pilih kelas —</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id_kelas }}" @selected($riwayat && $riwayat->id_kelas === $k->id_kelas)>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $siswa->links() }}</div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                    Lihat Preview <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        @else
            <div class="text-center py-12 flex flex-col items-center text-[#7C8DB5]">
                <i class="fas fa-user-graduate text-3xl mb-2"></i>
                <p class="font-bold text-sm">Tidak ada siswa aktif yang cocok.</p>
            </div>
        @endif
    </form>
</div>

<script>
    // Kelas Tujuan hanya perlu dipilih Admin untuk "Naik Kelas". Untuk "Tidak Naik"
    // kelas tujuan otomatis = kelas sekarang (ditentukan backend), dan untuk
    // "Lulus"/"Keluar" memang tidak ada kelas tujuan sama sekali.
    function toggleKelasTujuan(idSiswa, keputusan) {
        const select = document.getElementById('kelas-tujuan-' + idSiswa);
        const row = select.closest('tr');
        if (keputusan === 'naik_kelas') {
            select.disabled = false;
            row.querySelector('td:last-child').style.opacity = '1';
        } else {
            select.disabled = true;
            select.value = '';
            row.querySelector('td:last-child').style.opacity = '0.4';
        }
    }

    document.querySelectorAll('.js-keputusan').forEach(function (el) {
        const idSiswa = el.name.match(/\[(\d+)\]/)[1];
        toggleKelasTujuan(idSiswa, el.value);
    });
</script>
@endsection