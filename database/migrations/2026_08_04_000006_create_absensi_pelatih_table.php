<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_pelatih', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->constrained('pelatihs')->cascadeOnDelete();
            $table->foreignId('sesi_id')->constrained('sesis')->cascadeOnDelete();
            $table->time('jam_lapor')->nullable();
            $table->enum('status', ['Menunggu', 'Divalidasi', 'Ditolak'])
                ->default('Menunggu');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('divalidasi_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_pelatih');
    }
};