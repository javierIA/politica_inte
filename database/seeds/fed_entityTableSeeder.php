<?php

use Illuminate\Database\Seeder;
use App\FedEntity;

class fed_entityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [ ['name' => "Aguascalientes", 'key' => '1'],
                    ['name' => "Baja California", 'key' => '2'],
                    ['name' => "Baja California Sur", 'key' => '3'],
                    ['name' => "Campeche", 'key' => '4'],
                    ['name' => "Coahuila de Zaragoza", 'key' => '5'],
                    ['name' => "Colima", 'key' => '6'],
                    ['name' => "Chiapas", 'key' => '7'],
                    ['name' => "Chihuahua", 'key' => '8'],
                    ['name' => "Ciudad De México", 'key' => '9'],
                    ['name' => "Durango", 'key' => '10'],
                    ['name' => "Guanajuato", 'key' => '11'],
                    ['name' => "Guerrero", 'key' => '12'],
                    ['name' => "Hidalgo", 'key' => '13'],
                    ['name' => "Jalisco", 'key' => '14'],
                    ['name' => "México", 'key' => '15'],
                    ['name' => "Michoacán de Ocampo", 'key' => '16'],
                    ['name' => "Morelos", 'key' => '17'],
                    ['name' => "Nayarit", 'key' => '18'],
                    ['name' => "Nuevo León", 'key' => '19'],
                    ['name' => "Oaxaca", 'key' => '20'],
                    ['name' => "Puebla", 'key' => '21'],
                    ['name' => "Querétaro", 'key' => '22'],
                    ['name' => "Quintana Roo", 'key' => '23'],
                    ['name' => "San Luis Potosí", 'key' => '24'],
                    ['name' => "Sinaloa", 'key' => '25'],
                    ['name' => "Sonora", 'key' => '26'],
                    ['name' => "Tabasco", 'key' => '27'],
                    ['name' => "Tamaulipas", 'key' => '28'],
                    ['name' => "Tlaxcala", 'key' => '29'],
                    ['name' => "Veracruz de Ignacio de la Llave", 'key' => '30'],
                    ['name' => "Yucatán", 'key' => '31'],
                    ['name' => "Zacatecas", 'key' => '32']];

        foreach ($states as $fe){
            $fedE = new FedEntity();
            $fedE->entity_name = $fe['name'];
            $fedE->entity_key = $fe['key'];
            $fedE->save();
        }
    }
}
