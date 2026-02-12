<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $fillable = [
        'dni', 
        'nombre', 
        'biblioteca_id', 
        'email', 
        'telefono', 
        'estado_cuota', 
        'es_activo'
    ];
    public function estado()
    {
        return $this->belongsTo(EstadoCuota::class, 'estado_cuota');
    }
    public function biblioteca()
    {
        return $this->belongsTo(Biblioteca::class, 'biblioteca_id');
    }
}
