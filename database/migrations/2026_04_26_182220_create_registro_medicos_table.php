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
    Schema::create('registros_medicos', function (Blueprint $table) {
        $table->id();
        // Si es para una vaca específica:
        $table->foreignId('animal_id')->nullable()->constrained('animals')->onDelete('cascade');
        // Si es para un lote (ej. Lote de becerros):
        $table->string('lote_nombre')->nullable(); 
        
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->date('fecha');
        $table->enum('categoria', ['enfermedad', 'vacuna', 'revision']);
        $table->string('diagnostico_tratamiento');
        $table->enum('estado', ['activo', 'programado', 'completado']);
        $table->string('veterinario')->nullable();
        $table->decimal('costo', 10, 2)->default(0); // Para sumar en el Gasto Mensual
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_medicos');
    }
};