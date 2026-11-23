<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);

        // 2. Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@utt.edu.mx'],
            [
                'name' => 'Administrador UTT',
                'password' => Hash::make('12345678'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        // 3. Estudiante prueba 1
        $est1 = User::firstOrCreate(
            ['email' => 'estudiante1@utt.edu.mx'],
            [
                'name' => 'Estudiante Prueba 1',
                'password' => Hash::make('12345678'),
            ]
        );
        $est1->syncRoles([$estudianteRole]);

        // 4. Estudiante prueba 2
        $est2 = User::firstOrCreate(
            ['email' => 'estudiante2@utt.edu.mx'],
            [
                'name' => 'Estudiante Prueba 2',
                'password' => Hash::make('12345678'),
            ]
        );
        $est2->syncRoles([$estudianteRole]);
    }
}