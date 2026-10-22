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

        $recentEkskuls = Ekskul::with(['pembina'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalUser',
            'totalEkskul',
            'totalPembina',
            'totalKelas',
            'recentEkskuls'
        ));
    }
}