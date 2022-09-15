<?php

use Illuminate\Database\Seeder;
use App\Group;

class grupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grupo = new Group();
        $grupo->group_name = 'Partido Integral';
        $grupo->description = 'Grupo general del sistema Partido Integral';
        $grupo->default = true;
        $grupo->save();

        $grupo = new Group();
        $grupo->group_name = 'Organizadores';
        $grupo->description = 'Grupo de organizadores del partido';
        $grupo->default = false;
        $grupo->save();
    }
}
