<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_ekskul')->constrained('ekskuls', 'id_ekskul')->onDelete('cascade');
            $table->date('tanggal_bergabung');
            $table->enum('status', ['aktif', 'nonaktif', 'keluar'])->default('aktif');
            $table->timestamps();

            $table->unique(['id_siswa', 'id_ekskul']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_anggota');
    }
};