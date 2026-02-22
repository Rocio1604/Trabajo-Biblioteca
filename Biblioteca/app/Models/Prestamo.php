<?php

namespace App\Models;

use App\Models\Socio;
use App\Models\Biblioteca;
use App\Models\EstadoPrestamo;
use App\Models\Ejemplare;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestamo extends Model
{
    protected $table = 'prestamos';

    protected $fillable = [
        'socio_id',
        'ejemplar_id',
        'biblioteca_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'fecha_devolucion_real',
        'multa',
        'estado_id',
        'es_activo'
    ];

    protected $casts = [
        'fecha_prestamo'  => 'date:Y-m-d',
        'fecha_devolucion'=> 'date:Y-m-d',
        'fecha_devolucion_real'=> 'date:Y-m-d',
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
    public function recibos(): HasMany
    {
        return $this->hasMany(Recibo::class);
    }
    public static function actualizarAtrasados()
    {
        return self::where('estado_id', 1)
                ->where('fecha_devolucion', '<', now())
                ->update(['estado_id' => 3]);
    }
}