<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
//    protected $table = 'address';
    protected $fillable = [
        'street',
        'number',
        'latitude',
        'longitude',
        'internal_number',
        'external_number',
        'neighborhood',
        'postal_code',
        'id_municipality',
        'id_fed_entity',
        'id_section'
    ];

    public static function getTableColumns() {
        return [
            'street'=> Controller::getComponent('street_filter', trans('admin.street')),
            //'latitude'=> Controller::getComponent('latitude', trans('admin.latitude')),
            //'longitude'=> Controller::getComponent('longitude', trans('admin.longitude')),
            'internal_number'=> Controller::getComponent('internal_number_filter', trans('admin.internal_number')),
            'external_number'=> Controller::getComponent('external_number_filter', trans('admin.external_number')),
            'neighborhood'=> Controller::getComponent('neighborhood_filter', trans('admin.neighborhood')),
            'postal_code'=> Controller::getComponent('postal_code_filter', trans('admin.postal_code')),
            'id_municipality'=> Controller::getComponent('id_municipality_filter', trans('admin.municipality_name'), Municipality::pluck('municipality_name', 'id')->toArray()),
            'id_fed_entity'=> Controller::getComponent('id_fed_entity_filter', trans('admin.entity_name'), FedEntity::pluck('entity_name', 'id')->toArray()),
        ];
    }

   /* public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }*/

    public function get_municipality(){
        return Municipality::findOrFail($this->id_municipality);
    }

    public function get_fed_entity(){
        return FedEntity::findOrFail($this->id_fed_entity);
    }

    public function inUse(){
        $count = Person::where('id_oficial_address',$this->id)
            ->orWhere('id_real_address',$this->id)->count();
        return $count > 0;
    }

    public function get_full_address(){
        return $this->street . ', No.'. $this->external_number . ', '. trans('admin.neighborhood') .':'. $this->neighborhood. ', ' . trans('admin.postal_code'). ':'. $this->postal_code;
    }



    //----------------relations--------------------
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function fed_entity()
    {
        return $this->belongsTo(FedEntity::class);
    }

    public function Box()
    {
        return $this->hasMany(Box::class);
    }
}
