<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Galeri;
use App\Models\Pelatih;
use App\Models\Pembina;
use App\Models\Peserta;

class LandingController extends Controller
{
    public function index()
    {
        // Semua ekskul yang dibuat admin, lengkap dengan pembina, pelatih, ketua,
        // jumlah anggota aktif, dan satu foto galeri pertama untuk sampul kartu.
        $ekskuls = Ekskul::with(['pembina', 'pelatih', 'ketua', 'galeri' => fn ($q) => $q->orderBy('urutan')])
            ->withCount(['peserta as anggota_aktif' => fn ($q) => $q->where('status', 'aktif')])
            ->orderBy('nama_ekskul')
            ->get();

        $pembina = Pembina::with('ekskuls:id_ekskul,id_pembina,nama_ekskul')
            ->orderBy('nama_pembina')
            ->get();

        // Galeri diurutkan sesuai pengaturan admin (urutan kecil tampil paling depan).
        $galeri = Galeri::with('ekskul:id_ekskul,nama_ekskul')
            ->orderBy('urutan')
            ->orderByDesc('id_galeri')
            ->get();

        $stat = [
            'ekskul'  => $ekskuls->count(),
            'anggota' => Peserta::where('status', 'aktif')->count(),
            'pembina' => $pembina->count(),
            'pelatih' => Pelatih::count(),
        ];

        // Data sekolah. Ubah di sini kalau alamat/peta perlu diganti.
        $sekolah = [
            'nama'       => 'SMK Negeri 11 Bandung',
            'alamat'     => 'Jl. Budi, Cilember, Kota Bandung, Jawa Barat',
            'peta_query' => 'SMK Negeri 11 Bandung, Jl. Budi, Cilember, Bandung',
            'youtube_id' => 'ONWUEFy4wjE',

            // Data footer. Ganti tanda '#' pada sosmed dengan link akun resmi sekolah.
            'singkat'      => 'SMKN 11 Bandung',
            'tagline'      => 'Sekolah Pusat Keunggulan',
            'deskripsi'    => 'Sekolah Menengah Kejuruan yang berfokus pada pengembangan kompetensi dan karakter siswa untuk menghadapi tantangan masa depan.',
            'alamat_footer' => 'Jl. Budhi Cilember, Sukaraja, Cicendo, Bandung',
            'telepon'      => '(022) 6652442',
            'email'        => 'smkn11bdg@gmail.com',
            'sosmed'       => [
                'facebook'  => '#',
                'tiktok'    => '#',
                'instagram' => '#',
                'youtube'   => '#',
            ],
        ];

        return view('layouts.landing', compact('ekskuls', 'pembina', 'galeri', 'stat', 'sekolah'));
    }

    /**
     * Route /ekskul/{ekskul}: kartu ekskul sudah lengkap di landing page,
     * jadi tautan detail diarahkan ke bagian ekskul pada halaman utama.
     */
    public function show(Ekskul $ekskul)
    {
        return redirect()->to(route('landing') . '#ekskul');
    }
}