@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Manajemen Akun')

@section('content')
@php
    $p = $user->pembina;
    $roleStyle = match($user->role) {
        'Admin' => ['bg' => 'bg-periwinkle/10', 'text' => 'text-periwinkle', 'icon' => 'fa-user-shield'],
        'Pembina' => ['bg' => 'bg-sky/15', 'text' => 'text-sky-500', 'icon' => 'fa-user-tie'],
        'Ketua' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'icon' => 'fa-user-graduate'],
        default => ['bg' => 'bg-bgsoft', 'text' => 'text-inksoft', 'icon' => 'fa-user'],
    };
    $initial = strtoupper(substr($user->name, 0, 1));
    $jkLabel = fn ($v) => $v === 'L' ? 'Laki-laki' : ($v === 'P' ? 'Perempuan' : '-');
@endphp

<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-8 max-w-4xl mx-auto">

    <!-- Header profil -->
    <div class="flex flex-wrap items-center gap-4 mb-8 pb-6 border-b border-gray-100">
        <div class="w-20 h-20 rounded-2xl {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }} flex items-center justify-center font-display font-bold text-2xl overflow-hidden shrink-0">
            @if($p && $p->foto_url)
                <img src="{{ $p->foto_url }}" class="w-full h-full object-cover" alt="Foto {{ $user->name }}">
            @else
                {{ $initial }}
            @endif
        </div>
        <div class="flex-1 min-w-[200px]">
            <h3 class="text-xl font-extrabold text-[#10316B] leading-tight">{{ $user->name }}</h3>
            <p class="text-sm text-[#7C8DB5] mt-0.5">{{ '@' . ($user->username ?? '-') }}</p>
            <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full text-xs font-bold {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }}">
                <i class="fas {{ $roleStyle['icon'] }} text-[10px]"></i> {{ $user->role }}
            </span>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.user.edit', $user->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-pen text-xs"></i> Edit
            </a>
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info akun -->
    <p class="text-[11px] font-bold text-[#0B409C] uppercase tracking-wider mb-3">Informasi Akun</p>
    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8">
        <div>
            <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Nama</dt>
            <dd class="text-sm text-[#10316B] font-semibold">{{ $user->name }}</dd>
        </div>
        <div>
            <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Username (Login)</dt>
            <dd class="text-sm text-[#10316B] font-semibold">{{ $user->username ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Role</dt>
            <dd class="text-sm text-[#10316B] font-semibold">{{ $user->role }}</dd>
        </div>
        <div>
            <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Akun Dibuat</dt>
            <dd class="text-sm text-[#10316B] font-semibold">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</dd>
        </div>
    </dl>

    {{-- ===== Detail Pembina ===== --}}
    @if($user->role == 'Pembina')
        <p class="text-[11px] font-bold text-[#0B409C] uppercase tracking-wider mb-3">Biodata Pembina</p>

        @if($p)
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8">
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Nama Lengkap</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $p->nama_pembina }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Jenis Kelamin</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $jkLabel($p->jk) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Agama</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $p->agama ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Nomor HP</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $p->nomor_hp ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Email</dt>
                    <dd class="text-sm text-[#10316B] font-semibold break-all">{{ $p->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Media Sosial</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $p->medsos ?: '-' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Alamat</dt>
                    <dd class="text-sm text-[#10316B] font-semibold whitespace-pre-line">{{ $p->alamat ?: '-' }}</dd>
                </div>
            </dl>

            <p class="text-[11px] font-bold text-[#0B409C] uppercase tracking-wider mb-3">Ekskul yang Dibina</p>
            <div class="flex flex-wrap gap-2">
                @forelse($p->ekskuls as $e)
                    <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold bg-periwinkle/20 text-[#5E5CC7]">{{ $e->nama_ekskul }}</span>
                @empty
                    <span class="text-sm text-[#7C8DB5]">Belum membina ekskul apa pun.</span>
                @endforelse
            </div>
        @else
            <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-3 py-2 rounded-full">
                <i class="fas fa-triangle-exclamation"></i> Belum ada biodata. Lengkapi lewat tombol Edit.
            </span>
        @endif

    {{-- ===== Detail Ketua ===== --}}
    @elseif($user->role == 'Ketua')
        <p class="text-[11px] font-bold text-[#0B409C] uppercase tracking-wider mb-3">Data Siswa (Ketua)</p>

        @if($user->siswa)
            @php $s = $user->siswa; @endphp
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Nama Siswa</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $s->nama_siswa }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">NISN</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $s->NISN ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Kelas</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $s->nama_kelas }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-1">Memimpin Ekskul</dt>
                    <dd class="text-sm text-[#10316B] font-semibold">{{ $s->ekskulDipimpin->nama_ekskul ?? 'Belum memimpin ekskul' }}</dd>
                </div>
            </dl>
        @else
            <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-3 py-2 rounded-full">
                <i class="fas fa-triangle-exclamation"></i> Belum terkait siswa
            </span>
        @endif
    @endif
</div>
@endsection