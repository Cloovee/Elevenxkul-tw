<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembina', function (Blueprint $table) {
            $table->id('id_pembina');
            $table->foreignId('id_user')->unique()->constrained('users', 'id')->onDelete('cascade');
            $table->string('nama_pembina', 100);
            $table->enum('jk', ['L', 'P'])->nullable();
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
        Schema::dropIfExists('pembina');
    }
};