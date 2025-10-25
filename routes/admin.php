<?php

use App\Http\Controllers\Admin\EmpleadosController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('admin.dashboard');
})->name('dashboard');

//ruta de Roles
Route::resource('roles', RoleController::class);


// ruta para empleados 

Route::resource('empleados',EmpleadosController::class);