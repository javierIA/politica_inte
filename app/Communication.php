<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
//    protected $table = 'communications';
    protected $fillable = [
        'type',
        'lada',
        'info',
        'is_propietary',
        'is_smartphone',
        'is_exclusive',
        'country_code',
        'person_id',
        'phone_code_id'
    ];

    public function getFullphone(){
        if(is_null($this->info))
            return null;
        $cc = PhoneCode::findOrFail($this->phone_code_id);
        $cc = is_null($cc)? '':$cc;
        return  $cc->phone_code . $this->lada . $this->info;
    }

    //----------------relations--------------------
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

}
