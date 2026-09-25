<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/smkn11logo.png') }}" type="image/png">
    <title>Edit Ketua — ElevenXkul</title>
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
        <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Edit Ketua</h1>
        <p class="text-sm text-inksoft mb-6">Ubah biodata & akun login ketua. Kosongkan password kalau tidak ingin menggantinya.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.ketua.update', $siswa->id_siswa) }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Ekskul yang Dipimpin <span class="text-red-500">*</span></label>
                <select name="id_ekskul" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    <option value="">-- Pilih Ekskul --</option>
                    @foreach($ekskuls as $e)
                        <option value="{{ $e->id_ekskul }}" {{ old('id_ekskul', $ekskulSaatIni) == $e->id_ekskul ? 'selected' : '' }}>
                            {{ $e->nama_ekskul }}{{ $e->ketua && $e->id_ekskul != $ekskulSaatIni ? ' (akan menggantikan: '.$e->ketua->nama_siswa.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs font-bold text-inksoft uppercase tracking-wide pt-2 border-t border-[#F1F1FA]">Biodata Siswa</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">NISN <span class="text-red-500">*</span></label>
                    <input type="text" name="NISN" required value="{{ old('NISN', $siswa->NISN) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="NIS" required value="{{ old('NIS', $siswa->NIS) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama_siswa" required value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jk" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                        <option value="L" {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Agama</label>
                    <input type="text" name="agama" value="{{ old('agama', $siswa->agama) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nomor HP</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $siswa->nomor_hp) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email', $siswa->email) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">MedSos</label>
                    <input type="text" name="medsos" value="{{ old('medsos', $siswa->medsos) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            <p class="text-xs font-bold text-inksoft uppercase tracking-wide pt-2 border-t border-[#F1F1FA]">Akun Login</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" required value="{{ old('username', $siswa->user->username ?? '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div></div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Password Baru</label>
                    <input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak diganti"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" minlength="8"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            @unless($siswa->user)
                <p class="text-xs text-amber-700 bg-amber-50 px-3 py-2 rounded-xl">
                    Siswa ini belum punya akun login. Username/password di atas tidak akan tersimpan sampai akunnya dibuatkan lewat CRUD Ketua ini lagi (hapus lalu tambahkan ulang), atau hubungi Admin.
                </p>
            @endunless

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.ketua.index') }}"
                   class="flex-1 text-center py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-tr from-[#57C785] to-[#1F9D5E] hover:opacity-90 text-white font-bold text-sm py-2.5 rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
