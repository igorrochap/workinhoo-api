<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestador_bairros', function (Blueprint $table) {
            $table->foreignId('prestador_id')->constrained('prestadores')->cascadeOnDelete();
            $table->foreignId('bairro_id')->constrained('bairros')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestador_bairros');
    }
};
