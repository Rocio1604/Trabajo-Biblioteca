<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coche extends Model
{
    protected $table = 'cochees';
        protected $fillable = [
        'pass'
    ];
    	public function usuarios()
	{
		return $this->belongsTo(autor::class, 'id_usuario');
	}
}
