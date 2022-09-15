<?php

namespace App;

use App\Http\Controllers\Controller;
use Cassandra\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\This;

class Person extends Model
{
    protected $table = 'persons';
    protected $fillable = [
        'person_name',
        'father_lastname',
        'mother_lastname',
        'birth_date',
        'person_sex',
        'elector_key',
        'id_oficial_address',
        'id_real_address',
        'id_section',
        'id_municipality_born',
        'id_fed_entity_born',
        'validity',
        'educ_level',
        'is_studying',
        'is_working',
        'card_pdf',
        'territory_volunteer',
        'electoral_volunteer',
        'occupation',
        'credential_date',
        'promoter'
    ];

    public static $educ_levels = [
        0 => 'admin.preescolar_educ',
        1 => 'admin.primary_educ',
        2 => 'admin.secundary_educ',
        3 => 'admin.media_superior_educ',
        4 => 'admin.superior_educ',
        5 => 'admin.master_educ',
        6 => 'admin.doctor_educ'
    ];

    public function getCommunication($type = null){
        return Communication::where('person_id', $this->id)->where('type',$type)->first();
    }

    public static function getTableColumns() {

        $elevel = [];
        foreach (Person::$educ_levels as $key => $val)
            $elevel[$key] = trans($val);

        $bool = array(
            0 => trans('admin.no'),
            1 => trans('admin.yes'),
        );
        $sex = array(
            'f' => trans('admin.female'),
            'm' => trans('admin.male'),
        );
        $promoter = DB::table('persons')
            ->leftJoin('persons as p','persons.id','=','p.promoter')
            ->select(
                DB::raw("persons.person_name || ' ' || persons.father_lastname || ' ' || persons.mother_lastname AS full_name"),
                'persons.id'
            )->pluck('full_name','id')->toArray();

        $data = Controller::politicalAccessLevel(false);
        $municipality = empty($data['municipality_id'])? Municipality::all(): Municipality::whereIn('id',$data['municipality_id']);
        $fedEntity = empty($data['fed_entity_id'])? FedEntity::all(): FedEntity::whereIn('id',$data['fed_entity_id']);
        $fedDistrict = empty($data['fed_district_id'])? FedDistrict::all(): FedDistrict::whereIn('id',$data['fed_district_id']);
        $locDistrict = empty($data['loc_district_id'])? LocDistrict::all(): LocDistrict::whereIn('id',$data['loc_district_id']);
        $area = empty($data['area_id'])? Area::all(): Area::whereIn('id',$data['area_id']);
        $zone = empty($data['zone_id'])? Zone::all(): Zone::whereIn('id',$data['zone_id']);
        $section = empty($data['section_id'])? Section::all(): Section::whereIn('id',$data['section_id']);
        $block = empty($data['block_id'])? Block::all(): Block::whereIn('id',$data['block_id']);

        return [
            'person_name'=> Controller::getComponent('person_name_filter', trans('admin.name')),
            'father_lastname'=> Controller::getComponent('father_lastname_filter', trans('admin.father_lastname')),
            'mother_lastname'=> Controller::getComponent('mother_lastname_filter', trans('admin.mother_lastname')),
            'birth_date'=> Controller::getComponent('birth_date_filter', trans('admin.birth_date'), new \DateTime()),
            'person_sex'=> Controller::getComponent('person_sex_filter', trans('admin.person_sex'),$sex),
            'elector_key'=> Controller::getComponent('elector_key_filter', trans('admin.elector_key')),
            'person_cellphone'=> Controller::getComponent('person_cellphone_filter', trans('admin.person_cellphone')),
            'person_phone'=> Controller::getComponent('person_phone_filter', trans('admin.person_phone')),
            'persons_email'=> Controller::getComponent('person_email_filter', trans('admin.email')),
            'educ_level'=> Controller::getComponent('educ_level_filter', trans('admin.educ_level'), $elevel),
            'is_studying'=> Controller::getComponent('is_studying_filter', trans('admin.studies'),$bool),
            'is_working'=> Controller::getComponent('is_working_filter', trans('admin.works'),$bool),
            'territory_volunteer'=> Controller::getComponent('territory_volunteer_filter', trans('admin.territory_volunteer'),$bool),
            'electoral_volunteer'=> Controller::getComponent('electoral_volunteer_filter', trans('admin.electoral_volunteer'),$bool),
            'cellphone_titular'=> Controller::getComponent('cellphone_titular_filter', trans('admin.cellphone_titular'),$bool),
            'email_titular'=> Controller::getComponent('email_titular_filter', trans('admin.email_titular'),$bool),
            'occupation'=> Controller::getComponent('occupation_filter', trans('admin.occupation'),Ocupation::pluck('occupation_name', 'id')->toArray()),

            'id_fed_entity_born'=> Controller::getComponent('id_fed_entity_born_filter', trans('admin.id_fed_entity_born'), $fedEntity->pluck('entity_name', 'id')->toArray()),
            //'id_fed_entity'=> Controller::getComponent('id_fed_entity_address', trans('admin.fed_entity'), $fedEntity->pluck('entity_name', 'id')->toArray()),
            'id_municipality_born'=> Controller::getComponent('id_municipality_born_filter', trans('admin.id_municipality_born'), $municipality->pluck('municipality_name', 'id')->toArray()),
            //'id_municipality'=> Controller::getComponent('id_municipality_address', trans('admin.municipality'), $municipality->pluck('municipality_name', 'id')->toArray()),
            //'id_fed_district'=> Controller::getComponent('id_fed_district_address', trans('admin.fed_district'), $fedDistrict->pluck('district_number', 'id')->toArray()  ),
            //'id_loc_district'=> Controller::getComponent('id_loc_district_address', trans('admin.loc_district'), $locDistrict->pluck('district_number', 'id')->toArray()),
            //'id_area'=> Controller::getComponent('id_area_address', trans('admin.area'), $area->pluck('area_key', 'id')->toArray()),
            //'id_zone'=> Controller::getComponent('id_zone_address', trans('admin.zone'), $zone->pluck('zone_key', 'id')->toArray()),
            //'id_section'=> Controller::getComponent('id_section_address', trans('admin.section'), $section->pluck('section_key', 'id')->toArray()),
            'id_block'=> Controller::getComponent('id_block_filter', trans('admin.block'), $block->pluck('block_key', 'id')->toArray()),

            //'promoter' => Controller::getComponent('promoter', trans('admin.promoter'), $promoter),
        ];
    }

