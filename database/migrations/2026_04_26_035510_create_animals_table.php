<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('arete')->unique(); // Ej: #SF-2023-089
            $table->string('foto')->nullable();
            $table->string('nombre')->nullable(); // Ej: Bella
            $table->string('raza'); // Ej: Holstein
            $table->string('sexo')->default('hembra');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('estado'); // lactante, gestante, seca, etc.
            $table->timestamps(); // Crea fechas de registro automáticamente
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};