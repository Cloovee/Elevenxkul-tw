<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use App\Models\User;
use Illuminate\Http\Request;
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

        $pembina = $query->orderBy('nama_pembina')->paginate(20);

        return view('admin.CRUD-pembina.index', compact('pembina'));
    }

    public function create()
    {
        // Cuma akun role Pembina yang BELUM punya biodata yang boleh dipilih
        $availableUsers = User::where('role', 'Pembina')
            ->whereDoesntHave('pembina')
            ->orderBy('name')
            ->get();

        return view('admin.CRUD-pembina.create', compact('availableUsers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id',
            'nama_pembina' => 'required|string|max:100',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Jaga-jaga race condition: pastikan user itu masih role Pembina & belum ada biodata
        $user = User::findOrFail($request->id_user);
        if ($user->role !== 'Pembina') {
            return back()->withErrors(['id_user' => 'Akun ini bukan role Pembina.'])->withInput();
        }
        if ($user->pembina) {
            return back()->withErrors(['id_user' => 'Akun ini sudah punya biodata pembina.'])->withInput();
        }

        Pembina::create([
            'id_user' => $request->id_user,
            'nama_pembina' => $request->nama_pembina,
            'jk' => $request->jk,
            'agama' => $request->agama,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'medsos' => $request->medsos,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Biodata pembina berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $pembina = Pembina::with('user')->findOrFail($id);
        return view('admin.CRUD-pembina.edit', compact('pembina'));
    }

    public function update(Request $request, int $id)
    {
        $pembina = Pembina::findOrFail($id);

        // Akun (id_user) tidak diubah lewat sini -- cuma biodata
        $validator = Validator::make($request->all(), [
            'nama_pembina' => 'required|string|max:100',
            'jk' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pembina->update($request->only([
            'nama_pembina', 'jk', 'agama', 'nomor_hp', 'email', 'medsos', 'alamat',
        ]));

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Biodata pembina berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $pembina = Pembina::findOrFail($id);

        // Cuma hapus biodatanya. Akun user TETAP ada (masih bisa login dengan role Pembina),
        // cuma statusnya balik jadi "belum ada biodata" di CRUD User.
        $pembina->delete();

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Biodata pembina berhasil dihapus. Akun login pembina tetap ada.');
    }
}