<?php

use Illuminate\Database\Seeder;
use App\PhoneCode;

class phone_codeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $code = new PhoneCode();
        $code->phone_code = '+52';
        $code->country = 'México';
        $code->flag_name = 'mx';
        $code->save();
    }
}
