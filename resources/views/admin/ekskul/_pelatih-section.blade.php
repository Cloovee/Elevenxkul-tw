{{--
    Bagian "Pelatih Ekskul" di form Tambah/Edit Ekskul (Admin).

    Saklar "Memiliki pelatih / Tanpa pelatih":
      - Tanpa pelatih  -> ekskuls.id_pelatih dikosongkan.
      - Memiliki pelatih -> muncul form data pelatih (disimpan ke tabel `pelatih`
        lalu dikaitkan lewat ekskuls.id_pelatih).

    Variabel: $ekskul (null saat Tambah, model Ekskul saat Edit).

    Gaya & animasi ditulis di blok <style> di bawah (prefix "plt-") supaya langsung
    berfungsi tanpa perlu build ulang Tailwind (npm run build).
--}}
@php
    $ekskul = $ekskul ?? null;
    $pelatihAda = $ekskul?->pelatih;
    $punyaAwal = (bool) old('punya_pelatih', $pelatihAda ? 1 : 0);
    $fotoLama = $pelatihAda?->foto_url;

    $daftarAgama = ['islam' => 'Islam', 'kristen' => 'Kristen', 'katolik' => 'Katolik', 'hindu' => 'Hindu', 'buddha' => 'Buddha', 'konghucu' => 'Konghucu'];
    $agamaNilai = strtolower((string) old('pelatih_agama', $pelatihAda?->agama));

    $kelasInput = 'w-full px-4 py-3 bg-[#f4f7fe] border %s rounded-xl text-sm text-[#2b3674] placeholder-[#a3aed1] focus:outline-none focus:bg-white focus:border-[#868dfb] focus:ring-2 focus:ring-[#868dfb]/20 transition-all';
@endphp

