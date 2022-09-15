<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PhoneCode extends Model
{
    protected $fillable = [
        'phone_code',
        'country',
        'flag_name'
    ];

    public static function getTableColumns() {

        $fileList = glob('flat/*');
        $arr = array();
        foreach($fileList as $filename){
            $name = explode("/",$filename);
            $name1 = explode(".",$name[1]);
            $arr[$name1[0]]=$name1[0];
        }




        return [
            'phone_code'=> Controller::getComponent('phone_code', trans('admin.phone_code')),
            'country'=> Controller::getComponent('country', trans('admin.country')),
            'flag_name'=> Controller::getComponent('flag_name', trans('admin.flag_name'), $arr),
        ];
    }

    //-----------relations---------------

}
