<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class History extends Model
{
    protected $table = 'historys';
    protected $fillable = [
        'action',
        'table',
        'description',
        'user',
        'role'
    ];

    public static function getTableColumns() {

        $actions = array(
            'delete' => 'delete',
            'save' => 'save',
            'update' => 'update',
        );

        $query = "SELECT  table_name FROM information_schema.tables WHERE table_schema = 'public';" ;
        $response = DB::select($query);

        $response_array  = array();
        for($i=0; $i<count($response); $i++) {
            $response_array[$response[$i]->table_name] = $response[$i]->table_name;
        }

        return [
            'action'=> Controller::getComponent('action', trans('admin.action'), $actions),
            'table'=> Controller::getComponent('table', trans('admin.table'), $response_array),
            'created_at'=> Controller::getComponent('created_at', trans('admin.created_at'),  new \DateTime() ),
            'description'=> Controller::getComponent('description', trans('admin.description')),
            'user'=> Controller::getComponent('user', trans('admin.user')),
            'role'=> Controller::getComponent('role', trans('admin.role')),
        ];
    }
}
