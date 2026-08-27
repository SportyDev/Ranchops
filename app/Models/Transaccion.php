<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $fillable = ['tipo', 'categoria', 'monto', 'fecha', 'descripcion', 'user_id'];

    // Para saber qué usuario registró el movimiento
    public function user() {
        return $this->belongsTo(User::class);
    }
}