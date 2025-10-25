<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Empleado extends Model
{
    use HasRoles;

    protected $fillable = [
        'cedula',
        'nombre',
        'area',
        'fecha_de_ingreso',
        'email',
        'password',
    ];

    protected $guard_name = 'web';
}
