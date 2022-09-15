<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'zone_key',
        'area_id'
    ];

    public static function getTableColumns() {
        return [
            'zone_key'=> Controller::getComponent('zone_key', trans('admin.zone_key')),
            'titular_person'=> Controller::getComponent('titular_person', trans('admin.titular_person')),
            'vocal_person'=> Controller::getComponent('vocal_person', trans('admin.vocal_person')),
            'id_area'=> Controller::getComponent('id_area', trans('admin.area'), Area::pluck('area_key', 'id')->toArray())
        ];
    }

    public function titular_person($id)
    {
        $zone = $this::findOrFail($id);
        return Person::findOrFail($zone->titular_person);
    }

    public function vocal_person($id)
    {
        $zone = $this::findOrFail($id);
        return Person::findOrFail($zone->vocal_person);
    }

    //---------relations----------
    public function area()
    {
        return $this->belongsTo(Area::class)->withTimestamps();
    }
}
