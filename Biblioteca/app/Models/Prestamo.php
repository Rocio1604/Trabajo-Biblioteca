<?php

namespace App\Models;

use App\Models\Socio;
use App\Models\Biblioteca;
use App\Models\EstadoPrestamo;
use App\Models\Ejemplare;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';

    protected $fillable = [
        'socio_id',
        'ejemplar_id',
        'biblioteca_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'multa',
        'estado_id'
    ];

    protected $casts = [
        'fecha_prestamo'  => 'date',
        'fecha_devolucion'=> 'date',
        'multa'           => 'decimal:2'
    ];

    protected $appends = ['numero_prestamo'];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function ejemplar()
    {
        return $this->belongsTo(Ejemplare::class, 'ejemplar_id');
    }

    public function biblioteca()
    {
        return $this->belongsTo(Biblioteca::class, 'biblioteca_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPrestamo::class, 'estado_id');
    }

    public function getNumeroPrestamoAttribute()
    {
        return 'PRES-' . date('Y', strtotime($this->fecha_prestamo)) . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}