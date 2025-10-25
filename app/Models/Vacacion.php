<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacacion extends Model
{
    use HasFactory;

    protected $table = 'vacaciones';

    protected $fillable = [
        'usuario_id',
        'fecha_inicio',
        'fecha_fin',
        'dias_habiles',
        'dias_calendario',
        'autorizador_id',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function autorizador()
    {
        return $this->belongsTo(User::class, 'autorizador_id');
    }
}
