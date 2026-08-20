<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembina', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->after('nama_pembina');
        });
    }

    public function down(): void
    {
        Schema::table('pembina', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
