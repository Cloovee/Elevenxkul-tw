<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Foto profil pelatih: diunggah pembina saat menambah/mengedit pelatih,
     * lalu ditampilkan di halaman Absensi Pelatih milik Ketua.
     */
    public function up(): void
    {
        if (Schema::hasColumn('pelatih', 'foto')) {
            return;
        }

        Schema::table('pelatih', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->after('nama_pelatih');
        });
    }

    public function down(): void
    {
        Schema::table('pelatih', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};