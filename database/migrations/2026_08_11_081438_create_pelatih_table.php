<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatih', function (Blueprint $table) {
            $table->id('id_pelatih');
            $table->string('nama_pelatih', 100);
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('nomor_hp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('medsos', 100)->nullable();
            $table->string('sertifikat_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatih');
    }
};