<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">📋 Data Siswa</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                ➕ Tambah
                            </a>
                            <a href="{{ route('admin.siswa.import.form') }}" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                📤 Import
                            </a>
                            <a href="{{ route('admin.siswa.export') }}" class="inline-flex items-center px-3 py-1 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700">
                                📥 Export
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filter -->
                    <form method="GET" class="flex flex-wrap gap-2 mb-4">
                        <input type="text" name="search" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="🔍 Cari NISN/NIS/Nama..." value="{{ request('search') }}">
                        <select name="kelas" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->jurusan }} - {{ $k->rombel }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filter</button>
                        <a href="{{ route('admin.siswa.index') }}" class="px-4 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Reset</a>
                    </form>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">JK</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Agama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">MedSos</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($siswa as $key => $s)
                                <tr>
                                    <td class="px-4 py-2">{{ $siswa->firstItem() + $key }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->NISN }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->NIS }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->nama_siswa }}</td>
                                    <td class="px-4 py-2">{{ $s->jk }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->nama_kelas }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->agama ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->nomor_hp ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->email ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $s->medsos ?? '-' }}</td>
                                    <td class="px-4 py-2 max-w-xs truncate" title="{{ $s->alamat }}">{{ $s->alamat ?? '-' }}</td>
                                    <td class="px-4 py-2 flex gap-1">
                                        <a href="{{ route('admin.siswa.edit', $s->id_siswa) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">✏️</a>
                                        <form action="{{ route('admin.siswa.destroy', $s->id_siswa) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs" onclick="return confirm('Yakin?')">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-6 text-gray-500">Belum ada data siswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $siswa->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>