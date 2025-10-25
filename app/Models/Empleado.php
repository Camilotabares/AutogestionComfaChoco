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
        'email',
        'password',

    ];
//Rerelación muchos a muchos con roles

    public function roles()
{
    return $this->belongsToMany(Role::class);
}
}
