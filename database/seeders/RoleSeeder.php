<?php

namespace Database\Seeders;

use App\Models\User;    
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //Roles
            'show roles',
            'create roles',
            'edit roles',
            'delete roles',
            //Empleados
            'show empleados',
            'create empleados',
            'edit empleados',
            'delete empleados',
            //Permisos
            'show permisos',
            'create permisos',
            'edit permisos',
            'delete permisos',
            //vacaciones
            'show vacaciones',
            'create vacaciones',
            'edit vacaciones',
            'delete vacaciones',

        ];

        // Crear permisos si no existen
        foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        Role::create(['name' => 'admin'])
        ->givePermissionTo(Permission::all());

        Role::create(['name' => 'rrhh'])
        ->givePermissionTo([
            'show empleados',
            'create empleados',
            'edit empleados',
            'delete empleados',
            'show permisos',
            'create permisos',
            'edit permisos',
            'delete permisos',
            'show vacaciones',
            'create vacaciones',
            'edit vacaciones',
            'delete vacaciones',
        ]);

        Role::create(['name' => 'supervisor'])
        ->givePermissionTo([
            'show permisos',
            'create permisos',
            'edit permisos',
            'show vacaciones',
            'create vacaciones',
            'edit vacaciones',
        ]);
        Role::create(['name' => 'empleado'])
        ->givePermissionTo([
            'show permisos',
            'create permisos',
            'show vacaciones',
            'create vacaciones',
        ]);
        User::factory()->create([
            'name' => 'Daniel',
            'email' => 'dani@gmail.com',
            'password' => bcrypt('1298'),
        ])->assignRole('admin');
    }
}
