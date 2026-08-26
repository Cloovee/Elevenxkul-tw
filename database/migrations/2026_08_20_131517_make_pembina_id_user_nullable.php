<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembina', function (Blueprint $table) {
            // Drop constraint lama (NOT NULL + CASCADE) dulu
            $table->dropForeign('pembina_id_user_foreign');
        });

        Schema::table('pembina', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->change();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pembina', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('pembina', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable(false)->change();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }
};