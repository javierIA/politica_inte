<?php

use Illuminate\Database\Seeder;
use App\Setting;

class settingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->address = 'Calle 123';
        $setting->email = 'admin@example.com';
        $setting->phone = '65688904';
        $setting->election_year = 2020;
        $setting->max_cellphone = 10;
        $setting->max_mails = 10;
        $setting->allow_functions = true;
        $setting->default_role = 1;
        $setting->save();
    }
}
