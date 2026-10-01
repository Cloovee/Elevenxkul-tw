<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::withCount('siswa');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tingkat', 'LIKE', "%{$search}%")
                  ->orWhere('program_keahlian', 'LIKE', "%{$search}%")
                  ->orWhere('rombel', 'LIKE', "%{$search}%");
            });
        }

        if ($request->tingkat) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->program_keahlian) {
            $query->where('program_keahlian', $request->program_keahlian);
        }

        $daftarTingkat = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');
        $daftarProgram = Kelas::select('program_keahlian')->distinct()->orderBy('program_keahlian')->pluck('program_keahlian');

        $kelas = $query->orderBy('tingkat')->orderBy('program_keahlian')->orderBy('rombel')->paginate(20)->withQueryString();

        return view('admin.kelas.index', compact('kelas', 'daftarTingkat', 'daftarProgram'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->aturanValidasi($request), [
            'tingkat.unique' => 'Kelas dengan tingkat, program keahlian, dan rombel yang sama sudah ada.',
            'tingkat.in' => 'Tingkat harus salah satu dari: 10, 11, 12.',
            'program_keahlian.in' => 'Program Keahlian tidak sesuai dengan Tingkat yang dipilih.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Kelas::create($request->only('tingkat', 'program_keahlian', 'rombel'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, int $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validator = Validator::make($request->all(), $this->aturanValidasi($request, $kelas->id_kelas), [
            'tingkat.unique' => 'Kelas dengan tingkat, program keahlian, dan rombel yang sama sudah ada.',
            'tingkat.in' => 'Tingkat harus salah satu dari: 10, 11, 12.',
            'program_keahlian.in' => 'Program Keahlian tidak sesuai dengan Tingkat yang dipilih.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kelas->update($request->only('tingkat', 'program_keahlian', 'rombel'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $kelas = Kelas::withCount('siswa')->findOrFail($id);

        if ($kelas->siswa_count > 0) {
            return redirect()->route('admin.kelas.siswa', $kelas->id_kelas)
                ->with('error', "Kelas {$kelas->nama_kelas} tidak bisa dihapus karena masih ada {$kelas->siswa_count} siswa di dalamnya. Pindahkan atau hapus siswanya dulu.");
        }

        $nama = $kelas->nama_kelas;
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$nama} berhasil dihapus!");
    }

    /**
     * Daftar siswa yang ada di satu kelas.
     * Dari sini admin bisa edit siswa, memindahkan siswa ke kelas lain,
     * atau menghapus siswa (satu per satu / pilih semua) supaya kelas bisa dihapus.
     */
    public function siswa(Request $request, int $id)
    {
        $kelas = Kelas::withCount('siswa')->findOrFail($id);

        $query = $kelas->siswa()->getQuery();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'LIKE', "%{$search}%")
                  ->orWhere('NISN', 'LIKE', "%{$search}%")
                  ->orWhere('NIS', 'LIKE', "%{$search}%");
            });
        }

        $siswa = $query->orderBy('nama_siswa')->paginate(50)->withQueryString();

        // Pilihan kelas tujuan untuk fitur "Pindahkan" (kelas ini sendiri dikecualikan).
        $kelasLain = Kelas::where('id_kelas', '!=', $kelas->id_kelas)
            ->orderBy('tingkat')->orderBy('program_keahlian')->orderBy('rombel')
            ->get();

        // Semua id siswa di kelas ini -> dipakai tombol "Pilih semua siswa di kelas" (lintas halaman).
        $semuaId = $kelas->siswa()->pluck('id_siswa');

        return view('admin.kelas.siswa', compact('kelas', 'siswa', 'kelasLain', 'semuaId'));
    }

    /**
     * Pindahkan siswa terpilih ke kelas lain.
     */
    public function pindahSiswa(Request $request, int $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'id_siswa' => ['required', 'array', 'min:1'],
            'id_siswa.*' => ['integer'],
            'id_kelas_tujuan' => ['required', 'exists:kelas,id_kelas', Rule::notIn([$kelas->id_kelas])],
        ], [
            'id_siswa.required' => 'Pilih minimal 1 siswa dulu.',
            'id_kelas_tujuan.required' => 'Pilih kelas tujuan dulu.',
            'id_kelas_tujuan.not_in' => 'Kelas tujuan harus berbeda dengan kelas asal.',
        ]);

        // Hanya siswa yang memang berada di kelas ini yang boleh dipindah.
        $jumlah = Siswa::where('id_kelas', $kelas->id_kelas)
            ->whereIn('id_siswa', $request->id_siswa)
            ->update(['id_kelas' => $request->id_kelas_tujuan]);

        $tujuan = Kelas::find($request->id_kelas_tujuan);

        return redirect()->route('admin.kelas.siswa', $kelas->id_kelas)
            ->with('success', "{$jumlah} siswa berhasil dipindahkan ke kelas {$tujuan->nama_kelas}.");
    }

    /**
     * Hapus siswa terpilih dari kelas ini (data siswanya ikut terhapus).
     */
    public function hapusSiswa(Request $request, int $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'id_siswa' => ['required', 'array', 'min:1'],
            'id_siswa.*' => ['integer'],
        ], [
            'id_siswa.required' => 'Pilih minimal 1 siswa dulu.',
        ]);

        $daftar = Siswa::with('user')
            ->where('id_kelas', $kelas->id_kelas)
            ->whereIn('id_siswa', $request->id_siswa)
            ->get();

        DB::transaction(function () use ($daftar) {
            foreach ($daftar as $s) {
                // Akun login (kalau siswa ini dijadikan Ketua) ikut dihapus supaya tidak yatim.
                $user = $s->user;
                $s->delete();
                if ($user) {
                    $user->delete();
                }
            }
        });

        $sisa = $kelas->siswa()->count();
        $pesan = "{$daftar->count()} siswa berhasil dihapus dari kelas {$kelas->nama_kelas}.";
        if ($sisa === 0) {
            $pesan .= ' Kelas sudah kosong, sekarang kelas ini bisa dihapus.';
        }

        return redirect()->route('admin.kelas.siswa', $kelas->id_kelas)->with('success', $pesan);
    }

    /**
     * Aturan validasi Create & Edit Kelas (disatukan supaya dua form itu
     * konsisten, sesuai requirement). Tingkat WAJIB salah satu dari
     * Kelas::TINGKAT_OPTIONS, dan Program Keahlian WAJIB salah satu dari
     * daftar yang valid UNTUK tingkat yang dipilih (Kelas::PROGRAM_KEAHLIAN_PER_TINGKAT) --
     * jadi kombinasi seperti "10"+"RPL" atau "11"+"PPLG" otomatis ditolak.
     * Rombel sengaja tetap bebas teks (bukan dropdown), sesuai requirement.
     */
    private function aturanValidasi(Request $request, ?int $ignoreId = null): array
    {
        $tingkat = $request->input('tingkat');
        $pilihanProgram = Kelas::PROGRAM_KEAHLIAN_PER_TINGKAT[$tingkat] ?? [];

        $uniqueRule = Rule::unique('kelas')->where(function ($q) use ($request) {
            return $q->where('program_keahlian', $request->program_keahlian)
                     ->where('rombel', $request->rombel);
        });

        if ($ignoreId) {
            $uniqueRule = $uniqueRule->ignore($ignoreId, 'id_kelas');
        }

        return [
            'tingkat' => ['required', Rule::in(Kelas::TINGKAT_OPTIONS), $uniqueRule],
            'program_keahlian' => ['required', 'string', Rule::in($pilihanProgram)],
            'rombel' => ['required', 'string', 'max:20'],
        ];
    }
}