<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
//    protected $table = 'notifications';
    protected $fillable = [
        'id_user_to',
        'message',
        'type',
        'acepted_time',
        'id_user_from',
        'showed',
    ];

    public static function getTableColumns() {
        /*$fed_entity = DB::table('fed_entitys')
            ->select('entity_name','id')
            ->pluck('entity_name','id')->toArray();*/

        return [
            'type'=> Controller::getComponent('type', trans('admin.importance'), array(1=>trans('admin.high'),2=>trans('admin.medium'),3=>trans('admin.low'))),
            'created_at'=> Controller::getComponent('created_at', trans('admin.created_at'),  new \DateTime() ),
            /*'municipality_key'=> Controller::getComponent('municipality_key', trans('admin.municipality_key')),
            'municipality_name'=> Controller::getComponent('municipality_name', trans('admin.municipality_name')),
            'fed_entity_id'=> Controller::getComponent('fed_entity_id', trans('admin.fed_entity'), $fed_entity),
       */ ];
    }

    //------------relations-------------
    public function user_from()
    {
        return $this->belongsTo(User::class, 'id_user_from','id');
    }

    public function person_to()
    {
        return $this->belongsTo(User::class, 'id_user_to','id');
    }
}
