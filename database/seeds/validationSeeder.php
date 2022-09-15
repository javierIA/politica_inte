<?php

use Illuminate\Database\Seeder;
use App\Validation;

class validationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [['name' => 'correo', 'active' => '1'],
                 ['name' => 'sms', 'active' => '0']];

        foreach ($data as $it){
            $validation = new Validation();
            $validation->name = $it['name'];
            $validation->active = $it['active'];
            $validation->save();
        }
    }
}
