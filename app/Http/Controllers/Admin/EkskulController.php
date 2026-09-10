<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pembina;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    public function index(Request $request)
    {
        $ekskuls = Ekskul::with(['pembina', 'pelatih'])
            ->when($request->search, function ($query, $search) {
                $query->where('nama_ekskul', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.ekskul.index', compact('ekskuls'));
    }

    public function create()
    {
        $pembinas = Pembina::orderBy('nama_pembina')->get();

        return view('admin.ekskul.create', compact('pembinas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
            // Relasi: Pembina yang bertanggung jawab atas ekskul ini. Dari sinilah nanti
            // pembina yang bersangkutan bisa mengelola CRUD pelatih untuk ekskul ini.
            'id_pembina' => 'nullable|exists:pembina,id_pembina',
        ]);

        Ekskul::create($validated);

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil ditambahkan');
    }

    public function show(Ekskul $ekskul)
    {
        $ekskul->load(['pembina', 'pelatih']);

        return view('admin.ekskul.show', compact('ekskul'));
    }

    public function edit(Ekskul $ekskul)
    {
        $pembinas = Pembina::orderBy('nama_pembina')->get();

        return view('admin.ekskul.edit', compact('ekskul', 'pembinas'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $validated = $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
            'id_pembina' => 'nullable|exists:pembina,id_pembina',
        ]);

        // Kalau pembina diganti/dilepas, pelatih yang sebelumnya terkait ekskul ini ikut
        // dilepas juga -- karena CRUD pelatih pembina lama tidak boleh lagi mengelolanya.
        if ((string) $ekskul->id_pembina !== (string) ($validated['id_pembina'] ?? '')) {
            $validated['id_pelatih'] = null;
        }

        $ekskul->update($validated);

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