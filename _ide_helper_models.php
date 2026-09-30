<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id_absensi
 * @property int $id_pelatih
 * @property \Illuminate\Support\Carbon $tanggal_absensi
 * @property string|null $kegiatan
 * @property string $status_kehadiran
 * @property string|null $foto_kehadiran
 * @property string $status_validasi
 * @property int|null $id_pembina_validasi
 * @property \Illuminate\Support\Carbon|null $tgl_validasi
 * @property string|null $catatan_validasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pelatih $pelatih
 * @property-read \App\Models\Pembina|null $validator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereCatatanValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereFotoKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereIdAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereIdPelatih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereIdPembinaValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereStatusKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereStatusValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereTanggalAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereTglValidasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPelatih whereUpdatedAt($value)
 */
	class AbsensiPelatih extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_absensi
 * @property int $id_anggota
 * @property \Illuminate\Support\Carbon $tanggal_absensi
 * @property string $status_kehadiran
 * @property string|null $catatan
 * @property string|null $deskripsi_kegiatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Peserta $peserta
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereDeskripsiKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereIdAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereIdAnggota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereStatusKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereTanggalAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiPeserta whereUpdatedAt($value)
 */
	class AbsensiPeserta extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_ekskul
 * @property int|null $id_pembina
 * @property int|null $id_pelatih
 * @property int|null $id_ketua
 * @property string $nama_ekskul
 * @property string $kategori
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Siswa|null $ketua
 * @property-read \App\Models\Pelatih|null $pelatih
 * @property-read \App\Models\Pembina|null $pembina
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peserta> $peserta
 * @property-read int|null $peserta_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereIdEkskul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereIdKetua($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereIdPelatih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereIdPembina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereNamaEkskul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekskul whereUpdatedAt($value)
 */
	class Ekskul extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_kelas
 * @property string $tingkat
 * @property string $jurusan
 * @property string $rombel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $nama_kelas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswa
 * @property-read int|null $siswa_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereUpdatedAt($value)
 */
	class Kelas extends \Eloquent {}
}

namespace App\Models{
/**
 * Nilai peserta ekskul, disimpan di tabel `penilaian`.
 *
 * @property int $id_nilai
 * @property int $id_anggota
 * @property numeric|null $nilai
 * @property string|null $tahun_ajaran
 * @property string|null $semester
 * @property string|null $catatan_pembina
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Peserta $peserta
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereCatatanPembina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereIdAnggota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereIdNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NilaiPeserta whereUpdatedAt($value)
 */
	class NilaiPeserta extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pelatih
 * @property string $nama_pelatih
 * @property string|null $jk
 * @property string|null $agama
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property string|null $alamat
 * @property string|null $medsos
 * @property string|null $sertifikat_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiPelatih> $absensi
 * @property-read int|null $absensi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ekskul> $ekskuls
 * @property-read int|null $ekskuls_count
 * @property-read string $nama
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereIdPelatih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereMedsos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereNamaPelatih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereSertifikatPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelatih whereUpdatedAt($value)
 */
	class Pelatih extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pembina
 * @property int|null $id_user
 * @property string $nama_pembina
 * @property string|null $foto
 * @property string|null $jk
 * @property string|null $agama
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property string|null $medsos
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ekskul> $ekskuls
 * @property-read int|null $ekskuls_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereIdPembina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereMedsos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereNamaPembina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembina whereUpdatedAt($value)
 */
	class Pembina extends \Eloquent {}
}

namespace App\Models{
/**
 * Merepresentasikan "peserta" ekskul dari sisi pembina, yaitu satu baris
 * keanggotaan siswa pada sebuah ekskul (tabel data_anggota).
 *
 * @property int $id_anggota
 * @property int $id_siswa
 * @property int $id_ekskul
 * @property \Illuminate\Support\Carbon $tanggal_bergabung
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiPeserta> $absensi
 * @property-read int|null $absensi_count
 * @property-read \App\Models\Ekskul $ekskul
 * @property-read string|null $kelas
 * @property-read string $nama
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiPeserta> $nilai
 * @property-read int|null $nilai_count
 * @property-read \App\Models\Siswa $siswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereIdAnggota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereIdEkskul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereIdSiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereTanggalBergabung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peserta whereUpdatedAt($value)
 */
	class Peserta extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pelatih_id
 * @property string $nama_sesi
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiPelatih> $absensiPelatih
 * @property-read int|null $absensi_pelatih_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiPeserta> $absensiPeserta
 * @property-read int|null $absensi_peserta_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiPeserta> $nilai
 * @property-read int|null $nilai_count
 * @property-read \App\Models\Pelatih|null $pelatih
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereNamaSesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi wherePelatihId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sesi whereUpdatedAt($value)
 */
	class Sesi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_siswa
 * @property int|null $id_kelas
 * @property int|null $id_user
 * @property string $NISN
 * @property string $NIS
 * @property string $nama_siswa
 * @property string $jk
 * @property string|null $agama
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property string|null $medsos
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ekskul|null $ekskulDipimpin
 * @property-read mixed $nama_kelas
 * @property-read \App\Models\Kelas|null $kelas
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereIdSiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereMedsos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNIS($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNISN($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNamaSiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereUpdatedAt($value)
 */
	class Siswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Pembina|null $pembina
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

