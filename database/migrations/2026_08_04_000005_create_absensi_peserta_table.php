<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->foreignId('sesi_id')->constrained('sesis')->cascadeOnDelete();
            $table->time('jam_hadir')->nullable();
            $table->enum('status', ['Hadir', 'Tidak Hadir', 'Terlambat', 'Izin'])
                ->default('Tidak Hadir');
            $table->timestamps();

            $table->unique(['peserta_id', 'sesi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_peserta');
    }
};