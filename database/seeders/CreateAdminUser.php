<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'phone_number'=>'545454545445',
            'zip'=>'98106',
            'city'=>'california',
            'state'=>'US',
            'street_address'=>'123 main st',
            'address_apt'=>'',
            'company_name'=>''

        ]);

        if($user) {
            $role = Role::create(['name' => 'super-admin']);
            $permissions = Permission::pluck('id','id')->all();
   
            $role->syncPermissions($permissions);
         
            $user->assignRole([$role->id]);

        }
    }
}
