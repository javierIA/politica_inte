<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class userTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user= new User();
        $user->name = 'admin';
        $user->password = '$2y$10$wzDb9nuzbyPYvNnorDZ79u1H3dNavo.7GWBcIS/vyZWJOLP4kdFsG';
        $user->email = 'admin@example.com';
        $user->save();

        $role = Role::where('name','admin')->first();
        $user->roles()->attach($role);
    }
}
