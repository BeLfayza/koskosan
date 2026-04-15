<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            $table->string('foto_ktp')->nullable()->after('nik');
            $table->string('foto_kk')->nullable()->after('foto_ktp');
            $table->string('foto_diri')->nullable()->after('foto_kk');
        });
    }

    public function down(): void
    {
        Schema::table('penghunis', function (Blueprint $table) {
            $table->dropColumn(['foto_ktp', 'foto_kk', 'foto_diri']);
        });
    }
};
