<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_pelatih', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_pelatih')->constrained('pelatih', 'id_pelatih')->onDelete('cascade');
            $table->date('tanggal_absensi');
            $table->string('kegiatan', 100)->nullable();
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->string('foto_kehadiran', 255)->nullable();
            $table->enum('status_validasi', ['Menunggu', 'Divalidasi', 'Ditolak'])->default('Menunggu');
            $table->foreignId('id_pembina_validasi')->nullable()->constrained('pembina', 'id_pembina')->onDelete('set null');
            $table->datetime('tgl_validasi')->nullable();
            $table->text('catatan_validasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_pelatih');
    }
};