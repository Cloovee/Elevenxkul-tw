<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_peserta', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_anggota')->constrained('data_anggota', 'id_anggota')->onDelete('cascade');
            $table->date('tanggal_absensi');
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_peserta');
    }
};