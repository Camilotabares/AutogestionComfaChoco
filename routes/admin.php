<?php

use App\Http\Controllers\Admin\EmpleadosController;
use App\Http\Controllers\Admin\PermisosController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\VacationController;
use App\Http\Controllers\Admin\SolicitudesPendientesController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('admin.dashboard');
})->name('dashboard');

//ruta de Roles
Route::resource('roles', RoleController::class)->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

// ruta para empleados 
Route::resource('empleados',EmpleadosController::class)->middleware('permission:empleados.index|empleados.create|empleados.edit|empleados.delete');

// Solicitudes Pendientes (solo para RRHH, Supervisor, Admin)
Route::get('solicitudes-pendientes', [SolicitudesPendientesController::class, 'index'])
    ->name('solicitudes-pendientes.index')
    ->middleware('role:rrhh|supervisor|admin');

// rutas para vacaciones
Route::resource('vacaciones', VacationController::class)->middleware('permission:vacaciones.index|vacaciones.create|vacaciones.edit|vacaciones.delete');
Route::post('vacaciones/{id}/approve', [VacationController::class, 'approve'])->name('vacaciones.approve')->middleware('permission:vacaciones.approve');
Route::post('vacaciones/{id}/reject', [VacationController::class, 'reject'])->name('vacaciones.reject')->middleware('permission:vacaciones.reject');

// rutas para permisos
Route::resource('permisos', PermisosController::class)->middleware('permission:permisos.index|permisos.create|permisos.edit|permisos.delete');
Route::post('permisos/{id}/approve', [PermisosController::class, 'approve'])->name('permisos.approve')->middleware('permission:permisos.approve');
Route::post('permisos/{id}/reject', [PermisosController::class, 'reject'])->name('permisos.reject')->middleware('permission:permisos.reject');
