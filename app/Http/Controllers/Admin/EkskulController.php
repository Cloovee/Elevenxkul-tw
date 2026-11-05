<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Pelatih;
use App\Models\Pembina;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EkskulController extends Controller
{
    public function index(Request $request)
    {
        $ekskuls = Ekskul::with(['pembina', 'pelatih', 'ketua'])
            ->when($request->search, function ($query, $search) {
                $query->where('nama_ekskul', 'like', "%{$search}%");
            })
            ->when($request->kategori, function ($query, $kategori) {
                $query->where('kategori', $kategori);
            })
            ->when($request->pembina, function ($query, $pembina) {
                $pembina === 'none'
                    ? $query->whereNull('id_pembina')
                    : $query->where('id_pembina', $pembina);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pembinas = Pembina::orderBy('nama_pembina')->get();

        return view('admin.ekskul.index', compact('ekskuls', 'pembinas'));
    }

    public function create()
    {
        $pembinas = Pembina::orderBy('nama_pembina')->get();
        $calonKetua = $this->calonKetua();

        return view('admin.ekskul.create', compact('pembinas', 'calonKetua'));
    }

    public function store(Request $request)
    {
        $punyaPelatih = $request->boolean('punya_pelatih');

        $validated = $request->validate(array_merge([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
            // Relasi: Pembina yang bertanggung jawab atas ekskul ini. Dari sinilah nanti
            // pembina yang bersangkutan bisa mengelola CRUD pelatih untuk ekskul ini.
            'id_pembina' => 'nullable|exists:pembina,id_pembina',
            // Relasi: Siswa (yang sudah punya akun role Ketua) yang memimpin ekskul ini.
            // Dari sinilah nanti fitur sisi Ketua (kelola anggota, absensi peserta)
            // tahu ekskul mana yang boleh dia kelola.
            'id_ketua' => 'nullable|exists:siswa,id_siswa',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'hapus_poster' => 'nullable|boolean',
            // Logo ekskul: tampil di kop surat laporan cetak (sisi kanan).
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_logo' => 'nullable|boolean',
        ], $this->aturanPelatih($punyaPelatih)), array_merge([
            'poster.image' => 'Poster harus berupa gambar.',
            'poster.mimes' => 'Poster harus berformat JPG, PNG, atau WEBP.',
            'poster.max' => 'Ukuran poster maksimal 4 MB.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
            'logo.uploaded' => 'Logo gagal diunggah. Ukuran file mungkin melebihi batas server, coba gambar yang lebih kecil atau kosongkan logo.',
            'poster.uploaded' => 'Poster gagal diunggah. Ukuran file mungkin melebihi batas server, coba gambar yang lebih kecil.',
        ], $this->pesanPelatih()));

        // Pisahkan input pelatih dari data ekskul (supaya tidak ikut masuk ke Ekskul::create).
        $dataPelatih = $this->pisahInputPelatih($validated);

        unset($validated['hapus_poster'], $validated['hapus_logo']);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('poster-ekskul', 'public');
        }

        // Logo bersifat OPSIONAL: kalau tidak diisi, ekskul tetap tersimpan tanpa logo.
        if ($request->hasFile('logo')) {
            if (! $this->pastikanKolomLogo()) {
                return back()->withInput()->withErrors([
                    'logo' => 'Kolom logo belum ada di database dan gagal dibuat otomatis. Kosongkan logo dulu, atau jalankan migration.',
                ]);
            }

            $validated['logo'] = $request->file('logo')->store('logo-ekskul', 'public');
        } else {
            unset($validated['logo']);
        }

        // Pelatih (opsional): kalau saklar "Memiliki pelatih" aktif, buat datanya lalu
        // kaitkan ke ekskul. Kalau tidak, ekskul disimpan tanpa pelatih (id_pelatih = null).
        if ($punyaPelatih && $request->hasFile('pelatih_foto')) {
            $dataPelatih['foto'] = $request->file('pelatih_foto')->store('pelatih-photos', 'public');
        }

        try {
            DB::transaction(function () use (&$validated, $punyaPelatih, $dataPelatih) {
                if ($punyaPelatih) {
                    $validated['id_pelatih'] = Pelatih::create($dataPelatih)->id_pelatih;
                }

                Ekskul::create($validated);
            });
        } catch (\Throwable $e) {
            if (! empty($dataPelatih['foto'])) {
                Storage::disk('public')->delete($dataPelatih['foto']);
            }

            throw $e;
        }

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil ditambahkan');
    }

    public function show(Ekskul $ekskul)
    {
        $ekskul->load(['pembina', 'pelatih', 'ketua']);

        return view('admin.ekskul.show', compact('ekskul'));
    }

    public function edit(Ekskul $ekskul)
    {
        $pembinas = Pembina::orderBy('nama_pembina')->get();
        $calonKetua = $this->calonKetua($ekskul->id_ketua);

        return view('admin.ekskul.edit', compact('ekskul', 'pembinas', 'calonKetua'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        // $kirimPelatih = form ini memang membawa saklar pelatih (form Edit versi baru).
        $kirimPelatih = $request->has('punya_pelatih');
        $punyaPelatih = $request->boolean('punya_pelatih');

        $validated = $request->validate(array_merge([
            'nama_ekskul' => 'required|string|max:255',
            'kategori' => 'required|in:organisasi,ekstrakulikuler,komunitas',
            'deskripsi' => 'nullable|string',
            'id_pembina' => 'nullable|exists:pembina,id_pembina',
            'id_ketua' => 'nullable|exists:siswa,id_siswa',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'hapus_poster' => 'nullable|boolean',
            // Logo ekskul: tampil di kop surat laporan cetak (sisi kanan).
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_logo' => 'nullable|boolean',
        ], $this->aturanPelatih($punyaPelatih)), array_merge([
            'poster.image' => 'Poster harus berupa gambar.',
            'poster.mimes' => 'Poster harus berformat JPG, PNG, atau WEBP.',
            'poster.max' => 'Ukuran poster maksimal 4 MB.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
            'logo.uploaded' => 'Logo gagal diunggah. Ukuran file mungkin melebihi batas server, coba gambar yang lebih kecil atau kosongkan logo.',
            'poster.uploaded' => 'Poster gagal diunggah. Ukuran file mungkin melebihi batas server, coba gambar yang lebih kecil.',
        ], $this->pesanPelatih()));

        $dataPelatih = $this->pisahInputPelatih($validated);

        // Aturan lama (form tanpa saklar pelatih): kalau pembina diganti/dilepas, pelatih
        // yang terkait ikut dilepas. Kalau form membawa saklar pelatih, Admin yang
        // menentukan sendiri lewat saklar itu, jadi aturan ini tidak dipakai.
        if (! $kirimPelatih
            && (string) $ekskul->id_pembina !== (string) ($validated['id_pembina'] ?? '')) {
            $validated['id_pelatih'] = null;
        }

        // Poster: ganti kalau ada file baru, hapus kalau kotak "hapus poster" dicentang.
        $hapus = ! empty($validated['hapus_poster']);
        unset($validated['hapus_poster']);

        if ($request->hasFile('poster')) {
            $this->hapusFilePoster($ekskul);
            $validated['poster'] = $request->file('poster')->store('poster-ekskul', 'public');
        } elseif ($hapus) {
            $this->hapusFilePoster($ekskul);
            $validated['poster'] = null;
        } else {
            unset($validated['poster']);
        }

        // Logo: sama seperti poster -- ganti kalau ada file baru, hapus kalau dicentang.
        $hapusLogo = ! empty($validated['hapus_logo']);
        unset($validated['hapus_logo']);

        // Logo bersifat OPSIONAL: tidak diisi = logo lama tetap dipakai (atau tetap kosong).
        if ($request->hasFile('logo') || $hapusLogo) {
            if (! $this->pastikanKolomLogo()) {
                return back()->withInput()->withErrors([
                    'logo' => 'Kolom logo belum ada di database dan gagal dibuat otomatis. Kosongkan logo dulu, atau jalankan migration.',
                ]);
            }
        }

        if ($request->hasFile('logo')) {
            $this->hapusFileLogo($ekskul);
            $validated['logo'] = $request->file('logo')->store('logo-ekskul', 'public');
        } elseif ($hapusLogo) {
            $this->hapusFileLogo($ekskul);
            $validated['logo'] = null;
        } else {
            unset($validated['logo']);
        }

        // Pelatih: ikuti saklar "Memiliki pelatih / Tanpa pelatih".
        $fotoBaru = null;
        $fotoUntukDihapus = null;

        try {
            DB::transaction(function () use ($request, $ekskul, &$validated, $kirimPelatih, $punyaPelatih, $dataPelatih, &$fotoBaru, &$fotoUntukDihapus) {
                if ($kirimPelatih && $punyaPelatih) {
                    $pelatih = $ekskul->pelatih;

                    if ($request->hasFile('pelatih_foto')) {
                        $fotoBaru = $request->file('pelatih_foto')->store('pelatih-photos', 'public');
                        $dataPelatih['foto'] = $fotoBaru;
                        $fotoUntukDihapus = $pelatih?->foto;
                    } elseif ($pelatih && $request->boolean('hapus_pelatih_foto')) {
                        $dataPelatih['foto'] = null;
                        $fotoUntukDihapus = $pelatih->foto;
                    }

                    if ($pelatih) {
                        // Perbarui data pelatih yang sudah terkait.
                        $pelatih->update($dataPelatih);
                    } else {
                        // Sebelumnya tanpa pelatih -> buat data pelatih baru lalu kaitkan.
                        $validated['id_pelatih'] = Pelatih::create($dataPelatih)->id_pelatih;
                    }
                } elseif ($kirimPelatih) {
                    // "Tanpa pelatih": cukup lepas dari ekskul. Data pelatih & riwayat absensinya
                    // sengaja TIDAK dihapus supaya laporan lama tetap aman.
                    $validated['id_pelatih'] = null;
                }

                $ekskul->update($validated);
            });
        } catch (\Throwable $e) {
            if ($fotoBaru) {
                Storage::disk('public')->delete($fotoBaru);
            }

            throw $e;
        }

        // File foto lama baru dihapus setelah data berhasil tersimpan.
        if ($fotoUntukDihapus && ! str_starts_with($fotoUntukDihapus, 'http')) {
            Storage::disk('public')->delete($fotoUntukDihapus);
        }

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil diperbarui');
    }

    public function destroy(Ekskul $ekskul)
    {
        $this->hapusFilePoster($ekskul);
        $this->hapusFileLogo($ekskul);
        $ekskul->delete();

        return redirect()->route('admin.ekskul.index')
            ->with('success', 'Ekskul berhasil dihapus');
    }

    /**
     * Aturan validasi bagian pelatih. Kolom pelatih hanya divalidasi kalau saklar
     * "Memiliki pelatih" aktif; kalau tidak, isian pelatih diabaikan.
     */
    private function aturanPelatih(bool $punyaPelatih): array
    {
        $aturan = ['punya_pelatih' => 'nullable|boolean'];

        if (! $punyaPelatih) {
            return $aturan;
        }

        return $aturan + [
            'pelatih_nama' => 'required|string|max:100',
            'pelatih_jk' => 'nullable|in:L,P',
            'pelatih_agama' => 'nullable|string|max:20',
            'pelatih_nomor_hp' => 'nullable|string|max:15',
            'pelatih_email' => 'nullable|email|max:100',
            'pelatih_medsos' => 'nullable|string|max:100',
            'pelatih_alamat' => 'nullable|string',
            'pelatih_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_pelatih_foto' => 'nullable|boolean',
        ];
    }

    private function pesanPelatih(): array
    {
        return [
            'pelatih_nama.required' => 'Nama pelatih wajib diisi kalau ekskul ini memiliki pelatih.',
            'pelatih_nama.max' => 'Nama pelatih maksimal 100 karakter.',
            'pelatih_jk.in' => 'Jenis kelamin pelatih tidak valid.',
            'pelatih_nomor_hp.max' => 'No. HP pelatih maksimal 15 karakter.',
            'pelatih_email.email' => 'Format email pelatih tidak valid.',
            'pelatih_foto.image' => 'Foto pelatih harus berupa gambar.',
            'pelatih_foto.mimes' => 'Foto pelatih harus berformat JPG, PNG, atau WEBP.',
            'pelatih_foto.max' => 'Ukuran foto pelatih maksimal 2 MB.',
            'pelatih_foto.uploaded' => 'Foto pelatih gagal diunggah. Ukuran file mungkin melebihi batas server, coba gambar yang lebih kecil.',
        ];
    }

    /**
     * Ambil isian pelatih dari hasil validasi (dipetakan ke nama kolom tabel `pelatih`),
     * lalu buang semua kunci pelatih dari $validated agar tidak ikut tersimpan ke ekskul.
     */
    private function pisahInputPelatih(array &$validated): array
    {
        $peta = [
            'pelatih_nama' => 'nama_pelatih',
            'pelatih_jk' => 'jk',
            'pelatih_agama' => 'agama',
            'pelatih_nomor_hp' => 'nomor_hp',
            'pelatih_email' => 'email',
            'pelatih_medsos' => 'medsos',
            'pelatih_alamat' => 'alamat',
        ];

        $data = [];
        foreach ($peta as $input => $kolom) {
            if (array_key_exists($input, $validated)) {
                $data[$kolom] = $validated[$input];
            }
            unset($validated[$input]);
        }

        unset($validated['punya_pelatih'], $validated['pelatih_foto'], $validated['hapus_pelatih_foto']);

        // Agama disimpan huruf kecil, sama seperti data pelatih yang sudah ada.
        if (isset($data['agama'])) {
            $data['agama'] = strtolower($data['agama']);
        }

        return $data;
    }

    /**
     * Hapus file poster dari storage (abaikan kalau berupa URL luar).
     */
    private function hapusFilePoster(Ekskul $ekskul): void
    {
        if ($ekskul->poster && ! str_starts_with($ekskul->poster, 'http')) {
            Storage::disk('public')->delete($ekskul->poster);
        }
    }

    /**
     * Pastikan kolom `logo` ada di tabel ekskuls. Di hosting migration sering belum
     * dijalankan, sehingga simpan logo gagal dengan "Unknown column 'logo'".
     * Kalau belum ada, dibuat otomatis (nullable). Return false kalau gagal dibuat.
     */
    private function pastikanKolomLogo(): bool
    {
        try {
            if (Schema::hasColumn('ekskuls', 'logo')) {
                return true;
            }

            Schema::table('ekskuls', function (Blueprint $table) {
                $table->string('logo', 255)->nullable()->after('poster');
            });

            return Schema::hasColumn('ekskuls', 'logo');
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Hapus file logo dari storage (abaikan kalau berupa URL luar).
     */
    private function hapusFileLogo(Ekskul $ekskul): void
    {
        if ($ekskul->logo && ! str_starts_with($ekskul->logo, 'http')) {
            Storage::disk('public')->delete($ekskul->logo);
        }
    }

    /**
     * Siswa yang boleh dijadikan calon Ketua di dropdown: harus sudah punya akun
     * role Ketua (dibuat lewat CRUD User), DAN belum memimpin ekskul lain.
     * $ketuaSaatIniId dipakai pas edit, biar Ketua ekskul ini sendiri tetap
     * muncul di dropdown meski dia "sudah memimpin ekskul" (ekskul ini sendiri).
     */
    private function calonKetua(?int $ketuaSaatIniId = null)
    {
        return Siswa::whereHas('user', function ($q) {
                $q->where('role', 'Ketua');
            })
            ->where(function ($q) use ($ketuaSaatIniId) {
                $q->whereDoesntHave('ekskulDipimpin');
                if ($ketuaSaatIniId) {
                    $q->orWhere('id_siswa', $ketuaSaatIniId);
                }
            })
            ->orderBy('nama_siswa')
            ->get();
    }
}