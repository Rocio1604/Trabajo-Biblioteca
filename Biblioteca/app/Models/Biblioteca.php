<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Biblioteca extends Model
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
        return $this->hasMany(Prestamo::class, 'biblioteca_id');
    }
    public function trabajadores() {
        return $this->hasMany(Usuario::class, 'biblioteca_id');
    }

    public function ejemplares() {
        return $this->hasMany(Ejemplare::class, 'biblioteca_id');
    }

    public function socios() {
        return $this->hasMany(Socio::class, 'biblioteca_id');
    }
}
