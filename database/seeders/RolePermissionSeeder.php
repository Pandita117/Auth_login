<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        //shit del cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //creamos los roles
        $RolMaestro = Role::firstOrCreate(['name' => 'maestro']);
        $RolAlumno = Role::firstOrCreate(['name' => 'alumno']);



        //permisos de lo que pueden hacer
        $verAlumnos =Permission::firstOrCreate(['name' => 'alumnos.view']);
        $registrarAlumnos =Permission::firstOrCreate(['name' => 'alumnos.create']);
        $editarAlumnos = Permission::firstOrCreate(['name' => 'alumnos.edit']);
        $eliminarAlumnos = Permission::firstOrCreate(['name' => 'alumnos.delete']);

        //permisos de lo que hace alumno
        $RolAlumno->syncPermissions([
            $verAlumnos,
        ]);
        

        //permisos de lo que hacer pues el profesora 67
        $RolMaestro->syncPermissions([
            $verAlumnos,
            $registrarAlumnos,
            $editarAlumnos,
            $eliminarAlumnos,

        ]);








    }
}
