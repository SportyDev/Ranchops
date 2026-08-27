<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = ['arete', 'foto', 'nombre', 'raza', 'sexo', 'fecha_nacimiento', 'estado'];
}
