@extends('layouts.admin')

@section('title', 'Preview Perubahan Tahun Ajaran')
@section('page-title', 'Preview Perubahan')

@section('content')
@php
    $labelKeputusan = [
        'naik_kelas' => ['Naik Kelas', 'bg-green-50 text-green-600'],
        'tidak_naik' => ['Tidak Naik', 'bg-amber-50 text-amber-600'],
        'lulus' => ['Lulus', 'bg-sky/15 text-sky-500'],
        'keluar' => ['Keluar', 'bg-red-50 text-red-600'],
    ];
@endphp

<div class="space-y-5">

    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">
        <h3 class="text-xl font-extrabold text-[#10316B]">Preview Perubahan Tahun Ajaran</h3>
        <p class="text-xs text-[#7C8DB5] mt-1">
            Menuju <span class="font-bold text-[#10316B]">{{ $tahunAjaran->tahun_ajaran }}</span>
            &middot; {{ count($baris) }} siswa akan diproses. Belum ada perubahan tersimpan ke database — periksa dulu sebelum Finalisasi.
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-3 py-3">NIS</th>
                        <th class="px-3 py-3">Nama</th>
                        <th class="px-3 py-3">Kelas Lama</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3">Kelas Baru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                @foreach($baris as $b)
                    <tr>
                        <td class="px-3 py-3 whitespace-nowrap">{{ $b['nis'] }}</td>
                        <td class="px-3 py-3 font-semibold text-[#10316B] whitespace-nowrap">{{ $b['nama_siswa'] }}</td>
                        <td class="px-3 py-3 whitespace-nowrap">{{ $b['kelas_lama'] }}</td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-1 rounded-full text-[11px] font-bold {{ $labelKeputusan[$b['keputusan']][1] }}">
                                {{ $labelKeputusan[$b['keputusan']][0] }}
                            </span>
                        </td>
                        <td class="px-3 py-3 font-semibold text-[#10316B] whitespace-nowrap">{{ $b['kelas_baru'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap justify-end gap-2">
            <a href="{{ route('admin.tahun-ajaran.perubahan', $tahunAjaran->id_tahun_ajaran) }}"
               class="px-5 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali &amp; Ubah
            </a>
            <form method="POST" action="{{ route('admin.tahun-ajaran.finalisasi', $tahunAjaran->id_tahun_ajaran) }}"
                  onsubmit="return confirm('Yakin? Perubahan akan langsung disimpan ke database dan tidak bisa dibatalkan.');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                    <i class="fas fa-check"></i> Finalisasi Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection