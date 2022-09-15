<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'number',
        'fed_entity_id',
        'municipality_id',
        'colony',
    ];

    public static function getTableColumns() {
        return [
            'number'=> Controller::getComponent('number', trans('admin.number')),
            'fed_entity_id'=> Controller::getComponent('fed_entity_id', trans('admin.fed_entity'), FedEntity::pluck('entity_name', 'id')->toArray()),
            'municipality_id'=> Controller::getComponent('municipality_id', trans('admin.municipality'), Municipality::pluck('municipality_name', 'id')->toArray()),
            'colony_id'=> Controller::getComponent('colony_filter', trans('admin.colony')),
        ];
    }
}
