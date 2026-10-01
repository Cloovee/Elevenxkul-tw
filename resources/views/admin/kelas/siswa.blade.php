@extends('layouts.admin')

@section('title', 'Siswa Kelas ' . $kelas->nama_kelas)
@section('page-title', 'Siswa di Kelas')

@section('content')
<div x-data="{
        selected: [],
        semuaId: @js($semuaId),
        idHalaman: @js($siswa->pluck('id_siswa')),
        modal: null,           // 'hapusSiswa' | 'pindah' | 'hapusKelas'
        siswaTunggal: null,    // {id, nama} saat hapus 1 siswa dari baris
        get jumlahDipilih() { return this.selected.length; },
        get semuaHalamanTerpilih() { return this.idHalaman.length > 0 && this.idHalaman.every(i => this.selected.includes(i)); },
        get semuaKelasTerpilih() { return this.semuaId.length > 0 && this.semuaId.every(i => this.selected.includes(i)); },
        toggleHalaman() { this.semuaHalamanTerpilih ? this.selected = this.selected.filter(i => !this.idHalaman.includes(i)) : this.selected = [...new Set([...this.selected, ...this.idHalaman])]; },
        pilihSemuaKelas() { this.selected = [...this.semuaId]; },
        kosongkan() { this.selected = []; },
        hapusSatu(id, nama) { this.siswaTunggal = { id, nama }; this.selected = [id]; this.modal = 'hapusSiswa'; },
        tutup() { this.modal = null; this.siswaTunggal = null; }
     }"
     @keydown.escape.window="tutup()"
     class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-4 sm:p-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
        <div>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#7C8DB5] hover:text-[#0B409C] mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Data Kelas
            </a>
            <h3 class="text-xl font-extrabold text-[#10316B]">Kelas {{ $kelas->nama_kelas }}</h3>
            <p class="text-sm text-[#7C8DB5] mt-1">
                <i class="fas fa-user-graduate mr-1"></i> {{ $kelas->siswa_count }} siswa terdaftar
            </p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#7C8DB5] text-sm"></i>
                <input type="text" name="search" placeholder="Cari NISN/NIS/Nama..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#F2F7FF] border-none rounded-full text-sm text-[#10316B] placeholder-[#7C8DB5] focus:ring-2 focus:ring-[#0B409C] w-56">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.kelas.siswa', $kelas->id_kelas) }}" class="px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#7C8DB5] rounded-full text-sm font-bold transition-colors">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-4 pb-4 border-b border-gray-100">
        <a href="{{ route('admin.kelas.edit', $kelas->id_kelas) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-500 text-amber-600 hover:text-white rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-pen"></i> Edit Kelas
        </a>
        <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
        <button type="button" @click="modal = 'hapusKelas'" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded-full text-sm font-bold transition-colors ml-auto">
            <i class="fas fa-trash"></i> Hapus Kelas
        </button>
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
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-2xl shadow-sm text-sm">
            @foreach($errors->all() as $e)<p><i class="fas fa-exclamation-circle mr-2"></i>{{ $e }}</p>@endforeach
        </div>
    @endif

    <!-- Info kelas masih berisi siswa -->
    @if($kelas->siswa_count > 0)
        <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-400 text-amber-700 rounded-2xl text-sm">
            <i class="fas fa-lightbulb mr-2"></i>
            Kelas ini baru bisa dihapus kalau sudah kosong. Centang siswa (atau pilih semua), lalu <b>Pindahkan</b> ke kelas lain atau <b>Hapus</b>.
        </div>
    @endif

    <!-- Bar aksi massal (muncul saat ada siswa dipilih) -->
    <div x-show="jumlahDipilih > 0" x-cloak x-transition class="mb-4 p-3 bg-[#F2F7FF] rounded-2xl flex flex-wrap items-center gap-3">
        <span class="text-sm font-bold text-[#10316B]"><span x-text="jumlahDipilih"></span> siswa dipilih</span>

        <button type="button" x-show="!semuaKelasTerpilih" @click="pilihSemuaKelas()" class="text-xs font-bold text-[#0B409C] hover:underline">
            Pilih semua {{ $semuaId->count() }} siswa di kelas ini
        </button>
        <button type="button" @click="kosongkan()" class="text-xs font-bold text-[#7C8DB5] hover:underline">Batalkan pilihan</button>

        <div class="flex gap-2 ml-auto">
            <button type="button" @click="modal = 'pindah'" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90">
                <i class="fas fa-right-left"></i> Pindahkan
            </button>
            <button type="button" @click="siswaTunggal = null; modal = 'hapusSiswa'" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold shadow-md shadow-red-500/30">
                <i class="fas fa-trash"></i> Hapus Terpilih
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="rtable min-w-full text-sm">
            <thead>
                <tr class="text-left text-[#7C8DB5] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3 w-10">
                        <input type="checkbox" :checked="semuaHalamanTerpilih" @change="toggleHalaman()" class="rounded border-gray-300 text-[#0B409C] focus:ring-[#0B409C]" title="Pilih semua di halaman ini">
                    </th>
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">NISN</th>
                    <th class="px-3 py-3">NIS</th>
                    <th class="px-3 py-3">Nama</th>
                    <th class="px-3 py-3">JK</th>
                    <th class="px-3 py-3">No. HP</th>
                    <th class="px-3 py-3">Status</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse($siswa as $key => $s)
                <tr class="hover:bg-[#F2F7FF]/60 transition-colors" :class="selected.includes({{ $s->id_siswa }}) ? 'bg-[#F2F7FF]/80' : ''">
                    <td class="px-3 py-3">
                        <input type="checkbox" :checked="selected.includes({{ $s->id_siswa }})" @change="selected.includes({{ $s->id_siswa }}) ? selected = selected.filter(i => i !== {{ $s->id_siswa }}) : selected.push({{ $s->id_siswa }})" class="rounded border-gray-300 text-[#0B409C] focus:ring-[#0B409C]">
                    </td>
                    <td data-label="No" class="px-3 py-3 text-[#7C8DB5] font-medium">{{ $siswa->firstItem() + $key }}</td>
                    <td data-label="NISN" class="px-3 py-3 text-[#10316B] font-semibold whitespace-nowrap">{{ $s->NISN }}</td>
                    <td data-label="NIS" class="px-3 py-3 text-[#10316B] whitespace-nowrap">{{ $s->NIS }}</td>
                    <td data-label="Nama" data-primary class="px-3 py-3 text-[#10316B] font-bold whitespace-nowrap">{{ $s->nama_siswa }}</td>
                    <td data-label="JK" class="px-3 py-3">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $s->jk == 'L' ? 'bg-sky/15 text-sky-500' : 'bg-pink-50 text-pink-500' }}">{{ $s->jk }}</span>
                    </td>
                    <td data-label="No. HP" class="px-3 py-3 text-[#7C8DB5] whitespace-nowrap">{{ $s->nomor_hp ?? '-' }}</td>
                    <td data-label="Status" class="px-3 py-3">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-[#F2F7FF] text-[#10316B]">{{ ucfirst(str_replace('_', ' ', $s->status ?? 'aktif')) }}</span>
                    </td>
                    <td data-label="Aksi" class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.siswa.edit', $s->id_siswa) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit siswa">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button type="button" @click="hapusSatu({{ $s->id_siswa }}, @js($s->nama_siswa))" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus siswa">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#7C8DB5]">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="font-bold text-sm">{{ request('search') ? 'Siswa tidak ditemukan.' : 'Kelas ini kosong, tidak ada siswa.' }}</p>
                            @if(!request('search'))
                                <p class="text-xs mt-1">Kelas ini sudah bisa dihapus.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $siswa->withQueryString()->links() }}
    </div>

    {{-- ================= MODAL ================= --}}

    <!-- Hapus siswa (satu / terpilih) -->
    <div x-show="modal === 'hapusSiswa'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#10316B]/40 backdrop-blur-sm" @click.self="tutup()">
        <form method="POST" action="{{ route('admin.kelas.siswa.hapus', $kelas->id_kelas) }}" class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 text-center">
            @csrf @method('DELETE')
            <template x-for="id in selected" :key="id"><input type="hidden" name="id_siswa[]" :value="id"></template>

            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl"><i class="fas fa-trash"></i></div>
            <h4 class="text-lg font-extrabold text-[#10316B] mb-2">Hapus siswa?</h4>
            <p class="text-sm text-[#7C8DB5] mb-2">
                <template x-if="siswaTunggal"><span><b class="text-[#10316B]" x-text="siswaTunggal.nama"></b> akan dihapus permanen.</span></template>
                <template x-if="!siswaTunggal"><span><b class="text-[#10316B]" x-text="jumlahDipilih"></b> siswa terpilih akan dihapus permanen.</span></template>
            </p>
            <p class="text-xs text-red-500 mb-6">Data keanggotaan ekskul dan akun login Ketua (jika ada) milik siswa ini juga ikut terhapus. Tindakan ini tidak bisa dibatalkan.</p>
            <div class="flex gap-2 justify-center">
                <button type="button" @click="tutup()" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold shadow-md shadow-red-500/30">Ya, Hapus</button>
            </div>
        </form>
    </div>

    <!-- Pindahkan siswa -->
    <div x-show="modal === 'pindah'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#10316B]/40 backdrop-blur-sm" @click.self="tutup()">
        <form method="POST" action="{{ route('admin.kelas.siswa.pindah', $kelas->id_kelas) }}" class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6">
            @csrf
            <template x-for="id in selected" :key="id"><input type="hidden" name="id_siswa[]" :value="id"></template>

            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center text-2xl"><i class="fas fa-right-left"></i></div>
            <h4 class="text-lg font-extrabold text-[#10316B] mb-2 text-center">Pindahkan siswa</h4>
            <p class="text-sm text-[#7C8DB5] mb-4 text-center">Pindahkan <b class="text-[#10316B]" x-text="jumlahDipilih"></b> siswa terpilih ke kelas:</p>

            <select name="id_kelas_tujuan" required class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] mb-6">
                <option value="">-- Pilih kelas tujuan --</option>
                @foreach($kelasLain as $kl)
                    <option value="{{ $kl->id_kelas }}">{{ $kl->nama_kelas }}</option>
                @endforeach
            </select>

            <div class="flex gap-2 justify-center">
                <button type="button" @click="tutup()" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90">Pindahkan</button>
            </div>
        </form>
    </div>

    <!-- Hapus kelas -->
    <div x-show="modal === 'hapusKelas'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#10316B]/40 backdrop-blur-sm" @click.self="tutup()">
        @if($kelas->siswa_count > 0)
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl"><i class="fas fa-triangle-exclamation"></i></div>
                <h4 class="text-lg font-extrabold text-[#10316B] mb-2">Kelas belum bisa dihapus</h4>
                <p class="text-sm text-[#7C8DB5] mb-6">
                    Kelas <b class="text-[#10316B]">{{ $kelas->nama_kelas }}</b> masih berisi <b class="text-[#10316B]">{{ $kelas->siswa_count }}</b> siswa.
                    Pilih semua siswa lalu pindahkan atau hapus, setelah itu kelas bisa dihapus.
                </p>
                <div class="flex gap-2 justify-center">
                    <button type="button" @click="tutup()" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Tutup</button>
                    <button type="button" @click="pilihSemuaKelas(); tutup()" class="px-5 py-2 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90">
                        <i class="fas fa-check-double mr-1"></i> Pilih Semua Siswa
                    </button>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('admin.kelas.destroy', $kelas->id_kelas) }}" class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 text-center">
                @csrf @method('DELETE')
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl"><i class="fas fa-trash"></i></div>
                <h4 class="text-lg font-extrabold text-[#10316B] mb-2">Hapus kelas ini?</h4>
                <p class="text-sm text-[#7C8DB5] mb-6">Kelas <b class="text-[#10316B]">{{ $kelas->nama_kelas }}</b> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <div class="flex gap-2 justify-center">
                    <button type="button" @click="tutup()" class="px-5 py-2 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold shadow-md shadow-red-500/30">Ya, Hapus</button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
