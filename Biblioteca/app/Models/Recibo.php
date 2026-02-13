<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';
    
    protected $fillable = [
        'socio_id',
        'concepto',
        'tipo_id',
        'importe',
        'fecha',
        'estado_id',
        'es_activo'
    ];

    protected $casts = [
        'fecha' => 'date',
        'importe' => 'decimal:2',
        'es_activo' => 'boolean'
    ];
     protected $appends = ['numero_recibo'];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoRecibo::class, 'tipo_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoRecibo::class, 'estado_id');
    }

    public function biblioteca()
{
    return $this->belongsTo(Biblioteca::class);
}
    public function getNumeroReciboAttribute()
    {
        return 'REC-' . date('Y', strtotime($this->fecha)) . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}