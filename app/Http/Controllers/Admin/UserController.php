<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['siswa', 'pembina']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        // Cuma siswa yang belum punya akun login yang boleh dijadiin Ketua
        $siswaBelumPunyaAkun = Siswa::whereNull('id_user')->orderBy('nama_siswa')->get();

        return view('admin.user.create', compact('siswaBelumPunyaAkun'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Pembina,Ketua',
            // wajib diisi HANYA kalau role = Ketua
            'id_siswa' => 'required_if:role,Ketua|nullable|exists:siswa,id_siswa',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Pastikan siswa yang dipilih belum kepakai akun lain (jaga-jaga race condition)
        if ($request->role === 'Ketua') {
            $siswa = Siswa::findOrFail($request->id_siswa);
            if ($siswa->id_user !== null) {
                return back()->withErrors(['id_siswa' => 'Siswa ini sudah punya akun login.'])->withInput();
            }
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'Ketua') {
                Siswa::where('id_siswa', $request->id_siswa)->update(['id_user' => $user->id]);
            }
        });

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun user berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $user = User::with('siswa')->findOrFail($id);

        // Siswa yang belum punya akun, DITAMBAH siswa yang sedang terkait ke user ini (biar tetap muncul di dropdown)
        $siswaBelumPunyaAkun = Siswa::whereNull('id_user')
            ->orWhere('id_user', $user->id)
            ->orderBy('nama_siswa')
            ->get();

        return view('admin.user.edit', compact('user', 'siswaBelumPunyaAkun'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::with('siswa')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,Pembina,Ketua',
            'id_siswa' => 'required_if:role,Ketua|nullable|exists:siswa,id_siswa',
        ]);

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

        DB::transaction(function () use ($request, $user) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
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
        });

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun user berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        // Info: kalau role Pembina, hapus user ini otomatis ikut menghapus
        // data biodata pembina-nya juga (foreign key ON DELETE CASCADE).
        // Kalau role Ketua, siswa yang terkait otomatis id_user-nya jadi NULL
        // (foreign key ON DELETE SET NULL) -- datanya tetap ada, cuma akunnya hilang.
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'Akun user berhasil dihapus!');
    }
}