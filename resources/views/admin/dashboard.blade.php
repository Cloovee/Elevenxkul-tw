@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="animate-fade-in-up animate-delay-1 bg-gradient-to-br from-white to-bgsoft rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-inksoft text-xs font-semibold uppercase tracking-wide">Total Ekskul</p>
                    <p class="font-display text-3xl font-bold text-ink mt-1">{{ $totalEkskul ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-periwinkle/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-building text-periwinkle text-lg"></i>
                </div>
            </div>
        </div>

        <div class="animate-fade-in-up animate-delay-2 bg-gradient-to-br from-white to-bgsoft rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-inksoft text-xs font-semibold uppercase tracking-wide">Total Siswa</p>
                    <p class="font-display text-3xl font-bold text-ink mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-mint/60 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-emerald-600 text-lg"></i>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.pembina.index') }}"
           class="animate-fade-in-up animate-delay-3 bg-gradient-to-br from-white to-bgsoft rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6 hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-inksoft text-xs font-semibold uppercase tracking-wide">Total Pembina</p>
                    <p class="font-display text-3xl font-bold text-ink mt-1">{{ $totalPembina ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-lavender/25 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-[#5E5CC7] text-lg"></i>
                </div>
            </div>
        </a>

        <div class="animate-fade-in-up animate-delay-4 bg-gradient-to-br from-white to-bgsoft rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-inksoft text-xs font-semibold uppercase tracking-wide">Total Pelatih</p>
                    <p class="font-display text-3xl font-bold text-ink mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-sky/50 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-[#1E6FA8] text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="animate-fade-in-up animate-delay-5 bg-white/90 rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-lg font-semibold text-ink">
                <i class="fas fa-clock mr-2 text-inksoft"></i>Ekskul Terbaru
            </h3>
            <a href="{{ route('admin.ekskul.index') }}" class="text-sm text-[#5E5CC7] font-semibold hover:underline">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if(isset($recentEkskuls) && $recentEkskuls->isNotEmpty())
            <div class="divide-y divide-[#F0F0F8]">
                @foreach($recentEkskuls as $ekskul)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-ink">{{ $ekskul->nama_ekskul }}</p>
                            <p class="text-sm mt-0.5">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                                    @if($ekskul->kategori == 'organisasi') bg-periwinkle/20 text-[#5E5CC7]
                                    @elseif($ekskul->kategori == 'ekstrakulikuler') bg-mint/60 text-emerald-700
                                    @else bg-lavender/25 text-[#5E5CC7] @endif">
                                    {{ ucfirst($ekskul->kategori) }}
                                </span>
                            </p>
                        </div>
                        <span class="text-sm text-inksoft">
                            {{ $ekskul->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-inksoft text-center py-8">Belum ada data ekskul.</p>
        @endif
    </div>
@endsection
