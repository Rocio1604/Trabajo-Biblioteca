<?php

namespace App\Models;
use App\Models\Recibo;
use Illuminate\Database\Eloquent\Model;
class EstadoRecibo extends Model
{
    protected $table = 'estados_recibos';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'estado_id');
    }
}