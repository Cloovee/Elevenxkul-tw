@extends('layouts.admin')

@section('title', 'Detail Ekskul ' . $ekskul->nama_ekskul)
@section('page-title', 'Detail Ekskul (Monitoring)')

@section('content')
@php
    $th = 'px-3 py-3';
    $thead = 'text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100';
    $card = 'bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6';
    $label = 'block text-xs font-bold text-inksoft uppercase tracking-wide mb-1';
@endphp

<div class="space-y-5">

    {{-- Header + aksi --}}
    <div class="{{ $card }} flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-[#10316B]">{{ $ekskul->nama_ekskul }}</h3>
            <p class="text-xs text-[#7C8DB5] mt-1">{{ ucfirst($ekskul->kategori) }} · {{ $peserta->count() }} peserta</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.monitoring-ekskul.index') }}"
               class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('admin.monitoring-ekskul.cetak', $ekskul->id_ekskul) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    {{-- Informasi dasar --}}
    <div class="{{ $card }}">
        <h4 class="font-extrabold text-[#10316B] mb-4">Informasi Ekskul</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><span class="{{ $label }}">Nama Ekskul</span><p class="font-semibold text-ink">{{ $ekskul->nama_ekskul }}</p></div>
            <div><span class="{{ $label }}">Kategori</span><p class="font-semibold text-ink">{{ ucfirst($ekskul->kategori) }}</p></div>
            <div><span class="{{ $label }}">Jumlah Peserta</span><p class="font-semibold text-ink">{{ $peserta->count() }}</p></div>
            <div><span class="{{ $label }}">Deskripsi</span><p class="text-ink">{{ $ekskul->deskripsi ?: '-' }}</p></div>
        </div>
    </div>

    {{-- Pembina, Ketua, Pelatih --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="{{ $card }}">
            <h4 class="font-extrabold text-[#10316B] mb-4"><i class="fas fa-user-tie mr-2 text-periwinkle"></i>Pembina</h4>
            @if($ekskul->pembina)
                <div class="space-y-3 text-sm">
                    <div><span class="{{ $label }}">Nama</span><p class="font-semibold text-ink">{{ $ekskul->pembina->nama_pembina }}</p></div>
                    <div><span class="{{ $label }}">No. HP</span><p class="text-ink">{{ $ekskul->pembina->nomor_hp ?: '-' }}</p></div>
                    <div><span class="{{ $label }}">Email</span><p class="text-ink break-all">{{ $ekskul->pembina->email ?: '-' }}</p></div>
                </div>
            @else
                <p class="text-sm text-[#7C8DB5]">Belum ada pembina.</p>
            @endif
        </div>

        <div class="{{ $card }}">
            <h4 class="font-extrabold text-[#10316B] mb-4"><i class="fas fa-user-graduate mr-2 text-periwinkle"></i>Ketua</h4>
            @if($ekskul->ketua)
                <div class="space-y-3 text-sm">
                    <div><span class="{{ $label }}">Nama</span><p class="font-semibold text-ink">{{ $ekskul->ketua->nama_siswa }}</p></div>
                    <div><span class="{{ $label }}">NIS</span><p class="text-ink">{{ $ekskul->ketua->NIS ?: '-' }}</p></div>
                    <div><span class="{{ $label }}">Kelas</span><p class="text-ink">{{ $ekskul->ketua->nama_kelas }}</p></div>
                </div>
            @else
                <p class="text-sm text-[#7C8DB5]">Belum ada ketua.</p>
            @endif
        </div>

        <div class="{{ $card }}">
            <h4 class="font-extrabold text-[#10316B] mb-4"><i class="fas fa-person-running mr-2 text-periwinkle"></i>Daftar Pelatih</h4>
            @if($ekskul->pelatih)
                <ol class="list-decimal list-inside space-y-3 text-sm">
                    <li class="text-ink font-semibold">{{ $ekskul->pelatih->nama_pelatih }}
                        <div class="ml-5 font-normal text-[#7C8DB5]">
                            {{ $ekskul->pelatih->nomor_hp ?: '-' }} · {{ $ekskul->pelatih->email ?: '-' }}
                        </div>
                    </li>
                </ol>
            @else
                <p class="text-sm text-[#7C8DB5]">Belum ada pelatih.</p>
            @endif
        </div>
    </div>

    {{-- Anggota --}}
    <div class="{{ $card }}">
        <h4 class="font-extrabold text-[#10316B] mb-4">Anggota ({{ $peserta->count() }})</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="{{ $thead }}">
                    <th class="{{ $th }}">No</th><th class="{{ $th }}">NIS</th><th class="{{ $th }}">Nama</th>
                    <th class="{{ $th }}">Kelas</th><th class="{{ $th }}">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-ink/5">
                @forelse($peserta as $i => $p)
                    <tr>
                        <td class="{{ $th }} text-[#7C8DB5]">{{ $i + 1 }}</td>
                        <td class="{{ $th }}">{{ $p->siswa->NIS ?? '-' }}</td>
                        <td class="{{ $th }} font-semibold text-[#10316B]">{{ $p->nama }}</td>
                        <td class="{{ $th }}">{{ $p->siswa->nama_kelas ?? '-' }}</td>
                        <td class="{{ $th }}">{{ ucfirst($p->status ?? '-') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-8 text-[#7C8DB5]">Belum ada anggota.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Laporan kegiatan --}}
    <div class="{{ $card }}">
        <h4 class="font-extrabold text-[#10316B] mb-1">Laporan Kegiatan</h4>
        <p class="text-xs text-[#7C8DB5] mb-4">Diringkas per tanggal dari data absensi peserta
            @if($totalKegiatan > $kegiatan->count())
                · menampilkan {{ $kegiatan->count() }} dari {{ $totalKegiatan }} tanggal terbaru (laporan cetak memuat semua)
            @endif
        </p>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="{{ $thead }}">
                    <th class="{{ $th }}">Tanggal</th><th class="{{ $th }}">Kegiatan</th>
                    <th class="{{ $th }}">Hadir</th><th class="{{ $th }}">Izin</th>
                    <th class="{{ $th }}">Sakit</th><th class="{{ $th }}">Alpha</th>
                </tr></thead>
                <tbody class="divide-y divide-ink/5">
                @forelse($kegiatan as $k)
                    <tr>
                        <td class="{{ $th }} whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                        <td class="{{ $th }}">{{ $k->kegiatan ?: '-' }}</td>
                        <td class="{{ $th }}">{{ $k->hadir }}</td><td class="{{ $th }}">{{ $k->izin }}</td>
                        <td class="{{ $th }}">{{ $k->sakit }}</td><td class="{{ $th }}">{{ $k->alpha }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-8 text-[#7C8DB5]">Belum ada laporan kegiatan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Penilaian --}}
    <div class="{{ $card }}">
        <h4 class="font-extrabold text-[#10316B] mb-4">Penilaian Peserta</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="{{ $thead }}">
                    <th class="{{ $th }}">No</th><th class="{{ $th }}">Nama</th><th class="{{ $th }}">Tahun Ajaran</th>
                    <th class="{{ $th }}">Semester</th><th class="{{ $th }}">Nilai</th><th class="{{ $th }}">Catatan Pembina</th>
                </tr></thead>
                <tbody class="divide-y divide-ink/5">
                @forelse($penilaian as $i => $n)
                    <tr>
                        <td class="{{ $th }} text-[#7C8DB5]">{{ $i + 1 }}</td>
                        <td class="{{ $th }} font-semibold text-[#10316B]">{{ $n->peserta->nama ?? '-' }}</td>
                        <td class="{{ $th }}">{{ $n->tahun_ajaran ?: '-' }}</td>
                        <td class="{{ $th }}">{{ $n->semester ?: '-' }}</td>
                        <td class="{{ $th }}">{{ $n->nilai !== null ? rtrim(rtrim(number_format($n->nilai, 2), '0'), '.') : '-' }}</td>
                        <td class="{{ $th }}">{{ $n->catatan_pembina ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-8 text-[#7C8DB5]">Belum ada penilaian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection