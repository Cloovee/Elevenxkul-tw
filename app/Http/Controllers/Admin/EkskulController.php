<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::latest()->paginate(10);
        return view('admin.ekskul.index', compact('ekskuls'));
    }

    public function create()
    {
        return view('admin.ekskul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
        ]);

        Ekskul::create($request->all());

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil ditambahkan');
    }

    public function show(Ekskul $ekskul)
    {
        return view('admin.ekskul.show', compact('ekskul'));
    }

    public function edit(Ekskul $ekskul)
    {
        return view('admin.ekskul.edit', compact('ekskul'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
        ]);

        $ekskul->update($request->all());

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil diperbarui');
    }

    public function destroy(Ekskul $ekskul)
    {
        $ekskul->delete();

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil dihapus');
    }
}