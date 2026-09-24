<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/smkn11logo.png') }}" type="image/png">
    <title>Edit Pelatih — ElevenXkul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bgsoft text-ink min-h-screen flex items-center justify-center p-5"
      style="background-image: radial-gradient(circle at 100% 0%, rgba(217,249,223,0.45), transparent 45%), radial-gradient(circle at 0% 100%, rgba(174,226,255,0.35), transparent 40%);">

<div class="w-full max-w-lg">
    <a href="{{ route('pembina.pelatih.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Kelola Pelatih
    </a>

    <div class="bg-white rounded-3xl p-7 shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)]">
        <div class="w-11 h-11 rounded-2xl bg-mint text-[#1F7A3D] flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <h1 class="font-display text-xl font-semibold mb-1">Edit Pelatih</h1>
        <p class="text-sm text-inksoft mb-6">
            Perbarui biodata <span class="font-semibold text-ink">{{ $pelatih->nama_pelatih }}</span>.
        </p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 text-sm font-medium px-4 py-3 rounded-2xl mb-4">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pembina.pelatih.update', $pelatih->id_pelatih) }}" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Ekskul <span class="text-red-500">*</span></label>
                <select name="id_ekskul" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                    <option value="">-- Pilih Ekskul --</option>
                    @foreach($ekskuls as $e)
                        <option value="{{ $e->id_ekskul }}" {{ old('id_ekskul', $ekskulSaatIni) == $e->id_ekskul ? 'selected' : '' }}>
                            {{ $e->nama_ekskul }}
                            @if($e->pelatih && $e->id_ekskul != $ekskulSaatIni)
                                (akan menggantikan: {{ $e->pelatih->nama_pelatih }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-inksoft">Hanya ekskul yang kamu bina yang muncul di sini.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nama Pelatih <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelatih" required value="{{ old('nama_pelatih', $pelatih->nama_pelatih) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Jenis Kelamin</label>
                    <select name="jk" class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jk', $pelatih->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk', $pelatih->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Agama</label>
                    <input type="text" name="agama" value="{{ old('agama', $pelatih->agama) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Nomor HP</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $pelatih->nomor_hp) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $pelatih->email) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
                <div>
                    <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">MedSos</label>
                    <input type="text" name="medsos" value="{{ old('medsos', $pelatih->medsos) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-inksoft uppercase tracking-wide mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E7E7F4] text-sm focus:outline-none focus:border-lavender">{{ old('alamat', $pelatih->alamat) }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
                <a href="{{ route('pembina.pelatih.index') }}"
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