<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
     protected $table = 'categorias';
      protected $fillable = [
        'id',
        'categoria'
    ];
}
