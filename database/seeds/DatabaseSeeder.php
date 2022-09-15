<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(roleTableSeeder::class);
        $this->call(userTableSeeder::class);
        $this->call(settingsTableSeeder::class);
        $this->call(systemFunctionTableSeeder::class);
        $this->call(role_system_functionTableSeeder::class);
        $this->call(colonyTableSeeder::class);
        $this->call(box_typeTableSeeder::class);
        $this->call(fed_entityTableSeeder::class);
        $this->call(municipalityTableSeeder::class);
        $this->call(phone_codeTableSeeder::class);
        $this->call(postal_codesTableSeeder::class);
        $this->call(political_functionTableSeeder::class);
        $this->call(ocupationTableSeeder::class);
        $this->call(socialNetworkSeeder::class);
        $this->call(validationSeeder::class);
        $this->call(grupoSeeder::class);
    }
}
