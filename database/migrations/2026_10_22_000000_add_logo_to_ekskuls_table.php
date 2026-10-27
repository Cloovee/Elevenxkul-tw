<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Logo ekskul: dipakai di kop surat laporan cetak (sisi kanan, berpasangan
     * dengan logo SMKN 11 di sisi kiri). Diunggah admin saat menambah/mengedit ekskul.
     */
    public function up(): void
    {
        if (Schema::hasColumn('ekskuls', 'logo')) {
            return;
        }

        Schema::table('ekskuls', function (Blueprint $table) {
            $table->string('logo', 255)->nullable()->after('poster');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('ekskuls', 'logo')) {
            return;
        }

        Schema::table('ekskuls', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
