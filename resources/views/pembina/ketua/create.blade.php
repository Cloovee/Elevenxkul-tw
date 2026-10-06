<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ketua - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen flex items-center justify-center p-5"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(217,249,223,0.45), transparent 45%), radial-gradient(circle at 0% 100%, rgba(174,226,255,0.35), transparent 40%);">

<div class="w-full max-w-2xl">
    <a href="{{ route('pembina.ketua.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Kelola Ketua
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <div class="w-11 h-11 rounded-2xl bg-mint text-[#1F7A3D] flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Tambah Ketua</h1>
        <p class="text-sm text-inksoft mb-6">Pilih ekskul/organisasi, lalu pilih ketuanya dari daftar anggota. Biodata diambil dari data siswa yang sudah diinput Admin, kamu tinggal mengisi akun login-nya.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.ketua.store') }}" class="flex flex-col gap-4"
              x-data="ketuaForm(@js([
                  'ekskuls' => $ekskulData,
                  'siswa' => $siswaList,
                  'ekskulId' => old('id_ekskul'),
                  'siswaId' => old('id_siswa'),
                  'email' => old('email'),
              ]))">
            @csrf

            {{-- 1. Ekskul / organisasi --}}
            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Ekskul / Organisasi yang Dipimpin <span class="text-red-500">*</span></label>
                <select name="id_ekskul" required x-model="ekskulId" @change="siswaId = ''; tampilLain = false"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    <option value="">-- Pilih Ekskul / Organisasi --</option>
                    @forelse($ekskuls as $e)
                        <option value="{{ $e->id_ekskul }}">{{ $e->nama_ekskul }}</option>
                    @empty
                        <option value="" disabled>Kamu belum membina ekskul manapun</option>
                    @endforelse
                </select>
                <p class="mt-1 text-xs text-inksoft">Hanya ekskul/organisasi (mis. OSIS, MPK) yang kamu bina yang muncul di sini.</p>
            </div>

            {{-- Peringatan ketua lama --}}
            <template x-if="ekskul && ekskul.ketua_lama">
                <p class="text-xs text-amber-700 bg-amber-50 px-3 py-2 rounded-xl">
                    Ekskul ini sudah punya ketua (<b x-text="ekskul.ketua_lama"></b>). Ketua baru akan menggantikannya.
                </p>
            </template>

            {{-- 2. Pilih ketua dari anggota --}}
            <div x-show="ekskulId" x-cloak>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Pilih Ketua (dari anggota) <span class="text-red-500">*</span></label>
                <select name="id_siswa" required x-model="siswaId"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    <option value="">-- Pilih Anggota --</option>
                    <template x-for="s in anggota" :key="s.id">
                        <option :value="s.id" :selected="String(s.id) === String(siswaId)" x-text="label(s)"></option>
                    </template>
                    <template x-if="tampilLain">
                        <optgroup label="Siswa yang belum jadi anggota">
                            <template x-for="s in lain" :key="s.id">
                                <option :value="s.id" :selected="String(s.id) === String(siswaId)" x-text="label(s)"></option>
                            </template>
                        </optgroup>
                    </template>
                </select>

                <p x-show="anggota.length === 0" class="mt-1.5 text-xs text-amber-700">
                    Belum ada anggota di ekskul ini. Centang opsi di bawah untuk memilih dari semua siswa.
                </p>

                <label class="mt-2 inline-flex items-center gap-2 text-xs text-inksoft cursor-pointer">
                    <input type="checkbox" x-model="tampilLain" class="rounded border-[#E7E7F4] text-lavender focus:ring-lavender">
                    Tampilkan juga siswa yang belum jadi anggota (otomatis didaftarkan sebagai anggota saat dijadikan ketua)
                </label>
            </div>

            {{-- 3. Preview biodata (read-only) --}}
            <template x-if="siswaTerpilih">
                <div class="bg-bgsoft rounded-2xl p-4 grid grid-cols-2 gap-3 text-sm">
                    <div><p class="text-[11px] font-bold text-inksoft uppercase">Nama</p><p class="font-semibold" x-text="siswaTerpilih.nama"></p></div>
                    <div><p class="text-[11px] font-bold text-inksoft uppercase">Kelas</p><p class="font-semibold" x-text="siswaTerpilih.kelas"></p></div>
                    <div><p class="text-[11px] font-bold text-inksoft uppercase">NISN</p><p class="font-semibold" x-text="siswaTerpilih.nisn"></p></div>
                    <div><p class="text-[11px] font-bold text-inksoft uppercase">NIS</p><p class="font-semibold" x-text="siswaTerpilih.nis"></p></div>
                </div>
            </template>

            {{-- 4. Akun login --}}
            <template x-if="siswaTerpilih && siswaTerpilih.punya_akun">
                <p class="text-xs text-[#1F7A3D] bg-mint/60 px-3 py-2 rounded-xl">Siswa ini sudah punya akun login, jadi tidak perlu membuat akun baru.</p>
            </template>

            <div x-show="siswaTerpilih && !siswaTerpilih.punya_akun" x-cloak class="flex flex-col gap-4">
                <p class="text-xs font-bold text-inksoft uppercase tracking-wide pt-2 border-t border-[#F1F1FA]">Akun Login (otomatis masuk tabel user, role Ketua)</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" x-model="email" :disabled="!(siswaTerpilih && !siswaTerpilih.punya_akun)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" :disabled="!(siswaTerpilih && !siswaTerpilih.punya_akun)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" minlength="8" :disabled="!(siswaTerpilih && !siswaTerpilih.punya_akun)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" minlength="8" :disabled="!(siswaTerpilih && !siswaTerpilih.punya_akun)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.ketua.index') }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit" :disabled="!siswaId"
                        class="flex-1 bg-gradient-to-tr from-[#57C785] to-[#1F9D5E] hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm py-2.5 rounded-xl">
                    Jadikan Ketua
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ketuaForm', (init) => ({
            ekskuls: init.ekskuls,
            semua: init.siswa,
            ekskulId: init.ekskulId || '',
            siswaId: init.siswaId || '',
            email: init.email || '',
            tampilLain: false,

            get ekskul() { return this.ekskuls.find(e => String(e.id) === String(this.ekskulId)) || null; },
            get anggota() {
                if (!this.ekskul) return [];
                return this.semua.filter(s => this.ekskul.anggota_ids.includes(s.id));
            },
            get lain() {
                if (!this.ekskul) return [];
                return this.semua.filter(s => !this.ekskul.anggota_ids.includes(s.id));
            },
            get siswaTerpilih() { return this.semua.find(s => String(s.id) === String(this.siswaId)) || null; },

            label(s) { return s.nama + ' — ' + s.kelas + ' (NISN ' + s.nisn + ')'; },

            init() {
                // Isi otomatis email dari data siswa (boleh diubah) kalau belum diisi manual.
                this.$watch('siswaId', () => {
                    if (this.siswaTerpilih && !this.siswaTerpilih.punya_akun) {
                        this.email = this.siswaTerpilih.email || '';
                    }
                });
                // Kalau form balik karena error validasi dengan siswa non-anggota, buka daftar lengkap.
                if (this.siswaId && this.ekskul && !this.ekskul.anggota_ids.includes(Number(this.siswaId))) {
                    this.tampilLain = true;
                }
            },
        }));
    });
</script>

</body>
</html>