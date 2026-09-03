<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('tingkat', 10); // contoh: "X", "XI", "XII"
            $table->string('jurusan', 50);
            $table->string('rombel', 10);
            $table->timestamps();

            $table->unique(['tingkat', 'jurusan', 'rombel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};