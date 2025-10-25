<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVacacion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_vacaciones';

    protected $fillable = [
        'cedula',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'dias_habiles',
        // 'autorizador', removed
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}
