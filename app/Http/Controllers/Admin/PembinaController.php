<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PembinaController extends Controller
{
    public function index()
    {
        $pembinas = Pembina::with('user')->latest('id_pembina')->paginate(10);

        return view('admin.pembina.index', compact('pembinas'));
    }

    public function create()
    {
        return view('admin.pembina.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pembina' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email'), Rule::unique('pembina', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'jk' => ['nullable', 'in:L,P'],
            'agama' => ['nullable', 'string', 'max:20'],
            'medsos' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = User::create([
            'name' => $validated['nama_pembina'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Pembina',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-pembina', 'public');
        }

        Pembina::create([
            'id_user' => $user->id,
            'nama_pembina' => $validated['nama_pembina'],
            'foto' => $fotoPath,
            'jk' => $validated['jk'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'nomor_hp' => $validated['nomor_hp'] ?? null,
            'email' => $validated['email'],
            'medsos' => $validated['medsos'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina berhasil ditambahkan.');
    }

    public function edit(Pembina $pembina)
    {
        return view('admin.pembina.edit', compact('pembina'));
    }

    public function update(Request $request, Pembina $pembina)
    {
        $validated = $request->validate([
            'nama_pembina' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pembina', 'email')->ignore($pembina->id_pembina, 'id_pembina')],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'jk' => ['nullable', 'in:L,P'],
            'agama' => ['nullable', 'string', 'max:20'],
            'medsos' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
                Storage::disk('public')->delete($pembina->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-pembina', 'public');
        }

        $pembina->update($validated);

        // Sinkronkan nama & email dengan akun user terkait.
        if ($pembina->user) {
            $pembina->user->update([
                'name' => $validated['nama_pembina'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Data pembina berhasil diperbarui.');
    }

    public function destroy(Pembina $pembina)
    {
        if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
            Storage::disk('public')->delete($pembina->foto);
        }

        // Menghapus user terkait akan ikut menghapus baris pembina (FK cascade).
        $pembina->user?->delete();
        $pembina->delete();

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Pembina berhasil dihapus.');
    }
}
