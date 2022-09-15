<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class GenRepresentation extends Model
{
    protected $fillable = [
        'gen_representation_key',
        'titular_person',
        'vocal_person',
        'id_fed_district',
        'id_loc_district',
        'map_pdf',
    ];

    public static function getTableColumns() {
        return [
            'gen_representation_key'=> Controller::getComponent('gen_representation_key', trans('admin.gen_representation_key')),
        ];
    }

    //---------relations-----------
    public function titular()
    {
        return $this->belongsTo(Person::class, 'titular_person', 'id');
    }

    public function vocal()
    {
        return $this->belongsTo(Person::class, 'vocal_person', 'id');
    }

    public function fed_district()
    {
        return $this->belongsTo(FedDistrict::class, 'id_fed_district', 'id');
    }

    public function loc_district()
    {
        return $this->belongsTo(LocDistrict::class, 'id_loc_district', 'id');
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
}
