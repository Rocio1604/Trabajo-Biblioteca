<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usuarios';
        protected $fillable = [
        'id',
        'nombre',
        'correo',
        'telefono',
        'rol_id',
        'biblioteca_id',
        'es_activo'
    ];

    public function rol():BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }
    public function validacion():HasOne
    {
        return $this->hasOne(ValidacionSistema::class, 'referencia_id');
    }
    public function biblioteca():BelongsTo
    {
        return $this->belongsTo(Biblioteca::class, 'biblioteca_id'); 
    }
}