<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->foreignId('id_anggota')->constrained('data_anggota', 'id_anggota')->onDelete('cascade');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('tahun_ajaran', 20)->nullable();
            $table->enum('semester', ['1', '2'])->nullable();
            $table->text('catatan_pembina')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};