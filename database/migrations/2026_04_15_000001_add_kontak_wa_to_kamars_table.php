<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('kontak_wa')->nullable()->after('harga_per_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn('kontak_wa');
        });
    }
};
