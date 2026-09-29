<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Histori penempatan kelas siswa per Tahun Ajaran.
     *
     * Diisi otomatis oleh proses Finalisasi Perubahan Tahun Ajaran (modul
     * Tahun Ajaran), supaya siswa.id_kelas tetap "kondisi sekarang" tapi
     * riwayat kelas/status per tahun ajaran tidak hilang.
     */
    public function up(): void
    {
        Schema::create('riwayat_kelas_siswa', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran', 'id_tahun_ajaran')->onDelete('cascade');
            // Kelas siswa PADA tahun ajaran tsb. Nullable karena Lulus/Keluar tidak punya kelas aktif.
            $table->foreignId('id_kelas')->nullable()->constrained('kelas', 'id_kelas')->onDelete('set null');
            // Snapshot status siswa saat itu (aktif / alumni / tidak_aktif) & keputusan yang dipilih Admin.
            $table->enum('status', ['aktif', 'alumni', 'tidak_aktif']);
            $table->enum('keputusan', ['naik_kelas', 'tidak_naik', 'lulus', 'keluar']);
            $table->timestamps();

            // Satu siswa hanya punya satu baris riwayat per Tahun Ajaran.
            $table->unique(['id_siswa', 'id_tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas_siswa');
    }
};