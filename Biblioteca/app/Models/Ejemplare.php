<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ejemplare extends Model
{
    protected $fillable = [
        'libro_id',
        'biblioteca_id',
        'estado_id',
        'disponibilidad_id',
        'es_activo'
    ];
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }
    public function biblioteca()
    {
        return $this->belongsTo(Biblioteca::class, 'biblioteca_id');
    }
    public function estado()
    {
        return $this->belongsTo(EstadoLibro::class, 'estado_id');
    }
    public function disponibilidad()
    {
        return $this->belongsTo(DisponibilidadLibro::class, 'disponibilidad_id');
    }
}
