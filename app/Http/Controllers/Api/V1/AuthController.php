<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends ApiController
{
    /** Jumlah perangkat (token) aktif per akun; yang lebih lama otomatis dicabut. */
    private const MAKS_TOKEN = 5;

    /**
     * POST /api/v1/auth/login
     * Sama seperti login web: boleh pakai email ATAU username. Hanya role Ketua.
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier'  => ['required', 'string', 'max:255'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:60'],
        ], [
            'identifier.required' => 'Email atau username wajib diisi.',
            'password.required'   => 'Kata sandi wajib diisi.',
        ]);

        $login = trim($data['identifier']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $login)->first();

        if (! $user || ! Hash::check($data['password'], (string) $user->password)) {
            return response()->json(['message' => 'Email/username atau kata sandi salah.'], 422);
        }

        if ($user->role !== 'Ketua') {
            return response()->json(['message' => 'Aplikasi ini khusus untuk Ketua ekskul.'], 403);
        }

        $token = $user->createToken($data['device_name'] ?? 'mobile', ['ketua'])->plainTextToken;

        // Batasi jumlah token aktif supaya tabel tidak menumpuk.
        $simpan = $user->tokens()->latest('id')->limit(self::MAKS_TOKEN)->pluck('id');
        $user->tokens()->whereNotIn('id', $simpan)->delete();

        return response()->json([
            'token'  => $token,
            'user'   => $this->userJson($user),
            'ekskul' => $this->ekskulJson($user->siswa?->ekskulDipimpin?->load('pelatih')),
        ]);
    }

    /** POST /api/v1/auth/logout - cabut token yang sedang dipakai. */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json(['message' => 'Berhasil keluar.']);
    }

    /** GET /api/v1/me - data akun + ekskul (dipakai untuk memulihkan sesi saat aplikasi dibuka). */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $ekskul = $user->siswa?->ekskulDipimpin?->load('pelatih');

        return response()->json([
            'user'   => $this->userJson($user),
            'ekskul' => $this->ekskulJson($ekskul),
        ]);
    }
}
