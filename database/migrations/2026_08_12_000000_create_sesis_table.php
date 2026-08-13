// database/migrations/2026_08_12_000000_create_sesis_table.php
Schema::create('sesis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pelatih_id')->constrained('pelatih')->onDelete('cascade');
    $table->string('nama_sesi');
    $table->date('tanggal');
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->timestamps();
});