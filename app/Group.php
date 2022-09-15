<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'default',
        'description'
    ];

    public static function getTableColumns() {
        $bool = array(
            0 => trans('admin.no'),
            1 => trans('admin.yes'),
        );

        return [
            'group_name'=> Controller::getComponent('group_name', trans('admin.group_name')),
            'default'=> Controller::getComponent('default', trans('admin.default'), $bool),
            'description'=> Controller::getComponent('description', trans('admin.description')),

        ];
    }

    //-----------relations-------------
    public function persons()
    {
        return $this->belongsToMany(Person::class,'group_person')->withPivot('permit')->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_group')->withTimestamps();
    }
}
