<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $role = Role::create(['name' => 'super-admin']);
        $role = Role::create(['name' => 'vendor']);
        $role = Role::create(['name' => 'customer']);
    }
}
