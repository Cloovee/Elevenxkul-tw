@extends('layouts.admin')

@section('title', 'Detail Ekskul')
@section('page-title', 'Detail Ekstrakurikuler')

@section('content')
<div class="animate-fade-in-up bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
    @php
        $colors = [
            'organisasi' => 'bg-periwinkle/20 text-[#7B77E0]',
            'ekstrakulikuler' => 'bg-mint/60 text-emerald-700',
            'komunitas' => 'bg-lavender/25 text-[#7B77E0]'
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nama Ekskul</label>
            <p class="text-lg font-semibold text-ink">{{ $ekskul->nama_ekskul }}</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Kategori</label>
            <span class="px-3 py-1 text-sm rounded-full {{ $colors[$ekskul->kategori] ?? 'bg-bgsoft text-inksoft' }}">
                {{ ucfirst($ekskul->kategori) }}
            </span>
        </div>

        <div>
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Pembina Penanggung Jawab</label>
            @if($ekskul->pembina)
                <p class="text-lg font-semibold text-ink">{{ $ekskul->pembina->nama_pembina }}</p>
            @else
                <span class="px-3 py-1 text-sm rounded-full bg-amber-50 text-amber-600">Belum ditentukan</span>
            @endif
        </div>

        <div>
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Pelatih</label>
            @if($ekskul->pelatih)
                <p class="text-lg font-semibold text-ink">{{ $ekskul->pelatih->nama_pelatih }}</p>
            @else
                <span class="px-3 py-1 text-sm rounded-full bg-bgsoft text-inksoft">Belum ada pelatih</span>
            @endif
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Deskripsi</label>
            <p class="text-ink">{{ $ekskul->deskripsi ?: '-' }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Dibuat Pada</label>
            <p class="text-ink">{{ $ekskul->created_at->format('d F Y H:i') }}</p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Terakhir Diupdate</label>
            <p class="text-ink">{{ $ekskul->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-[#F3F4FC]">
        <a href="{{ route('admin.ekskul.edit', $ekskul->id_ekskul) }}" class="px-6 py-2.5 bg-gradient-to-r from-sky to-periwinkle text-white font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-periwinkle/30">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2.5 bg-bgsoft text-ink font-semibold rounded-xl hover:bg-[#EEF0FD] transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
@endsection