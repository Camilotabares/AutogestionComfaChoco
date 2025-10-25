<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $fillable = [
        'empleado_id',
        'tipo_permiso',
        'tipo_de_ausentismo',
        'fecha_inicio',
        'fecha_final',
        'soporte',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
