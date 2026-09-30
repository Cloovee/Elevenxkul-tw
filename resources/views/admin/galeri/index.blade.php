@extends('layouts.admin')

@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri Ekstrakurikuler')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-4 sm:p-6">

    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <div>
            <h3 class="text-xl font-extrabold text-[#10316B]">Galeri Ekskul</h3>
            <p class="text-xs text-[#7C8DB5] mt-1">Foto di sini tampil bertumpuk di landing page, urut dari nomor urutan terkecil.</p>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
                <input type="text" name="search" placeholder="Cari judul foto..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">Reset</a>
        </form>
    </div>

    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.galeri.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-plus"></i> Tambah Foto
        </a>
        <a href="{{ route('landing') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-up-right-from-square"></i> Lihat di landing page
        </a>
    </div>

    @if($galeri->isEmpty())
        <div class="flex flex-col items-center text-[#7C8DB5] py-12">
            <i class="fas fa-images text-3xl mb-2"></i>
            <p class="font-bold text-sm">Belum ada foto galeri.</p>
            <p class="text-xs mt-1">Tambahkan foto kegiatan supaya galeri di landing page terisi.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($galeri as $foto)
                <div class="group rounded-2xl overflow-hidden border border-[#E8F0FE] bg-white hover:shadow-lg hover:shadow-[#0B409C]/10 transition-shadow">
                    <div class="relative aspect-[4/3] overflow-hidden bg-[#F2F7FF]">
                        <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-[#10316B]/85 text-white text-[11px] font-bold">
                            Urutan {{ $foto->urutan }}
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-[#10316B] truncate">{{ $foto->judul }}</p>
                        <p class="text-xs text-[#7C8DB5] mt-0.5">{{ $foto->ekskul->nama_ekskul ?? 'Umum (tanpa ekskul)' }}</p>
                        @if($foto->keterangan)
                            <p class="text-xs text-[#5B6E96] mt-2 line-clamp-2">{{ $foto->keterangan }}</p>
                        @endif
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('admin.galeri.edit', $foto->id_galeri) }}" class="flex-1 text-center px-3 py-2 rounded-xl bg-amber-50 text-amber-600 text-xs font-bold hover:bg-amber-500 hover:text-white transition-colors">
                                <i class="fas fa-pen mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.galeri.destroy', $foto->id_galeri) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 rounded-xl bg-red-50 text-red-500 text-xs font-bold hover:bg-red-500 hover:text-white transition-colors">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $galeri->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection