<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD Galeri (sisi Admin). Foto dikelompokkan PER EKSKUL dan tampil
 * dengan susunan yang sama di bagian "Galeri" pada landing page.
 */
class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $ekskuls = Ekskul::with(['galeri' => fn ($q) => $q->orderBy('urutan')->orderBy('id_galeri')])
            ->when($request->search, fn ($q, $s) => $q->where('nama_ekskul', 'like', "%{$s}%"))
            ->orderBy('nama_ekskul')
            ->get();

        // Foto lama yang belum punya ekskul (supaya tetap bisa dipindah/dihapus)
        $tanpaEkskul = Galeri::whereNull('id_ekskul')->orderBy('urutan')->orderBy('id_galeri')->get();

        $totalFoto = Galeri::count();

        return view('admin.galeri.index', compact('ekskuls', 'tanpaEkskul', 'totalFoto'));
    }

    public function create(Request $request)
    {
        $ekskuls  = Ekskul::orderBy('nama_ekskul')->get();
        $terpilih = $request->query('ekskul');

        return view('admin.galeri.create', compact('ekskuls', 'terpilih'));
    }

    /**
     * Satu kali simpan boleh banyak foto sekaligus (input foto[]).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ekskul'  => 'required|exists:ekskuls,id_ekskul',
            'judul'      => 'nullable|string|max:120',
            'keterangan' => 'nullable|string|max:500',
            'foto'       => 'required|array|min:1|max:20',
            'foto.*'     => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ], $this->pesan());

        $ekskul = Ekskul::findOrFail($validated['id_ekskul']);
        $urutan = (int) Galeri::where('id_ekskul', $ekskul->id_ekskul)->max('urutan');

        foreach ($request->file('foto') as $file) {
            Galeri::create([
                'id_ekskul'  => $ekskul->id_ekskul,
                'judul'      => $validated['judul'] ?: 'Kegiatan ' . $ekskul->nama_ekskul,
                'keterangan' => $validated['keterangan'] ?? null,
                'foto'       => $file->store('galeri-ekskul', 'public'),
                'urutan'     => ++$urutan,
            ]);
        }

        $jumlah = count($request->file('foto'));

        return redirect()->route('admin.galeri.index')
            ->with('success', "{$jumlah} foto berhasil ditambahkan ke galeri {$ekskul->nama_ekskul}");
    }

    public function edit(Galeri $galeri)
    {
        $ekskuls = Ekskul::orderBy('nama_ekskul')->get();

        return view('admin.galeri.edit', compact('galeri', 'ekskuls'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'id_ekskul'  => 'required|exists:ekskuls,id_ekskul',
            'judul'      => 'required|string|max:120',
            'keterangan' => 'nullable|string|max:500',
            'urutan'     => 'nullable|integer|min:0',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], $this->pesan());

        if ($request->hasFile('foto')) {
            $this->hapusFile($galeri);
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
        $this->hapusFile($galeri);
        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus');
    }

    private function hapusFile(Galeri $galeri): void
    {
        if (! str_starts_with($galeri->foto, 'http')) {
            Storage::disk('public')->delete($galeri->foto);
        }
    }

    private function pesan(): array
    {
        return [
            'id_ekskul.required' => 'Pilih ekskul untuk foto ini.',
            'judul.required'     => 'Judul foto wajib diisi.',
            'foto.required'      => 'Pilih minimal satu foto yang akan diunggah.',
            'foto.max'           => 'Maksimal 20 foto sekali unggah.',
            'foto.*.image'       => 'Semua file harus berupa gambar.',
            'foto.*.mimes'       => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.*.max'         => 'Ukuran tiap foto maksimal 4 MB.',
            'foto.image'         => 'File harus berupa gambar.',
            'foto.mimes'         => 'Format foto harus JPG, PNG, atau WEBP.',
        ];
    }
}
