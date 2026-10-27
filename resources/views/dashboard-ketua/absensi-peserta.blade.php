<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Peserta - ElevenXkul</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-bgsoft text-ink">

    <div class="py-8 px-4 sm:px-6 lg:px-10 pb-24 md:pb-8 min-h-screen relative overflow-hidden">

        <div class="absolute -top-20 right-0 w-96 h-96 rounded-full bg-periwinkle/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-72 h-72 rounded-full bg-mint/20 blur-3xl pointer-events-none"></div>

        <div class="w-full relative z-10">
            <div class="flex flex-col lg:flex-row gap-6">

                @include('partials.sidebar-ketua')

                <!-- Konten -->
                <div class="flex-1 space-y-6"
                     x-data="scannerAbsensi({
                         url: @js(route('ketua.absensi-peserta.scan')),
                         csrf: @js(csrf_token()),
                         tanggal: @js($tanggal),
                         deskripsi: @js(old('deskripsi_kegiatan', $deskripsi)),
                         total: {{ $totalPeserta }},
                         log: @js($log),
                         kelas: @js($kelas),
                         cara: @js($cara),
                         rows: @js($baris),
                     })"
                     x-init="
                         $watch('aktif', v => { if (!v) pesan = null });
                         $watch('modeQr', v => { if (!v && aktif) berhenti() });
                     "
                >

                    <div>
                        <p class="text-xs text-gray-400 tracking-wide uppercase font-semibold">Ketua</p>
                        <h1 class="text-2xl font-extrabold text-ink">Absensi Peserta</h1>
                        <p class="text-sm text-gray-500 mt-1">Pilih kelas dulu. Kelas 10 bisa diabsen lewat scan QR kartu peserta atau tabel manual, kelas 11 dan 12 lewat tabel manual. Data langsung tampil di halaman Absensi Peserta milik pembina.</p>
                    </div>

                    @if (session('success'))
                        <div class="bg-emerald-50 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-2xl">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Data kegiatan -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-5">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3">Data Kegiatan</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Ganti tanggal = muat ulang halaman, supaya daftar hadir ikut tanggal itu --}}
                            <form method="GET" action="{{ route('ketua.absensi-peserta') }}">
                                <input type="hidden" name="kelas" :value="kelas">
                                <input type="hidden" name="cara" :value="cara">
                                <label class="block text-sm font-medium text-ink mb-1.5">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}"
                                    onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </form>

                            <div>
                                <label class="block text-sm font-medium text-ink mb-1.5">Deskripsi Kegiatan</label>
                                <input type="text" x-model="deskripsi" placeholder="Contoh: Latihan rutin mingguan"
                                    class="w-full px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Pilih kelas, lalu (khusus kelas 10) pilih cara absen -->
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-xs font-bold text-inksoft uppercase tracking-wide">Kelas</span>
                            <div class="inline-flex flex-wrap gap-1 rounded-2xl bg-white ring-1 ring-black/5 p-1 shadow-sm">
                                <template x-for="k in daftarKelas" :key="k">
                                    <button type="button" @click="kelas = k"
                                        :class="kelas === k ? 'bg-periwinkle text-white shadow' : 'text-inksoft hover:text-ink'"
                                        class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                                        <span x-text="labelKelas(k)"></span>
                                        <span class="ml-1 text-xs opacity-80" x-text="'(' + jumlah(k) + ')'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Kelas 10 punya dua cara absen --}}
                        <div x-show="kelas === '10'" x-cloak class="flex flex-wrap items-center gap-3">
                            <span class="text-xs font-bold text-inksoft uppercase tracking-wide">Cara absen</span>
                            <div class="inline-flex gap-1 rounded-2xl bg-white ring-1 ring-black/5 p-1 shadow-sm">
                                <button type="button" @click="cara = 'qr'"
                                    :class="cara === 'qr' ? 'bg-mint text-ink shadow' : 'text-inksoft hover:text-ink'"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition">Scan QR</button>
                                <button type="button" @click="cara = 'manual'"
                                    :class="cara === 'manual' ? 'bg-mint text-ink shadow' : 'text-inksoft hover:text-ink'"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition">Tabel Manual</button>
                            </div>
                        </div>
                    </div>

                    <!-- ===== Kelas 10 + cara Scan QR ===== -->
                    <div x-show="modeQr" x-cloak class="space-y-6">

                    <!-- Scanner -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-4">
                        <h2 class="font-semibold text-ink border-b border-gray-100 pb-3">Scan QR Kartu Peserta</h2>

                        <div class="mx-auto w-full max-w-sm">
                            <div id="reader" class="overflow-hidden rounded-2xl bg-ink/5 min-h-[120px]" x-show="aktif" x-cloak></div>

                            <div x-show="!aktif" class="rounded-2xl border border-dashed border-ink/15 py-10 text-center text-sm text-inksoft">
                                Kamera belum aktif.
                            </div>
                        </div>

                        <p x-show="errKamera" x-text="errKamera" x-cloak class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl"></p>

                        <div class="flex justify-center">
                            <button type="button" x-show="!aktif" @click="mulai()"
                                class="px-6 py-2.5 rounded-xl bg-periwinkle text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30">
                                Buka Kamera
                            </button>
                            <button type="button" x-show="aktif" x-cloak @click="berhenti()"
                                class="px-6 py-2.5 rounded-xl bg-ink/10 text-ink font-semibold hover:bg-ink/15 transition">
                                Matikan Kamera
                            </button>
                        </div>

                        <!-- Hasil scan terakhir -->
                        <div x-show="pesan" x-cloak
                            class="text-sm font-semibold px-4 py-3 rounded-2xl"
                            :class="{
                                'bg-emerald-50 text-emerald-700': pesan && pesan.tipe === 'ok',
                                'bg-yellow-50 text-yellow-700': pesan && pesan.tipe === 'warn',
                                'bg-red-50 text-red-500': pesan && pesan.tipe === 'err',
                            }"
                            x-text="pesan ? pesan.teks : ''">
                        </div>

                        <!-- Input manual: cadangan kalau kamera bermasalah / QR rusak (juga jalan dengan scanner USB) -->
                        <form @submit.prevent="kirimManual()" class="flex gap-2 pt-2 border-t border-gray-100">
                            <input type="text" x-model="manualNis" inputmode="numeric" placeholder="Atau ketik NIS lalu Enter"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition"
                            />
                            <button type="submit" :disabled="busy"
                                class="px-5 py-2.5 rounded-xl bg-mint text-ink font-semibold hover:opacity-90 transition disabled:opacity-50">
                                Catat
                            </button>
                        </form>
                    </div>

                    <!-- Sudah hadir -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h2 class="font-semibold text-ink">Sudah Hadir</h2>
                            <span class="text-sm font-semibold text-periwinkle" x-text="log.length + ' / ' + total + ' peserta'"></span>
                        </div>

                        <p x-show="log.length === 0" class="text-sm text-inksoft border border-dashed border-ink/10 rounded-xl py-6 text-center">
                            Belum ada peserta yang di-scan pada tanggal ini.
                        </p>

                        <ul class="divide-y divide-ink/10" x-show="log.length > 0" x-cloak>
                            <template x-for="p in log" :key="p.nis">
                                <li class="py-2.5 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-ink truncate" x-text="p.nama"></p>
                                        <p class="text-xs text-inksoft" x-text="p.kelas + ' • NIS ' + p.nis"></p>
                                    </div>
                                    <span class="text-xs text-inksoft shrink-0" x-text="p.waktu"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    </div>
                    <!-- ===== /Scan QR ===== -->

                    <!-- ===== Tabel manual (kelas 10 cara manual, kelas 11, kelas 12) ===== -->
                    <div x-show="!modeQr" x-cloak>
                        <form method="POST" action="{{ route('ketua.absensi-peserta.manual') }}"
                              class="bg-white rounded-3xl shadow-xl shadow-periwinkle/10 ring-1 ring-black/5 p-6 space-y-4">
                            @csrf
                            <input type="hidden" name="tanggal_absensi" value="{{ $tanggal }}">
                            <input type="hidden" name="deskripsi_kegiatan" :value="deskripsi">
                            <input type="hidden" name="kelas" :value="kelas">

                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <h2 class="font-semibold text-ink" x-text="'Absensi Manual · ' + labelKelas(kelas)"></h2>
                                <span class="text-sm font-semibold text-periwinkle" x-text="terisi + ' / ' + rowsTampil.length + ' terisi'"></span>
                            </div>

                            <p x-show="rowsTampil.length === 0" class="text-sm text-inksoft border border-dashed border-ink/10 rounded-xl py-6 text-center">
                                Belum ada anggota aktif <span x-text="labelKelas(kelas).toLowerCase()"></span> di ekskul ini.
                            </p>

                            <div x-show="rowsTampil.length > 0" x-cloak class="overflow-x-auto rounded-2xl ring-1 ring-ink/10">
                                <table class="w-full text-sm">
                                    <thead class="bg-bgsoft text-inksoft text-xs font-bold uppercase tracking-wide">
                                        <tr>
                                            <th rowspan="2" class="px-4 py-3 text-left align-middle">NIS</th>
                                            <th rowspan="2" class="px-4 py-3 text-left align-middle">Nama</th>
                                            <th rowspan="2" class="px-4 py-3 text-left align-middle">Waktu</th>
                                            <th colspan="3" class="px-4 py-2 text-center border-b border-ink/10">Status Kehadiran</th>
                                            <th rowspan="2" x-show="adaIzin" x-cloak class="px-4 py-3 text-left align-middle">Catatan</th>
                                        </tr>
                                        <tr>
                                            <th class="w-14 px-2 py-1.5 text-center">H</th>
                                            <th class="w-14 px-2 py-1.5 text-center">S</th>
                                            <th class="w-14 px-2 py-1.5 text-center">I</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-ink/10">
                                        <template x-for="r in rowsTampil" :key="r.id">
                                            <tr class="hover:bg-bgsoft/50">
                                                <td class="px-4 py-2.5 whitespace-nowrap text-inksoft" x-text="r.nis"></td>
                                                <td class="px-4 py-2.5">
                                                    <p class="font-medium text-ink">
                                                        <span x-text="r.nama"></span>
                                                        <span x-show="r.status === 'alpha'" x-cloak class="ml-1 text-[11px] font-bold text-red-500 bg-red-50 rounded-full px-2 py-0.5">Alpha</span>
                                                    </p>
                                                    <p class="text-xs text-inksoft" x-text="r.kelas"></p>
                                                </td>
                                                {{-- Waktu = jam absensi tersimpan (otomatis), "-" kalau belum disimpan --}}
                                                <td class="px-4 py-2.5 whitespace-nowrap text-inksoft" x-text="r.waktu || '-'"></td>
                                                <td class="px-2 py-2.5 text-center">
                                                    <input type="radio" value="hadir" :name="'status[' + r.id + ']'" x-model="r.status"
                                                           :aria-label="'Hadir: ' + r.nama" class="w-4 h-4 accent-periwinkle cursor-pointer">
                                                </td>
                                                <td class="px-2 py-2.5 text-center">
                                                    <input type="radio" value="sakit" :name="'status[' + r.id + ']'" x-model="r.status"
                                                           :aria-label="'Sakit: ' + r.nama" class="w-4 h-4 accent-periwinkle cursor-pointer">
                                                </td>
                                                <td class="px-2 py-2.5 text-center">
                                                    <input type="radio" value="izin" :name="'status[' + r.id + ']'" x-model="r.status"
                                                           :aria-label="'Izin: ' + r.nama" class="w-4 h-4 accent-periwinkle cursor-pointer">
                                                </td>
                                                {{-- Catatan: kolom hanya ada kalau ada yang Izin, dan kotak isinya hanya di baris yang Izin --}}
                                                <td x-show="adaIzin" x-cloak class="px-4 py-2.5">
                                                    <input type="text" :name="'catatan[' + r.id + ']'" x-model="r.catatan"
                                                           x-show="r.status === 'izin'" x-cloak :disabled="r.status !== 'izin'"
                                                           maxlength="255" placeholder="Alasan izin"
                                                           class="w-full min-w-[160px] px-3 py-1.5 rounded-lg border border-ink/15 bg-white focus:outline-none focus:ring-2 focus:ring-periwinkle focus:border-periwinkle transition">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <p class="text-xs text-inksoft">Status Kehadiran: H = Hadir, S = Sakit, I = Izin. Waktu terisi otomatis saat absensi disimpan. Kolom Catatan muncul kalau ada peserta berstatus Izin.</p>

                            <button type="submit" :disabled="terisi === 0" x-show="rowsTampil.length > 0" x-cloak
                                class="w-full py-3 rounded-xl bg-mint text-ink font-semibold hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Absensi Manual
                            </button>
                        </form>
                    </div>
                    <!-- ===== /Tabel manual ===== -->

                    <!-- Tutup absensi: yang tidak scan jadi Alpha -->
                    <form method="POST" action="{{ route('ketua.absensi-peserta.selesai') }}"
                          @submit="if (!confirm('Tutup absensi? Peserta yang belum scan akan dicatat Alpha.')) $event.preventDefault()">
                        @csrf
                        <input type="hidden" name="tanggal_absensi" value="{{ $tanggal }}">
                        <input type="hidden" name="deskripsi_kegiatan" :value="deskripsi">

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-periwinkle text-white font-semibold hover:opacity-90 transition shadow-lg shadow-periwinkle/30">
                            Selesai Absen
                        </button>
                        <p class="text-xs text-inksoft mt-2 text-center">Semua peserta aktif (kelas 10, 11, dan 12) yang belum di-scan atau belum dipilih statusnya di tabel manual otomatis tercatat Alpha. Izin/sakit bisa diubah oleh pembina.</p>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Library pembaca QR lewat kamera. Kamera hanya jalan di HTTPS atau localhost. --}}
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        function scannerAbsensi(cfg) {
            // Instance kamera disimpan di luar state Alpine supaya tidak dibungkus proxy.
            let qr = null;
            let lastCode = null;
            let lastAt = 0;

            return {
                tanggal: cfg.tanggal,
                deskripsi: cfg.deskripsi,
                total: cfg.total,
                log: cfg.log,
                kelas: cfg.kelas,
                cara: cfg.cara,
                rows: cfg.rows,
                aktif: false,
                busy: false,
                manualNis: '',
                pesan: null,
                errKamera: null,

                // Scan QR hanya untuk kelas 10 yang memilih cara "Scan QR". Selain itu = tabel manual.
                get modeQr() { return this.kelas === '10' && this.cara === 'qr'; },

                // Pilihan kelas: 10, 11, 12.
                daftarKelas: ['10', '11', '12'],
                labelKelas(k) { return 'Kelas ' + k; },
                jumlah(k) { return this.rows.filter(r => r.tingkat === k).length; },

                // Tabel manual: hanya baris kelas yang dipilih yang tampil (dan terkirim saat disimpan).
                get rowsTampil() { return this.rows.filter(r => r.tingkat === this.kelas); },

                // Kolom Catatan hanya muncul kalau ada minimal satu peserta (di kelas yang tampil) berstatus Izin.
                get adaIzin() { return this.rowsTampil.some(r => r.status === 'izin'); },
                get terisi() { return this.rowsTampil.filter(r => ['hadir', 'sakit', 'izin'].includes(r.status)).length; },

                async mulai() {
                    this.errKamera = null;
                    try {
                        qr = new Html5Qrcode('reader');
                        await qr.start(
                            { facingMode: 'environment' },
                            { fps: 10, qrbox: { width: 240, height: 240 } },
                            (teks) => this.onScan(teks),
                            () => {}
                        );
                        this.aktif = true;
                    } catch (e) {
                        this.aktif = false;
                        this.errKamera = 'Kamera tidak bisa dibuka. Pastikan izin kamera diizinkan dan halaman dibuka lewat HTTPS (atau localhost).';
                    }
                },

                async berhenti() {
                    try {
                        if (qr) { await qr.stop(); qr.clear(); }
                    } catch (e) {}
                    this.aktif = false;
                },

                onScan(teks) {
                    const nis = String(teks).trim();
                    const sekarang = Date.now();

                    // Abaikan scan ganda: sedang proses, atau QR yang sama terbaca lagi < 3 detik.
                    if (this.busy || (nis === lastCode && sekarang - lastAt < 3000)) return;
                    lastCode = nis;
                    lastAt = sekarang;

                    this.kirim(nis);
                },

                kirimManual() {
                    const nis = this.manualNis.trim();
                    if (!nis || this.busy) return;
                    this.kirim(nis).then(() => { this.manualNis = ''; });
                },

                async kirim(nis) {
                    this.busy = true;
                    try {
                        const res = await fetch(cfg.url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': cfg.csrf,
                            },
                            body: JSON.stringify({
                                nis: nis,
                                tanggal_absensi: this.tanggal,
                                deskripsi_kegiatan: this.deskripsi || null,
                            }),
                        });
                        const data = await res.json().catch(() => ({}));

                        if (res.ok) {
                            if (!data.duplikat) {
                                this.log.unshift(data.peserta);
                                if (navigator.vibrate) navigator.vibrate(100);
                            }
                            this.pesan = { tipe: data.duplikat ? 'warn' : 'ok', teks: data.message };
                        } else {
                            this.pesan = { tipe: 'err', teks: data.message || 'Gagal mencatat absensi. Coba lagi.' };
                        }
                    } catch (e) {
                        this.pesan = { tipe: 'err', teks: 'Tidak bisa terhubung ke server. Cek koneksi lalu coba lagi.' };
                    } finally {
                        this.busy = false;
                    }
                },
            };
        }
    </script>

</body>
</html>