<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Kelas;
use App\Models\Pembina;
use App\Models\Peserta;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * CRUD Ketua (sisi Pembina).
 *
 * Beda dengan CRUD User milik Admin (yang cuma "mempromosikan" siswa yang
 * sudah ada jadi Ketua dengan memilihnya dari dropdown), CRUD ini dipakai
 * Pembina untuk mendaftarkan Ketua BARU dari nol dalam satu form:
 * biodata siswa (NISN, NIS, nama, jk, agama, kelas, no hp, email, medsos)
 * SEKALIGUS akun login (username + password, role otomatis "Ketua").
 *
 * Relasi yang dipakai/dijaga di sini:
 *   User (role=Ketua) <- id_user - Siswa - id_ketua -> Ekskul - id_pembina -> Pembina (login)
 *
 * Ketua yang dikelola di sini dibatasi hanya untuk ekskul-ekskul yang
 * dibina oleh pembina yang sedang login (id_pembina diisi Admin lewat
 * CRUD Ekskul), supaya satu pembina tidak bisa mengubah/menghapus Ketua
 * ekskul milik pembina lain.
 */
class KetuaController extends Controller
{
    /**
     * Ambil biodata pembina yang sedang login.
     */
    private function pembinaOrFail(): Pembina
    {
        $pembina = Auth::user()->pembina;

        abort_if(
            !$pembina,
            403,
            'Akun kamu belum punya biodata pembina. Hubungi Admin untuk melengkapinya dulu.'
        );

        return $pembina;
    }

    /**
     * Id ekskul-ekskul yang dibina oleh pembina ini (ditentukan Admin lewat CRUD Ekskul).
     */
    private function ekskulIds(Pembina $pembina)
    {
        return $pembina->ekskuls()->pluck('id_ekskul');
    }

