<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->foreignId('id_kelas')->nullable()->constrained('kelas', 'id_kelas')->onDelete('set null');
            $table->foreignId('id_user')->nullable()->unique()->constrained('users', 'id')->onDelete('set null');
            $table->string('NISN', 20)->unique();
            $table->string('NIS', 20)->unique();
            $table->string('nama_siswa', 100);
            $table->enum('jk', ['L', 'P']);
            $table->string('agama', 20)->nullable();
            $table->string('nomor_hp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('medsos', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};