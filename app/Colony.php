<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Colony extends Model
{
//    protected $table = 'colonys';
    protected $fillable = [
        'name',
    ];

    public static function getTableColumns() {
        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
        ];
    }
}