    public function index(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        // Filter ekskul: hanya diterima kalau memang ekskul milik pembina ini
        $ekskulList = $pembina->ekskuls()->orderBy('nama_ekskul')->get();
        $filterEkskul = (int) $request->query('ekskul');
        if (! $ekskulList->contains('id_ekskul', $filterEkskul)) {
            $filterEkskul = null;
        }
        $ekskulTampil = $filterEkskul ? collect([$filterEkskul]) : $ekskulIds;

        $query = Siswa::with(['kelas', 'user', 'ekskulDipimpin'])
            ->whereHas('ekskulDipimpin', function ($q) use ($ekskulTampil) {
                $q->whereIn('id_ekskul', $ekskulTampil);
            });

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'LIKE', "%{$search}%")
                  ->orWhere('NISN', 'LIKE', "%{$search}%")
                  ->orWhere('NIS', 'LIKE', "%{$search}%");
            });
        }

        $ketua = $query->orderBy('nama_siswa')->paginate(10)->withQueryString();

        $adaEkskulTanpaPembina = $ekskulIds->isEmpty();

        return view('pembina.ketua.index', compact('ketua', 'adaEkskulTanpaPembina', 'ekskulList', 'filterEkskul'));
    }

    public function create()
    {
        $pembina = $this->pembinaOrFail();

        $ekskuls = $pembina->ekskuls()->with('ketua')->orderBy('nama_ekskul')->get();
        $ekskulIds = $ekskuls->pluck('id_ekskul');

        // Siswa yang sudah jadi ketua di ekskul manapun tidak ditawarkan lagi
        // (satu siswa hanya boleh jadi ketua di satu ekskul).
        $sudahKetua = Ekskul::whereNotNull('id_ketua')->pluck('id_ketua')->all();

        // Anggota (peserta) aktif per ekskul yang dibina pembina ini.
        $anggotaPerEkskul = Peserta::with('siswa.kelas')
            ->whereIn('id_ekskul', $ekskulIds)
            ->where('status', 'aktif')
            ->get()
            ->groupBy('id_ekskul')
            ->map(fn ($rows) => $rows->pluck('id_siswa')->values());

        // Semua siswa aktif yang belum jadi ketua. Dipakai sebagai daftar utama (anggota)
        // dan juga pilihan cadangan "siswa yang belum jadi anggota" di dropdown.
        $siswaList = Siswa::with('kelas')
            ->where('status', Siswa::STATUS_AKTIF)
            ->whereNotIn('id_siswa', $sudahKetua)
            ->orderBy('nama_siswa')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id_siswa,
                'nama' => $s->nama_siswa,
                'nisn' => $s->NISN,
                'nis' => $s->NIS,
                'kelas' => $s->kelas ? $s->kelas->nama_kelas : '-',
                'email' => $s->email,
                'punya_akun' => $s->id_user !== null,
            ]);

        $ekskulData = $ekskuls->map(fn ($e) => [
            'id' => $e->id_ekskul,
            'nama' => $e->nama_ekskul,
            'ketua_lama' => optional($e->ketua)->nama_siswa,
            'anggota_ids' => ($anggotaPerEkskul[$e->id_ekskul] ?? collect())->all(),
        ])->values();

        return view('pembina.ketua.create', compact('ekskuls', 'ekskulData', 'siswaList'));
    }

    public function store(Request $request)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $request->validate([
            'id_ekskul' => ['required', Rule::in($ekskulIds)],
            'id_siswa' => ['required', 'exists:siswa,id_siswa'],
        ], [
            'id_ekskul.required' => 'Pilih ekskul/organisasi yang akan dipimpin dulu.',
            'id_ekskul.in' => 'Ekskul tersebut bukan ekskul yang kamu bina.',
            'id_siswa.required' => 'Pilih siswa yang akan dijadikan ketua.',
            'id_siswa.exists' => 'Siswa yang dipilih tidak ditemukan.',
        ]);

        $siswa = Siswa::findOrFail($request->id_siswa);

        if ($siswa->status !== Siswa::STATUS_AKTIF) {
            return back()->withErrors(['id_siswa' => 'Hanya siswa berstatus aktif yang bisa dijadikan ketua.'])->withInput();
        }

        if (Ekskul::where('id_ketua', $siswa->id_siswa)->exists()) {
            return back()->withErrors(['id_siswa' => 'Siswa ini sudah menjadi ketua di ekskul/organisasi lain.'])->withInput();
        }

        // Akun login hanya perlu diisi kalau siswa ini belum punya akun.
        $butuhAkun = $siswa->id_user === null;

        if ($butuhAkun) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:100|unique:users,email',
                'username' => 'required|string|max:50|alpha_dash|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.unique' => 'Email ini sudah dipakai akun lain.',
                'username.unique' => 'Username ini sudah dipakai akun lain.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }

        $ketuaLama = optional(Ekskul::with('ketua')->find($request->id_ekskul))->ketua;

        DB::transaction(function () use ($request, $siswa, $butuhAkun) {
            // 1) Akun login (role Ketua) dibuat otomatis dari data siswa yang dipilih.
            if ($butuhAkun) {
                $user = User::create([
                    'name' => $siswa->nama_siswa,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 'Ketua',
                ]);

                $siswa->forceFill(['id_user' => $user->id])->save();

                // Pengaman: kalau tautan akun -> siswa tidak tersimpan, batalkan semuanya
                // (jangan sampai ada akun Ketua yatim yang tidak bisa memakai menu apa pun).
                if ((int) Siswa::where('id_siswa', $siswa->id_siswa)->value('id_user') !== (int) $user->id) {
                    throw new \RuntimeException('Akun login gagal dihubungkan ke data siswa.');
                }
            }

            // 2) Kalau siswa belum tercatat sebagai anggota ekskul ini, otomatis didaftarkan.
            Peserta::firstOrCreate(
                ['id_siswa' => $siswa->id_siswa, 'id_ekskul' => $request->id_ekskul],
                ['tanggal_bergabung' => now()->toDateString(), 'status' => 'aktif']
            );

            // 3) Jadikan siswa ini Ketua di ekskul pilihan (menggantikan ketua lama kalau ada).
            Ekskul::where('id_ekskul', $request->id_ekskul)
                ->update(['id_ketua' => $siswa->id_siswa]);
        });

        return redirect()->route('pembina.ketua.index')
            ->with('success', "{$siswa->nama_siswa} berhasil dijadikan ketua."
                . ($ketuaLama && $ketuaLama->id_siswa !== $siswa->id_siswa
                    ? " Ketua sebelumnya ({$ketuaLama->nama_siswa}) tidak lagi memimpin ekskul ini."
                    : ''));
    }

    public function edit(int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $siswa = Siswa::with(['user', 'kelas'])
            ->whereHas('ekskulDipimpin', function ($q) use ($ekskulIds) {
                $q->whereIn('id_ekskul', $ekskulIds);
            })
            ->findOrFail($id);

        $ekskuls = $pembina->ekskuls()->with('ketua')->orderBy('nama_ekskul')->get();
        $kelas = Kelas::orderBy('tingkat')->orderBy('program_keahlian')->orderBy('rombel')->get();

        $ekskulSaatIni = Ekskul::whereIn('id_ekskul', $ekskulIds)
            ->where('id_ketua', $siswa->id_siswa)
            ->value('id_ekskul');

        return view('pembina.ketua.edit', compact('siswa', 'ekskuls', 'kelas', 'ekskulSaatIni'));
    }

    public function update(Request $request, int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $siswa = Siswa::with('user')
            ->whereHas('ekskulDipimpin', function ($q) use ($ekskulIds) {
                $q->whereIn('id_ekskul', $ekskulIds);
            })
            ->findOrFail($id);

        $user = $siswa->user;

        $validator = Validator::make($request->all(), [
            'id_ekskul' => ['required', Rule::in($ekskulIds)],
            'NISN' => 'required|string|max:20|unique:siswa,NISN,' . $siswa->id_siswa . ',id_siswa',
            'NIS' => 'required|string|max:20|unique:siswa,NIS,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required|string|max:100',
            'jk' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'nomor_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:100|unique:users,email,' . optional($user)->id,
            'medsos' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . optional($user)->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'id_ekskul.required' => 'Pilih ekskul untuk ketua ini.',
            'id_ekskul.in' => 'Ekskul tersebut bukan ekskul yang kamu bina.',
            'NISN.unique' => 'NISN ini sudah terdaftar.',
            'NIS.unique' => 'NIS ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'username.unique' => 'Username ini sudah dipakai akun lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $siswa, $user, $ekskulIds) {
            $siswa->update($request->only([
                'id_kelas', 'NISN', 'NIS', 'nama_siswa', 'jk', 'agama',
                'nomor_hp', 'email', 'medsos', 'alamat',
            ]));

            if ($user) {
                $userData = [
                    'name' => $request->nama_siswa,
                    'email' => $request->email,
                    'username' => $request->username,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);
            }

            // Lepaskan dari ekskul lama milik pembina ini kalau ekskul yang dipimpin berubah.
            Ekskul::whereIn('id_ekskul', $ekskulIds)
                ->where('id_ketua', $siswa->id_siswa)
                ->where('id_ekskul', '!=', $request->id_ekskul)
                ->update(['id_ketua' => null]);

            // Pasang ke ekskul pilihan (gantikan ketua lama ekskul itu kalau ada).
            Ekskul::where('id_ekskul', $request->id_ekskul)
                ->update(['id_ketua' => $siswa->id_siswa]);
        });

        return redirect()->route('pembina.ketua.index')
            ->with('success', 'Data ketua berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $pembina = $this->pembinaOrFail();
        $ekskulIds = $this->ekskulIds($pembina);

        $siswa = Siswa::with('user')
            ->whereHas('ekskulDipimpin', function ($q) use ($ekskulIds) {
                $q->whereIn('id_ekskul', $ekskulIds);
            })
            ->findOrFail($id);

        DB::transaction(function () use ($siswa, $ekskulIds) {
            // Lepaskan status ketua dari ekskul milik pembina ini.
            Ekskul::whereIn('id_ekskul', $ekskulIds)
                ->where('id_ketua', $siswa->id_siswa)
                ->update(['id_ketua' => null]);

            // Hapus akun login-nya (role Ketua). Biodata siswa TETAP disimpan --
            // FK id_user di tabel siswa otomatis jadi NULL (ON DELETE SET NULL).
            if ($siswa->user) {
                $siswa->user->delete();
            }
        });

        return redirect()->route('pembina.ketua.index')
            ->with('success', 'Ketua dilepas dari ekskul kamu. Akun login-nya dihapus, biodata siswa tetap tersimpan.');
    }
}