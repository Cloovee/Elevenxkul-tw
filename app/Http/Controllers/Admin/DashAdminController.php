<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pembina;
// use App\Models\Siswa;
// use App\Models\Pelatih;

class DashAdminController extends Controller
{
    public function index()
    {
        $totalEkskul = Ekskul::count();
        $totalPembina = Pembina::count();
        $recentEkskuls = Ekskul::latest()->take(5)->get();
        // $totalSiswa = Siswa::count();
        // $totalPelatih = Pelatih::count();

        return view('admin.dashboard', compact(
            'totalEkskul',
            'totalPembina',
            'recentEkskuls',
         ));
    }
}