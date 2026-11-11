<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pembina;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['siswa', 'pembina.ekskuls']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        // Halaman tambah khusus Pembina (akun + biodata sekaligus)
        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();

        return view('admin.user.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        // Tambah akun lewat menu ini khusus Pembina: role dipaksa Pembina di server,
        // jadi nilai role dari form (kalau diutak-atik) diabaikan.
        $request->merge(['role' => 'Pembina']);

        $validator = Validator::make($request->all(), array_merge([
            'name' => 'required|string|max:100',
            'username' => 'required|string|min:3|max:50|alpha_dash:ascii|unique:users,username',
            'email' => 'nullable|email:rfc,dns|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], $this->aturanBiodataPembina()), $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('pembina-photos', 'public')
            : null;

        DB::transaction(function () use ($request, $fotoPath) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->filled('email') ? $request->email : null,
                'password' => Hash::make($request->password),
                'role' => 'Pembina',
            ]);

            $pembina = Pembina::create(array_merge(
                $this->dataBiodata($request, $user),
                ['foto' => $fotoPath]
            ));

            $this->sinkronEkskul($pembina, $request->input('ekskul_ids', []));
        });

        return redirect()->route('admin.user.index')
            ->with('success', 'Pembina berhasil ditambahkan beserta akun login-nya!');
    }

    public function show(int $id)
    {
        $user = User::with(['siswa.kelas', 'siswa.ekskulDipimpin', 'pembina.ekskuls'])->findOrFail($id);

        return view('admin.user.show', compact('user'));
    }

    public function edit(int $id)
    {
        $user = User::with(['siswa', 'pembina'])->findOrFail($id);

        // Siswa yang belum punya akun, DITAMBAH siswa yang sedang terkait ke user ini (biar tetap muncul di dropdown)
        $siswaBelumPunyaAkun = Siswa::whereNull('id_user')
            ->orWhere('id_user', $user->id)
            ->orderBy('nama_siswa')
            ->get();

        $ekskuls = Ekskul::with('pembina')->orderBy('nama_ekskul')->get();
        $assignedEkskulIds = $user->pembina
            ? Ekskul::where('id_pembina', $user->pembina->id_pembina)->pluck('id_ekskul')->toArray()
            : [];

        return view('admin.user.edit', compact('user', 'siswaBelumPunyaAkun', 'ekskuls', 'assignedEkskulIds'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::with(['siswa', 'pembina'])->findOrFail($id);

        // Akun yang sudah punya biodata Pembina tidak boleh pindah role,
        // supaya biodata & ekskul binaannya tidak jadi yatim.
        if ($user->pembina && $request->role !== 'Pembina') {
            return back()->withErrors([
                'role' => 'Role akun Pembina tidak bisa diubah. Hapus akun ini lalu buat akun baru kalau memang perlu role lain.',
            ])->withInput();
        }

        $validator = Validator::make($request->all(), array_merge([
            'name' => 'required|string|max:100',
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash:ascii', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['nullable', 'email:rfc,dns', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,Pembina,Ketua',
            'id_siswa' => 'required_if:role,Ketua|nullable|exists:siswa,id_siswa',
            'hapus_foto' => 'nullable|boolean',
        ], $this->aturanBiodataPembina()), $this->pesan());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->role === 'Ketua') {
            $siswaBaru = Siswa::findOrFail($request->id_siswa);
            // Boleh lolos kalau siswa itu belum ada yang punya, ATAU emang siswa yang sudah terkait ke user ini sendiri
            if ($siswaBaru->id_user !== null && $siswaBaru->id_user !== $user->id) {
                return back()->withErrors(['id_siswa' => 'Siswa ini sudah punya akun login.'])->withInput();
            }
        }

        $pembina = $user->pembina;
        $fotoBaru = null;
        $hapusFotoLama = false;

        if ($request->role === 'Pembina') {
            if ($request->hasFile('foto')) {
                $fotoBaru = $request->file('foto')->store('pembina-photos', 'public');
                $hapusFotoLama = true;
            } elseif ($request->boolean('hapus_foto')) {
                $hapusFotoLama = true;
            }
        }

        DB::transaction(function () use ($request, $user, $pembina, $fotoBaru, $hapusFotoLama) {
            $userData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->filled('email') ? $request->email : null,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Lepas ikatan siswa lama kalau sebelumnya role Ketua tapi sekarang bukan lagi,
            // atau kalau siswa yang dipilih berubah
            if ($user->siswa && (!$request->id_siswa || $user->siswa->id_siswa != $request->id_siswa)) {
                $user->siswa()->update(['id_user' => null]);
            }

            if ($request->role === 'Ketua') {
                Siswa::where('id_siswa', $request->id_siswa)->update(['id_user' => $user->id]);
            }

            if ($request->role === 'Pembina') {
                $data = $this->dataBiodata($request, $user);

                if ($pembina) {
                    if ($fotoBaru) {
                        $data['foto'] = $fotoBaru;
                    } elseif ($hapusFotoLama) {
                        $data['foto'] = null;
                    }
                    $pembina->update($data);
                } else {
                    // Akun lama yang role-nya baru dijadikan Pembina: buatkan biodatanya
                    $pembina = Pembina::create(array_merge($data, ['foto' => $fotoBaru]));
                }

                $this->sinkronEkskul($pembina, $request->input('ekskul_ids', []));
            }
        });

        // File foto lama dihapus setelah transaksi sukses
        if ($hapusFotoLama && $pembina && $pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
            Storage::disk('public')->delete($pembina->foto);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun user berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $user = User::with('pembina')->findOrFail($id);

        // Akun Pembina: biodata, foto, dan keterikatan ekskulnya ikut dibersihkan.
        // Ekskul yang dibina tetap ada, hanya jadi tanpa pembina.
        if ($user->pembina) {
            $pembina = $user->pembina;

            if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
                Storage::disk('public')->delete($pembina->foto);
            }

            Ekskul::where('id_pembina', $pembina->id_pembina)
                ->update(['id_pembina' => null, 'id_pelatih' => null]);

            $pembina->delete();
        }

        // Kalau role Ketua, siswa yang terkait otomatis id_user-nya jadi NULL
        // (foreign key ON DELETE SET NULL) -- datanya tetap ada, cuma akunnya hilang.
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun user berhasil dihapus!');
    }

    /**
     * Aturan biodata Pembina -- hanya diwajibkan kalau role yang dipilih = Pembina.
     */
    private function aturanBiodataPembina(): array
    {
        $wajibPembina = Rule::requiredIf(fn () => request('role') === 'Pembina');

        return [
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'jk' => [$wajibPembina, 'nullable', 'in:L,P'],
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'ekskul_ids' => 'nullable|array',
            'ekskul_ids.*' => 'exists:ekskuls,id_ekskul',
        ];
    }

    private function dataBiodata(Request $request, User $user): array
    {
        return [
            'id_user' => $user->id,
            'nama_pembina' => $request->name,
            'jk' => $request->jk,
            'agama' => $request->agama,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'medsos' => $request->medsos,
            'alamat' => $request->alamat,
        ];
    }

    /**
     * Samakan daftar ekskul binaan pembina dengan centang di form.
     * Ekskul yang dicentang diambil alih; yang tidak dicentang dilepas.
     */
    private function sinkronEkskul(Pembina $pembina, array $selectedIds): void
    {
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
    }

    private function pesan(): array
    {
        return [
            'jk.required' => 'Pilih jenis kelamin.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username ini sudah dipakai akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip (-) dan underscore (_), tanpa spasi dan tanpa @.',
            'email.email' => 'Email tidak valid atau domainnya tidak ditemukan. Pakai email asli atau kosongkan saja.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}