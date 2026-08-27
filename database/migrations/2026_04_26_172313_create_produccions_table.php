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
        Schema::create('producciones', function (Blueprint $table) {
            $table->id();
            // Conectamos con la vaca y con el usuario que registró
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->decimal('litros', 5, 2); // Ej: 15.50
            $table->enum('turno', ['manana', 'tarde']);
            $table->dateTime('fecha_registro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccions');
    }
};
