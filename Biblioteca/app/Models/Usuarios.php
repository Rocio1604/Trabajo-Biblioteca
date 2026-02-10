<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'usuarios';
        protected $fillable = [
        'id_usuario',
        'nombre',
        'correo',
        'telefono',
        'rol',
        'biblioteca',
        'estado'
    ];
}
