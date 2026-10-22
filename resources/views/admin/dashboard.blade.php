@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    @php
        // Satu warna untuk semua ikon (biru utama) supaya dashboard tenang dan seragam.
        $stats = [
            ['label' => 'Total ekskul',  'value' => $totalEkskul  ?? 0, 'icon' => 'fa-layer-group'],
            ['label' => 'Total siswa',   'value' => $totalSiswa   ?? 0, 'icon' => 'fa-user-graduate'],
            ['label' => 'Total kelas',   'value' => $totalKelas   ?? 0, 'icon' => 'fa-school'],
            ['label' => 'Total pembina', 'value' => $totalPembina ?? 0, 'icon' => 'fa-user-tie'],
            ['label' => 'Total user',    'value' => $totalUser    ?? 0, 'icon' => 'fa-user-gear'],
        ];

        $menus = [
            ['route' => 'admin.siswa.index',   'icon' => 'fa-users',        'title' => 'Kelola siswa',   'desc' => 'Data siswa dan anggota ekskul'],
            ['route' => 'admin.kelas.index',   'icon' => 'fa-school',       'title' => 'Kelola kelas',   'desc' => 'Data jurusan dan rombel'],
            ['route' => 'admin.user.index',    'icon' => 'fa-user-gear',    'title' => 'Kelola user',    'desc' => 'Manajemen akun sistem'],
            ['route' => 'admin.pembina.index', 'icon' => 'fa-user-tie',     'title' => 'Kelola pembina', 'desc' => 'Manajemen guru pembina'],
            ['route' => 'admin.ekskul.index',  'icon' => 'fa-layer-group',  'title' => 'Kelola ekskul',  'desc' => 'Manajemen data ekskul'],
        ];
    @endphp

    <div class="space-y-5 pb-4">

        {{-- ================= RINGKASAN ================= --}}
        <section aria-label="Ringkasan data" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            @foreach ($stats as $s)
                <div class="bg-white rounded-2xl p-4 ring-1 ring-ink/10 flex items-center gap-3 min-w-0 {{ $loop->last ? 'col-span-2 sm:col-span-1' : '' }}">
                    <div class="w-10 h-10 rounded-xl bg-periwinkle/10 text-periwinkle flex items-center justify-center shrink-0">
                        <i class="fas {{ $s['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-inksoft truncate">{{ $s['label'] }}</p>
                        <p class="text-2xl font-extrabold text-ink leading-tight">{{ $s['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

            {{-- ================= MENU KELOLA ================= --}}
            <section class="lg:col-span-3 bg-white rounded-3xl ring-1 ring-ink/10 overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-ink/10">
                    <h2 class="font-bold text-ink">Kelola data</h2>
                </div>

                <ul class="divide-y divide-ink/10">
                    @foreach ($menus as $m)
                        <li>
                            <a href="{{ route($m['route']) }}"
                               class="flex items-center gap-4 px-5 sm:px-6 py-4 hover:bg-cloud focus-visible:bg-cloud focus-visible:outline-none transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-periwinkle/10 text-periwinkle flex items-center justify-center shrink-0">
                                    <i class="fas {{ $m['icon'] }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-ink">{{ $m['title'] }}</p>
                                    <p class="text-xs text-inksoft truncate">{{ $m['desc'] }}</p>
                                </div>
                                <i class="fas fa-chevron-right text-xs text-inksoft/60"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- ================= EKSKUL TERBARU ================= --}}
            <section class="lg:col-span-2 bg-white rounded-3xl ring-1 ring-ink/10 overflow-hidden flex flex-col">
                <div class="px-5 sm:px-6 py-4 border-b border-ink/10 flex items-center justify-between gap-3">
                    <h2 class="font-bold text-ink">Ekskul terbaru</h2>
                    <a href="{{ route('admin.ekskul.index') }}" class="text-xs font-semibold text-periwinkle hover:underline">Lihat semua</a>
                </div>

                @if (!empty($recentEkskuls) && count($recentEkskuls) > 0)
                    <ul class="divide-y divide-ink/10">
                        @foreach ($recentEkskuls as $ek)
                            <li class="px-5 sm:px-6 py-3.5 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-ink truncate">{{ $ek->nama_ekskul }}</p>
                                    <p class="text-xs text-inksoft truncate">{{ $ek->pembina->nama_pembina ?? 'Belum ada pembina' }}</p>
                                </div>
                                @if ($ek->kategori)
                                    <span class="text-xs font-medium text-periwinkle bg-periwinkle/10 rounded-full px-2.5 py-1 shrink-0">{{ $ek->kategori }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center px-6 py-10">
                        <p class="text-sm font-semibold text-ink">Belum ada ekskul</p>
                        <p class="text-xs text-inksoft mt-1">Tambahkan ekskul pertama lewat menu Kelola ekskul.</p>
                    </div>
                @endif
            </section>

        </div>
    </div>
@endsection