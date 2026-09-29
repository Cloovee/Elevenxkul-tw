@extends('layouts.admin')

@section('title', 'Tambah Ekskul')
@section('page-title', 'Tambah Ekstrakurikuler')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6 lg:p-8">
        
        <!-- Header Form -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-ink/10">
            <div>
                <h3 class="text-xl font-extrabold text-[#2b3674]">Tambah Ekskul Baru</h3>
                <p class="text-xs font-medium text-[#a3aed1] mt-1">Lengkapi formulir di bawah untuk menambahkan ekstrakurikuler baru.</p>
            </div>
            <a href="{{ route('admin.ekskul.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-xs font-bold transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Tambah -->
        <form action="{{ route('admin.ekskul.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Ekskul -->
                <div>
                    <label for="nama_ekskul" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Nama Ekskul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_ekskul" id="nama_ekskul" value="{{ old('nama_ekskul') }}"
                        placeholder="Contoh: Pramuka, OSIS, Futsal, dll"
                        class="w-full px-4 py-3 bg-[#f4f7fe] border @error('nama_ekskul') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] placeholder-[#a3aed1] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
                    @error('nama_ekskul')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="kategori"
                        class="w-full px-4 py-3 bg-[#f4f7fe] border @error('kategori') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
                        <option value="">Pilih Kategori</option>
                        <option value="organisasi" {{ old('kategori') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                        <option value="ekstrakulikuler" {{ old('kategori') == 'ekstrakulikuler' ? 'selected' : '' }}>Ekstrakulikuler</option>
                        <option value="komunitas" {{ old('kategori') == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Pembina -->
                <div class="md:col-span-2">
                    <label for="id_pembina" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Pembina Penanggung Jawab
                    </label>
                    <select name="id_pembina" id="id_pembina"
                        class="w-full px-4 py-3 bg-[#f4f7fe] border @error('id_pembina') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($pembinas as $p)
                            <option value="{{ $p->id_pembina }}" {{ old('id_pembina') == $p->id_pembina ? 'selected' : '' }}>
                                {{ $p->nama_pembina }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-[#a3aed1]">Pembina yang dipilih akan bisa mengelola data pelatih untuk ekskul ini.</p>
                    @error('id_pembina')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Ketua -->
                <div class="md:col-span-2">
                    <label for="id_ketua" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Ketua Ekskul
                    </label>
                    <select name="id_ketua" id="id_ketua"
                        class="w-full px-4 py-3 bg-[#f4f7fe] border @error('id_ketua') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($calonKetua as $s)
                            <option value="{{ $s->id_siswa }}" {{ old('id_ketua') == $s->id_siswa ? 'selected' : '' }}>
                                {{ $s->nama_siswa }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-[#a3aed1]">
                        Cuma siswa yang sudah punya akun role Ketua (dibuat lewat Kelola Users) yang muncul di sini.
                        @if($calonKetua->isEmpty())
                            <span class="text-amber-600 font-semibold">Belum ada akun Ketua yang tersedia/belum memimpin ekskul lain.</span>
                        @endif
                    </p>
                    @error('id_ketua')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Poster (tampil di carousel landing page) -->
                <div class="md:col-span-2" x-data="{ preview: @js(null), hapus: false }">
                    <label for="poster" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Poster Landing Page
                    </label>

                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="w-36 shrink-0 aspect-[3/5] rounded-2xl overflow-hidden bg-[#f4f7fe] ring-1 ring-[#10316B]/10 flex items-center justify-center">
                            <template x-if="preview && !hapus">
                                <img :src="preview" alt="Pratinjau poster" class="w-full h-full object-cover">
                            </template>
                            <div x-show="!preview || hapus" class="text-center px-3 text-[#a3aed1]">
                                <i class="fas fa-image text-2xl"></i>
                                <p class="text-[11px] font-semibold mt-2">Belum ada poster</p>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <input type="file" name="poster" id="poster" accept="image/png,image/jpeg,image/webp"
                                @change="const f = $event.target.files[0]; if (f) { preview = URL.createObjectURL(f); hapus = false }"
                                class="block w-full text-sm text-[#2b3674] file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#0B409C] file:text-white hover:file:opacity-90 file:cursor-pointer bg-[#f4f7fe] rounded-xl @error('poster') ring-2 ring-red-500 @enderror">
                            <p class="mt-1.5 text-xs text-[#a3aed1]">
                                JPG, PNG, atau WEBP, maksimal 4 MB. Paling bagus berbentuk potret (rasio 3:5, misalnya 900&times;1500 px) karena tampil sebagai kartu tegak di carousel.
                                Kalau dikosongkan, landing page memakai foto pertama dari Kelola Galeri.
                            </p>
                            @error('poster')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        placeholder="Deskripsi kegiatan ekstrakurikuler"
                        class="w-full px-4 py-3 bg-[#f4f7fe] border @error('deskripsi') border-red-500 @else border-transparent @enderror rounded-xl text-sm text-[#2b3674] placeholder-[#a3aed1] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.ekskul.index') }}" class="px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection