<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    protected $fillable = [
        'name_social_network',
        'icon'
    ];

    public static function getTableColumns() {
        $bool = array(
            0 => trans('admin.yes'),
            1 => trans('admin.no'),
        );

        return [
            'name_social_network'=> Controller::getComponent('name_social_network', trans('admin.name_social_network')),

        ];
    }

    //----------relations-----------
    public function persons()
    {
        return $this->belongsToMany(Person::class,'social_network_person')->withPivot('account')->withTimestamps();
    }
}
