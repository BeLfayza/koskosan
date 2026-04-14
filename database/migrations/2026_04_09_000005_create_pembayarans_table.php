<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kamar_id')->constrained('kamars')->cascadeOnDelete();
            $table->date('periode_bulan');
            $table->decimal('nominal', 12, 2);
            $table->enum('status', ['lunas', 'tidak_lunas'])->default('tidak_lunas');
            $table->timestamps();
            $table->unique(['kamar_id', 'periode_bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
