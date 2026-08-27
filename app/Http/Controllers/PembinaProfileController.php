<?php

namespace App\Http\Controllers;

use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PembinaProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pembina.
     */
    public function index()
    {
        $user = Auth::user();
        $pembina = Pembina::where('id_user', $user->id)->first();

        return view('pembina.profile', [
            'user' => $user,
            'pembina' => $pembina,
        ]);
    }

    /**
     * Perbarui username (name/email) & data pembina.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $pembina = Pembina::where('id_user', $user->id)->first();
        if ($pembina) {
            $dataUpdate = [
                'nama_pembina' => $validated['name'],
                'email' => $validated['email'],
                'nomor_hp' => $validated['nomor_hp'] ?? $pembina->nomor_hp,
                'alamat' => $validated['alamat'] ?? $pembina->alamat,
            ];

            if ($request->hasFile('foto')) {
                if ($pembina->foto && Storage::disk('public')->exists($pembina->foto)) {
                    Storage::disk('public')->delete($pembina->foto);
                }

                $dataUpdate['foto'] = $request->file('foto')->store('foto-pembina', 'public');
            }

            $pembina->update($dataUpdate);
        }

        return redirect()
            ->route('pembina.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (! Hash::check($validated['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($validated['password_baru'])]);

        return redirect()
            ->route('pembina.profile.index')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
