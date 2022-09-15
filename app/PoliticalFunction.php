<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PoliticalFunction extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'fed_entity',
        'municipality',
        'section',
        'block',
        'loc_district',
        'area',
        'zone',
        'fed_district',
        'position'
    ];

    public static function getTableColumns() {

        $function_type = ['territorial' => trans('admin.territorial'),
                          'political' => trans('admin.political')];
        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
            'description'=> Controller::getComponent('description', trans('admin.description')),
            'type'=> Controller::getComponent('type', trans('admin.type'),$function_type),
        ];
    }

    public function toStr(){
        $type = $this->type == 'territorial'? trans('admin.territorial'): trans('admin.political');
        $text = "&nbsp;&nbsp;&nbsp;<strong>$type</strong>: ". $this->name .' ( ';
        if(!is_null($this->pivot->fed_entity_id)){
            $obj = FedEntity::findOrFail($this->pivot->fed_entity_id);
            $text .= trans('admin.fed_entity').': '.$obj->entity_name.', ';
        }
        if(!is_null($this->pivot->municipality_id)){
            $obj = Municipality::findOrFail($this->pivot->municipality_id);
            $text .= trans('admin.municipality').': '.$obj->municipality_name.', ';
        }
        if(!is_null($this->pivot->section_id)){
            $obj = Section::findOrFail($this->pivot->section_id);
            $text .= trans('admin.section').': '.$obj->section_key.', ';
        }
        if(!is_null($this->pivot->loc_district_id)){
            $obj = LocDistrict::findOrFail($this->pivot->loc_district_id);
            $text .= trans('admin.loc_district').': '.$obj->district_number.', ';
        }
        if(!is_null($this->pivot->area_id)){
            $obj = Area::findOrFail($this->pivot->area_id);
            $text .= trans('admin.area').': '.$obj->area_key.', ';
        }
        if(!is_null($this->pivot->zone_id)){
            $obj = Zone::findOrFail($this->pivot->zone_id);
            $text .= trans('admin.zone').': '.$obj->zone_key.', ';
        }
        if(!is_null($this->pivot->fed_district_id)){
            $obj = FedDistrict::findOrFail($this->pivot->fed_district_id);
            $text .= trans('admin.fed_district').': '.$obj->district_number.', ';
        }
        if(!is_null($this->pivot->block_id)){
            $obj = Block::findOrFail($this->pivot->block_id);
            $text .= trans('admin.block').': '.$obj->block_key.', ';
        }
        $text = rtrim($text, ', ').' )';
        return $text;
    }

    //-----------relations---------------
    public function persons()
    {
        return $this->belongsToMany(Person::class,'political_function_person')->withPivot('fed_entity_id','municipality_id','fed_district_id', 'loc_district_id', 'area_id', 'zone_id' ,'section_id', 'block_id')->withTimestamps();
    }
}
