<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    protected $fillable = [
        'area_key',
        'titular_person',
        'vocal_person',
        'loc_district_id'
    ];

    public function person($id)
    {
        return Person::findOrFail($id);
    }

    public function titular_person()
    {
        return Person::findOrFail($this->titular_person);
    }

    public function vocal_person()
    {
        return Person::findOrFail($this->vocal_person);
    }

    public static function getTableColumns() {

        $person = DB::table('persons')
            ->select(
                DB::raw("persons.person_name || ' ' || persons.father_lastname || ' ' || persons.mother_lastname AS full_name"),
                'persons.id'
            )->pluck('full_name','id')->toArray();
        $municipality = DB::table('municipalitys')
            ->select('municipality_name','id')
            ->pluck('municipality_name','id')->toArray();

        return [
            'area_key'=> Controller::getComponent('area_key', trans('admin.area_key')),
            'titular_person'=> Controller::getComponent('titular_person', trans('admin.titular_person'),$person),
            'vocal_person'=> Controller::getComponent('vocal_person', trans('admin.vocal_person'),$person),
            'municipality_id'=> Controller::getComponent('municipality_id', trans('admin.municipality'),$municipality),
        ];
    }

    //------------relations------------
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function loc_district()
    {
        return $this->belongsTo(LocDistrict::class)->withTimestamps();
    }
}
