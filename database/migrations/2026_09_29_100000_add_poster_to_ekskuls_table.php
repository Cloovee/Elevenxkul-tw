<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Poster ekskul: gambar potret yang tampil di carousel landing page.
     * Diunggah admin saat menambah/mengedit ekskul.
     */
    public function up(): void
    {
        if (Schema::hasColumn('ekskuls', 'poster')) {
            return;
        }

        Schema::table('ekskuls', function (Blueprint $table) {
            $table->string('poster', 255)->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('ekskuls', function (Blueprint $table) {
            $table->dropColumn('poster');
        });
    }
};
