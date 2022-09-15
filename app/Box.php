<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
//    protected $table = 'boxs';
    protected $fillable = [
        'id_box_type',
        'titular_person1',
        'titular_person2',
        'vocal_person',
        'owner',
        'id_address',
        'id_section',
        'president',
        'secretary',
        'teller1',
        'teller2',
        'substitute1',
        'substitute2',
        'substitute3',
        'box_index',
        'description',
        'map_pdf',
        'owner_name',
        'address_text',
        'id_gen_representation'
    ];

    public function name($id)
    {
       $prod = $this::findOrFail($id);
       return $prod->id_box_type->box_type_name . $prod->box_index;
    }

    public static function getTableColumns() {
        return [
            'box_type_name'=> Controller::getComponent('box_type_name', trans('admin.box_type_name')),
        ];
    }

    public function get_address(){
        return Address::findOrFail($this->id_address);
    }

    //------------relations--------------
    public function persons()
    {
        return $this->belongsToMany(Person::class,'person_box')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(Person::class, 'owner');
    }

    public function section()
    {
        return $this->belongsTo(Section::class,'id_section' );
    }

    public function box_type()
    {
        return $this->belongsTo(BoxType::class, 'id_box_type');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'id_address');
    }

    public function gen_representation()
    {
        return $this->belongsTo(GenRepresentation::class,'id_gen_representation', 'id');
    }
}
