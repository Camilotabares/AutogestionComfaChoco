<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            // Empleados
            'empleados.index',
            'empleados.create',
            'empleados.edit',
            'empleados.delete',
            
            // Vacaciones
            'vacaciones.index',
            'vacaciones.create',
            'vacaciones.edit',
            'vacaciones.delete',
            'vacaciones.approve',
            'vacaciones.reject',
            
            // Permisos
            'permisos.index',
            'permisos.create',
            'permisos.edit',
            'permisos.delete',
            'permisos.approve',
            'permisos.reject',
            
            // Roles
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos a roles
        $admin = Role::where('name', 'admin')->first();
        $rrhh = Role::where('name', 'rrhh')->first();
        $supervisor = Role::where('name', 'supervisor')->first();
        $empleado = Role::where('name', 'empleado')->first();

        // Admin: todos los permisos
        if ($admin) {
            $admin->givePermissionTo(Permission::all());
        }

        // RRHH: ver empleados (solo lectura), aprobar vacaciones/permisos
        if ($rrhh) {
            $rrhh->givePermissionTo([
                'empleados.index',  // Solo ver empleados
                'vacaciones.index',
                'vacaciones.approve',
                'vacaciones.reject',
                'permisos.index',
                'permisos.approve',
                'permisos.reject',
            ]);
        }

        // Supervisor: aprobar solicitudes menores a 3 días y ver empleados
        if ($supervisor) {
            $supervisor->givePermissionTo([
                'empleados.index',  // Solo ver empleados
                'vacaciones.index',
                'vacaciones.approve',
                'vacaciones.reject',
                'permisos.index',
                'permisos.approve',
                'permisos.reject',
            ]);
        }

        // Empleado: solo crear sus propias solicitudes
        if ($empleado) {
            $empleado->givePermissionTo([
                'vacaciones.index',
                'vacaciones.create',
                'permisos.index',
                'permisos.create',
            ]);
        }
    }
}
