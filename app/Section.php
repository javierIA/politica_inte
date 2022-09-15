<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Section extends Model
{
    protected $fillable = [
        'section_key',
        'section_type',
        'municipality_id',
        'fed_district_id',
        'loc_district_id',
    ];

    public function titular_person($id)
    {
        $section = $this::findOrFail($id);
        return Person::findOrFail($section->titular_person);
    }

    public function vocal_person($id)
    {
        $section = $this::findOrFail($id);
        return Person::findOrFail($section->vocal_person);
    }

    public static function getTableColumns() {

        $person = DB::table('persons')
            ->select(
                DB::raw("persons.person_name || ' ' || persons.father_lastname || ' ' || persons.mother_lastname AS full_name"),
                'persons.id'
            )->pluck('full_name','id')->toArray();

        $zones = DB::table('zones')
            ->select('zone_key','id')
            ->pluck('zone_key','id')->toArray();

        $fed_district = DB::table('fed_districts')
            ->select('district_number','id')
            ->pluck('district_number','id')->toArray();

        $loc_district = DB::table('loc_districts')
            ->select('district_number','id')
            ->pluck('district_number','id')->toArray();
        $items = ['urban' => trans('admin.urban'), 'rural' => trans('admin.rural'), 'mixed' => trans('admin.mixed'),];

        return [
            'section_key'=> Controller::getComponent('section_key', trans('admin.section_key')),
            'section_type'=> Controller::getComponent('section_type', trans('admin.section_type'), $items),
            'titular_person'=> Controller::getComponent('titular_person', trans('admin.titular_person'),$person),
            'vocal_person'=> Controller::getComponent('vocal_person', trans('admin.vocal_person'),$person),
            'fed_district_id'=> Controller::getComponent('fed_district_id', trans('admin.fed_district'),$fed_district),
            'loc_district_id'=> Controller::getComponent('loc_district_id', trans('admin.loc_district'),$loc_district),
            'zone_id'=> Controller::getComponent('zone_id', trans('admin.zone'),$zones),
        ];
    }

    //------------relations-------------

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }

    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function fed_district()
    {
        return $this->belongsTo(FedDistrict::class);
    }

    public function loc_districts()
    {
        return $this->belongsTo(LocDistrict::class);
    }

}
