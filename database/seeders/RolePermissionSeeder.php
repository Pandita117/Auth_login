<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creamos los roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);

        
    }
}
