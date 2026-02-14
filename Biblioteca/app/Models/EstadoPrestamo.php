<?php

namespace App\Models;
use App\Models\Socio;
use Illuminate\Database\Eloquent\Model;

class EstadoPrestamo extends Model
{
    protected $table = 'estados_prestamos';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nombre'
    ];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'estado_id');
    }
}