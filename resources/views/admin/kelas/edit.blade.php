@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-gray-50 p-5 sm:p-8 max-w-lg mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 bg-[#0B409C] rounded-xl flex items-center justify-center text-white">
            <i class="fas fa-school"></i>
        </div>
        <h3 class="text-xl font-extrabold text-[#10316B]">Edit Kelas</h3>
    </div>

    <form action="{{ route('admin.kelas.update', $kelas->id_kelas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Tingkat <span class="text-red-500">*</span></label>
            <select name="tingkat" id="tingkat-select" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('tingkat') ring-2 ring-red-400 @enderror" required>
                <option value="">-- Pilih Tingkat --</option>
                @foreach(\App\Models\Kelas::TINGKAT_OPTIONS as $t)
                    <option value="{{ $t }}" @selected(old('tingkat', $kelas->tingkat) === $t)>{{ $t }}</option>
                @endforeach
            </select>
            @error('tingkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Program Keahlian <span class="text-red-500">*</span></label>
            <select name="program_keahlian" id="program-keahlian-select" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('program_keahlian') ring-2 ring-red-400 @enderror" required>
                <option value="">-- Pilih Tingkat dulu --</option>
            </select>
            @error('program_keahlian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-[#7C8DB5] uppercase tracking-wider mb-2">Rombel <span class="text-red-500">*</span></label>
            <input type="text" name="rombel" placeholder="Contoh: 1" class="w-full px-4 py-2.5 bg-[#F2F7FF] border-none rounded-xl text-sm text-[#10316B] focus:ring-2 focus:ring-[#0B409C] @error('rombel') ring-2 ring-red-400 @enderror" value="{{ old('rombel', $kelas->rombel) }}" required>
            @error('rombel')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B409C] text-white rounded-full text-sm font-bold shadow-md shadow-[#0B409C]/30 hover:opacity-90 transition-opacity">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F2F7FF] hover:bg-[#DDE8FB] text-[#10316B] rounded-full text-sm font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<script>
    // Sama seperti Create: Program Keahlian tergantung Tingkat yang dipilih.
    const programKeahlianPerTingkat = @json(\App\Models\Kelas::PROGRAM_KEAHLIAN_PER_TINGKAT);
    const tingkatSelect = document.getElementById('tingkat-select');
    const programSelect = document.getElementById('program-keahlian-select');
    const programTerpilihAwal = @json(old('program_keahlian', $kelas->program_keahlian));

    function isiDropdownProgramKeahlian(tingkatTerpilih, programTerpilih) {
        programSelect.innerHTML = '';

        const daftar = programKeahlianPerTingkat[tingkatTerpilih] || [];

        if (daftar.length === 0) {
            programSelect.appendChild(new Option('-- Pilih Tingkat dulu --', ''));
            return;
        }

        programSelect.appendChild(new Option('-- Pilih Program Keahlian --', ''));
        daftar.forEach(function (pk) {
            const option = new Option(pk, pk, false, pk === programTerpilih);
            programSelect.appendChild(option);
        });
    }

    tingkatSelect.addEventListener('change', function () {
        isiDropdownProgramKeahlian(this.value, null);
    });

    isiDropdownProgramKeahlian(tingkatSelect.value, programTerpilihAwal);
</script>
@endsection