<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoCuota extends Model
{
    protected $table = 'estados_cuotas';
    public $timestamps = false;

    public function socios():HasMany
    {
        return $this->hasMany(Socio::class, 'estado_cuota');
    }
}
