<?php

use Illuminate\Database\Seeder;
use App\Role;

class roleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new role();
        $role->name = 'admin';
        $role->description = 'testing';
        $role->type = 'territorial';
        $role->save();
    }
}
