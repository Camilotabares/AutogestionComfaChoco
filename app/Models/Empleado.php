<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    // Temporal: apuntar a la tabla temporal mientras se hacen cambios en el esquema
    // Cambiar de nuevo a 'empleados' cuando se consolide el cambio.
    protected $table = 'empleados_temp';

    protected $fillable = [
        'user_id',
        'cedula',
        'nombre',
        'email',
        'password',
        'area',
        'rol',
        'fecha_de_ingreso',
    ];

    protected $casts = [
        'fecha_de_ingreso' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
