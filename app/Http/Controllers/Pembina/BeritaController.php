<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Pembina menulis berita/pengumuman untuk Ketua ekskul yang dibinanya.
 * Ketua membacanya di aplikasi mobile (halaman Beranda + tab Berita).
 *
 * Relasi: User (login) -> Pembina -> Ekskul (id_pembina) -> Berita (id_ekskul).
 * Pembina hanya bisa mengelola berita untuk ekskul yang ia bina.
 */
class BeritaController extends Controller
{
    private function pembinaOrFail(): Pembina
    {
        $pembina = Auth::user()->pembina;

        abort_if(
            ! $pembina,
            403,
            'Akun kamu belum punya biodata pembina. Hubungi Admin untuk melengkapinya dulu.'
        );

        return $pembina;
    }

    public function index(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulList = $pembina->ekskuls()->orderBy('nama_ekskul')->get();
        $ekskulIds = $ekskulList->pluck('id_ekskul');

        // Filter ekskul: hanya diterima kalau memang ekskul milik pembina ini
        $filterEkskul = (int) $request->query('ekskul');
        if (! $ekskulIds->contains($filterEkskul)) {
            $filterEkskul = null;
        }

        $query = Berita::with('ekskul')
            ->whereIn('id_ekskul', $filterEkskul ? [$filterEkskul] : $ekskulIds);

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'LIKE', "%{$cari}%")
                  ->orWhere('isi', 'LIKE', "%{$cari}%");
            });
        }

        $berita = $query->latest('created_at')->paginate(10)->withQueryString();

        $adaEkskulTanpaPembina = $ekskulIds->isEmpty();

        return view('pembina.berita.index', compact('berita', 'ekskulList', 'filterEkskul', 'adaEkskulTanpaPembina'));
    }

    public function create()
    {
        $ekskuls = $this->pembinaOrFail()->ekskuls()->orderBy('nama_ekskul')->get();

        return view('pembina.berita.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $pembina->ekskuls()->pluck('id_ekskul');

        $data = $request->validate([
            'id_ekskul' => ['required'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string', 'max:5000'],
            'penting' => ['nullable', 'boolean'],
        ], [
            'id_ekskul.required' => 'Pilih ekskul tujuan berita.',
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 150 karakter.',
            'isi.required' => 'Isi berita wajib diisi.',
            'isi.max' => 'Isi berita maksimal 5000 karakter.',
        ]);

        // "semua" = kirim ke semua ekskul yang dibina (satu baris berita per ekskul).
        if ((string) $data['id_ekskul'] === 'semua') {
            $tujuan = $ekskulIds;
        } else {
            $id = (int) $data['id_ekskul'];
            abort_unless($ekskulIds->contains($id), 403, 'Ekskul tersebut bukan ekskul yang kamu bina.');
            $tujuan = collect([$id]);
        }

        abort_if($tujuan->isEmpty(), 422, 'Kamu belum membina ekskul manapun.');

        foreach ($tujuan as $idEkskul) {
            Berita::create([
                'id_ekskul' => $idEkskul,
                'id_pembina' => $pembina->id_pembina,
                'judul' => trim($data['judul']),
                'isi' => trim($data['isi']),
                'penting' => (bool) ($data['penting'] ?? false),
            ]);
        }

        $pesan = $tujuan->count() > 1
            ? "Berita berhasil dikirim ke {$tujuan->count()} ekskul."
            : 'Berita berhasil dikirim ke ketua ekskul.';

        return redirect()->route('pembina.berita.index')->with('success', $pesan);
    }

    public function edit(int $id)
    {
        $berita = $this->milikku($id);

        return view('pembina.berita.edit', compact('berita'));
    }

    public function update(Request $request, int $id)
    {
        $berita = $this->milikku($id);

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string', 'max:5000'],
            'penting' => ['nullable', 'boolean'],
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 150 karakter.',
            'isi.required' => 'Isi berita wajib diisi.',
            'isi.max' => 'Isi berita maksimal 5000 karakter.',
        ]);

        $berita->update([
            'judul' => trim($data['judul']),
            'isi' => trim($data['isi']),
            'penting' => (bool) ($data['penting'] ?? false),
        ]);

        return redirect()->route('pembina.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->milikku($id)->delete();

        return redirect()->route('pembina.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    /** Berita harus untuk ekskul yang dibina pembina yang login; kalau bukan -> 404. */
    private function milikku(int $id): Berita
    {
        $ekskulIds = $this->pembinaOrFail()->ekskuls()->pluck('id_ekskul');

        return Berita::with('ekskul')
            ->whereIn('id_ekskul', $ekskulIds)
            ->findOrFail($id);
    }
}
