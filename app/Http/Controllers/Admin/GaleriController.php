<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD Galeri (sisi Admin). Foto yang disimpan di sini tampil di bagian
 * "Galeri" pada landing page dengan susunan kartu bertumpuk.
 */
class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $galeri = Galeri::with('ekskul')
            ->when($request->search, fn ($q, $s) => $q->where('judul', 'like', "%{$s}%"))
            ->orderBy('urutan')
            ->orderByDesc('id_galeri')
            ->paginate(12)
            ->withQueryString();

        return view('admin.galeri.index', compact('galeri'));
    }

    public function create()
    {
        $ekskuls = Ekskul::orderBy('nama_ekskul')->get();
        $urutanBerikut = (int) Galeri::max('urutan') + 1;

        return view('admin.galeri.create', compact('ekskuls', 'urutanBerikut'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:120',
            'keterangan' => 'nullable|string|max:500',
            'id_ekskul'  => 'nullable|exists:ekskuls,id_ekskul',
            'urutan'     => 'nullable|integer|min:0',
            'foto'       => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], $this->pesan());

        $validated['foto']   = $request->file('foto')->store('galeri-ekskul', 'public');
        $validated['urutan'] = $validated['urutan'] ?? ((int) Galeri::max('urutan') + 1);

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil ditambahkan');
    }

    public function edit(Galeri $galeri)
    {
        $ekskuls = Ekskul::orderBy('nama_ekskul')->get();

        return view('admin.galeri.edit', compact('galeri', 'ekskuls'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:120',
            'keterangan' => 'nullable|string|max:500',
            'id_ekskul'  => 'nullable|exists:ekskuls,id_ekskul',
            'urutan'     => 'nullable|integer|min:0',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], $this->pesan());

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($galeri->foto);
            $validated['foto'] = $request->file('foto')->store('galeri-ekskul', 'public');
        } else {
            unset($validated['foto']);
        }

        $validated['urutan'] = $validated['urutan'] ?? $galeri->urutan;

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui');
    }

    public function destroy(Galeri $galeri)
    {
        if (! str_starts_with($galeri->foto, 'http')) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus');
    }

    private function pesan(): array
    {
        return [
            'judul.required' => 'Judul foto wajib diisi.',
            'foto.required'  => 'Pilih foto yang akan diunggah.',
            'foto.image'     => 'File harus berupa gambar.',
            'foto.mimes'     => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max'       => 'Ukuran foto maksimal 4 MB.',
        ];
    }
}