<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autores';
    protected $fillable = [
        'nombre',
        'fecha_nacimiento',
        'es_activo'
    ];
    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'autor_libro');
    }
}
