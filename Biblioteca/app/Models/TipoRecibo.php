<?php

namespace App\Models;
use App\Models\Recibo;
use Illuminate\Database\Eloquent\Model;

class TipoRecibo extends Model
{
    protected $table = 'tipos_recibos';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'tipo_id');
    }
}