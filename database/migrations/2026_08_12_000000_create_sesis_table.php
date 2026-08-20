<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->constrained('pelatih', 'id_pelatih')->onDelete('cascade');
            $table->string('nama_sesi');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesis');
    }
};