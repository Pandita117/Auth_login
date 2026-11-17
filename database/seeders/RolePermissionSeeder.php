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

            //esto solo es paea limpiar la cache
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //creamos los roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminEmplead = Role::firstOrCreate(['name' => 'Empleado']);

        //Permisos del CRUD de usuarios 
        $verUsuarios = Permission::firstOrCreate(['name' => 'users.view']);
        $crearUsuarios = Permission::firstOrCreate(['name' => 'users.create']);
        $editarUsuarios = Permission::firstOrCreate(['name' => 'users.edit']);
        $eliminarUsuarios = Permission::firstOrCreate(['name' => 'users.delete']);
        $softDeleteUsuarios = Permission::firstOrCreate(['name' => 'users.soft_delete']);
        $restaurarUsuarios = Permission::firstOrCreate(['name' => 'users.restore']);
        
        // los permisos especificados por el documento

        //Rol de empleado
        $adminEmplead->syncPermissions([
            $verUsuarios,
            $crearUsuarios,
            $editarUsuarios,
        ]);

        //Rol de amin
        $adminRole->syncPermissions([
            $verUsuarios,
            $crearUsuarios,
            $editarUsuarios,
            $eliminarUsuarios,
            $softDeleteUsuarios,
            $restaurarUsuarios,
        ]);
    }
}
