<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'metodo_id');
    }
}
