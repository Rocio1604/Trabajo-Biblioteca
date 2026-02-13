<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidacionSistema extends Model
{
    protected $table = 'validaciones_sistema';

    protected $fillable = [
        'referencia_id',
        'firma_digital'
    ];

    public function usuario():BelongsTo
    {
        return $this->belongsTo(User::class, 'referencia_id');
    }
}
