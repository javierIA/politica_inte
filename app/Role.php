<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'fed_entity_id',
        'municipality_id',
        'fed_district_id',
        'loc_district_id',
        'area_id',
        'zone_id',
        'section_id',
        'block_id'
    ];

    public static function getTableColumns() {

        $type = array(
            'territorial' => trans('admin.territorial'),
                'electoral' => trans('admin.electoral')

        );

        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
            'description'=> Controller::getComponent('description', trans('admin.description')),
            'created_at'=> Controller::getComponent('created_at', trans('admin.created_at'),  new \DateTime() ),
            'type'=> Controller::getComponent('type', trans('admin.type'), $type),
        ];
    }

    //---------relations-------------
    public function system_functions()
    {
        return $this->belongsToMany(SystemFunction::class,'role_system_function')->withPivot('methods')->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'role_group')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'user_role')->withTimestamps();
    }
}
