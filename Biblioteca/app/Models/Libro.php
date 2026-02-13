<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
        protected $fillable = [
        'isbn',
        'titulo',
        'estado',
        'categoria_id',
        'precio',
        'es_activo'
    ];
    public function ejemplares()
    {
        return $this->hasMany(Ejemplare::class, 'libro_id');
    }
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'autor_libro', 'libro_id', 'autor_id');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
