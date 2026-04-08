<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_banjir', function (Blueprint $table) {
            $table->softDeletes(); // Tambah kolom deleted_at
        });
    }

    public function down()
    {
        Schema::table('laporan_banjir', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
