<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom ini menjawab pertanyaan "siswa mana yang jadi Ketua ekskul ini?" --
     * sebelumnya tidak ada kolom ini sama sekali, jadi semua fitur sisi Ketua
     * (kelola anggota, absensi peserta) terpaksa hardcode id_ekskul = 1.
     */
    public function up(): void
    {
        Schema::table('ekskuls', function (Blueprint $table) {
            $table->foreignId('id_ketua')
                  ->nullable()
                  ->after('id_pelatih')
                  ->constrained('siswa', 'id_siswa')
                  ->onDelete('set null');

            // Satu siswa idealnya cuma jadi ketua di SATU ekskul.
            // Unique di kolom nullable tetap boleh banyak NULL, cuma nilai non-NULL yang harus unik.
            $table->unique('id_ketua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ekskuls', function (Blueprint $table) {
            $table->dropUnique(['id_ketua']);
            $table->dropForeign(['id_ketua']);
            $table->dropColumn('id_ketua');
        });
    }
};