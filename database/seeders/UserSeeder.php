<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Admin
        $adminUser = User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@comfachoco.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $adminEmpleado = Empleado::create([
            'cedula' => '1000000001',
            'nombre' => 'Administrador Sistema',
            'area' => 'administrativa',
            'email' => 'admin@comfachoco.com',
            'fecha_de_ingreso' => now()->subYears(2),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_id' => $adminUser->id,
        ]);
        
        $adminUser->assignRole('admin');

        // Usuario RRHH
        $rrhhUser = User::create([
            'name' => 'María González RRHH',
            'email' => 'rrhh@comfachoco.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $rrhhEmpleado = Empleado::create([
            'cedula' => '1000000002',
            'nombre' => 'María González',
            'area' => 'talentoHumano',
            'email' => 'rrhh@comfachoco.com',
            'fecha_de_ingreso' => now()->subYears(2),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_id' => $rrhhUser->id,
        ]);
        
        $rrhhUser->assignRole('rrhh');

        // Usuario Supervisor
        $supervisorUser = User::create([
            'name' => 'Carlos Ramírez Supervisor',
            'email' => 'supervisor@comfachoco.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $supervisorEmpleado = Empleado::create([
            'cedula' => '1000000003',
            'nombre' => 'Carlos Ramírez',
            'area' => 'operativa',
            'email' => 'supervisor@comfachoco.com',
            'fecha_de_ingreso' => now()->subYears(2),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_id' => $supervisorUser->id,
        ]);
        
        $supervisorUser->assignRole('supervisor');

        // Usuario Empleado
        $empleadoUser = User::create([
            'name' => 'Juan Pérez Empleado',
            'email' => 'empleado@comfachoco.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        $empleado = Empleado::create([
            'cedula' => '1000000004',
            'nombre' => 'Juan Pérez',
            'area' => 'comercial',
            'email' => 'empleado@comfachoco.com',
            'fecha_de_ingreso' => now()->subYears(2),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_id' => $empleadoUser->id,
        ]);
        
        $empleadoUser->assignRole('empleado');

        $this->command->info('✓ Usuarios de prueba creados exitosamente');
    }
}
