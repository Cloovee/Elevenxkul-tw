<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Galeri foto kegiatan ekskul yang tampil di landing page.
     * Diisi & diatur oleh Admin lewat menu "Kelola Galeri".
     */
    public function up(): void
    {
        // Kalau tabel sudah ada (mis. dibuat dari migration lain), lewati saja.
        if (Schema::hasTable('galeri_ekskuls')) {
            return;
        }

        Schema::create('galeri_ekskuls', function (Blueprint $table) {
            $table->id('id_galeri');
            $table->foreignId('id_ekskul')
                  ->nullable()
                  ->constrained('ekskuls', 'id_ekskul')
                  ->onDelete('set null');
            $table->string('judul', 120);
            $table->text('keterangan')->nullable();
            $table->string('foto', 255);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_ekskuls');
    }
};