<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Penilaian berbasis grade (A/B/C) dari kehadiran + keaktifan.
 *
 *  - nilai_keaktifan : angka keaktifan yang diisi pembina (min. 70, maks. 100)
 *  - jumlah_hadir, jumlah_pertemuan, persen_kehadiran : rekap absensi saat disimpan
 *  - grade_kehadiran / grade_keaktifan / grade_akhir  : hasil konversi
 *
 * Kolom lama `nilai` tetap ada (diisi sama dengan nilai_keaktifan) supaya data
 * lama tidak hilang. Nilai lama dibawa ke nilai_keaktifan; grade kehadiran dan
 * grade akhirnya terisi saat pembina menyimpan ulang nilai tsb.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('penilaian', 'nilai_keaktifan')) {
            Schema::table('penilaian', function (Blueprint $table) {
                $table->decimal('nilai_keaktifan', 5, 2)->nullable()->after('nilai');
                $table->unsignedSmallInteger('jumlah_hadir')->nullable()->after('nilai_keaktifan');
                $table->unsignedSmallInteger('jumlah_pertemuan')->nullable()->after('jumlah_hadir');
                $table->decimal('persen_kehadiran', 5, 2)->nullable()->after('jumlah_pertemuan');
                $table->char('grade_kehadiran', 1)->nullable()->after('persen_kehadiran');
                $table->char('grade_keaktifan', 1)->nullable()->after('grade_kehadiran');
                $table->char('grade_akhir', 1)->nullable()->after('grade_keaktifan');
            });
        }

        // Bawa nilai lama ke keaktifan + tentukan grade keaktifannya.
        DB::table('penilaian')->whereNotNull('nilai')->whereNull('nilai_keaktifan')
            ->update(['nilai_keaktifan' => DB::raw('nilai')]);

        DB::table('penilaian')->whereNotNull('nilai_keaktifan')->whereNull('grade_keaktifan')
            ->where('nilai_keaktifan', '>=', 90)->update(['grade_keaktifan' => 'A']);
        DB::table('penilaian')->whereNotNull('nilai_keaktifan')->whereNull('grade_keaktifan')
            ->where('nilai_keaktifan', '>=', 80)->update(['grade_keaktifan' => 'B']);
        DB::table('penilaian')->whereNotNull('nilai_keaktifan')->whereNull('grade_keaktifan')
            ->update(['grade_keaktifan' => 'C']);
    }

    public function down(): void
    {
        $kolom = ['grade_akhir', 'grade_keaktifan', 'grade_kehadiran', 'persen_kehadiran', 'jumlah_pertemuan', 'jumlah_hadir', 'nilai_keaktifan'];

        Schema::table('penilaian', function (Blueprint $table) use ($kolom) {
            foreach ($kolom as $k) {
                if (Schema::hasColumn('penilaian', $k)) {
                    $table->dropColumn($k);
                }
            }
        });
    }
};
