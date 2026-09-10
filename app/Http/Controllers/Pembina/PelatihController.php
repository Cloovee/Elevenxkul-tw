<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pelatih;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PelatihController extends Controller
{
    /**
     * Ambil biodata pembina yang sedang login.
     * Relasi: User (login) -> Pembina (biodata, diisi Admin) -> Ekskul (dibina, id_pembina
     * diisi Admin) -> Pelatih (dikelola Pembina lewat CRUD ini).
     */
    private function pembinaOrFail(): Pembina
    {
        $pembina = Auth::user()->pembina;

        abort_if(
            !$pembina,
            403,
            'Akun kamu belum punya biodata pembina. Hubungi Admin untuk melengkapinya dulu.'
        );

        return $pembina;
    }

    /**
     * Id ekskul-ekskul yang dibina oleh pembina ini (ditentukan oleh Admin lewat CRUD Ekskul).
     */
    private function ekskulIds(Pembina $pembina)
    {
        return $pembina->ekskuls()->pluck('id_ekskul');
    }

    public function index(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $query = Pelatih::whereHas('ekskuls', function ($q) use ($ekskulIds) {
            $q->whereIn('id_ekskul', $ekskulIds);
        })->with(['ekskuls' => function ($q) use ($ekskulIds) {
            $q->whereIn('id_ekskul', $ekskulIds);
        }]);

        if ($request->search) {
            $search = $request->search;
            $query->where('nama_pelatih', 'LIKE', "%{$search}%");
        }

        $pelatih = $query->orderBy('nama_pelatih')->paginate(10)->withQueryString();

        $adaEkskulTanpaPembina = $ekskulIds->isEmpty();

        return view('pembina.pelatih.index', compact('pelatih', 'adaEkskulTanpaPembina'));
    }

    public function create()
    {
        $pembina = $this->pembinaOrFail();

        $ekskuls = $pembina->ekskuls()->with('pelatih')->orderBy('nama_ekskul')->get();

        return view('pembina.pelatih.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $validator = Validator::make($request->all(), [
            'id_ekskul' => ['required', Rule::in($ekskulIds)],
            'nama_pelatih' => 'required|string|max:100',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'medsos' => 'nullable|string|max:100',
        ], [
            'id_ekskul.required' => 'Pilih ekskul kamu yang akan dilatih pelatih ini.',
            'id_ekskul.in' => 'Ekskul tersebut bukan ekskul yang kamu bina.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pelatih = Pelatih::create($request->only([
            'nama_pelatih', 'jk', 'agama', 'nomor_hp', 'email', 'alamat', 'medsos',
        ]));

        // Kaitkan pelatih baru ke ekskul milik pembina ini (menggantikan pelatih lama kalau ada).
        Ekskul::where('id_ekskul', $request->id_ekskul)
            ->where('id_pembina', $pembina->id_pembina)
            ->update(['id_pelatih' => $pelatih->id_pelatih]);

        return redirect()->route('pembina.pelatih.index')
            ->with('success', 'Pelatih berhasil ditambahkan dan dikaitkan ke ekskul kamu.');
    }

    public function edit(int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $pelatih = Pelatih::whereHas('ekskuls', function ($q) use ($ekskulIds) {
            $q->whereIn('id_ekskul', $ekskulIds);
        })->findOrFail($id);

        $ekskuls = $pembina->ekskuls()->with('pelatih')->orderBy('nama_ekskul')->get();

        $ekskulSaatIni = Ekskul::whereIn('id_ekskul', $ekskulIds)
            ->where('id_pelatih', $pelatih->id_pelatih)
            ->value('id_ekskul');

        return view('pembina.pelatih.edit', compact('pelatih', 'ekskuls', 'ekskulSaatIni'));
    }

    public function update(Request $request, int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $pelatih = Pelatih::whereHas('ekskuls', function ($q) use ($ekskulIds) {
            $q->whereIn('id_ekskul', $ekskulIds);
        })->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_ekskul' => ['required', Rule::in($ekskulIds)],
            'nama_pelatih' => 'required|string|max:100',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'medsos' => 'nullable|string|max:100',
        ], [
            'id_ekskul.required' => 'Pilih ekskul kamu untuk pelatih ini.',
            'id_ekskul.in' => 'Ekskul tersebut bukan ekskul yang kamu bina.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pelatih->update($request->only([
            'nama_pelatih', 'jk', 'agama', 'nomor_hp', 'email', 'alamat', 'medsos',
        ]));

        // Lepaskan pelatih ini dari ekskul lain milik pembina ini yang bukan pilihan sekarang.
        Ekskul::whereIn('id_ekskul', $ekskulIds)
            ->where('id_pelatih', $pelatih->id_pelatih)
            ->where('id_ekskul', '!=', $request->id_ekskul)
            ->update(['id_pelatih' => null]);

        // Pasang ke ekskul pilihan (gantikan pelatih lama ekskul itu kalau ada).
        Ekskul::where('id_ekskul', $request->id_ekskul)
            ->where('id_pembina', $pembina->id_pembina)
            ->update(['id_pelatih' => $pelatih->id_pelatih]);

        return redirect()->route('pembina.pelatih.index')
            ->with('success', 'Data pelatih berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $pelatih = Pelatih::whereHas('ekskuls', function ($q) use ($ekskulIds) {
            $q->whereIn('id_ekskul', $ekskulIds);
        })->findOrFail($id);

        // Lepaskan dari ekskul-ekskul milik pembina ini dulu.
        Ekskul::whereIn('id_ekskul', $ekskulIds)
            ->where('id_pelatih', $pelatih->id_pelatih)
            ->update(['id_pelatih' => null]);

        // Kalau pelatih ini sudah tidak terhubung ke ekskul manapun (termasuk milik pembina
        // lain), baru hapus datanya. Kalau masih dipakai ekskul lain, biarkan datanya tetap ada.
        $masihDipakaiEkskulLain = Ekskul::where('id_pelatih', $pelatih->id_pelatih)->exists();

        if (!$masihDipakaiEkskulLain) {
            $pelatih->delete();
            $pesan = 'Pelatih berhasil dihapus dari sistem.';
        } else {
            $pesan = 'Pelatih dilepas dari ekskul kamu. Datanya tetap ada karena masih dipakai ekskul lain.';
        }

        return redirect()->route('pembina.pelatih.index')->with('success', $pesan);
    }
}