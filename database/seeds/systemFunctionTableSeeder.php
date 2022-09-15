<?php

use Illuminate\Database\Seeder;
use App\SystemFunction;

class systemFunctionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sf = new SystemFunction();
        $sf->system_function_name = 'UserController';
        $sf->save();

        $sf1 = new SystemFunction();
        $sf1->system_function_name = 'BoxTypeController';
        $sf1->save();

        $sf2 = new SystemFunction();
        $sf2->system_function_name = 'GroupController';
        $sf2->save();

        $sf3 = new SystemFunction();
        $sf3->system_function_name = 'PoliticalFunctionController';
        $sf3->save();

        $sf4 = new SystemFunction();
        $sf4->system_function_name = 'RoleController';
        $sf4->save();

        $sf5 = new SystemFunction();
        $sf5->system_function_name = 'SystemFunctionController';
        $sf5->save();

        $sf6 = new SystemFunction();
        $sf6->system_function_name = 'AreaController';
        $sf6->save();

        $sf7 = new SystemFunction();
        $sf7->system_function_name = 'SocialNetworkController';
        $sf7->save();

        $sf8 = new SystemFunction();
        $sf8->system_function_name = 'ValidationController';
        $sf8->save();

        $sf9 = new SystemFunction();
        $sf9->system_function_name = 'HistoryController';
        $sf9->save();

        $sf10 = new SystemFunction();
        $sf10->system_function_name = 'MunicipalityController';
        $sf10->save();

        $sf11 = new SystemFunction();
        $sf11->system_function_name = 'FedEntityController';
        $sf11->save();

        $sf12 = new SystemFunction();
        $sf12->system_function_name = 'AddressController';
        $sf12->save();

        $sf13 = new SystemFunction();
        $sf13->system_function_name = 'PersonController';
        $sf13->save();

        $sf15 = new SystemFunction();
        $sf15->system_function_name = 'LocDistrictController';
        $sf15->save();

        $sf16 = new SystemFunction();
        $sf16->system_function_name = 'PhoneCodeController';
        $sf16->save();

        $sf17 = new SystemFunction();
        $sf17->system_function_name = 'DashBoardController';
        $sf17->save();

        $sf18 = new SystemFunction();
        $sf18->system_function_name = 'FedDistrictController';
        $sf18->save();

        $sf19 = new SystemFunction();
        $sf19->system_function_name = 'ZoneController';
        $sf19->save();

        $sf20 = new SystemFunction();
        $sf20->system_function_name = 'ColonyController';
        $sf20->save();

        $sf21 = new SystemFunction();
        $sf21->system_function_name = 'SectionController';
        $sf21->save();

        $sf22 = new SystemFunction();
        $sf22->system_function_name = 'BlockController';
        $sf22->save();

        $sf23 = new SystemFunction();
        $sf23->system_function_name = 'OcupationController';
        $sf23->save();

        $sf24 = new SystemFunction();
        $sf24->system_function_name = 'StreetController';
        $sf24->save();

        $sf25 = new SystemFunction();
        $sf25->system_function_name = 'PostalCodeController';
        $sf25->save();
    }
}
