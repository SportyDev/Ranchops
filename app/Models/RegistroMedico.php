<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroMedico extends Model
{
    protected $table = 'registros_medicos';

    protected $fillable = [
        'animal_id', 'lote_nombre', 'user_id', 'fecha', 
        'categoria', 'diagnostico_tratamiento', 'estado', 
        'veterinario', 'costo'
    ];

    public function animal() {
        return $this->belongsTo(Animal::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}