    public function getUserName($data){
        $names = explode(' ',$data['person_name']);
        $user = '';
        foreach ($names as $n)
            $user .= $n[0];
        $user .= $data['father_lastname'];

        $pos = 0;
        $exist = Person::where('name',$user).count() > 0;
        do{
            if($exist){
                if($pos< count($data['mother_lastname'])) {
                    $user .= $data['mother_lastname'][$pos];
                    $pos++;
                }
            }
            $exist = Person::where('name',$user).count() > 0;
        }while($exist);
        return $user;
    }

    public function get_full_name()
    {
        return $this->person_name . ' '. $this->father_lastname . ' '. $this->mother_lastname;
    }

    public function getValidity($data){ //se validan email, celular, identificación
        $values =  str_split($this->validity);
        switch($data){
            case 'email': return $values[0] == '1';
            case 'cellphone': return $values[1] == '1';
            case 'id': return $values[2] == '1';
            case 'phone': return $values[3] == '1';
        }
    }

    //----------------relations--------------------
    public function communications()
    {
        return $this->hasMany(Communication::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function fed_entity()
    {
        return $this->belongsTo(FedEntity::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Ocupation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function political_functions()
    {
        return $this->belongsToMany(PoliticalFunction::class,'political_function_person')->withPivot('fed_entity_id','municipality_id','fed_district_id', 'loc_district_id', 'area_id', 'zone_id' ,'section_id', 'block_id')->withTimestamps();
    }

    public function social_networks()
    {
        return $this->belongsToMany(SocialNetwork::class,'social_network_person')->withPivot('account')->withTimestamps();
    }

    public function validations()
    {
        return $this->belongsToMany(Validation::class,'validation_person')->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'group_person')->withPivot('permit')->withTimestamps();
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class,'person_box')->withTimestamps();
    }


}
