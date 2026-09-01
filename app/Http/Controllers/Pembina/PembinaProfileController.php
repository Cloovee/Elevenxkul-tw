<?php

namespace App\Http\Controllers;

use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Perbarui data pembina yang boleh diubah sendiri (nomor HP & alamat saja).
     *
     * Foto, nama, dan email adalah data identitas akun yang hanya boleh
     * diubah oleh admin (lewat panel Admin > Pembina), sehingga field-field
     * itu sengaja tidak diterima/diproses di sini.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);

        $pembina = Pembina::where('id_user', $user->id)->first();
        if ($pembina) {
            $pembina->update([
                'nomor_hp' => $validated['nomor_hp'] ?? $pembina->nomor_hp,
                'alamat' => $validated['alamat'] ?? $pembina->alamat,
            ]);
        }

        return redirect()
            ->route('pembina.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}