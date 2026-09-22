@extends('layouts.admin')

@section('title', 'Edit Akun User')
@section('page-title', 'Manajemen Akun')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_10px_30px_-18px_rgba(46,43,85,0.35)] border border-ink/5 p-8 max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-gradient-to-br from-[#9FA1FF] to-[#7B77E0] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-pen"></i>
        </div>
        <h3 class="text-xl font-display font-bold text-[#2E2B55]">Edit Akun User</h3>
    </div>

    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('name') ring-2 ring-red-400 @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan kalau tidak ingin ganti" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] placeholder-[#6B6795] focus:ring-2 focus:ring-[#9FA1FF] @error('password') ring-2 ring-red-400 @enderror">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF]">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role-select" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('role') ring-2 ring-red-400 @enderror" required>
                    <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Pembina" {{ old('role', $user->role) == 'Pembina' ? 'selected' : '' }}>Pembina</option>
                    <option value="Ketua" {{ old('role', $user->role) == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2" id="siswa-field" style="display: none;">
                <label class="block text-xs font-bold text-[#6B6795] uppercase tracking-wider mb-2">Pilih Siswa (jadi Ketua) <span class="text-red-500">*</span></label>
                <select name="id_siswa" class="w-full px-4 py-2.5 bg-[#F3F4FC] border-none rounded-xl text-sm text-[#2E2B55] focus:ring-2 focus:ring-[#9FA1FF] @error('id_siswa') ring-2 ring-red-400 @enderror">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaBelumPunyaAkun as $s)
                        <option value="{{ $s->id_siswa }}" {{ old('id_siswa', $user->siswa->id_siswa ?? '') == $s->id_siswa ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} ({{ $s->NISN }})
                        </option>
                    @endforeach
                </select>
                @error('id_siswa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            @if($user->role == 'Pembina')
                <div class="md:col-span-2 flex items-start gap-2 p-4 bg-sky/15 border-l-4 border-sky-400 rounded-2xl text-sm text-sky-600">
                    <i class="fas fa-circle-info mt-0.5"></i>
                    <p>Ganti biodata Pembina (nama, agama, alamat, dll) lewat menu <strong>Kelola Pembina</strong>.</p>
                </div>
            @endif
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-[#9FA1FF] to-[#7B77E0] text-white rounded-full text-sm font-bold shadow-md shadow-[#9FA1FF]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F3F4FC] hover:bg-[#EEF0FD] text-[#2E2B55] rounded-full text-sm font-bold transition-colors">
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
    toggleSiswaField();
</script>
@endsection