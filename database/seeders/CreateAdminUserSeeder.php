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
        $hasManager = User::where('email', 'manager@hotmail.com')->count();

        if (!$hasManager) {
            $manager = new User();
            $manager->name = 'manager';
            $manager->email = 'manager@hotmail.com';           
            $manager->password = bcrypt('12345678');
            $manager->save();
            $manager->syncRoles('manager');
        }    
    }
}
