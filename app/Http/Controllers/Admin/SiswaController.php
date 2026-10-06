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
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ], [
            'id_kelas.required' => 'Pilih kelas tujuan dulu sebelum import.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak ditemukan.',
        ]);

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
            $import = new SiswaImport($request->id_kelas);
            Excel::import($import, $pathFull);

            $barisError = $import->getBarisError();

            foreach ($import->failures() as $failure) {
                $barisError[] = [
                    'baris' => $failure->row(),
                    'error' => implode(', ', $failure->errors()),
                ];
            }

            if (!empty($barisError)) {
                return back()->with([
                    'warning' => 'Sebagian/semua data gagal diimport.',
                    'baris_error' => $barisError,
                ]);
            }

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diimport!');

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
        // Template TANPA kolom Kelas: kelas dipilih lewat dropdown di form import.
        $data = [
            ['NISN', 'NIS', 'Nama', 'JK', 'Agama', 'No. HP', 'Email', 'Medsos', 'Alamat'],
            ['1234567890', '10001', 'Budi Santoso', 'L', 'Islam', '08123456789', 'budi@email.com', '@budi', 'Jl. Merdeka No.1'],
            ['1234567891', '10002', 'Siti Rahayu', 'P', 'Islam', '08123456788', 'siti@email.com', '@siti', 'Jl. Merdeka No.2'],
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