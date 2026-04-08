<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('laporan_banjir', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pelapor');
        $table->string('no_telp')->nullable();
        $table->decimal('latitude', 10, 8);
        $table->decimal('longitude', 11, 8);
        $table->string('kecamatan');
        $table->string('desa')->nullable();
        $table->text('deskripsi');
        $table->string('foto')->nullable();
        $table->decimal('kedalaman_cm', 5, 2)->nullable();
        $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
        $table->timestamp('waktu_laporan')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_banjir');
    }
};
