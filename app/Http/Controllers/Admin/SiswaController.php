<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::latest()->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn',
            'nis' => 'required|string|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:255',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'email' => 'nullable|email',
            'alamat' => 'nullable|string',
            'medsos' => 'nullable|string',
        ]);

        Siswa::create($request->all());

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn,' . $siswa->id_siswa . ',id_siswa',
            'nis' => 'required|string|unique:siswas,nis,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required|string|max:255',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'email' => 'nullable|email',
            'alamat' => 'nullable|string',
            'medsos' => 'nullable|string',
        ]);

        $siswa->update($request->all());

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diimport');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaExport, 'template_siswa.xlsx');
    }
}