<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class FedEntity extends Model
{
    protected $table = 'fed_entitys';
    protected $fillable = [
        'entity_key',
        'entity_name',
        'titular_person',
        'vocal_person',
        'representative',
        'alternate'
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
        return [
            'entity_key'=> Controller::getComponent('entity_key', trans('admin.entity_key')),
            'entity_name'=> Controller::getComponent('entity_name', trans('admin.entity_name')),
        ];
    }

    //----------------relations---------------------

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    public function municipality()
    {
        return $this->hasMany(Municipality::class);
    }
}
