@extends('layouts.admin')

@section('title', 'Ganti Tahun Ajaran')
@section('page-title', 'Ganti Tahun Ajaran')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6 max-w-lg">

    <div class="mb-6 pb-6 border-b border-gray-100">
        <h3 class="text-xl font-extrabold text-[#10316B]">Ganti Tahun Ajaran</h3>
        <p class="text-xs text-[#7C8DB5] mt-1">Langkah 1: tentukan periode tujuan. Siswa belum diubah di langkah ini.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 px-4 py-3 rounded-2xl bg-red-50 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tahun-ajaran.ganti.store') }}" class="space-y-6">
        @csrf

        <div class="flex items-center gap-4">
            <div class="flex-1">
                <span class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1">Dari</span>
                <div class="px-4 py-2.5 bg-[#F2F7FF] rounded-2xl text-sm font-extrabold text-[#10316B]">
                    {{ $aktif->tahun_ajaran }}
                </div>
            </div>
            <i class="fas fa-arrow-right text-[#7C8DB5] mt-5"></i>
            <div class="flex-1">
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1">Ke</label>
                <input type="text" name="tahun_ajaran_baru" value="{{ old('tahun_ajaran_baru', $saran) }}" required
                    class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-2xl text-sm font-extrabold text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
            </div>
        </div>
        <p class="text-[11px] text-[#7C8DB5]">Format: TAHUN/TAHUN, contoh {{ $saran }}. Bisa diubah jika Tahun Ajaran tujuan berbeda dari saran.</p>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                Lanjutkan
            </button>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-5 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection