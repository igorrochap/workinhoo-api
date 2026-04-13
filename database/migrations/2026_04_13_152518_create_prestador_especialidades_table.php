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
        Schema::create('prestador_especialidades', function (Blueprint $table) {
            $table->foreignId('prestador_id')->constrained('prestadores')->cascadeOnDelete();
            $table->foreignId('especialidade_id')->constrained('especialidades')->cascadeOnDelete();
            $table->json('subcategorias')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestador_especialidades');
    }
};
