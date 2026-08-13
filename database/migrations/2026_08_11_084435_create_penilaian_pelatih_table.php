<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_pelatih', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->foreignId('id_pelatih')->constrained('pelatih', 'id_pelatih')->onDelete('cascade');
            $table->foreignId('id_pembina')->constrained('pembina', 'id_pembina')->onDelete('cascade');
            $table->foreignId('id_ekskul')->constrained('ekskuls', 'id_ekskul')->onDelete('cascade');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->enum('semester', ['1', '2'])->nullable();
            $table->text('catatan')->nullable();
            $table->datetime('tanggal_nilai')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_pelatih');
    }
};