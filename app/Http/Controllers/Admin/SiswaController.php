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

        $siswa = $query->orderBy('nama_siswa')->paginate(20);
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
        $kelas = Kelas::all();
        return view('admin.siswa.import', compact('kelas'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
        ]);

        try {
            $import = new SiswaImport($request->id_kelas);
            Excel::import($import, $request->file('file'));

            $errors = $import->getBarisError();
            $failures = $import->failures(); // tambahan ini

            if (!empty($errors) || $failures->isNotEmpty()) {
                $failMessages = [];
                foreach ($failures as $failure) {
                    $failMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }
                

    return back()->with([
        'warning' => 'Sebagian/semua data gagal diimport.',
        'errors' => array_merge($errors, array_map(fn($m, $i) => ['baris' => $i, 'error' => $m], $failMessages, array_keys($failMessages)))
    ]);
}

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diimport!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
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
        $data = [
            ['NISN', 'NIS', 'Nama Siswa', 'Jenis Kelamin', 'Agama', 'Jurusan', 'Rombel', 'Nomor HP', 'Email', 'MedSos', 'Alamat'],
            ['1234567890', '10001', 'Budi Santoso', 'L', 'Islam', 'IPA', '10', '08123456789', 'budi@email.com', '@budi', 'Jl. Merdeka No.1'],
            ['1234567891', '10002', 'Siti Rahayu', 'P', 'Islam', 'IPA', '10', '08123456788', 'siti@email.com', '@siti', 'Jl. Merdeka No.2'],
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