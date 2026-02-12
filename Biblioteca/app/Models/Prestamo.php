<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
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
		return $this->belongsTo(Socio::class, 'socio');
	}
    	public function libros()
	{
		return $this->belongsTo(Libro::class, 'ISBN');
	}
}
