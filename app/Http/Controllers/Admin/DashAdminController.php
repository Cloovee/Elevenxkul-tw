<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Kelas;
use App\Models\Pembina;
use App\Models\Siswa;
use App\Models\User;

class DashAdminController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalUser = User::count();
        $totalEkskul = Ekskul::count();
        $totalPembina = Pembina::count();
        $totalKelas = Kelas::count();

        // 5 ekskul terbaru + jumlah anggotanya (dipakai di daftar "Ekskul terbaru").
        $recentEkskuls = Ekskul::with(['pembina'])
            ->withCount('peserta')
            ->latest()
            ->take(5)
            ->get();

        // Bahan panel "Perlu perhatian": data ekskul yang belum lengkap.
        $ekskulTanpaPembina = Ekskul::whereNull('id_pembina')->count();
        $ekskulTanpaKetua = Ekskul::whereNull('id_ketua')->count();

        // Bahan grafik "Sebaran kategori": ['organisasi' => 3, 'komunitas' => 2, ...]
        $kategoriCounts = Ekskul::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->all();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalUser',
            'totalEkskul',
            'totalPembina',
            'totalKelas',
            'recentEkskuls',
            'ekskulTanpaPembina',
            'ekskulTanpaKetua',
            'kategoriCounts'
        ));
    }
}