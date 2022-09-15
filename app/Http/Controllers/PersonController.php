<?php

namespace App\Http\Controllers;

use App\Address;
use App\Area;
use App\Block;
use App\Communication;
use App\Exports\ClassExport;
use App\FedDistrict;
use App\FedEntity;
use App\Group;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Municipality;
use App\Ocupation;
use App\Person;
use App\PhoneCode;
use App\PoliticalFunction;
use App\Section;
use App\SocialNetwork;
use App\Street;
use App\User;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;

class PersonController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData'],
        'assignResponsibilities' => ['assignResponsibilities','saveResponsibilities'],
        'myData' => ['myData','updateMyData']
    ];

    public static $check_query =  "Select *, (Select fed_district_id FROM sections where id = T.section_id) as fed_district_id,
                                    (Select loc_district_id FROM sections where id = T.section_id) as loc_district_id
                                    FROM (Select id_fed_entity as fed_entity_id, municipality_id, id_section as section_id
                                    from address WHERE id = (Select id_oficial_address FROM persons where id = %s)) as T";

       // "Select * from address WHERE id = (Select id_oficial_address FROM persons where id = %s)";

    /**
     * exportData the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $format
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportData(Request $request, $format, $ids){
        $selection = json_decode($ids);
        $items = $request->all();
        unset($items['_token']);
        if (ob_get_contents()) ob_end_clean();
        ob_start(); // and this

        $data = Controller::politicalAccessLevel();

        if(is_null($selection)) {
            $temp = "id_oficial_address in (Select id from address %s)";
            $where = '';
            $where .= is_null($data['fed_entity_id']) ? '' : $data['fed_entity_id'] . ' OR ';
            $where .= is_null($data['municipality_id']) ? '' : $data['municipality_id'] . ' OR ';
            $where .= is_null($data['fed_district_id']) ? '' : $data['fed_district_id'] . ' OR ';
            $where .= is_null($data['loc_district_id']) ? '' : $data['loc_district_id'] . ' OR ';
            $where .= is_null($data['area_id']) ? '' : $data['area_id'] . ' OR ';
            $where .= is_null($data['zone_id']) ? '' : $data['zone_id'] . ' OR ';
            $where .= is_null($data['section_id']) ? '' : $data['section_id'] . ' OR ';
            $where .= is_null($data['block_id']) ? '' : $data['block_id'] . ' OR ';
            $where = rtrim($where, 'OR ');
            $items['raw'] = sprintf($temp, $where);
        }
        else
            $items['raw'] = sprintf("id in (%s)", join(',', $selection));

        //verify
        return Excel::download(new ClassExport(Person::class, $items),'Person.'.$format);
    }

    /**
     * importData the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $format
     * @return BinaryFileResponse
     */
    public function importData(Request $request){
        $file = $request->file;
        $exceptions = ['titular_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'vocal_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'id_loc_district'=>['table'=>'loc_districts', 'param' => 'district_number']
        ];
        Excel::import(new ExcelImport(Person::class), $file);
        return redirect(route('person.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.person');
        $repo = Person::all();
        $columns = Person::getTableColumns();
        return view('admin.person.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.add_person');
        $persons = Person::all();

        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        $functions = PoliticalFunction::all();

        $columns_address = Address::getTableColumns();
        $columns_person = Person::getTableColumns();
        $occupations = Ocupation::all();
        $networks = SocialNetwork::all();
        $phone_codes = PhoneCode::all();
        $person_sex = array(
            'f' => trans('admin.female'),
            'm' => trans('admin.male'),
        );
        $streets = json_encode(DB::table('streets')
            ->select('name')
            ->get()->toArray());


        $groups_stdclass =DB::table('role_group')->select('group_id')->where('role_id', '=', Auth::user()->roles()->first()->id)->get()->toArray();
        $groups_array = [];
        foreach ($groups_stdclass as $gs)
            $groups_array[] = $gs->group_id;

        $groups =  DB::table('groups')
                        ->select('id','group_name')
                        ->where('default', false)
                        ->whereNotIn('id', $groups_array)
                        ->get()->toArray();
        $educ_levels = Person::$educ_levels;
        return view('admin.person.create', compact('title', 'persons', 'columns_address', 'columns_person', 'functions', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district','area', 'zone', 'sections', 'block', 'educ_levels', 'networks', 'occupations', 'groups', 'phone_codes', 'person_sex'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['name'] = $request->username;
        $request->validate([
            'person_name' => 'required',
            'father_lastname' => 'required',
            'mother_lastname' => 'required',
            'birth_date' => 'required',
            'person_sex' => 'required',
            'educ_level' => 'integer|required',
            'name' => 'required|unique:users',
            'elector_key' => 'required|unique:persons',
            'person_email' => 'email',
            'id_municipality' => 'integer|required',
            'id_fed_entity' => 'integer|required',
            'id_section' => 'integer|required',
            'id_oficial_address' => 'integer|required'
        ]);
        try{
            $items = $request->all();
            $insert_data = [
                'user' => [
                    'name' => $items['username'],
                    'email' => $items['person_email'],
                    'temp_address' => Str::random(10),
                    'password' => Str::random(10),
                    'person_id' => null,
                ],
                'email' => [
                    'type' => 'email',
                    'info' => $items['person_email'],
                    'is_propietary' => isset($items['person_email_is_propietary']),
                    'is_exclusive' => isset($items['person_email_exclusive_use']),
                    'person_id' => null,
                ],
                'phone' => [
                    'type' => 'phone',
                    'info' => $items['person_phone'],
                    'is_smartphone' => isset($items['phone_is_smartphone']),
                    'lada' => $items['lada_phone'],
                    'phone_code_id' => $items['phone_phone_code_id'],
                    'person_id' => null
                ],
                'cellphone' => [
                    'type' => 'cellphone',
                    'info' => $items['person_cellphone'],
                    'is_propietary' => isset($items['cellphone_is_propietary']),
                    'is_smartphone' => isset($items['cellphone_is_smartphone']),
                    'is_exclusive' => isset($items['cellphone_exclusive_use']),
                    'lada' => $items['lada_cellphone'],
                    'phone_code_id' => $items['cellphone_phone_code_id'],
                    'phone_code' => PhoneCode::findOrFail($items['cellphone_phone_code_id']),
                    'person_id' => null
                ],
                'political' => [
                    'political_function_id' => isset($items['id_political'])?$items['id_political']:null,
                    'person_id' => null,
                    'fed_entity_id' => isset($items['id_fed_entitys_political'])?$items['id_fed_entitys_political']:null,
                    'municipality_id' => isset($items['id_municipality_political'])? $items['id_municipality_political']:null,
                    'fed_district_id' => isset($items['id_fed_district_political'])? $items['id_fed_district_political']:null,
                    'loc_district_id' => isset($items['id_loc_district_political'])? $items['id_loc_district_political']:null,
                    'area_id' => isset($items['id_area_political'])? $items['id_area_political']:null,
                    'zone_id' => isset($items['id_zone_political'])?$items['id_zone_political'] :null,
                    'section_id' => isset($items['id_section_political'])?$items['id_section_political']:null,
                    'block_id' => isset($items['id_block_political'])? $items['id_block_political']:null,
                ],
                'territorial' => [
                    'political_function_id' => isset($items['id_territorial'])? $items['id_territorial']: null,
                    'person_id' => null,
                    'fed_entity_id' => isset($items['id_fed_entitys_territorial'])?$items['id_fed_entitys_territorial']:null,
                    'municipality_id' => isset($items['id_municipality_territorial'])? $items['id_municipality_territorial']:null,
                    'fed_district_id' => isset($items['id_fed_district_territorial'])? $items['id_fed_district_territorial']:null,
                    'loc_district_id' => isset($items['id_loc_district_territorial'])? $items['id_loc_district_territorial']:null,
                    'area_id' => isset($items['id_area_territorial'])? $items['id_area_territorial']:null,
                    'zone_id' => isset($items['id_zone_territorial'])?$items['id_zone_territorial'] :null,
                    'section_id' => isset($items['id_section_territorial'])?$items['id_section_territorial']:null,
                    'block_id' => isset($items['id_block_territorial'])? $items['id_block_territorial']:null,
                ]
            ];

            $items['validity'] = '0000'; //se validan email, celular, identificación
            $items['is_studying'] = isset($items['is_studying']);
            $items['is_working'] = isset($items['is_working']);

            $person = new Person();
            $person->fill($items);
            $front = $this->saveImage($request->file, 'front', $person, 0);
            $back = $this->saveImage($request->file, 'back', $person, 1);
            $person->card_pdf =$this->saveCardPdf($person,$front, $back);
            $person->save();
            $this->savelog($person,'save');

            foreach ($insert_data as $key => $val)
                $insert_data[$key]['person_id'] = $person->id;

            //adicionar email
            $email = new Communication();
            $email->fill($insert_data['email']);
            $email->save();
            $this->savelog($email,'save');

            //adicionar phone
            $phone = new Communication();
            $phone->fill($insert_data['phone']);
            $phone->save();
            $this->savelog($phone,'save');

            //adicionar cellphone
            $cellphone = new Communication();
            $cellphone->fill($insert_data['cellphone']);
            $cellphone->save();
            $this->savelog($cellphone,'save');

            //adicionar responsabilidades politicas
            if(!is_null($insert_data['political']['political_function_id'])){
                $id = $insert_data['political']['political_function_id'];
                unset($insert_data['political']['political_function_id']);
                unset($insert_data['political']['person_id']);
                $person->political_functions()->attach($id,$insert_data['political']);
            }

            //adicionar responsabilidades territoriales
            if(!is_null($insert_data['territorial']['political_function_id'])){
                $id = $insert_data['territorial']['political_function_id'];
                unset($insert_data['territorial']['political_function_id']);
                unset($insert_data['territorial']['person_id']);
                $person->political_functions()->attach($id,$insert_data['territorial']);
            }

            $networks = SocialNetwork::all()->pluck('id','name_social_network')->toArray();
            foreach( $networks as $key => $val)
                if(!is_null($items[$key]))
                    $person->social_networks()->attach($val, ['account' => $items[$key]]);

            //asignar grupos a la persona
            $groups_default = Group::where('default',true)->pluck('id')->toArray();
            foreach (auth()->user()->roles()->get() as $rol)
                $groups_default = array_merge($groups_default, $rol->groups()->pluck('group_id')->toArray());
            foreach( $groups_default as $key => $val)
                $person->groups()->attach($val);

            //notificaciones a clientes

            //solo si el correo y el telefono son exclusivos
            if($insert_data['email']['is_propietary'] && $insert_data['email']['is_exclusive']  &&
                $insert_data['cellphone']['is_propietary'] && $insert_data['cellphone']['is_exclusive']){

                $exist = User::where('email',$insert_data['user']['email'])->count();

                if($exist == 0) {
                    //adicionar user
                    $user = new User();
                    $user->fill($insert_data['user']);
                    $user->save();
                    $this->savelog($user, 'save');

                    //asignar rol por defecto
                    $role = $this->getSettingsInfo()->default_role;
                    if (!is_null($role))
                        $user->roles()->attach($role);

                    $com = new CommunicationController();
                    $com->sendNotifications($insert_data, $person);
                }
            }
            return redirect(route('person.index'));

        } catch (Exception $e) {
            DB::rollback();
        } catch (\Throwable $e){
            DB::rollback();
        } finally {
            return redirect(route('person.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return void
     */
    public function assignResponsibilities($id)
    {
        $person = Person::findOrFail($id);
        $title = trans('admin.assignResponsibilities');
        $person_sex = $person->person_sex == 'f'? trans('admin.female'): trans('admin.male');
        $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
        $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
        $email = Communication::where('person_id', $person->id)->where('type','email')->first();
        $oficial_address = Address::findOrFail($person->id_oficial_address);
        $person->birth_date = date_format(date_create($person->birth_date),"d/m/Y");
        $person->credential_date = date_format(date_create($person->credential_date),"d/m/Y");

        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        $functions = DB::table('political_functions')
            ->orderBy('position', 'asc')
            ->get();
        $p_function = $person->political_functions()->where('type','political')->first();
        $t_function = $person->political_functions()->where('type','territorial')->first();
        return view('admin.person.assign_responsabilities', compact('title', 'person', 'person_sex', 'phone', 'cellphone', 'email', 'oficial_address', 'functions', 'p_function', 't_function', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district','area', 'zone', 'sections', 'block'));
    }

    public function saveResponsibilities(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $items = $request->all();
        $data = [];
        $null_data = [
            'municipality_id' => null,
            'fed_entity_id' =>null,
            'section_id' => null,
            'fed_district_id' =>null,
            'loc_district_id' => null,
            'area_id' => null,
            'zone_id' => null,
            'block_id' =>null
        ];

        if(isset($items['id_territorial']) && !is_null($items['id_territorial'])){
            $data[$items['id_territorial']] = [];
            $t_data = $null_data;
            foreach($items as $key => $val)
                if(strstr($key,'_territorial')){
                    $temp = str_replace('_territorial','',$key);
                    if($temp != 'id' && !empty($val))
                        $t_data[$temp] = $val;
                }
            $data[$items['id_territorial']] = $t_data;
        }

        if(isset($items['id_political']) && !is_null($items['id_political'])){
            $data[$items['id_political']] = [];
            $p_data = $null_data;
            foreach($items as $key => $val)
                if(strstr($key,'_political')){
                    $temp = str_replace('_political','',$key);
                    if($temp != 'id' && !empty($val))
                        $p_data[$temp] = $val;
                }
            $data[$items['id_political']] = $p_data;
        }

        $person->political_functions()->sync($data);
        return redirect(route('person.index'));
    }

    public function saveCardPdf($object,$front, $back){
        $save_folder = public_path().'/pdf/' . $object->getTable().'/'.$object->elector_key;
        if(!file_exists($save_folder))
            mkdir($save_folder, 0777, true);

        $save_folder.='/card.pdf';
        if(file_exists($save_folder))
            unlink($save_folder);

        // create pdf from card
        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'landscape');

        $type_front = pathinfo($front, PATHINFO_EXTENSION);
        $base64_front = "data:image/$type_front;base64," . base64_encode(file_get_contents($front));

        $type_back = pathinfo($back, PATHINFO_EXTENSION);
        $base64_back = "data:image/$type_back;base64," . base64_encode(file_get_contents($back));
        $name = $object->get_full_name();
        $html = <<<EOD
                    <div align="center"> $name</div><br>
                    <div align="center"><img src="$base64_front" width="800" /></div>
                    <div align="center"><img src="$base64_back" width="800" /></div>
EOD;
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($save_folder, $output);
        return $save_folder;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return Response
     */
    public function show(Person $person)
    {
        if(is_dir(public_path().'/pdf/' . $person->getTable().'/'.$person->elector_key.'/')) {
            $root = scandir(public_path() . '/pdf/' . $person->getTable() . '/' . $person->elector_key . '/');
            $imgs = [
                'front' => is_null(array_search('front.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/front.png') : url('pdf/persons/' . $person->elector_key . '/front.jpeg'),
                'back' => is_null(array_search('back.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/back.png') : url('pdf/persons/' . $person->elector_key . '/back.jpeg')
            ];
        }
        else
            $imgs = [
                'front' => null,
                'back' => null
            ];

        $title = $person->get_full_name();
        $groups_stdclass =DB::table('role_group')->select('group_id')->where('role_id', '=', Auth::user()->roles()->first()->id)->get()->toArray();
        $groups_array = [];
        foreach ($groups_stdclass as $gs)
            $groups_array[] = $gs->group_id;

        $groups =  DB::table('groups')
            ->select('id','group_name')
            ->where('default', false)
            ->whereNotIn('id', $groups_array)
            ->get()->toArray();

        $user = User::where('person_id', $person->id)->first();
        $person->birth_date = date_format(date_create($person->birth_date),"d/m/Y");
        $person->credential_date = date_format(date_create($person->credential_date),"d/m/Y");
        $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
        $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
        $email = Communication::where('person_id', $person->id)->where('type','email')->first();
        $oficial_address = is_null($person->id_oficial_address)? null: Address::findOrFail($person->id_oficial_address);
        $real_address = is_null($person->id_real_address)? null: Address::findOrFail($person->id_real_address);
        $promoter = is_null($person->promoter)? null: Person::findOrFail($person->promoter);

        $p_function = $person->political_functions()->where('type','political')->first();
        $t_function = $person->political_functions()->where('type','territorial')->first();

        $text = '';
        if(!is_null($t_function))
            $text .= $t_function->toStr();
        if(!is_null($p_function)){
            if(!empty($text)) $text .= '<br>';
            $text .= $p_function->toStr();
        }

        return view('admin.person.show', compact('text', 'person', 'imgs' ,'p_function', 't_function','user','title', 'groups', 'phone', 'cellphone', 'email', 'oficial_address', 'real_address', 'promoter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return Response
     */
    public function edit(Person $person)
    {
        if(!Controller::checkPoliticalAccess($person, PersonController::$check_query))
            return redirect(route('person.index'));
        if(is_dir(public_path().'/pdf/' . $person->getTable().'/'.$person->elector_key.'/')) {
            $root = scandir(public_path() . '/pdf/' . $person->getTable() . '/' . $person->elector_key . '/');
            $imgs = [
                'front' => is_null(array_search('front.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/front.png') : url('pdf/persons/' . $person->elector_key . '/front.jpeg'),
                'back' => is_null(array_search('back.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/back.png') : url('pdf/persons/' . $person->elector_key . '/back.jpeg')
            ];
        }
        else
            $imgs = [
                'front' => null,
                'back' => null
            ];

        $title = $person->get_full_name();
        $persons = Person::all();

        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        //$functions = PoliticalFunction::all()->sortBy("position",sort);
        $functions = DB::table('political_functions')
            ->orderBy('position', 'asc')
            ->get();
        //var_dump($functions);die();
        $p_function = $person->political_functions()->where('type','political')->first();
        $t_function = $person->political_functions()->where('type','territorial')->first();


        $columns_address = Address::getTableColumns();
        $columns_person = Person::getTableColumns();
        $occupations = Ocupation::all();
        $networks = SocialNetwork::all();
        $phone_codes = PhoneCode::all();
        $person_sex = array(
            'f' => trans('admin.female'),
            'm' => trans('admin.male'),
        );

        $groups_stdclass =DB::table('role_group')->select('group_id')->where('role_id', '=', Auth::user()->roles()->first()->id)->get()->toArray();
        $groups_array = [];
        foreach ($groups_stdclass as $gs)
            $groups_array[] = $gs->group_id;

        $groups =  DB::table('groups')
            ->select('id','group_name')
            ->where('default', false)
            ->whereNotIn('id', $groups_array)
            ->get()->toArray();
        $educ_levels = Person::$educ_levels;

        $person->birth_date = date_format(date_create($person->birth_date),"d/m/Y");
        $person->credential_date = date_format(date_create($person->credential_date),"d/m/Y");
        $user = User::where('person_id', $person->id)->first();
        $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
        $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
        $email = Communication::where('person_id', $person->id)->where('type','email')->first();

        $oficial_address = is_null($person->id_oficial_address)? null: Address::findOrFail($person->id_oficial_address);
        $real_address = is_null($person->id_real_address)? null: Address::findOrFail($person->id_real_address);
        $promoter = is_null($person->promoter)? null: Person::findOrFail($person->promoter);

        $text = '';
        if(!is_null($t_function))
            $text .= $t_function->toStr();
        if(!is_null($p_function)){
            if(!empty($text)) $text .= '<br>';
            $text .= $p_function->toStr();
        }

        return view('admin.person.edit', compact('text', 'title', 'phone','columns_address','columns_person', 'imgs', 'cellphone', 'email', 'occupations', 'persons', 'networks', 'functions', 'p_function', 't_function', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district','area', 'zone', 'sections', 'block', 'educ_levels', 'person', 'user', 'oficial_address', 'real_address', 'promoter', 'phone_codes', 'person_sex', 'groups'));

 }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return Response
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'person_name' => 'required',
            'father_lastname' => 'required',
            'mother_lastname' => 'required',
            'birth_date' => 'required',
            'person_sex' => 'required',
            'educ_level' => 'integer|required',
            'elector_key' => 'required',
            'person_email' => 'email',
            'id_municipality' => 'integer|required',
            'id_fed_entity' => 'integer|required',
            'id_section' => 'integer|required',
            'id_oficial_address' => 'integer|required'
        ]);

        try{
            $items = $request->all();
            //var_dump(isset($items['fed_entity_id_political']));die();
            $insert_data = [
                'email' => [
                    'type' => 'email',
                    'info' => $items['person_email'],
                    'is_propietary' => isset($items['person_email_is_propietary']),
                    'is_exclusive' => isset($items['person_email_exclusive_use']),
                    'person_id' => null,
                ],
                'phone' => [
                    'type' => 'phone',
                    'info' => $items['person_phone'],
                    'is_smartphone' => isset($items['phone_is_smartphone']),
                    'lada' => $items['lada_phone'],
                    'phone_code_id' => $items['phone_phone_code_id'],
                    'person_id' => null
                ],
                'cellphone' => [
                    'type' => 'cellphone',
                    'info' => $items['person_cellphone'],
                    'is_propietary' => isset($items['cellphone_is_propietary']),
                    'is_smartphone' => isset($items['cellphone_is_smartphone']),
                    'is_exclusive' => isset($items['cellphone_exclusive_use']),
                    'lada' => $items['lada_cellphone'],
                    'phone_code_id' => $items['cellphone_phone_code_id'],
                    'person_id' => null
                ],
                'political' => [
                    'political_function_id' => isset($items['id_political'])?$items['id_political']:null,
                    'fed_entity_id' => isset($items['fed_entity_id_political'])?$items['fed_entity_id_political']:null,
                    'municipality_id' => isset($items['municipality_id_political'])? $items['municipality_id_political']:null,
                    'fed_district_id' => isset($items['fed_district_id_political'])? $items['fed_district_id_political']:null,
                    'loc_district_id' => isset($items['loc_district_id_political'])? $items['loc_district_id_political']:null,
                    'area_id' => isset($items['area_id_political'])? $items['area_id_political']:null,
                    'zone_id' => isset($items['zone_id_political'])?$items['zone_id_political'] :null,
                    'section_id' => isset($items['section_id_political'])?$items['section_id_political']:null,
                    'block_id' => isset($items['block_id_political'])? $items['block_id_political']:null,
                ],
                'territorial' => [
                    'political_function_id' => isset($items['id_territorial'])? $items['id_territorial']: null,
                    'fed_entity_id' => isset($items['fed_entity_id_territorial'])?$items['fed_entity_id_territorial']:null,
                    'municipality_id' => isset($items['municipality_id_territorial'])? $items['municipality_id_territorial']:null,
                    'fed_district_id' => isset($items['fed_district_id_territorial'])? $items['fed_district_id_territorial']:null,
                    'loc_district_id' => isset($items['loc_district_id_territorial'])? $items['loc_district_id_territorial']:null,
                    'area_id' => isset($items['area_id_territorial'])? $items['area_id_territorial']:null,
                    'zone_id' => isset($items['zone_id_territorial'])?$items['zone_id_territorial'] :null,
                    'section_id' => isset($items['section_id_territorial'])?$items['section_id_territorial']:null,
                    'block_id' => isset($items['block_id_territorial'])? $items['block_id_territorial']:null,
                ]
            ];

            //var_dump($insert_data['political']);die();

            $items['is_studying'] = isset($items['is_studying']);
            $items['is_working'] = isset($items['is_working']);
            $person->fill($items);
            $person->validity = '0000';

            if( !is_null($request->file)){
                $front = $this->saveImage($request->file, 'front', $person, 0);
                $back = $this->saveImage($request->file, 'back', $person, 1);
                $person->card_pdf =$this->saveCardPdf($person,$front, $back);
            }

            $phone_temp = $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
            $cellphone_temp = $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
            $email_temp = $email = Communication::where('person_id', $person->id)->where('type','email')->first();

            $email->fill($insert_data['email']);
            $phone->fill($insert_data['phone']);
            $cellphone->fill($insert_data['cellphone']);

            $networks = SocialNetwork::all()->pluck('id','name_social_network')->toArray();
            $sync_data = [];

            foreach( $networks as $key => $val)
                if(!is_null($items[$key]))
                    $sync_data[$val]= ['account' => $items[$key]];
            $person->social_networks()->sync($sync_data);

            //asignar grupos a la persona
            $groups_default = Group::where('default',true)->pluck('id')->toArray();

            foreach (auth()->user()->roles()->get() as $rol)
                $groups_default = array_merge($groups_default, $rol->groups()->pluck('group_id')->toArray());
            if(isset($items['my-select']))
                $groups_default = array_merge($groups_default, $items['my-select']);
            $person->groups()->sync($groups_default);

            $udp = [];

            //adicionar responsabilidades politicas
            if(!is_null($insert_data['political']['political_function_id'])){
                $idp = $insert_data['political']['political_function_id'];
                unset($insert_data['political']['political_function_id']);
                $udp[$idp] = $insert_data['political'];
            }

            //adicionar responsabilidades territoriales
            if(!is_null($insert_data['territorial']['political_function_id'])){
                $idt = $insert_data['territorial']['political_function_id'];
                unset($insert_data['territorial']['political_function_id']);
                $udp[$idt] = $insert_data['territorial'];
            }
            $person->political_functions()->sync($udp);

            //notificaciones a clientes

            //solo si el correo y el telefono son exclusivos
            if($email != $email_temp || $phone != $phone_temp || $cellphone != $cellphone_temp){

                $email->update();
                $this->savelog($email,'update');

                $phone->update();
                $this->savelog($phone,'update');

                $cellphone->update();
                $this->savelog($cellphone,'update');

                $com = new CommunicationController();
                /*$com->sendsms(['to'   => $insert_data['cellphone']['info'],
                    'from' => trans('user.app_title'),
                    'text' => $com->getText($person,'sms')]);*/

                $com->sendmail(['to'   => [$insert_data['email']['info']],
                    'subject' => trans('admin.mail_subject'),
                    'text' => $com->getText($person)]);
            }

            $person->update();
            $this->savelog($person,'update');

            return redirect(route('person.index'));
        } catch (Exception $e) {
            DB::rollback();
        } catch (\Throwable $e){
            DB::rollback();
        } finally {
            return redirect(route('person.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return Response
     */
    public function myData($id)
    {
        $person = Person::findOrFail($id);
        if(is_null($person) || empty(auth()->user()->person()->first()) || auth()->user()->person()->first()->id != $id)
            return redirect(route('home'));

        if(is_dir(public_path().'/pdf/' . $person->getTable().'/'.$person->elector_key.'/')) {
            $root = scandir(public_path() . '/pdf/' . $person->getTable() . '/' . $person->elector_key . '/');
            $imgs = [
                'front' => is_null(array_search('front.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/front.png') : url('pdf/persons/' . $person->elector_key . '/front.jpeg'),
                'back' => is_null(array_search('back.jpeg', $root)) ? url('pdf/persons/' . $person->elector_key . '/back.png') : url('pdf/persons/' . $person->elector_key . '/back.jpeg')
            ];
        }
        else
            $imgs = [
                'front' => null,
                'back' => null
            ];

        $title = $person->get_full_name();
        $persons = Person::all();

        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        $functions = PoliticalFunction::all();
        $p_function = $person->political_functions()->where('type','political')->first();
        $t_function = $person->political_functions()->where('type','territorial')->first();

        $columns_address = Address::getTableColumns();
        $columns_person = Person::getTableColumns();
        $occupations = Ocupation::all();
        $networks = SocialNetwork::all();
        $phone_codes = PhoneCode::all();
        $person_sex = array(
            'f' => trans('admin.female'),
            'm' => trans('admin.male'),
        );

        $groups_stdclass =DB::table('role_group')->select('group_id')->where('role_id', '=', Auth::user()->roles()->first()->id)->get()->toArray();
        $groups_array = [];
        foreach ($groups_stdclass as $gs)
            $groups_array[] = $gs->group_id;

        $groups =  DB::table('groups')
            ->select('id','group_name')
            ->where('default', false)
            ->whereNotIn('id', $groups_array)
            ->get()->toArray();
        $educ_levels = Person::$educ_levels;

        $person->birth_date = date_format(date_create($person->birth_date),"d/m/Y");
        $person->credential_date = date_format(date_create($person->credential_date),"d/m/Y");
        $user = User::where('person_id', $person->id)->first();
        $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
        $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
        $email = Communication::where('person_id', $person->id)->where('type','email')->first();

        $oficial_address = is_null($person->id_oficial_address)? null: Address::findOrFail($person->id_oficial_address);
        $real_address = is_null($person->id_real_address)? null: Address::findOrFail($person->id_real_address);
        $promoter = is_null($person->promoter)? null: Person::findOrFail($person->promoter);

        return view('admin.person.mydata', compact('title', 'phone','columns_address','columns_person', 'imgs', 'cellphone', 'email', 'occupations', 'persons', 'networks', 'functions', 'p_function', 't_function', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district','area', 'zone', 'sections', 'block', 'educ_levels', 'person', 'user', 'oficial_address', 'real_address', 'promoter', 'phone_codes', 'person_sex', 'groups'));

    }

    public function updateMyData(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $request->validate([
            'person_name' => 'required',
            'father_lastname' => 'required',
            'mother_lastname' => 'required',
            'birth_date' => 'required',
            'person_sex' => 'required',
            'educ_level' => 'integer|required',
            'elector_key' => 'required',
            'person_email' => 'email',
            'id_municipality' => 'integer|required',
            'id_fed_entity' => 'integer|required',
            'id_section' => 'integer|required',
            'id_oficial_address' => 'integer|required'
        ]);

        try{
            $items = $request->all();
            $insert_data = [
                'email' => [
                    'type' => 'email',
                    'info' => $items['person_email'],
                    'is_propietary' => isset($items['person_email_is_propietary']),
                    'is_exclusive' => isset($items['person_email_exclusive_use']),
                    'person_id' => null,
                ],
                'phone' => [
                    'type' => 'phone',
                    'info' => $items['person_phone'],
                    'is_smartphone' => isset($items['phone_is_smartphone']),
                    'lada' => $items['lada_phone'],
                    'phone_code_id' => $items['phone_phone_code_id'],
                    'person_id' => null
                ],
                'cellphone' => [
                    'type' => 'cellphone',
                    'info' => $items['person_cellphone'],
                    'is_propietary' => isset($items['cellphone_is_propietary']),
                    'is_smartphone' => isset($items['cellphone_is_smartphone']),
                    'is_exclusive' => isset($items['cellphone_exclusive_use']),
                    'lada' => $items['lada_cellphone'],
                    'phone_code_id' => $items['cellphone_phone_code_id'],
                    'person_id' => null
                ],
            ];

            $items['is_studying'] = isset($items['is_studying']);
            $items['is_working'] = isset($items['is_working']);
            $person->fill($items);
            $person->validity = '1111';

            if( !is_null($request->file)){
                $front = $this->saveImage($request->file, 'front', $person, 0);
                $back = $this->saveImage($request->file, 'back', $person, 1);
                $person->card_pdf =$this->saveCardPdf($person,$front, $back);
            }

            $phone_temp = $phone = Communication::where('person_id', $person->id)->where('type','phone')->first();
            $cellphone_temp = $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
            $email_temp = $email = Communication::where('person_id', $person->id)->where('type','email')->first();

            $email->fill($insert_data['email']);
            $phone->fill($insert_data['phone']);
            $cellphone->fill($insert_data['cellphone']);

            $networks = SocialNetwork::all()->pluck('id','name_social_network')->toArray();
            $sync_data = [];

            foreach( $networks as $key => $val)
                if(!is_null($items[$key]))
                    $sync_data[$val]= ['account' => $items[$key]];
            $person->social_networks()->sync($sync_data);

            //notificaciones a clientes

            //solo si el correo y el telefono son exclusivos
            if($email != $email_temp || $phone != $phone_temp || $cellphone != $cellphone_temp){

                $email->update();
                $this->savelog($email,'update');

                $phone->update();
                $this->savelog($phone,'update');

                $cellphone->update();
                $this->savelog($cellphone,'update');

                $com = new CommunicationController();

                $com->sendmail(['to'   => [$insert_data['email']['info']],
                    'subject' => trans('admin.mail_subject'),
                    'text' => $com->getText($person)]);
            }

            $person->update();
            $this->savelog($person,'update');

            return redirect(route('person.index'));
        } catch (Exception $e) {
            DB::rollback();
        } catch (\Throwable $e){
            DB::rollback();
        } finally {
            return redirect(route('home'));
        }

    }

    /**
     * Filter the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        if(isset($data['name_lastname']))
            $exceptions['name_lastname'] = "lower(person_name) like lower('%".$data['name_lastname']."%') OR lower(father_lastname) like lower('%".$data['name_lastname']."%') OR mother_lastname like lower('%".$data['name_lastname']."%')";
        if(isset($data['id_section_address']))
            $exceptions['id_section_address'] = "oficial_address_id in (Select id from address where section_id = '".$data['section_id']."')";
        if(isset($data['id_municipality_address']))
            $exceptions['id_municipality_address'] = "oficial_address_id in (Select id from address where municipality_id = '".$data['id_municipality_address']."')";
        if(isset($data['id_fed_entity_address']))
            $exceptions['id_fed_entity_address'] = "oficial_address_id in (Select id from addresses where fed_entity_id = '".$data['id_fed_entity_address']."')";
        if(isset($data['id_zone_address']))
            $exceptions['id_zone_address'] = "oficial_address_id in (Select id from addresses where section_id in (Select id from sections where zone_id = '".$data['id_zone_address']."'))";
        if(isset($data['id_area_address']))
            $exceptions['id_area_address'] = "oficial_address_id in (Select id from addresses where section_id in (Select id from sections where zone_id in (SELECT id from zones WHERE area_id = '".$data['id_area_address']."')))";
        if(isset($data['id_loc_district_address']))
            $exceptions['id_loc_district_address'] = "oficial_address_id in (Select id from addresses where section_id in (Select id from sections where zone_id in (Select id from zones where area_id in (Select id from areas where loc_district_id = '".$data['id_loc_district_address']."'))))";
        if(isset($data['id_fed_district_address']))
            $exceptions['id_fed_district_address'] = "oficial_address_id in (Select id from addresses where section_id in (Select id from sections where zone_id in (Select id from zones where area_id in (Select id from areas where loc_district_id in (Select loc_district_id from fed_district_loc_district where fed_district_id = '".$data['id_fed_district_address']."')))))";
        if(isset($data['id_block']))
            $exceptions['id_block'] = "oficial_address_id in (Select id from addresses where section_id in (Select section_id from blocks where id = '".$data['id_block']."'))";

        $selects = ["person_name || ' ' || father_lastname || ' ' || mother_lastname  as full_name",
                    "(SELECT info FROM communications WHERE person_id = T.id and type = 'email') as persons_email",
                    "(SELECT info FROM communications WHERE person_id = T.id and type = 'phone') as persons_phone",
                    "(SELECT PC.phone_code || C.lada || C.info FROM communications as C join phone_codes as PC on (C.phone_code_id = PC.id) WHERE person_id = T.id and type = 'cellphone') as persons_cellphone",
                    "(Select A.municipality_id FROM addresses as A WHERE T.oficial_address_id = A.id) as municipality_id",
                    "(Select A.municipality_id FROM addresses as A WHERE T.real_address_id = A.id) as municipality_address_id",
                    "(Select A.fed_entity_id FROM addresses as A WHERE T.oficial_address_id = A.id) as fed_entity_id",
                    "(Select A.fed_entity_id FROM addresses as A WHERE T.real_address_id = A.id) as fed_entity_address_id",
                    "(Select A.section_id FROM addresses as A WHERE T.section_id = A.id) as section_id",
                    "(Select A.is_propietary FROM communications as A WHERE T.id = A.person_id and type = 'email') as email_titular",
                    "(Select district_number FROM fed_districts WHERE id = (Select fed_district_id FROM sections WHERE id = (SELECT section_id FROM addresses WHERE id = (SELECT oficial_address_id FROM persons WHERE id = T.id )))) as fed_district_address_id",
                    "CASE WHEN person_sex = 'f' THEN '".trans('admin.female')."' WHEN person_sex = 'm' THEN '".trans('admin.male')."' ELSE '' END as person_sex_text",
                    "(Select street || ', No.' || external_number || ', ' || '".trans('admin.neighborhood')." ' || neighborhood || ' ".trans('admin.postal_code')." ' || postal_code FROM addresses WHERE id = oficial_address_id) as oficial_address",
                    "null as selected"];

        $join = [
            'addresses as A on (A.id = T.oficial_address_id OR A.id = T.real_address_id)'
        ];

        $not_perm = isset($data['remove_permits']);
        unset($data['remove_permits']);

        $info = [
            'columns' =>  ['selected' ,'id', 'persons_id', 'full_name','persons_email','persons_phone','persons_cellphone','municipality_id','municipality_address_id','fed_entity_id','fed_entity_address_id','section_id','fed_district_address_id','elector_key','email_titular', 'person_sex_text'],
            'controller' => 'PersonController',
            'table' => with(new Person())->getTable(),
            'route' => [
                'assignResponsibilities' => [
                    'name' => 'person.assignResponsibilities',
                    'text' => trans('admin.assign_responsibilities'),
                    'col' => 'col-sm-3'
                ],
                'show' => [
                    'name' => 'person.show',
                    'text' => trans('admin.show_person'),
                    'col' => 'col-sm-3'
                ],
                'edit' => [
                    'name' => 'person.edit',
                    'text' => trans('admin.edit_person'),
                    'col' => 'col-sm-3'
                ],
                'destroy' => [
                    'name' => 'person.destroy',
                    'text' => trans('admin.delete_person'),
                    'col' => 'col-sm-3'
                ]
            ]
        ];

        return $this->internalFilter($data, $info, $exceptions, $join, $selects, $not_perm);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        if(!Controller::checkPoliticalAccess($person, PersonController::$check_query))
            return redirect(route('person.index'));

        $delete_folder = public_path().'/pdf/' . $person->getTable().'/'.$person->elector_key.'/';
        $this->savelog($person,'delete');
        $person->delete();

        $files = glob($delete_folder . '*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }
        try {
            rmdir($delete_folder);
        } catch (\Exception $e){

        } finally {
            return redirect(route('person.index'));
        }
    }
}
