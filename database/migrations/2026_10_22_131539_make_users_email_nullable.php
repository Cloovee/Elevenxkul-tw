<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Akun yang dibuat admin (Pembina / Ketua / Admin) sekarang login pakai
     * USERNAME saja, tanpa email. Karena itu kolom users.email tidak boleh
     * lagi wajib diisi. Index unique-nya tetap (MySQL membolehkan banyak NULL).
     * Akun lama tetap bisa login dengan email seperti biasa.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Catatan: rollback gagal kalau sudah ada akun dengan email NULL.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};