<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $table = 'validations';
    protected $fillable = [
        'name',
        'active'
    ];

    public static function getTableColumns() {
        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
        ];
    }

    //---------relations----------
    public function persons()
    {
        return $this->belongsToMany(Person::class,'validation_person')->withTimestamps();
    }
}
