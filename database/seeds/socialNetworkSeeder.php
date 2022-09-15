<?php

use Illuminate\Database\Seeder;
use App\SocialNetwork;

class socialNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [['name' => 'Facebook', 'icon' => 'fa fa-facebook'],
                 ['name' => 'Tweeter', 'icon' => 'fa fa-twitter'],
                 ['name' => 'Linkedin', 'icon' => 'fa fa-linkedin'],
                 ['name' => 'Youtube', 'icon' => 'fa fa-youtube'],
                 ['name' => 'Whatsap', 'icon' => 'fa fa-whatsapp']];

        foreach ($data as $it){
            $socialN = new SocialNetwork();
            $socialN->name_social_network = $it['name'];
            $socialN->icon = $it['icon'];
            $socialN->save();
        }
    }
}
