<?php

use Illuminate\Database\Seeder;
use App\BoxType;

class box_typeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [['name' => 'B', 'quantity' => '1'],
                  ['name' => 'C', 'quantity' => '1'],
                  ['name' => 'S', 'quantity' => '1'],
                  ['name' => 'a', 'quantity' => '1'],
                  ['name' => 'Ex', 'quantity' => '3']];

        foreach ($types as $t){
            $bt = new BoxType();
            $bt->box_type_name = $t['name'];
            $bt->quantity_per_box = $t['quantity'];
            $bt->save();
        }
    }
}
