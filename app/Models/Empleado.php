<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'cedula',
        'nombre',
        'area',
        'fecha_de_ingreso',
    ];
}
