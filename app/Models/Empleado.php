<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Empleado extends Model
{
    use HasRoles;

    protected $fillable = [
        'user_id',
        'cedula',
        'nombre',
        'email',
        'password',
    ];

    protected $guard_name = 'web';
}
