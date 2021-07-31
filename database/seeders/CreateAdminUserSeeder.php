<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Sandro Silva', 
            'email' => 'Manager@gmail.com',
            'cpf' => '05511560172',
            'password' => bcrypt('12345678')
        ]);
    
        $role = Role::create(['name' => 'manager']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

        $role = Role::create(['name' => 'executor']);
    }
}
