<?php

namespace App\Models;


use App\Models\EstadoCuota;
use App\Models\Biblioteca;
use Illuminate\Support\Carbon;
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
        'fecha_vencimiento',
        'es_activo'
    ];
    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function estado()
    {
        return $this->belongsTo(EstadoCuota::class, 'estado_cuota');
    }
    public function biblioteca()
    {
        return $this->belongsTo(Biblioteca::class, 'biblioteca_id');
    }
    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'socio_id');
    }
    public static function actualizarCuotasVencidas()
    {
        $sociosParaVencer = self::where('estado_cuota', 1)
                                ->where('fecha_vencimiento', '<', now())
                                ->get();

        foreach ($sociosParaVencer as $socio) {
            $socio->update(['estado_cuota' => 2]);
            Recibo::create([
                'socio_id' => $socio->id,
                'biblioteca_id' => $socio->biblioteca_id,
                'concepto' => 'Cuota anual '.Carbon::now()->year,
                'tipo_id' => 1,//suscripcion anual
                'importe' =>10,
                'fecha' => now()->format('Y-m-d'),
                'estado_id' => 2, //Pendiente
                'es_activo' => true
            ]);
        }
    }
}