<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'admin-dashboard',
            'cusomer-dashboard',
            'cusomer-list',
            'cusomer-create',
            'cusomer-edit',
            'cusomer-delete',
            'supplier-dashboard',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete'
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
