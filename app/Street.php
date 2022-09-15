<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Street extends Model
{
    protected $fillable = [
        'name',
    ];

    public static function getTableColumns() {
        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
        ];
    }
}
