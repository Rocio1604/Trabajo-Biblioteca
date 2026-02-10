<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class socios extends Model
{
    protected $table = 'socios';
    
        protected $fillable = [
        'id_socio',
        'dni',
        'nombre',
        'email',
        'telefono'
    ];
}
