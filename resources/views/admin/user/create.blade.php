@extends('layouts.admin')

@section('title', 'Tambah Pembina')
@section('page-title', 'Manajemen Akun')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-8 max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Tambah Pembina</h3>
    </div>

    <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('name') ring-2 ring-red-400 @enderror" value="{{ old('name') }}" required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                <input type="text" name="username" minlength="3" maxlength="50" pattern="[A-Za-z0-9_\-]+" title="Hanya huruf, angka, strip (-) dan underscore (_), tanpa spasi dan tanpa @" autocomplete="off" autocapitalize="none" spellcheck="false" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('username') ring-2 ring-red-400 @enderror" value="{{ old('username') }}" required>
                @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-[#7C8DB5] mt-1.5">Dipakai untuk login. Cukup huruf, angka, - atau _ (tanpa @ dan .com).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Email <span class="text-xs normal-case font-normal text-[#7C8DB5]">(opsional)</span></label>
                <input type="email" name="email" autocomplete="off" autocapitalize="none" spellcheck="false" placeholder="nama@sekolah.sch.id" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('email') ring-2 ring-red-400 @enderror" value="{{ old('email', '') }}">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-[#7C8DB5] mt-1.5">Boleh dikosongkan. Kalau diisi harus email asli yang aktif, dan bisa dipakai login selain username.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" minlength="8" autocomplete="new-password" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('password') ring-2 ring-red-400 @enderror" required>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-[#7C8DB5] mt-1.5">Minimal 8 karakter. Campur huruf dan angka supaya lebih aman.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" minlength="8" autocomplete="new-password" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C]" required>
                <p class="text-xs text-[#7C8DB5] mt-1.5">Ketik ulang password di atas, harus sama persis.</p>
            </div>

            <input type="hidden" name="role" value="Pembina">

            @include('admin.user._biodata-pembina')
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<script>
    // Form ini khusus Pembina, jadi biodata selalu tampil
    document.getElementById('pembina-fields').style.display = 'block';
    document.getElementById('jk-select').required = true;

    document.getElementById('foto-input')?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('foto-preview');
        if (!file || !preview) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            preview.innerHTML = '<img src="' + ev.target.result + '" class="w-full h-full object-cover" alt="Preview foto">';
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection