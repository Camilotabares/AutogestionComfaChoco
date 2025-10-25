<?php

use App\Http\Controllers\Admin\EmpleadosController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\VacationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermisosController;


Route::get('/',function(){
    return view('admin.dashboard');
})->name('dashboard');

//ruta de Roles
Route::resource('roles', RoleController::class);


// ruta para empleados 

Route::resource('empleados',EmpleadosController::class);

// ruta para permisos

Route::resource('permisos',PermisosController::class);
Route::resource('vacaciones', VacationController::class);
