<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur Berita: Pembina menulis berita/pengumuman untuk Ketua ekskul yang dibinanya.
 *
 *  - berita.id_ekskul : ekskul tujuan. Ketua hanya melihat berita ekskul yang dipimpinnya.
 *  - berita.penting   : ditandai "Penting" (tampil dengan label khusus di aplikasi).
 *  - users.berita_dilihat_at : kapan Ketua terakhir membuka halaman Berita di aplikasi.
 *    Berita yang dibuat setelah waktu ini dihitung "baru" (titik notifikasi di navbar).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('berita')) {
            Schema::create('berita', function (Blueprint $table) {
                $table->id('id_berita');
                $table->foreignId('id_ekskul')
                    ->constrained('ekskuls', 'id_ekskul')
                    ->onDelete('cascade');
                $table->foreignId('id_pembina')
                    ->nullable()
                    ->constrained('pembina', 'id_pembina')
                    ->onDelete('set null');
                $table->string('judul', 150);
                $table->text('isi');
                $table->boolean('penting')->default(false);
                $table->timestamps();

                $table->index(['id_ekskul', 'created_at']);
            });
        }

        if (! Schema::hasColumn('users', 'berita_dilihat_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('berita_dilihat_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'berita_dilihat_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('berita_dilihat_at');
            });
        }

        Schema::dropIfExists('berita');
    }
};
