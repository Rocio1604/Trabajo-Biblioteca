<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class bibliotecas extends Model
{
    protected $table = 'bibliotecas';
    protected $fillable = [
        'id_biblioteca',
        'nombre',
        'provincia',
        'direccion',
        'telefono',
        'correo',
        'es_activo'
    ];
    public function prestamos()
    {
        return $this->hasMany(Prestamos::class, 'biblioteca_id');
    }
}
