<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ekskuls', function (Blueprint $table) {
            $table->id('id_ekskul');
            $table->foreignId('id_pembina')
                  ->nullable()
                  ->constrained('pembina', 'id_pembina')
                  ->onDelete('set null');
            $table->foreignId('id_pelatih')
                  ->nullable()
                  ->constrained('pelatih', 'id_pelatih')
                  ->onDelete('set null');
            $table->string('nama_ekskul', 100);
            $table->enum('kategori', ['organisasi', 'ekstrakulikuler', 'komunitas']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekskuls');
    }
};