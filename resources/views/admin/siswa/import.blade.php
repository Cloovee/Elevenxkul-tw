<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">📤 Import Data Siswa</h3>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
                    @endif
                    @if(session('warning'))
                        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            {{ session('warning') }}
                            @if(session('errors'))
                                <ul class="list-disc pl-5 mt-2">
                                    @foreach(session('errors') as $e)
                                        <li>Baris {{ $e['baris'] }}: {{ $e['error'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">📁 Pilih File Excel</label>
                            <input type="file" name="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" accept=".xlsx,.xls,.csv" required>
                            <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, .csv | Maks: 5MB</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">🏫 Kelas Default (Opsional)</label>
                            <select name="id_kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Gunakan dari file --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->jurusan }} - {{ $k->rombel }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Jika jurusan/rombel tidak ditemukan di database, akan pakai kelas ini.</p>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700">
                                📄 Download Template
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                🚀 Import
                            </button>
                            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                ⬅ Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>