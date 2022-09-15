<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Municipality extends Model
{
    protected $table = 'municipalitys';
    protected $fillable = [
        'municipality_key',
        'municipality_name',
        'titular_person',
        'vocal_person',
        'representative',
        'alternate',
        'fed_entity_id'
    ];

    public function titular_person(){
        return Person::findOrFail($this->titular_person);
    }

    public function vocal_person(){
        return Person::findOrFail($this->vocal_person);
    }

    public function representative(){
        return Person::findOrFail($this->representative);
    }

    public function alternate(){
        return Person::findOrFail($this->alternate);
    }

    public static function getTableColumns() {
        $fed_entity = DB::table('fed_entitys')
            ->select('entity_name','id')
            ->pluck('entity_name','id')->toArray();

        return [
            'municipality_key'=> Controller::getComponent('municipality_key', trans('admin.municipality_key')),
            'municipality_name'=> Controller::getComponent('municipality_name', trans('admin.municipality_name')),
            'fed_entity_id'=> Controller::getComponent('fed_entity_id', trans('admin.fed_entity'), $fed_entity),
        ];
    }

    //------------relations-------------
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    public function fed_entity()
    {
        return $this->belongsTo(FedEntity::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function loc_district()
    {
        return $this->hasMany(LocDistrict::class);
    }

    public function fed_district()
    {
        return $this->hasMany(FedDistrict::class);
    }

    public function system_functions()
    {
        return $this->belongsToMany(SystemFunction::class,'role_system_function')->withPivot('methods')->withTimestamps();
    }

}
