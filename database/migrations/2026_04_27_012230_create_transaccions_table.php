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
    Schema::create('transaccions', function (Blueprint $table) {
        $table->id();
        $table->enum('tipo', ['ingreso', 'gasto']);
        $table->string('categoria'); // Leche, Venta Ganado, Alimento, Veterinaria, Nómina, Otros
        $table->decimal('monto', 10, 2);
        $table->date('fecha');
        $table->string('descripcion');
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};