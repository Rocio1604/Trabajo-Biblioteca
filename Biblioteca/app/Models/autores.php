<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class autores extends Model
{
    protected $table = 'autores';
    protected $fillable = [
        'id_autor',
        'nombre',
        'fecha_nacimiento'
    ];
}
