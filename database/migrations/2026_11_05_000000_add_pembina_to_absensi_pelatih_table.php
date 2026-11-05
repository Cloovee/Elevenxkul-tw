<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ekskul tanpa pelatih: foto/absensi kegiatan dikirim oleh pembina sebagai pengganti pelatih.
 *
 *  - id_pelatih dibuat nullable (baris milik pembina tidak punya pelatih).
 *  - id_pembina  : pembina yang mengunggah (diisi hanya bila ekskul tidak punya pelatih).
 *  - id_ekskul   : ekskul tempat kegiatan, supaya riwayat/laporan baris milik pembina
 *                  tidak tercampur antar-ekskul (satu pembina bisa menaungi banyak ekskul).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_pelatih', function (Blueprint $table) {
            $table->dropForeign(['id_pelatih']);
        });

        Schema::table('absensi_pelatih', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelatih')->nullable()->change();
            $table->foreign('id_pelatih')->references('id_pelatih')->on('pelatih')->onDelete('cascade');

            $table->foreignId('id_pembina')->nullable()->after('id_pelatih')
                ->constrained('pembina', 'id_pembina')->onDelete('cascade');
            $table->foreignId('id_ekskul')->nullable()->after('id_pembina')
                ->constrained('ekskuls', 'id_ekskul')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Baris milik pembina tidak punya id_pelatih, jadi dihapus sebelum kolom dikembalikan NOT NULL.
        \DB::table('absensi_pelatih')->whereNull('id_pelatih')->delete();

        Schema::table('absensi_pelatih', function (Blueprint $table) {
            $table->dropForeign(['id_ekskul']);
            $table->dropColumn('id_ekskul');
            $table->dropForeign(['id_pembina']);
            $table->dropColumn('id_pembina');
            $table->dropForeign(['id_pelatih']);
        });

        Schema::table('absensi_pelatih', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelatih')->nullable(false)->change();
            $table->foreign('id_pelatih')->references('id_pelatih')->on('pelatih')->onDelete('cascade');
        });
    }
};
