@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div x-data="{
        open: false, kelas: { url: '', siswaUrl: '', nama: '', jumlah: 0 },
        hapus(k) { this.kelas = k; this.open = true; }
     }"
     @keydown.escape.window="open = false"
     class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-4 sm:p-6">

    <!-- Header: Judul + Search -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#10316B]">Kelola Kelas</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
                <input type="text" name="search" placeholder="Cari tingkat/jurusan/rombel..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C] w-56" onkeydown="if(event.key==='Enter'){this.form.submit();}">
            </div>
            <select name="tingkat" class="px-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                <option value="">Semua Tingkat</option>
                @foreach($daftarTingkat as $t)
                    <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <select name="program_keahlian" class="px-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]">
                <option value="">Semua Jurusan</option>
                @foreach($daftarProgram as $pk)
                    <option value="{{ $pk }}" {{ request('program_keahlian') == $pk ? 'selected' : '' }}>{{ $pk }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="rtable min-w-full text-sm">
            <thead>
                <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">ID</th>
                    <th class="px-3 py-3">Tingkat</th>
                    <th class="px-3 py-3">Jurusan</th>
                    <th class="px-3 py-3">Rombel</th>
                    <th class="px-3 py-3">Jumlah Siswa</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse($kelas as $key => $k)
                <tr class="hover:bg-[#F2F7FF]/60 transition-colors">
                    <td class="px-3 py-3 text-[#7C8DB5] font-medium">{{ $kelas->firstItem() + $key }}</td>
                    <td class="px-3 py-3 text-[#7C8DB5] font-mono text-xs">#{{ $k->id_kelas }}</td>
                    <td class="px-3 py-3 text-[#10316B] font-semibold">{{ $k->tingkat }}</td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center font-display font-bold text-sm ring-2 ring-orange-100 flex-shrink-0">
                                <i class="fas fa-school text-xs"></i>
                            </div>
                            <p class="text-[#10316B] font-bold">{{ $k->program_keahlian }}</p>
                        </div>
                    </td>
                    <td data-label="Rombel" class="px-3 py-3 text-[#10316B] font-semibold">{{ $k->rombel }}</td>
                    <td data-label="Jumlah Siswa" class="px-3 py-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-lavender/10 text-lavender">
                            <i class="fas fa-user-graduate text-[10px]"></i> {{ $k->siswa_count }} siswa
                        </span>
                    </td>
                    <td data-label="Aksi" class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.kelas.siswa', $k->id_kelas) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-500 hover:bg-sky-500 hover:text-white transition-colors" title="Lihat siswa di kelas ini">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.kelas.edit', $k->id_kelas) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button type="button"
                                @click="hapus({ url: '{{ route('admin.kelas.destroy', $k->id_kelas) }}', siswaUrl: '{{ route('admin.kelas.siswa', $k->id_kelas) }}', nama: @js($k->nama_kelas), jumlah: {{ $k->siswa_count }} })"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#7C8DB5]">
                            <i class="fas fa-school text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada data kelas.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $kelas->withQueryString()->links() }}
    </div>
    <!-- Modal Konfirmasi Hapus Kelas -->
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#10316B]/40 backdrop-blur-sm" @click.self="open = false">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 text-center">
            <!-- Kelas masih ada siswa: tidak bisa dihapus -->
            <template x-if="kelas.jumlah > 0">
                <div>
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl"><i class="fas fa-triangle-exclamation"></i></div>
                    <h4 class="text-lg font-extrabold text-[#10316B] mb-2">Kelas belum bisa dihapus</h4>
                    <p class="text-sm text-[#7C8DB5] mb-6">
                        Kelas <b class="text-[#10316B]" x-text="kelas.nama"></b> masih berisi
                        <b class="text-[#10316B]" x-text="kelas.jumlah"></b> siswa. Pindahkan atau hapus semua siswanya dulu di halaman daftar siswa.
                    </p>
                    <div class="flex gap-2 justify-center">
                        <button type="button" @click="open = false" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Batal</button>
                        <a :href="kelas.siswaUrl" class="px-5 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90"><i class="fas fa-eye mr-1"></i> Lihat Siswa</a>
                    </div>
                </div>
            </template>
            <!-- Kelas kosong: konfirmasi hapus -->
            <template x-if="kelas.jumlah === 0">
                <form :action="kelas.url" method="POST">
                    @csrf @method('DELETE')
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl"><i class="fas fa-trash"></i></div>
                    <h4 class="text-lg font-extrabold text-[#10316B] mb-2">Hapus kelas ini?</h4>
                    <p class="text-sm text-[#7C8DB5] mb-6">Kelas <b class="text-[#10316B]" x-text="kelas.nama"></b> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                    <div class="flex gap-2 justify-center">
                        <button type="button" @click="open = false" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold shadow-md shadow-red-500/30">Ya, Hapus</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection