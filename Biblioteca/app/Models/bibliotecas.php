<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bibliotecas extends Model
{
    protected $table = 'bibliotecas';
    protected $fillable = [
        'id_biblioteca',
        'provincia',
        'direccion',
        'telefono',
        'correo'
    ];
}
