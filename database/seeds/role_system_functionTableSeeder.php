<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\SystemFunction;

class role_system_functionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name','admin')->first();
        $data_to_sync = [];
        foreach (SystemFunction::all() as $sf){
            $methods = ["index","create","edit","destroy","exportData","importData"];
            $role->system_functions()->attach($sf);
            if ($sf->system_function_name === 'RoleController')
                array_push($methods, "assignFunction", "assignGroup");
            if($sf->system_function_name === 'PersonController')
                array_push($methods, "assignResponsibilities", "myData");
            if($sf->system_function_name === 'DashBoardController')
                array_push($methods, "managePerson"); //, "assignRepresentingTable"
            $data_to_sync[$sf->id] = ['methods' => json_encode($methods)];
        }
        $role->system_functions()->sync($data_to_sync);
    }
}
