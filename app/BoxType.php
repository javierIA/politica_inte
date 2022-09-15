<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class BoxType extends Model
{
    protected $fillable = [
        'box_type_name',
        'quantity_per_box'
    ];

    public static function getTableColumns() {
        return [
            'box_type_name'=> Controller::getComponent('box_type_name', trans('admin.box_type_name')),
        ];
    }


    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
}
