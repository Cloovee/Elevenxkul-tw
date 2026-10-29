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
use Illuminate\Validation\Rule;

class PembinaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembina::with(['user', 'ekskuls']);

        if ($request->search) {
            $search = $request->search;
            $query->where('nama_pembina', 'LIKE', "%{$search}%");
        }

        if ($request->jk) {
            $query->where('jk', $request->jk);
        }

        if ($request->status === 'membina') {
            $query->has('ekskuls');
        } elseif ($request->status === 'belum') {
            $query->doesntHave('ekskuls');
        }

        $pembina = $query->orderBy('nama_pembina')->paginate(20)->withQueryString();

        return view('admin.CRUD-pembina.index', compact('pembina'));
    }

    public function create()
    {
    
        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();

        return view('admin.CRUD-pembina.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'username' => 'required|string|min:3|max:50|alpha_dash:ascii|unique:users,username',
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'ekskul_ids' => 'nullable|array',
            'ekskul_ids.*' => 'exists:ekskuls,id_ekskul',
        ], $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('pembina-photos', 'public')
            : null;

        DB::transaction(function () use ($request, $fotoPath) {
           
            $user = User::create([
                'name' => $request->nama_pembina,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'Pembina',
            ]);

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
        $punyaAkun = (bool) $pembina->user;

        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'username' => [
                $punyaAkun ? 'required' : 'nullable',
                'string', 'min:3', 'max:50', 'alpha_dash:ascii',
                Rule::unique('users', 'username')->ignore($pembina->id_user),
            ],
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'ekskul_ids' => 'nullable|array',
            'ekskul_ids.*' => 'exists:ekskuls,id_ekskul',
        ], $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'nama_pembina', 'jk', 'agama', 'nomor_hp', 'email', 'medsos', 'alamat',
        ]);

        if ($request->hasFile('foto')) {
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

            if ($pembina->user) {
                $userData = [
                    'name' => $request->nama_pembina,
                    'username' => $request->username,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $pembina->user->update($userData);
            }

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
            ->with('success', 'Data pembina berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);
        $foto = $pembina->foto;

        DB::transaction(function () use ($pembina) {
            // Lepas dulu ekskul yang dibina
            Ekskul::where('id_pembina', $pembina->id_pembina)
                ->update(['id_pembina' => null, 'id_pelatih' => null]);

            $user = $pembina->user;

            // Hapus biodata pembina, lalu akun login-nya (FK pembina.id_user -> users
            // hanya "set null", jadi akun users TIDAK ikut terhapus otomatis).
            $pembina->delete();

            // Pengaman: hanya hapus akun yang memang berperan Pembina.
            if ($user && $user->role === 'Pembina') {
                $user->delete();
            }
        });

        // Hapus file foto setelah transaksi sukses, supaya foto tidak hilang kalau DB gagal.
        if ($foto && Storage::disk('public')->exists($foto)) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina beserta akun login-nya berhasil dihapus. Ekskul yang dibina kini tanpa pembina.');
    }

    private function pesan(): array
    {
        return [
            'jk.required' => 'Pilih jenis kelamin.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username ini sudah dipakai akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip (-) dan underscore (_).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}