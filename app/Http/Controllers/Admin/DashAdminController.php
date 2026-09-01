<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Pembina;
use App\Models\Kelas; 


class DashAdminController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalUser = User::count();
        $totalEkskul = Ekskul::count();
        $totalPembina = Pembina::count();
        $recentEkskuls = Ekskul::latest()->take(5)->get();
        $totalSiswa = Siswa::count();
        $totalPembina = Pembina::count();
        $totalKelas = Kelas::count(); 
        
        
        $recentEkskuls = Ekskul::with(['pembina'])
        ->latest()
        ->take(5)
        ->get();
        
        return view('admin.dashboard', compact(
            'totalEkskul',
            'totalPembina',
            'recentEkskuls',
            'totalSiswa',
            'totalUser',
            'totalPembina',
            'recentEkskuls'
        ));

    }
}                           