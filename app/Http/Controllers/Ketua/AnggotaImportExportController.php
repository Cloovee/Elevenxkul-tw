<?php

namespace App\Http\Controllers\Ketua;

use App\Exports\AnggotaExport;
use App\Http\Controllers\Controller;
use App\Imports\AnggotaImport;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Import / export anggota untuk role Ketua.
 * Ekskul SELALU diambil dari middleware 'ketua.ekskul' (ekskul yang dipimpin
 * Ketua yang login), jadi Ketua tidak bisa mengimpor/mengekspor ekskul lain.
 */
class AnggotaImportExportController extends Controller
{
    public function showImportForm(Request $request)
    {
        $ekskul = $request->attributes->get('ekskul_ketua');

        return view('dashboard-ketua.kelola-anggota.import', compact('ekskul'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $ekskul = $request->attributes->get('ekskul_ketua');

        // File disimpan dulu ke storage/app (bukan path temp upload PHP) supaya
        // aman di hosting dengan open_basedir -- sama seperti import siswa Admin.
        $pathTersimpan = $request->file('file')->store('temp-import');
        $pathFull = Storage::path($pathTersimpan);

        try {
            $import = new AnggotaImport((int) $ekskul->id_ekskul);
            Excel::import($import, $pathFull);

            $berhasil   = $import->getJumlahBerhasil();
            $sudah      = $import->getJumlahSudahAnggota();
            $barisError = $import->getBarisError();

            $pesan = "{$berhasil} anggota berhasil diimport.";
            if ($sudah > 0) {
                $pesan .= " {$sudah} siswa dilewati karena sudah jadi anggota.";
            }

            if (! empty($barisError)) {
                return back()->with([
                    'warning'     => $pesan . ' ' . count($barisError) . ' baris dilewati karena bermasalah. Perbaiki baris di bawah lalu import ulang (baris yang sudah berhasil tidak perlu dikirim lagi).',
                    'baris_error' => $barisError,
                ]);
            }

            if ($berhasil === 0 && $sudah === 0) {
                return back()->with('error', 'Tidak ada baris yang bisa diimport. Pastikan kolom NIS atau NISN terisi dan memakai header dari template.');
            }

            return redirect()->route('ketua.kelola-anggota')->with('success', $pesan);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        } finally {
            Storage::delete($pathTersimpan);
        }
    }

    public function export(Request $request)
    {
        $ekskul = $request->attributes->get('ekskul_ketua');

        $nama = 'anggota_' . Str::slug($ekskul->nama_ekskul) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new AnggotaExport((int) $ekskul->id_ekskul), $nama);
    }

    public function downloadTemplate(Request $request)
    {
        $ekskul = $request->attributes->get('ekskul_ketua');

        // Contoh baris diambil dari siswa asli yang belum jadi anggota (kalau ada).
        $contoh = Siswa::with('kelas')
            ->whereNotIn('id_siswa', Peserta::where('id_ekskul', $ekskul->id_ekskul)->pluck('id_siswa'))
            ->orderBy('nama_siswa')
            ->take(2)
            ->get();

        // Cukup dua kolom: data lain (kelas, JK, dll) diambil otomatis dari Data Siswa.
        $data = [['NIS', 'Nama']];

        if ($contoh->isNotEmpty()) {
            foreach ($contoh as $s) {
                $data[] = [(string) $s->NIS, $s->nama_siswa];
            }
        } else {
            $data[] = ['10001', 'Budi Santoso'];
            $data[] = ['10002', 'Siti Rahayu'];
        }

        return Excel::download(
            new class($data) implements FromArray {
                public function __construct(private array $data) {}
                public function array(): array { return $this->data; }
            },
            'template_import_anggota_' . Str::slug($ekskul->nama_ekskul) . '.xlsx'
        );
    }
}