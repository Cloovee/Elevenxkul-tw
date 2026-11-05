@extends('layouts.admin')

@section('title', 'Detail Ekskul')
@section('page-title', 'Detail Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-8">
    @php
        $colors = [
            'organisasi' => 'bg-[#DDE8FB] text-[#0B409C]',
            'ekstrakulikuler' => 'bg-green-50 text-green-600',
            'komunitas' => 'bg-[#FFF7CC] text-[#7A6200]'
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Nama Ekskul</label>
            <p class="text-lg font-semibold text-ink">{{ $ekskul->nama_ekskul }}</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Kategori</label>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colors[$ekskul->kategori] ?? 'bg-bgsoft text-inksoft' }}">
                {{ ucfirst($ekskul->kategori) }}
            </span>
        </div>

        <div>
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Pembina Penanggung Jawab</label>
            @if($ekskul->pembina)
                <p class="text-lg font-semibold text-ink">{{ $ekskul->pembina->nama_pembina }}</p>
            @else
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-600">Belum ditentukan</span>
            @endif
        </div>

        <div>
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Pelatih</label>
            @if($ekskul->pelatih)
                <p class="text-lg font-semibold text-ink">{{ $ekskul->pelatih->nama_pelatih }}</p>
            @else
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-bgsoft text-inksoft">Belum ada pelatih</span>
            @endif
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Deskripsi</label>
            <p class="text-ink">{{ $ekskul->deskripsi ?: '-' }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Dibuat Pada</label>
            <p class="text-ink">{{ $ekskul->created_at->format('d F Y H:i') }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1.5">Terakhir Diupdate</label>
            <p class="text-ink">{{ $ekskul->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 mt-6 pt-6 border-t border-gray-100">
        <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="px-6 py-2.5 bg-[#0B409C] text-white text-sm font-bold rounded-full shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] text-sm font-bold rounded-full transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
@endsection