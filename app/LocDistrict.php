<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LocDistrict extends Model
{
    protected $fillable = [
        'district_number',
        'municipality_id',
        'map_pdf'
    ];

    public function titular_person($id)
    {
        $loc_district = $this::findOrFail($id);
        return Person::findOrFail($loc_district->titular_person);
    }

    public function vocal_person($id)
    {
        $loc_district = $this::findOrFail($id);
        return Person::findOrFail($loc_district->vocal_person);
    }

    public static function getTableColumns() {

        $bool = array(
            0 => trans('admin.yes'),
            1 => trans('admin.no'),
        );
        $sex = array(
            'f' => trans('admin.female'),
            'm' => trans('admin.male'),
        );
        $person = DB::table('persons')
            ->select(
                DB::raw("persons.person_name || ' ' || persons.father_lastname || ' ' || persons.mother_lastname AS full_name"),
                'persons.id'
            )->pluck('full_name','id')->toArray();

        return [
            'district_number'=> Controller::getComponent('district_number', trans('admin.name')),
            'titular_person' => Controller::getComponent('titular_person', trans('admin.titular_person'), $person),
            'vocal_person' => Controller::getComponent('vocal_person', trans('admin.vocal_person'), $person),
        ];
    }

    //-------------relations---------------

    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function loc_districts()
    {
        return $this->hasMany(GenRepresentation::class);
    }
}