<style>
    .plt-card {
        background: #fff;
        border: 1.5px solid #e3e8f8;
        border-radius: 1.25rem;
        padding: 1.1rem 1.25rem;
        transition: border-color .35s ease, box-shadow .35s ease;
    }
    .plt-card.is-on {
        border-color: rgba(134, 141, 251, .65);
        box-shadow: 0 14px 34px -18px rgba(67, 24, 255, .45);
    }

    .plt-head { display: flex; align-items: center; gap: .9rem; flex-wrap: wrap; }
    .plt-title { flex: 1 1 14rem; min-width: 0; }
    .plt-label { font-size: .75rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: #2b3674; }
    .plt-sub { font-size: .8rem; color: #a3aed1; margin-top: .15rem; font-weight: 500; }

    /* Ikon: ganti warna + goyang kecil saat saklar berubah */
    .plt-ico {
        position: relative; width: 46px; height: 46px; border-radius: 14px; overflow: hidden;
        display: grid; place-items: center; color: #868dfb; background: #e9edfb; flex: none;
        transition: transform .5s cubic-bezier(.34, 1.56, .64, 1), color .3s ease;
    }
    .plt-ico::before {
        content: ""; position: absolute; inset: 0; opacity: 0;
        background: linear-gradient(135deg, #868dfb, #4318FF); transition: opacity .35s ease;
    }
    .plt-ico i { position: relative; z-index: 1; font-size: 1.1rem; }
    .plt-card.is-on .plt-ico { color: #fff; transform: rotate(-7deg) scale(1.08); }
    .plt-card.is-on .plt-ico::before { opacity: 1; }

    /* Saklar */
    .plt-switch { position: relative; display: inline-flex; align-items: center; gap: .7rem; cursor: pointer; user-select: none; }
    .plt-switch input[type="checkbox"] { position: absolute; opacity: 0; width: 1px; height: 1px; margin: 0; }
    .plt-track {
        position: relative; width: 54px; height: 30px; border-radius: 999px; background: #d5dbef; flex: none;
        transition: background .3s ease, box-shadow .3s ease;
    }
    .plt-knob {
        position: absolute; top: 3px; left: 3px; width: 24px; height: 24px; border-radius: 50%; background: #fff;
        box-shadow: 0 2px 6px rgba(43, 54, 116, .3);
        transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
    }
    .plt-switch:active .plt-knob { width: 29px; }
    .plt-switch input:checked + .plt-track {
        background: linear-gradient(135deg, #868dfb, #4318FF);
        box-shadow: 0 8px 18px -8px rgba(67, 24, 255, .65);
    }
    .plt-switch input:checked + .plt-track .plt-knob { transform: translateX(24px); }
    .plt-switch:active input:checked + .plt-track .plt-knob { transform: translateX(19px); }
    .plt-switch input:focus-visible + .plt-track { outline: 3px solid rgba(134, 141, 251, .5); outline-offset: 3px; }
    .plt-switch-text { font-size: .8rem; font-weight: 700; color: #2b3674; min-width: 6.6rem; }

    /* Catatan kecil di bawah header */
    .plt-note { margin-top: .85rem; padding: .7rem .9rem; border-radius: .9rem; font-size: .78rem; line-height: 1.45; font-weight: 500; }
    .plt-note i { margin-right: .35rem; }
    .plt-note-info { background: #f4f7fe; color: #5b6e96; animation: pltRise .45s ease both; }
    .plt-note-warn { background: #fff7e0; color: #946200; animation: pltRise .45s ease both; }

    /* Panel form: terbuka halus dengan trik grid 0fr -> 1fr */
    .plt-collapse {
        display: grid; grid-template-rows: 0fr; opacity: 0; visibility: hidden;
        transition: grid-template-rows .55s cubic-bezier(.22, 1, .36, 1), opacity .4s ease, visibility 0s linear .55s;
    }
    .plt-collapse.open {
        grid-template-rows: 1fr; opacity: 1; visibility: visible;
        transition: grid-template-rows .55s cubic-bezier(.22, 1, .36, 1), opacity .4s ease .05s, visibility 0s;
    }
    .plt-collapse-inner { overflow: hidden; min-height: 0; }
    .plt-body { padding: 1.1rem 3px 3px; border-top: 1px dashed #dfe5f7; margin-top: 1rem; }
    .plt-grid { display: grid; grid-template-columns: 1fr; gap: 1.1rem; }
    @media (min-width: 768px) { .plt-grid { grid-template-columns: 1fr 1fr; } }
    .plt-span { grid-column: 1 / -1; }

    /* Kolom muncul berurutan setiap panel dibuka */
    .plt-collapse.open .plt-field { animation: pltRise .55s cubic-bezier(.22, 1, .36, 1) both; animation-delay: calc(var(--i, 0) * 55ms + 120ms); }
    @keyframes pltRise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
    @keyframes pltPop { 0% { transform: scale(.6); opacity: 0; } 70% { transform: scale(1.06); opacity: 1; } 100% { transform: scale(1); } }

    /* Foto pelatih */
    .plt-foto { display: flex; gap: 1.1rem; align-items: center; flex-wrap: wrap; }
    .plt-avatar {
        width: 88px; height: 88px; border-radius: 50%; overflow: hidden; flex: none; background: #f4f7fe;
        box-shadow: 0 0 0 1px rgba(16, 49, 107, .1); display: grid; place-items: center; color: #a3aed1;
    }
    .plt-avatar img { width: 100%; height: 100%; object-fit: cover; animation: pltPop .5s cubic-bezier(.34, 1.56, .64, 1) both; }
    .plt-avatar i { font-size: 1.7rem; }
    .plt-foto-input { flex: 1 1 14rem; min-width: 0; }

    @media (prefers-reduced-motion: reduce) {
        .plt-card, .plt-ico, .plt-knob, .plt-track, .plt-collapse, .plt-collapse.open { transition: none; }
        .plt-collapse.open .plt-field, .plt-note, .plt-avatar img { animation: none; }
    }
</style>

<div class="md:col-span-2"
     x-data="{ punya: @js($punyaAwal), foto: @js($fotoLama), hapusFoto: false }">

    <div class="plt-card" :class="punya ? 'is-on' : ''">

        <!-- Header + saklar -->
        <div class="plt-head">
            <div class="plt-ico">
                <i class="fas" :class="punya ? 'fa-person-chalkboard' : 'fa-user-slash'"></i>
            </div>

            <div class="plt-title">
                <p class="plt-label">Pelatih Ekskul</p>
                <p class="plt-sub" x-text="punya
                    ? 'Ekskul ini memiliki pelatih. Lengkapi data pelatih di bawah.'
                    : 'Ekskul ini berjalan tanpa pelatih.'">
                    {{ $punyaAwal ? 'Ekskul ini memiliki pelatih. Lengkapi data pelatih di bawah.' : 'Ekskul ini berjalan tanpa pelatih.' }}
                </p>
            </div>

            <input type="hidden" name="punya_pelatih" value="0">
            <label class="plt-switch">
                <input type="checkbox" name="punya_pelatih" value="1" role="switch"
                       x-model="punya" @checked($punyaAwal)>
                <span class="plt-track"><span class="plt-knob"></span></span>
                <span class="plt-switch-text" x-text="punya ? 'Memiliki pelatih' : 'Tanpa pelatih'">
                    {{ $punyaAwal ? 'Memiliki pelatih' : 'Tanpa pelatih' }}
                </span>
            </label>
        </div>

        <!-- Catatan saat "Tanpa pelatih" -->
        <div class="plt-note plt-note-info" x-show="!punya" x-cloak>
            <i class="fas fa-circle-info"></i>
            Aktifkan saklar jika ekskul ini dilatih oleh pelatih. Absensi pelatih di sisi Ketua hanya bisa dipakai oleh ekskul yang memiliki pelatih.
        </div>

        @if($pelatihAda)
            <div class="plt-note plt-note-warn" x-show="!punya" x-cloak>
                <i class="fas fa-triangle-exclamation"></i>
                Saat disimpan, pelatih saat ini (<strong>{{ $pelatihAda->nama_pelatih }}</strong>) akan dilepas dari ekskul ini.
                Datanya dan riwayat absensinya tetap tersimpan.
            </div>
        @endif

        <!-- Form data pelatih (muncul kalau "Memiliki pelatih") -->
        <div class="plt-collapse" :class="punya ? 'open' : ''"
             :aria-hidden="(!punya).toString()" :inert="!punya">
            <div class="plt-collapse-inner">
                <div class="plt-body">
                    <div class="plt-grid">

                        <!-- Foto -->
                        <div class="plt-field plt-span" style="--i:0">
                            <label for="pelatih_foto" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Foto Pelatih
                            </label>
                            <div class="plt-foto">
                                <div class="plt-avatar">
                                    <template x-if="foto && !hapusFoto">
                                        <img :src="foto" alt="Pratinjau foto pelatih">
                                    </template>
                                    <i class="fas fa-user" x-show="!foto || hapusFoto"></i>
                                </div>
                                <div class="plt-foto-input">
                                    <input type="file" name="pelatih_foto" id="pelatih_foto" accept="image/png,image/jpeg,image/webp"
                                        @change="const f = $event.target.files[0]; if (f) { foto = URL.createObjectURL(f); hapusFoto = false }"
                                        class="block w-full text-sm text-[#2b3674] file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#0B409C] file:text-white hover:file:opacity-90 file:cursor-pointer bg-[#f4f7fe] rounded-xl @error('pelatih_foto') ring-2 ring-red-500 @enderror">
                                    <p class="mt-1.5 text-xs text-[#a3aed1]">JPG, PNG, atau WEBP, maksimal 2 MB. Opsional.</p>
                                    @if($pelatihAda?->foto)
                                        <label class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-red-600 cursor-pointer">
                                            <input type="checkbox" name="hapus_pelatih_foto" value="1" x-model="hapusFoto" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            Hapus foto saat ini
                                        </label>
                                    @endif
                                    @error('pelatih_foto')
                                        <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nama -->
                        <div class="plt-field" style="--i:1">
                            <label for="pelatih_nama" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Nama Pelatih <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pelatih_nama" id="pelatih_nama" maxlength="100"
                                value="{{ old('pelatih_nama', $pelatihAda?->nama_pelatih) }}"
                                placeholder="Nama lengkap pelatih" :required="punya"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_nama') ? 'border-red-500' : 'border-transparent') }}">
                            @error('pelatih_nama')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Jenis kelamin -->
                        <div class="plt-field" style="--i:2">
                            <label for="pelatih_jk" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Jenis Kelamin
                            </label>
                            <select name="pelatih_jk" id="pelatih_jk"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_jk') ? 'border-red-500' : 'border-transparent') }}">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('pelatih_jk', $pelatihAda?->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('pelatih_jk', $pelatihAda?->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('pelatih_jk')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Agama -->
                        <div class="plt-field" style="--i:3">
                            <label for="pelatih_agama" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Agama
                            </label>
                            <select name="pelatih_agama" id="pelatih_agama"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_agama') ? 'border-red-500' : 'border-transparent') }}">
                                <option value="">-- Pilih --</option>
                                @foreach($daftarAgama as $nilai => $label)
                                    <option value="{{ $nilai }}" {{ $agamaNilai === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                                @if($agamaNilai !== '' && ! array_key_exists($agamaNilai, $daftarAgama))
                                    <option value="{{ $agamaNilai }}" selected>{{ ucfirst($agamaNilai) }}</option>
                                @endif
                            </select>
                            @error('pelatih_agama')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- No HP -->
                        <div class="plt-field" style="--i:4">
                            <label for="pelatih_nomor_hp" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                No. HP
                            </label>
                            <input type="text" name="pelatih_nomor_hp" id="pelatih_nomor_hp" maxlength="15" inputmode="tel"
                                value="{{ old('pelatih_nomor_hp', $pelatihAda?->nomor_hp) }}"
                                placeholder="08xxxxxxxxxx"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_nomor_hp') ? 'border-red-500' : 'border-transparent') }}">
                            @error('pelatih_nomor_hp')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="plt-field" style="--i:5">
                            <label for="pelatih_email" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Email
                            </label>
                            <input type="email" name="pelatih_email" id="pelatih_email" maxlength="100"
                                value="{{ old('pelatih_email', $pelatihAda?->email) }}"
                                placeholder="nama@email.com"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_email') ? 'border-red-500' : 'border-transparent') }}">
                            @error('pelatih_email')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Media sosial -->
                        <div class="plt-field" style="--i:6">
                            <label for="pelatih_medsos" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Media Sosial
                            </label>
                            <input type="text" name="pelatih_medsos" id="pelatih_medsos" maxlength="100"
                                value="{{ old('pelatih_medsos', $pelatihAda?->medsos) }}"
                                placeholder="@username"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_medsos') ? 'border-red-500' : 'border-transparent') }}">
                            @error('pelatih_medsos')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="plt-field plt-span" style="--i:7">
                            <label for="pelatih_alamat" class="block text-xs font-bold text-[#2b3674] uppercase tracking-wider mb-2">
                                Alamat
                            </label>
                            <textarea name="pelatih_alamat" id="pelatih_alamat" rows="2"
                                placeholder="Alamat pelatih"
                                class="{{ sprintf($kelasInput, $errors->has('pelatih_alamat') ? 'border-red-500' : 'border-transparent') }}">{{ old('pelatih_alamat', $pelatihAda?->alamat) }}</textarea>
                            @error('pelatih_alamat')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <p class="plt-field plt-span text-xs text-[#a3aed1]" style="--i:8">
                            <i class="fas fa-circle-info"></i>
                            Data pelatih ini juga bisa diperbarui oleh Pembina lewat menu Kelola Pelatih.
                        </p>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
