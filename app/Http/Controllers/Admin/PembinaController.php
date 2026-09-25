<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pembina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembinaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembina::with('user');

        if ($request->search) {
            $search = $request->search;
            $query->where('nama_pembina', 'LIKE', "%{$search}%");
        }

        $pembina = $query->orderBy('nama_pembina')->paginate(20)->withQueryString();

        return view('admin.CRUD-pembina.index', compact('pembina'));
    }

    public function create()
    {
        // Semua ekskul ditampilkan sebagai checklist, termasuk yang sudah
        // punya pembina lain -- admin boleh "ambil alih" dari sini.
        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();

        return view('admin.CRUD-pembina.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:100|unique:users,email',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'ekskul_ids' => 'nullable|array',
            'ekskul_ids.*' => 'exists:ekskuls,id_ekskul',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('pembina-photos', 'public')
            : null;

        DB::transaction(function () use ($request, $fotoPath) {
            // 1. Akun login dibuat otomatis bareng biodata -- email di form ini
            //    yang jadi username buat login (role Pembina).
            $user = User::create([
                'name' => $request->nama_pembina,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Pembina',
            ]);

            // 2. Biodata pembina
            $pembina = Pembina::create([
                'id_user' => $user->id,
                'nama_pembina' => $request->nama_pembina,
                'foto' => $fotoPath,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'nomor_hp' => $request->nomor_hp,
                'email' => $request->email,
                'medsos' => $request->medsos,
                'alamat' => $request->alamat,
            ]);

            // 3. Assign Ekskul yang dicentang ke pembina baru ini. Boleh "ambil
            //    alih" dari pembina lain -- kalau pembina-nya berubah, pelatih
            //    yang sebelumnya terkait ekskul itu ikut dilepas (sama seperti
            //    logic di CRUD Ekskul), karena CRUD pelatih pembina lama tidak
            //    boleh lagi mengelolanya.
            $ekskulIds = $request->input('ekskul_ids', []);
            if (!empty($ekskulIds)) {
                Ekskul::whereIn('id_ekskul', $ekskulIds)->update([
                    'id_pembina' => $pembina->id_pembina,
                    'id_pelatih' => null,
                ]);
            }
        });

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina berhasil ditambahkan beserta akun login-nya!');
    }

    public function edit(int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);
        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();
        $assignedEkskulIds = Ekskul::where('id_pembina', $pembina->id_pembina)
            ->pluck('id_ekskul')
            ->toArray();

        return view('admin.CRUD-pembina.edit', compact('pembina', 'ekskuls', 'assignedEkskulIds'));
    }

    public function update(Request $request, int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:100|unique:users,email,' . $pembina->id_user,
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'ekskul_ids' => 'nullable|array',
            'ekskul_ids.*' => 'exists:ekskuls,id_ekskul',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'nama_pembina', 'jk', 'agama', 'nomor_hp', 'email', 'medsos', 'alamat',
        ]);

        if ($request->hasFile('foto')) {
            // Ganti foto lama: unggah yang baru, baru hapus file lama supaya aman
            // kalau proses upload gagal di tengah jalan.
            $newPath = $request->file('foto')->store('pembina-photos', 'public');

            if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
                Storage::disk('public')->delete($pembina->foto);
            }

            $data['foto'] = $newPath;
        } elseif ($request->boolean('hapus_foto')) {
            if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
                Storage::disk('public')->delete($pembina->foto);
            }

            $data['foto'] = null;
        }

        DB::transaction(function () use ($request, $pembina, $data) {
            $pembina->update($data);

            // Sinkronkan akun login: nama & email ikut berubah, password cuma
            // diupdate kalau diisi.
            if ($pembina->user) {
                $userData = [
                    'name' => $request->nama_pembina,
                    'email' => $request->email,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $pembina->user->update($userData);
            }

            // Sinkronkan Ekskul yang dibina: lepas yang tidak dicentang lagi,
            // ambil alih yang baru dicentang (boleh dari pembina lain). Pelatih
            // ikut dilepas untuk ekskul yang pembinanya berubah.
            $selectedIds = $request->input('ekskul_ids', []);

            Ekskul::where('id_pembina', $pembina->id_pembina)
                ->whereNotIn('id_ekskul', $selectedIds)
                ->update(['id_pembina' => null, 'id_pelatih' => null]);

            if (!empty($selectedIds)) {
                Ekskul::whereIn('id_ekskul', $selectedIds)
                    ->where(function ($q) use ($pembina) {
                        $q->whereNull('id_pembina')->orWhere('id_pembina', '!=', $pembina->id_pembina);
                    })
                    ->update(['id_pembina' => $pembina->id_pembina, 'id_pelatih' => null]);
            }
        });

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Biodata pembina berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $pembina = Pembina::findOrFail($id);

        if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
            Storage::disk('public')->delete($pembina->foto);
        }

        // Cuma hapus biodatanya. Akun user TETAP ada (masih bisa login dengan role Pembina),
        // cuma statusnya balik jadi "belum ada biodata" di CRUD User. Ekskul yang tadinya
        // dibina jadi tidak punya pembina (id_pembina null), pelatihnya ikut dilepas.
        Ekskul::where('id_pembina', $pembina->id_pembina)
            ->update(['id_pembina' => null, 'id_pelatih' => null]);

        $pembina->delete();

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Biodata pembina berhasil dihapus. Akun login pembina tetap ada.');
    }
}