<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
        $table->id('id_siswa'); 
        $table->string('nisn')->unique(); 
        $table->string('nis')->unique(); 
        $table->string('nama_siswa');
        $table->enum('jk', ['L', 'P']);
        $table->string('agama')->nullable();
        $table->string('nomor_hp')->nullable();
        $table->string('email')->nullable();
        $table->string('medsos')->nullable();
        $table->text('alamat')->nullable();
        $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};