<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
        ]);
        
        $user = User::factory()->create([
            'name' => 'Daniel',
            'email' => 'dani@gmail.com',
            'password' => bcrypt('1298'),
        ]);
        
        $user->assignRole('Admin');
        
        // Crear el empleado asociado al usuario
        Empleado::create([
            'user_id' => $user->id,
            'cedula' => '1234567890',
            'nombre' => 'Daniel',
            'email' => 'dani@gmail.com',
            'password' => bcrypt('1298'),
            'area' => 'administrativa',
            'fecha_de_ingreso' => now()->subYears(2),
        ]);
    }
}
