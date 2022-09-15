<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class SystemFunction extends Model
{
    protected $fillable = [
        'system_function_name',
    ];

    public static function getTableColumns() {
        return [
            'system_function_name'=> Controller::getComponent('system_function_name', trans('admin.system_function_name')),
        ];
    }

    //-------------relations-------------
    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_system_function')->withPivot('methods')->withTimestamps();
    }
}
