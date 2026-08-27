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
    Schema::create('reproducciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->date('fecha_servicio');
        $table->string('metodo'); // 'Inseminación Artificial' o 'Monta Natural'
        $table->string('toro_semen')->nullable(); // Identificador del Toro o pajilla
        $table->enum('estado', ['gestante', 'observacion', 'vacia'])->default('observacion');
        $table->date('fecha_parto_estimada')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reproduccions');
    }
};