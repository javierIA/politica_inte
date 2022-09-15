<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Ocupation extends Model
{
    protected $table = 'occupations';
    protected $fillable = [
        'occupation_name',
    ];
    public static function getTableColumns() {
        return [
            'occupation_name'=> Controller::getComponent('occupation_name', trans('admin.occupation_name')),
        ];
    }

    //------------relations-------------

    public function persons()
    {
        return $this->hasMany(Person::class);
    }
}
