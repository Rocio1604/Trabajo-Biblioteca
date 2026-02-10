<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class recibos extends Model
{
    protected $table = 'recibos';
        protected $fillable = [
        'id_recibo',
        'concepto',
        'tipo',
        'importe',
        'fecha'
    ];
    	public function socio()
	{
		return $this->belongsTo(socios::class, 'id_socio');
	}
}
