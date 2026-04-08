<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_banjir', function (Blueprint $table) {
            $table->string('foto2')->nullable()->after('foto');
            $table->string('foto3')->nullable()->after('foto2');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_banjir', function (Blueprint $table) {
            $table->dropColumn(['foto2', 'foto3']);
        });
    }
};
