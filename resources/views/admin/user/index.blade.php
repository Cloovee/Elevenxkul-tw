@extends('layouts.admin')

@section('title', 'Kelola Akun User')
@section('page-title', 'Manajemen Akun')

@section('content')
<div class="bg-white rounded-[1.5rem] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-6">

    <!-- Header: Judul + Search + Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h3 class="text-xl font-extrabold text-[#2b3674]">Kelola Akun User</h3>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#a3aed1] text-sm"></i>
                <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] placeholder-[#a3aed1] focus:ring-2 focus:ring-[#868dfb] w-56">
            </div>

            <select name="role" class="px-4 py-2 bg-[#f4f7fe] border-none rounded-full text-sm text-[#2b3674] focus:ring-2 focus:ring-[#868dfb]">
                <option value="">Semua Role</option>
                <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Pembina" {{ request('role') == 'Pembina' ? 'selected' : '' }}>Pembina</option>
                <option value="Ketua" {{ request('role') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#2b3674] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.user.index') }}" class="px-4 py-2 bg-[#f4f7fe] hover:bg-[#e9edfb] text-[#a3aed1] rounded-full text-sm font-bold transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-100">
        <a href="{{ route('admin.user.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-tr from-[#868dfb] to-[#4318FF] text-white rounded-full text-sm font-bold shadow-md shadow-[#868dfb]/30 hover:opacity-90 transition-opacity">
            <i class="fas fa-user-plus"></i> Tambah Akun
        </a>

        <!-- Ringkasan mini per role, biar keliatan sebaran akunnya -->
        <div class="ml-auto flex gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-purple-50 text-purple-600 rounded-full text-xs font-bold">
                <i class="fas fa-user-shield"></i> {{ $users->where('role', 'Admin')->count() }} Admin
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold">
                <i class="fas fa-user-tie"></i> {{ $users->where('role', 'Pembina')->count() }} Pembina
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-50 text-green-600 rounded-full text-xs font-bold">
                <i class="fas fa-user-graduate"></i> {{ $users->where('role', 'Ketua')->count() }} Ketua
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-[#a3aed1] text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">Akun</th>
                    <th class="px-3 py-3">Role</th>
                    <th class="px-3 py-3">Terkait Data</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $key => $u)
                @php
                    $roleStyle = match($u->role) {
                        'Admin' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'ring' => 'ring-purple-100', 'icon' => 'fa-user-shield'],
                        'Pembina' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'ring' => 'ring-blue-100', 'icon' => 'fa-user-tie'],
                        'Ketua' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'ring' => 'ring-green-100', 'icon' => 'fa-user-graduate'],
                        default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'ring' => 'ring-gray-100', 'icon' => 'fa-user'],
                    };
                    $initial = strtoupper(substr($u->name, 0, 1));
                @endphp
                <tr class="hover:bg-[#f4f7fe]/60 transition-colors">
                    <td class="px-3 py-3 text-[#a3aed1] font-medium">{{ $users->firstItem() + $key }}</td>

                    <!-- Kolom Akun: avatar inisial + nama + email -->
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }} flex items-center justify-center font-extrabold text-sm ring-2 {{ $roleStyle['ring'] }} flex-shrink-0">
                                {{ $initial }}
                            </div>
                            <div>
                                <p class="text-[#2b3674] font-bold leading-tight">{{ $u->name }}</p>
                                <p class="text-[#a3aed1] text-xs">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-3 py-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }}">
                            <i class="fas {{ $roleStyle['icon'] }} text-[10px]"></i> {{ $u->role }}
                        </span>
                    </td>

                    <td class="px-3 py-3">
                        @if($u->role == 'Ketua')
                            @if($u->siswa)
                                <span class="text-[#2b3674] font-semibold">{{ $u->siswa->nama_siswa }}</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">
                                    <i class="fas fa-triangle-exclamation"></i> Belum terkait siswa
                                </span>
                            @endif
                        @elseif($u->role == 'Pembina')
                            @if($u->pembina)
                                <span class="text-[#2b3674] font-semibold">{{ $u->pembina->nama_pembina }}</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">
                                    <i class="fas fa-triangle-exclamation"></i> Belum ada biodata
                                </span>
                            @endif
                        @else
                            <span class="text-[#a3aed1]">&mdash;</span>
                        @endif
                    </td>

                    <td class="px-3 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.user.edit', $u->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus akun ini? Data terkait (siswa/pembina) tetap tersimpan, cuma akun login-nya yang hilang.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12">
                        <div class="flex flex-col items-center text-[#a3aed1]">
                            <i class="fas fa-users-slash text-3xl mb-2"></i>
                            <p class="font-bold text-sm">Belum ada akun user.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection