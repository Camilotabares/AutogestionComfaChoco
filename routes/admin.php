<?php

use App\Http\Controllers\Admin\EmpleadosController;
use App\Http\Controllers\Admin\PermisosController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\VacationController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('admin.dashboard');
})->name('dashboard');

//ruta de Roles
Route::resource('roles', RoleController::class);

// ruta para empleados 
Route::resource('empleados',EmpleadosController::class);

// rutas para vacaciones
Route::resource('vacaciones', VacationController::class);
Route::post('vacaciones/{id}/approve', [VacationController::class, 'approve'])->name('vacaciones.approve');
Route::post('vacaciones/{id}/reject', [VacationController::class, 'reject'])->name('vacaciones.reject');

// rutas para permisos
Route::resource('permisos', PermisosController::class);
