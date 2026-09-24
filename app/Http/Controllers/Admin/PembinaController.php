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

/**
 * CRUD Pembina (sisi Admin).
 *
 * Format input dari Admin:
 *   | nama | jk | agama | no hp | email | alamat | username | password | ekskul yang dibina (bisa > 1) |
 *
 * Satu kali submit otomatis mengisi 2 tabel sekaligus:
 *   - users   : akun login (role "Pembina", username + password)
 *   - pembina : biodata (nama, jk, agama, no hp, email, alamat) yang terhubung ke akun di atas
 * dan menghubungkan pembina ke ekskul pilihan lewat kolom ekskuls.id_pembina.
 */
class PembinaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembina::with(['user', 'ekskuls']);

        if ($request->search) {
            $search = $request->search;
            $query->where('nama_pembina', 'LIKE', "%{$search}%");
        }

        $pembina = $query->orderBy('nama_pembina')->paginate(20);

        return view('admin.CRUD-pembina.index', compact('pembina'));
    }

    public function create()
    {
        // Semua ekskul ditampilkan; yang sudah dibina pembina lain hanya tampil (disabled)
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
            'email' => 'required|email|max:100|unique:users,email',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'password' => 'required|string|min:8',
            'ekskul' => 'nullable|array',
            'ekskul.*' => 'integer|exists:ekskuls,id_ekskul',
        ], $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ekskulIds = collect($request->input('ekskul', []))->unique()->values();

        // Ekskul yang dipilih tidak boleh sedang dibina pembina lain
        if ($this->adaEkskulMilikPembinaLain($ekskulIds, null)) {
            return back()
                ->withErrors(['ekskul' => 'Ada ekskul pilihan yang sudah dibina pembina lain.'])
                ->withInput();
        }

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('pembina-photos', 'public')
            : null;

        DB::transaction(function () use ($request, $fotoPath, $ekskulIds) {
            // 1) Akun login -> tabel users (role Pembina)
            $user = User::create([
                'name' => $request->nama_pembina,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'Pembina',
            ]);

            // 2) Biodata -> tabel pembina, langsung terhubung ke akun di atas
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

            // 3) Ekskul yang dibina
            if ($ekskulIds->isNotEmpty()) {
                Ekskul::whereIn('id_ekskul', $ekskulIds)
                    ->update(['id_pembina' => $pembina->id_pembina]);
            }
        });

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina berhasil ditambahkan beserta akun login-nya!');
    }

    public function edit(int $id)
    {
        $pembina = Pembina::with(['user', 'ekskuls'])->findOrFail($id);
        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();

        return view('admin.CRUD-pembina.edit', compact('pembina', 'ekskuls'));
    }

    public function update(Request $request, int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);
        $user = $pembina->user;

        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore(optional($user)->id)],
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore(optional($user)->id)],
            // Kosongkan = password lama tidak berubah (wajib hanya jika akunnya sudah terhapus)
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'ekskul' => 'nullable|array',
            'ekskul.*' => 'integer|exists:ekskuls,id_ekskul',
        ], $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ekskulIds = collect($request->input('ekskul', []))->unique()->values();

        if ($this->adaEkskulMilikPembinaLain($ekskulIds, $pembina->id_pembina)) {
            return back()
                ->withErrors(['ekskul' => 'Ada ekskul pilihan yang sudah dibina pembina lain.'])
                ->withInput();
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

        DB::transaction(function () use ($request, $pembina, $user, $data, $ekskulIds) {
            // Akun login (tabel users)
            $userData = [
                'name' => $request->nama_pembina,
                'email' => $request->email,
                'username' => $request->username,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($user) {
                $user->update($userData);
            } else {
                $userData['role'] = 'Pembina';
                $userData['password'] = Hash::make($request->password);
                $user = User::create($userData);
                $data['id_user'] = $user->id;
            }

            // Biodata (tabel pembina)
            $pembina->update($data);

            // Sinkronkan ekskul yang dibina: lepas yang tidak dipilih, pasang yang dipilih
            Ekskul::where('id_pembina', $pembina->id_pembina)
                ->whereNotIn('id_ekskul', $ekskulIds)
                ->update(['id_pembina' => null]);

            if ($ekskulIds->isNotEmpty()) {
                Ekskul::whereIn('id_ekskul', $ekskulIds)
                    ->update(['id_pembina' => $pembina->id_pembina]);
            }
        });

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Data pembina berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);

        if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
            Storage::disk('public')->delete($pembina->foto);
        }

        DB::transaction(function () use ($pembina) {
            // Ekskul yang dibina dilepas (jadi "belum punya pembina")
            Ekskul::where('id_pembina', $pembina->id_pembina)->update(['id_pembina' => null]);

            $user = $pembina->user;
            $pembina->delete();

            // Akun login ikut dihapus karena dibuat otomatis bersama biodata ini
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina beserta akun login-nya berhasil dihapus. Ekskul yang dibina kini tanpa pembina.');
    }

    /**
     * Apakah ada ekskul di daftar yang sedang dibina pembina LAIN?
     */
    private function adaEkskulMilikPembinaLain($ekskulIds, ?int $idPembinaSaatIni): bool
    {
        if ($ekskulIds->isEmpty()) {
            return false;
        }

        return Ekskul::whereIn('id_ekskul', $ekskulIds)
            ->whereNotNull('id_pembina')
            ->when($idPembinaSaatIni, fn ($q) => $q->where('id_pembina', '!=', $idPembinaSaatIni))
            ->exists();
    }

    private function pesan(): array
    {
        return [
            'jk.required' => 'Pilih jenis kelamin.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'username.unique' => 'Username ini sudah dipakai akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip (-) dan underscore (_).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}