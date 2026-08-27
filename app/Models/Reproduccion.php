<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reproduccion extends Model
{
    protected $table = 'reproducciones'; 

    protected $fillable = [
        'animal_id', 'user_id', 'fecha_servicio', 'metodo', 
        'toro_semen', 'estado', 'fecha_parto_estimada'
    ];

    public function animal() {
        return $this->belongsTo(Animal::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}