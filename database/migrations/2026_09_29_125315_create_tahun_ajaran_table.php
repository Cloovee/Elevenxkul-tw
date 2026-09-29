<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id('id_tahun_ajaran');
            $table->string('tahun_ajaran', 20)->unique(); // contoh: "2026/2027"
            $table->enum('status', ['aktif', 'selesai'])->default('selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};