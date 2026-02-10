<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class libros_autor extends Model
{
    protected $table = 'libros_autor';

        protected $fillable = [];

	public function libros()
	{
		return $this->belongsTo(libros::class, 'ISBN');
	}

	public function autor()
	{
		return $this->belongsTo(autores::class, 'id_autor');
	}
}
