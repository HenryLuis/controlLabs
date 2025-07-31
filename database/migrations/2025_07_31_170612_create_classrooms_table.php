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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ej: "Aula 301", "Laboratorio de Redes"
            $table->text('description')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
            $table->softDeletes(); // ¡Excelente que ya lo usen por defecto!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
