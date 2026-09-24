<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom ini menjawab kebutuhan form "Tambah Ketua" di sisi Pembina:
     * |nisn|nis|nama|jk|agama|kelas|no. hp|email|medsos|username|password|
     *
     * `email` tetap dipakai sebagai kontak & syarat kolom users.email (unique,
     * not null). `username` ditambahkan sebagai identitas login terpisah,
     * supaya Ketua bisa login pakai username tanpa harus pakai email.
     *
     * Nullable & unique: akun Admin/Pembina yang sudah ada sebelumnya tetap
     * login pakai email seperti biasa (username-nya NULL), tidak ada yang perlu
     * di-backfill.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
