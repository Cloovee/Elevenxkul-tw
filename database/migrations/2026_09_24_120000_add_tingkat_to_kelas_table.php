<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan: migration lama "create_kelas_table" sudah tercatat status
     * "Ran" di database dengan isi versi LAMA (cuma jurusan + rombel, tanpa
     * kolom `tingkat`), padahal file migration itu sekarang sudah direvisi
     * jadi menyertakan `tingkat`. Karena Laravel tidak menjalankan ulang
     * migration yang sudah "Ran", kolom `tingkat` nyatanya tidak pernah
     * benar-benar dibuat di database -> makanya error "Unknown column
     * 'tingkat'".
     *
     * Migration BARU ini menambahkan kolom yang kurang tanpa menyentuh data
     * `jurusan`/`rombel` yang sudah ada di tabel `kelas`.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('kelas', 'tingkat')) {
            Schema::table('kelas', function (Blueprint $table) {
                // Nullable dulu -- baris kelas yang sudah ada otomatis tingkat-nya
                // NULL, nanti tinggal diisi lewat menu Admin > Kelas > Edit.
                $table->string('tingkat', 10)->nullable()->after('id_kelas');
            });
        }

        // Tambahkan unique constraint gabungan tingkat+jurusan+rombel (sesuai
        // migration aslinya) kalau belum ada. Dibungkus try/catch karena kalau
        // sebelumnya sudah ada unique index dengan nama lain di jurusan+rombel,
        // ini tidak boleh bikin migration gagal total.
        try {
            Schema::table('kelas', function (Blueprint $table) {
                $table->unique(['tingkat', 'jurusan', 'rombel'], 'kelas_tingkat_jurusan_rombel_unique');
            });
        } catch (\Throwable $e) {
            // Index sudah ada / bentrok dengan index lama -- aman diabaikan,
            // kolom `tingkat` tetap berhasil ditambahkan di atas.
        }
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            try {
                $table->dropUnique('kelas_tingkat_jurusan_rombel_unique');
            } catch (\Throwable $e) {
                //
            }

            if (Schema::hasColumn('kelas', 'tingkat')) {
                $table->dropColumn('tingkat');
            }
        });
    }
};
