<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creamos los roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminEmplead = Role::firstOrCreate(['name' => 'Empleado']);


    }
}
