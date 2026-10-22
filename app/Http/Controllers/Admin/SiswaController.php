<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'LIKE', "%{$search}%")
                  ->orWhere('NISN', 'LIKE', "%{$search}%")
                  ->orWhere('NIS', 'LIKE', "%{$search}%");
            });
        }

        if ($request->kelas) {
            $query->where('id_kelas', $request->kelas);
        }

        $siswa = $query->orderBy('nama_siswa')->paginate(20)->withQueryString();
        $kelas = Kelas::all();

        return view('admin.siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'NISN' => 'required|string|max:20|unique:siswa,NISN',
            'NIS' => 'required|string|max:20|unique:siswa,NIS',
            'nama_siswa' => 'required|string|max:100',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Siswa::create($request->all());

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, int $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'NISN' => 'required|string|max:20|unique:siswa,NISN,' . $id . ',id_siswa',
            'NIS' => 'required|string|max:20|unique:siswa,NIS,' . $id . ',id_siswa',
            'nama_siswa' => 'required|string|max:100',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $siswa->update($request->all());

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    public function showImportForm()
    {
        // Daftar rombel terdaftar ditampilkan sebagai referensi penulisan kode di Excel.
        $kelas = Kelas::orderBy('tingkat')
            ->orderBy('program_keahlian')
            ->orderBy('rombel')
            ->get();

        return view('admin.siswa.import', compact('kelas'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        if (! Kelas::exists()) {
            return back()->with('error', 'Belum ada rombel terdaftar. Tambahkan dulu di menu Kelas sebelum import siswa.');
        }

        // Simpan dulu file upload-nya ke storage/app (bukan langsung pakai path
        // temp upload PHP). Di beberapa hosting shared dengan open_basedir aktif,
        // PhpSpreadsheet gagal baca file .xlsx langsung dari path temp upload
        // (error "file_exists(): open_basedir restriction... xl/worksheets/sheet1.xml")
        // karena path temp-nya di luar folder yang diizinkan. Menyimpan file ke
        // storage/app/temp-import dulu (pasti di dalam folder project) menghindari
        // masalah ini.
        $pathTersimpan = $request->file('file')->store('temp-import');
        $pathFull = \Illuminate\Support\Facades\Storage::path($pathTersimpan);

        try {
            // Kelas tiap siswa diambil dari kolom "Rombel" di Excel, dan rombel
            // itu harus sudah terdaftar di tabel kelas (kalau tidak, baris ditolak).
            $import = new SiswaImport();
            Excel::import($import, $pathFull);

            // Satu baris Excel bisa gagal di beberapa kolom -> gabungkan per baris.
            $perBaris = [];
            foreach ($import->failures() as $failure) {
                $perBaris[$failure->row()][] = implode(', ', $failure->errors());
            }
            ksort($perBaris);

            $barisError = [];
            foreach ($perBaris as $baris => $pesan) {
                $barisError[] = ['baris' => $baris, 'error' => implode(' | ', $pesan)];
            }

            $berhasil = $import->getJumlahBerhasil();

            if (!empty($barisError)) {
                return back()->with([
                    'warning' => "{$berhasil} siswa berhasil diimport, " . count($barisError) . ' baris dilewati karena bermasalah. Perbaiki baris di bawah lalu import ulang (baris yang sudah berhasil tidak perlu dikirim lagi).',
                    'baris_error' => $barisError,
                ]);
            }

            return redirect()->route('admin.siswa.index')
                ->with('success', "{$berhasil} siswa berhasil diimport!");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        } finally {
            // File sementara selalu dihapus, baik import sukses, sebagian gagal,
            // maupun exception -- tidak boleh numpuk di storage.
            \Illuminate\Support\Facades\Storage::delete($pathTersimpan);
        }
    }

    public function export(Request $request)
    {
        $id_kelas = $request->kelas ?? null;

        return Excel::download(
            new SiswaExport($id_kelas),
            'data_siswa_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function downloadTemplate()
    {
        // Kolom Rombel WAJIB diisi dengan rombel yang sudah terdaftar di menu Kelas.
        // Contoh rombel diambil dari data kelas asli (kalau ada) supaya tidak membingungkan.
        $contoh = Kelas::orderBy('tingkat')->orderBy('program_keahlian')->orderBy('rombel')->take(2)->get()
            ->map(fn ($k) => $k->kode_rombel)->values();
        $rombel1 = $contoh[0] ?? '10TJKT1';
        $rombel2 = $contoh[1] ?? $rombel1;

        $data = [
            ['NISN', 'NIS', 'Nama', 'JK', 'Rombel', 'Agama', 'No. HP', 'Email', 'Medsos', 'Alamat'],
            ['1234567890', '10001', 'Budi Santoso', 'L', $rombel1, 'Islam', '08123456789', 'budi@email.com', '@budi', 'Jl. Merdeka No.1'],
            ['1234567891', '10002', 'Siti Rahayu', 'P', $rombel2, 'Islam', '08123456788', 'siti@email.com', '@siti', 'Jl. Merdeka No.2'],
        ];

        return Excel::download(
            new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
                private array $data;
                public function __construct(array $data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            },
            'template_import_siswa.xlsx'
        );
    }
}