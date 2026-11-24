<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Creamos los roles
        $RolMaestro = Role::firstOrCreate(['name' => 'maestro']);
        $RolAlumno  = Role::firstOrCreate(['name' => 'alumno']);

        // Permisos de lo que pueden hacer
        $verAlumnos       = Permission::firstOrCreate(['name' => 'alumnos.view']);
        $registrarAlumnos = Permission::firstOrCreate(['name' => 'alumnos.create']);
        $editarAlumnos    = Permission::firstOrCreate(['name' => 'alumnos.edit']);
        $eliminarAlumnos  = Permission::firstOrCreate(['name' => 'alumnos.delete']);

        // Permisos de alumno
        $RolAlumno->syncPermissions([
            $verAlumnos,
        ]);

        // Permisos de maestro
        $RolMaestro->syncPermissions([
            $verAlumnos,
            $registrarAlumnos,
            $editarAlumnos,
            $eliminarAlumnos,
        ]);

        // --- USUARIOS DE PRUEBA ---

        // 1. Maestro
        $maestro = User::firstOrCreate(
            ['email' => 'maestro@utt.edu.mx'],
            [
                'name'     => 'Profesor Prueba',
                'password' => Hash::make('12345678'),
            ]
        );
        $maestro->syncRoles([$RolMaestro]);

        // 2. Alumno 1
        $alumno1 = User::firstOrCreate(
            ['email' => 'alumno1@utt.edu.mx'],
            [
                'name'     => 'Alumno Prueba 1',
                'password' => Hash::make('12345678'),
            ]
        );
        $alumno1->syncRoles([$RolAlumno]);

        // 3. Alumno 2
        $alumno2 = User::firstOrCreate(
            ['email' => 'alumno2@utt.edu.mx'],
            [
                'name'     => 'Alumno Prueba 2',
                'password' => Hash::make('12345678'),
            ]
        );
        $alumno2->syncRoles([$RolAlumno]);
    }
}