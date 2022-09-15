<?php

use Illuminate\Database\Seeder;
use App\PoliticalFunction;

class political_functionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $function = [['type' => 'territorial', 'name' => "Coordinador del Estado", 'description' => 'TCES'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador del Estado", 'description' => 'TCEA'],
            ['type' => 'territorial', 'name' => "Coordinador del Municipio", 'description' => 'TCMU'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador del Municipio", 'description' => 'TCMA'],
            ['type' => 'territorial', 'name' => "Coordinador del Distrito Federal", 'description' => 'TCFD'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador del Distrito Federal", 'description' => 'TCFA'],
            ['type' => 'territorial', 'name' => "Coordinador del Distrito Local", 'description' => 'TCLC'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador del Distrito Local", 'description' => 'TCLA'],
            ['type' => 'territorial', 'name' => "Coordinador de Área", 'description' => 'TCAR'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador de Área", 'description' => 'TCAA'],
            ['type' => 'territorial', 'name' => "Coordinador de Zona", 'description' => 'TCZN'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador de Zona", 'description' => 'TCZA'],
            ['type' => 'territorial', 'name' => "Coordinador de Sección", 'description' => 'TCSC'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador de Sección", 'description' => 'TCSA'],
            ['type' => 'territorial', 'name' => "Coordinador de Manzana", 'description' => 'TCMZ'],
            ['type' => 'territorial', 'name' => "Auxiliar de Coordinador de Manzana", 'description' => 'TCMA'],
            ['type' => 'political', 'name' => "Propietario del Estado", 'description' => 'EPES'],
            ['type' => 'political', 'name' => "Suplente de Propietario del Estado", 'description' => 'EPES'],
            ['type' => 'political', 'name' => "Propietario del Municipio", 'description' => 'EPMU'],
            ['type' => 'political', 'name' => "Suplente de Propietario del Municipio", 'description' => 'EPMS'],
            ['type' => 'political', 'name' => "Propietario del Distrito Federal", 'description' => 'EPFD'],
            ['type' => 'political', 'name' => "Suplente de Propietario del Distrito Federal", 'description' => 'EPFS'],
            ['type' => 'political', 'name' => "Propietario del Distrito Local", 'description' => 'EPLC'],
            ['type' => 'political', 'name' => "Suplente de Propietario del Distrito Local", 'description' => 'EPLS'],
            ['type' => 'political', 'name' => "Representante General", 'description' => 'ERGE'],
            ['type' => 'political', 'name' => "Representante General Suplente", 'description' => 'ERGS'],
            ['type' => 'political', 'name' => "Representante de Casilla 1", 'description' => 'ERGP'],
            ['type' => 'political', 'name' => "Representante de Casilla 2", 'description' => 'ERGD'],
            ['type' => 'political', 'name' => "Representante de Casilla Suplente", 'description' => 'ERGT'],
            ['type' => 'political','name' => "Representante de Traslado", 'description' => 'ERTR']];

        foreach ($function as $f){
            $pf = new PoliticalFunction();
            $pf->name = $f['name'];
            $pf->description = $f['description'];
            $pf->type = $f['type'];
            $pf->save();
        }
    }
}
