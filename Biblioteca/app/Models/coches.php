<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class coches extends Model
{
    protected $table = 'cochees';
        protected $fillable = [
        'pass'
    ];
    	public function usuarios()
	{
		return $this->belongsTo(autores::class, 'id_usuario');
	}
}
