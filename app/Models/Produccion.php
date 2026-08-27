<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'producciones';

    protected $fillable = ['animal_id', 'user_id', 'litros', 'turno', 'fecha_registro'];

    // Relación: Una producción pertenece a una vaca
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Relación: Una producción fue registrada por un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
