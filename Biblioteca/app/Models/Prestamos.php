<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamos extends Model
{
    protected $table = 'prestamos';

        protected $fillable = [
        'id_prestamo',
        'fecha_prestamo',
        'fecha_limite',
        'feha_devolucion'
    ];
    	public function socios()
	{
		return $this->belongsTo(autores::class, 'socio');
	}
    	public function libros()
	{
		return $this->belongsTo(autores::class, 'ISBN');
	}
}
