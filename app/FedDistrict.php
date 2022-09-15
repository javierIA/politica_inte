<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FedDistrict extends Model
{
    protected $table = 'fed_districts';
    protected $fillable = [
        'district_number',
        'municipality_id'
    ];

    public function titular_person($id)
    {
        $fed_district = $this::findOrFail($id);
        return Person::findOrFail($fed_district->titular_person);
    }

    public function vocal_person($id)
    {
        $fed_district = $this::findOrFail($id);
        return Person::findOrFail($fed_district->vocal_person);
    }

    public function representative($id)
    {
        $fed_district = $this::findOrFail($id);
        return Person::findOrFail($fed_district->representative);
    }

    public function alternate($id)
    {
        $fed_district = $this::findOrFail($id);
        return Person::findOrFail($fed_district->alternate);
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
            'district_number'=> Controller::getComponent('district_number', trans('admin.district_number')),
            'titular_person' => Controller::getComponent('titular_person', trans('admin.titular_person'), $person),
            'vocal_person' => Controller::getComponent('vocal_person', trans('admin.vocal_person'), $person),
            'id_municipality'=> Controller::getComponent('id_municipality', trans('admin.municipality'), Municipality::pluck('municipality_name', 'id')->toArray()),
        ];
    }

    //------------relations-------------
    public function section()
    {
        $this->hasMany(Section::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function gen_representations()
    {
        return $this->hasMany(GenRepresentation::class);
    }
}
