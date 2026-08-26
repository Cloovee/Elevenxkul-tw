@extends('layouts.admin')

@section('title', 'Tambah Akun User')
@section('page-title', 'Manajemen Akun')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-8 max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#868dfb] to-[#4318FF] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-plus"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#2b3674]">Tambah Akun User</h3>
    </div>

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('name') ring-2 ring-red-400 @enderror" value="{{ old('name') }}" required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email') }}" required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('password') ring-2 ring-red-400 @enderror" required>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb]" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role-select" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('role') ring-2 ring-red-400 @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Pembina" {{ old('role') == 'Pembina' ? 'selected' : '' }}>Pembina</option>
                    <option value="Ketua" {{ old('role') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Muncul cuma kalau role = Ketua -->
            <div class="md:col-span-2" id="siswa-field" style="display: none;">
                <label class="block text-xs font-bold text-[#a3aed1] uppercase tracking-wider mb-2">Pilih Siswa (jadi Ketua) <span class="text-red-500">*</span></label>
                <select name="id_siswa" class="w-full px-4 py-2.5 bg-[#f4f7fe] border-none rounded-xl text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb] @error('id_siswa') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaBelumPunyaAkun as $s)
                        <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} ({{ $s->NISN }})
                        </option>
                    @endforeach
                </select>
                @error('id_siswa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @if($siswaBelumPunyaAkun->isEmpty())
                    <p class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full mt-2">
                        <i class="fas fa-triangle-exclamation"></i> Semua siswa sudah punya akun, atau belum ada data siswa.
                    </p>
                @endif
            </div>

            @if(old('role') == 'Pembina')
                <div class="md:col-span-2 flex items-start gap-2 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-2xl text-sm text-blue-700">
                    <i class="fas fa-circle-info mt-0.5"></i>
                    <p>Biodata Pembina (nama, agama, alamat, dll) diisi terpisah nanti di menu <strong>Kelola Pembina</strong>, setelah akun ini dibuat.</p>
                </div>
            @endif
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<script>
    const roleSelect = document.getElementById('role-select');
    const siswaField = document.getElementById('siswa-field');

    function toggleSiswaField() {
        siswaField.style.display = roleSelect.value === 'Ketua' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleSiswaField);
    toggleSiswaField(); // jalankan sekali pas halaman dibuka (buat kasus old() setelah validasi gagal)
</script>
@endsection