<?php

namespace App\Http\Controllers;


use App\Area;
use App\Block;
use App\History;
use App\Person;
use App\Setting;
use App\Zone;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static $default_methods = [
        'index' => ['index'],
        'create' => ['create', 'store'],
        'edit' => ['edit','update'],
        'show' => ['show'],
        'destroy' => ['destroy']
    ];

    public function __construct()
    {
        View::share('setting_info', $this->getSettingsInfo());
        switch (class_basename($this)) {
            case 'LanguageController':
            case 'DefaultController':
                break;
            default:
                $this->middleware('auth');
        }
    }

    public static function getSettingsInfo(){
        return Setting::first();
    }

    public function savelog($object, $action){
        $keys = \Schema::getColumnListing($object->getTable());
        $params = $object->toArray();
        $description = array();

        //object
        foreach ($keys as $key) {
            if (isset($params[$key]))
                $description[$key] = $params[$key];
        }

        $values = array('action' => $action,
            'table' => $object->getTable(),
            'description' => json_encode($description),
            'user' => Auth::user()->jsonUser(),
            'role' => Auth::user()->jsonRole());
        $history = new History();
        $history->fill($values);
        $history->save();
    }

    public function saveImage($file, $name, $object, $pos = null)
    {
        $save_folder = public_path().'/pdf/' . $object->getTable().'/'.$object->elector_key;

        if(!is_dir($save_folder))
            mkdir($save_folder, 0777, true);

        if(is_null($pos)){
            $n = $name . '.' . $file->getClientOriginalExtension();
            $tmp_name = $_FILES['file']['tmp_name'];
        }
        else{
            $n = $name . '.' . $file[$pos]->getClientOriginalExtension();
            $tmp_name = $_FILES['file']['tmp_name'][$pos];
        }
        move_uploaded_file($tmp_name, $save_folder.'/temp_'.$n);
        list($width, $height, $type, $attr) = getimagesize($save_folder.'/temp_'.$n);
        if($height > $width){
            $source = imagecreatefromjpeg($save_folder.'/temp_'.$n);
            $rotate = imagerotate($source, 90, 0);
            imagejpeg($rotate, $save_folder.'/'.$n);
            unlink ( $save_folder.'/temp_'.$n);
        }
        else
            rename($save_folder.'/temp_'.$n,$save_folder.'/'.$n);

        return $save_folder.'/'.$n;
    }

    public function savefile($file, $save_folder, $name)
    {
        if(!is_dir($save_folder))
            mkdir($save_folder, 0777);
        $n = $name . '.' . $file->getClientOriginalExtension();
        move_uploaded_file($_FILES['file']['tmp_name'], $save_folder.$n);
        return $save_folder.$n;
    }

    public function saveAvatar($file, $name,  $pos)
    {
        $save_folder = public_path().'\\images\\avatar\\';

        if(!is_dir($save_folder))
            mkdir($save_folder, 0777);

        $n = $name . '.' . $file->getClientOriginalExtension();

        move_uploaded_file($_FILES['avatar']['tmp_name'], $save_folder.'avatar_'.$n);
        list($width, $height, $type, $attr) = getimagesize($save_folder.'avatar_'.$n);
        if($height > $width){
            $source = imagecreatefromjpeg($save_folder.'avatar_'.$n);
            $rotate = imagerotate($source, 90, 0);
            imagejpeg($rotate, $save_folder.$n);
            unlink ( $save_folder.'avatar_'.$n);
        }

        return '\\images\\avatar\\'.'avatar_'.$n;
    }

    /**
     * Generic filter
     *
     * @param $data data to filter
     * @param $table table to search
     * @param array $exceptions in case of specific param if you want to change the query
     * @param array $join
     * @param array $selects
     * @param bool $not_perm
     * @return \Illuminate\Http\Response
     */
    public function internalFilter($data, $info, $exceptions = [], $join = [], $selects = [], $not_perm = false){
        $string_types = ['text','char','character varying'];
        $date_types = ['timestamp without time zone','date'];
        $var_types = DB::table('information_schema.columns')
            ->select('column_name','data_type')
            ->where('table_name',$info['table'])
            ->pluck('data_type','column_name')->toArray();

        $autocomplete = isset($data['autocomplete']);
        $allparams = isset($data['allparams'])? $data['allparams']: true;

        $info['order_by']= isset($info['order_by'])? $info['order_by']:'T.id';

        if(!$autocomplete && $allparams) {
            $totalData = DB::select(sprintf("Select count(*) from %s", $info['table']))[0]->count;
            $totalFiltered = $totalData;
            $limit = $data['length'];
            $start = $data['start'];
            $order = $info['columns'][$data['order'][0]['column']];
            $dir = $data['order'][0]['dir'];
            $draw = $data['draw'];
            $limit = sprintf("  LIMIT %s OFFSET %s ", $data['length'], $data['start']);
        }
        else
            $limit = "  LIMIT 10 OFFSET 0 ";

        $where = '';
        $query = sprintf("SELECT * FROM (SELECT %s %s",
            $autocomplete?'Distinct ':'',
              $allparams == true? sprintf('T.id as id_%s, *', $info['table']): array_keys($data['filter'])[0]);//. array_keys($data['filter'])[0]
        foreach ($selects as $s)
            $query .= ','. $s;

        $query .= sprintf(" from %s as T ", $info['table']);
        if(isset($data['filter']))
            foreach($data['filter'] as $key => $value) {
                $temp = '';
                $key =  str_replace("_filter", "", $key);
                if (!empty($value) || !is_null($value))
                    if(array_key_exists($key, $exceptions))
                        $temp = $exceptions[$key];
                    else{
                        if(isset($var_types[$key]) && in_array($var_types[$key], $string_types))
                            $temp = "lower(\"$key\") LIKE lower('%$value%')";
                        else if(isset($var_types[$key]) && in_array($var_types[$key], $date_types)){
                            $dtime = \DateTime::createFromFormat("d/m/Y", $value);
                            $temp = "to_char($key, 'DD-MM-YYYY') = "."'".date_format($dtime, 'd-m-Y')."'";
                        }
                        else if(isset($var_types[$key]))
                            $temp = '"'.$key.'" = '."'$value'";
                        else
                            $temp =  !is_numeric($value)? "lower(\"$key\") LIKE lower('%$value%')": '"'.$key.'" = '."'$value'";
                    }

                if (!empty($temp))
                    $where .= $temp . " AND ";
            }
        //joins
        foreach($join as $key => $j)
            $query .= 'LEFT JOIN ' . $j;

        $query .= sprintf(' ORDER BY %s ',$info['order_by']);

        //adicionando permisos segun lugar
        if(!$not_perm) {
            $pal = $this->politicalAccessLevel();
            foreach ($pal as $key => $val)
                if ((isset($var_types[$key]) || strstr($query, $key)) && !is_null($val))
                    $where .= "$val AND ";
        }
        $query .= ') as TT ';
        $where = empty($where)? '': ' where '. rtrim($where, 'AND ');
//        var_dump($query.$where.$limit);die;
        $response = DB::select($query.$where.$limit);
        if($autocomplete)
            return json_encode($response);

        $totalFiltered = count(DB::select($query.$where));

        $data = array();
        if(!empty($response)){
            foreach ($response as $r) {
                if(Controller::checkPoliticalAccess($r, PersonController::$check_query)) {
                    foreach ($info['columns'] as $c) {
                        if (isset($info['replace'][$c])) {
                            if (isset($info['replace'][$c]['replace_link'])) {
                                $url = null;
                                if (!is_null($r->$c)) {
                                    $arr = explode('\\', $r->$c);
                                    $url = $info['replace'][$c]['replace_link']['data']['url'] . '\\' . end($arr);
                                }
                                $name = $info['replace'][$c]['replace_link']['data']['name'];
                                $nestedData[$c] = is_null($url) ? '' : sprintf($info['replace'][$c]['replace_link']['text'], $url, $r->$name);
                            } else $nestedData[$c] = $info['replace'][$c][$r->$c];
                        } else {
                            $nestedData[$c] = $r->$c;
                        }
                    }

                    $options = '';
                    $id = 'id_' . $info['table'];
                    if (auth()->user()->get_Permission($info['controller'], 'show') && isset($info['route']['show'])) {
                        $show = route($info['route']['show']['name'], $r->$id);
                        $col = isset($info['route']['show']['col']) ? $info['route']['show']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="' . $col . '">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-eye"></i>
                                                    </a>
                                                </div>', $show, $info['route']['show']['text']);
                    }
                    if (auth()->user()->get_Permission($info['controller'], 'edit') && isset($info['route']['edit'])) {
                        $edit = route($info['route']['edit']['name'], $r->$id);
                        $col = isset($info['route']['edit']['col']) ? $info['route']['edit']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="' . $col . '">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-pencil"></i>
                                                    </a>
                                                </div>', $edit, $info['route']['edit']['text']);
                    }
                    if (auth()->user()->get_Permission($info['controller'], 'assignResponsibilities') && isset($info['route']['assignResponsibilities'])) {
                        $assignResponsibilities = route($info['route']['assignResponsibilities']['name'], $r->$id);
                        $col = isset($info['route']['assignResponsibilities']['col']) ? $info['route']['assignResponsibilities']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="'.$col.'">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-flow-cascade"></i>
                                                    </a>
                                               </div>', $assignResponsibilities, $info['route']['assignResponsibilities']['text']);
                    }

                    if (auth()->user()->get_Permission($info['controller'], 'assignRepresenting') && isset($info['route']['assignRepresenting'])) {
                        $assignRepresenting = route($info['route']['assignRepresenting']['name'], $r->$id);
                        $col = isset($info['route']['assignRepresenting']['col']) ? $info['route']['assignRepresenting']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="'.$col.'">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-users"></i>
                                                    </a>
                                               </div>', $assignRepresenting, $info['route']['assignRepresenting']['text']);
                    }

                    if (auth()->user()->get_Permission($info['controller'], 'assignFunction') && isset($info['route']['assignFunction'])) {
                        $assignFunction = route($info['route']['assignFunction']['name'], $r->$id);
                        $col = isset($info['route']['assignFunction']['col']) ? $info['route']['assignFunction']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="' . $col . '">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-check"></i>
                                                    </a>
                                               </div>', $assignFunction, $info['route']['assignFunction']['text']);
                    }
                    if (auth()->user()->get_Permission($info['controller'], 'assignGroup') && isset($info['route']['assignGroup'])) {
                        $assignGroup = route($info['route']['assignGroup']['name'], $r->$id);
                        $col = isset($info['route']['assignGroup']['col']) ? $info['route']['assignGroup']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="' . $col . '">
                                                    <a href="%s" class="btn btn-default btn-sm" title="%s">
                                                        <i class="entypo-users"></i>
                                                    </a>
                                               </div>', $assignGroup, $info['route']['assignGroup']['text']);
                    }

                    foreach (array_keys($info['route']) as $script_funct){
                        if(strpos($script_funct,'script') !== false){
                            $function = $info['route'][$script_funct]['function'];
                            $col = isset($info['route'][$script_funct]['col']) ? $info['route']['destroy']['col'] : 'col-sm-3';
                            $modal = isset($info['route'][$script_funct]['modal'])? 'data-toggle="modal" data-target="#'.$info['route'][$script_funct]['modal'].'"': '';
                                                     $da = isset($info['route'][$script_funct]['data'])? $info['route'][$script_funct]['data']: '';
                            $options .= sprintf('<div class="' . $col . '">
                                            <div id="cat-'.$script_funct.'"   
                                                <button type="button" name="btn_'.$script_funct.'" class="btn btn-default btn-sm" title="%s" '.$modal.' onclick="%s ( %s )">
                                                    <i class="%s"></i>
                                                </button>
                                            </div>
                                         </div>', $info['route'][$script_funct]['text'], $info['route'][$script_funct]['function'], $r->$da, $info['route'][$script_funct]['icon']);
                        }
                    }
                    if (auth()->user()->get_Permission($info['controller'], 'destroy') && isset($info['route']['destroy'])) {
                        $destroy = route($info['route']['destroy']['name'], $r->$id);
                        $col = isset($info['route']['destroy']['col']) ? $info['route']['destroy']['col'] : 'col-sm-3';
                        $options .= sprintf('<div class="' . $col . '">
                                            <form id="cat-%s" action="%s" method="post">
                                                %s
                                                <input type="hidden" name="_method" value="DELETE"/>    
                                                <button type="button" name="btnTrash" class="btn btn-danger btn-sm" title="%s" data-id="%s">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </form>
                                         </div>', $r->$id, $destroy, csrf_field(), $info['route']['destroy']['text'], $r->$id);
                    }

                    $nestedData['options'] = $options;
                    $data[] = $nestedData;
                }
            }
        }
        //var_dump('hola');die();

        return json_encode([
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ]);
    }

    public static function getComponent($name, $text = '', $value= '',$others = null){
        $id = $name;
        $component = '';
        switch (gettype($value)){
            case "boolean": $component = <<<EOD
                    <div class="col-sm-2 row" style="margin-left: 1px; margin-bottom: 5px">
                         <input type="checkbox" style="alignment: left" value="1" class="form-control filter_item" id="$id" title='$text' name="$id">$text                         
                    </div>
EOD;
                break;
            case "integer":
            case "double":$component = <<<EOD
                     <div class="row col-sm-2" style="margin-left: 1px; margin-bottom: 5px">
                        <input type="number" class="form-control filter_item" name="$id" id="$id" placeholder="$text" title='$text' autofocus/>
                     </div>
EOD;
                break;
            case "string":$component = <<<EOD
                     <div class="row col-sm-2" style="margin-left: 1px; margin-bottom: 5px">
                            <input type="text" class="form-control filter_item" name="$id" id="$id" placeholder="$text" title='$text' autofocus/>
                     </div>
EOD;
                break;
            case "object":
                if($value instanceof \DateTime){
                    $component = <<<EOD
                        <div class="row col-sm-2" style='margin-left: 1px; margin-bottom: 5px;'>
                            <input type="text" class="form-control js_datepicker filter_item" name='$id' id='$id' title='$text'  placeholder="$text" autofocus>
                        </div>
EOD;
                }
                break;

            case "array":
                $component = "
                     <div class='row col-sm-2' style='margin-left: 1px; margin-bottom: 5px;'>
                        <select class='form-control filter_item js_select' name='$id' id='$id' title='$text' autofocus>
                            <option value='' style='display:none' disabled selected hidden>$text</option>";
                foreach($value as $key => $value)
                    $component .="<option value='$key'>".$value."</option>";
                $component .="</select>
                     </div>";
                break;
        }
        return ['component'=>$component, 'text'=>$text, 'id'=>$id];
    }

    public static function methodsByController($class){
        try {
            switch ($class) {
                case 'UserController':
                    return isset(UserController::$methods)? UserController::$methods:[];
                    break;
                case 'BoxController':
                    return isset(BoxController::$methods)? BoxController::$methods:[];
                    break;
                case 'BoxTypeController':
                    return isset(BoxTypeController::$methods)? BoxTypeController::$methods:[];
                    break;
                case 'GroupController':
                    return isset(GroupController::$methods)? GroupController::$methods:[];
                    break;
                case 'PoliticalFunctionController':
                    return isset(PoliticalFunctionController::$methods)? PoliticalFunctionController::$methods:[];
                    break;
                case 'RoleController':
                    return isset(RoleController::$methods)? RoleController::$methods:[];
                    break;
                case 'SystemFunctionController':
                    return isset(SystemFunctionController::$methods)? SystemFunctionController::$methods:[];
                    break;
                case 'AreaController':
                    return isset(AreaController::$methods)? AreaController::$methods:[];
                    break;
                case 'SocialNetworkController':
                    return isset(SocialNetworkController::$methods)? SocialNetworkController::$methods:[];
                    break;
                case 'ValidationController':
                    return isset(ValidationController::$methods)? ValidationController::$methods:[];
                    break;
                case 'HistoryController':
                    return isset(HistoryController::$methods)? HistoryController::$methods:[];
                    break;
                case 'MunicipalityController':
                    return isset(MunicipalityController::$methods)? MunicipalityController::$methods:[];
                    break;
                case 'FedEntityController':
                    return isset(FedEntityController::$methods)? FedEntityController::$methods:[];
                    break;
                case 'AddressController':
                    return isset(AddressController::$methods)? AddressController::$methods:[];
                    break;
                case 'PersonController':
                    return isset(PersonController::$methods)? PersonController::$methods:[];
                    break;
                case 'SettingController':
                    return isset(SettingController::$methods)? SettingController::$methods:[];
                    break;
                case 'PhoneCodeController':
                    return isset(PhoneCodeController::$methods)? PhoneCodeController::$methods:[];
                    break;
                case 'LocDistrictController':
                    return isset(LocDistrictController::$methods)? LocDistrictController::$methods:[];
                    break;
                case 'ZoneController':
                    return isset(ZoneController::$methods)? ZoneController::$methods:[];
                    break;
                case 'SectionController':
                    return isset(SectionController::$methods)? SectionController::$methods:[];
                    break;
                case 'BlockController':
                    return isset(BlockController::$methods)? BlockController::$methods:[];
                    break;
                case 'DashBoardController':
                    return isset(DashBoardController::$methods)? DashBoardController::$methods:[];
                    break;
                case 'FedDistrictController':
                    return isset(FedDistrictController::$methods)? FedDistrictController::$methods:[];
                    break;
                case 'ColonyController':
                    return isset(ColonyController::$methods)? ColonyController::$methods:[];
                    break;
                case 'OcupationController':
                    return isset(OcupationController::$methods)? OcupationController::$methods:[];
                    break;
                case 'StreetController':
                    return isset(StreetController::$methods)? StreetController::$methods:[];
                    break;
                case 'PostalCodeController':
                    return isset(PostalCodeController::$methods)? PostalCodeController::$methods:[];
                    break;
                case 'GenRepresentationController':
                    return isset(GenRepresentationController::$methods)? GenRepresentationController::$methods:[];
                    break;
                case 'NotificationController':
                    return isset(NotificationController::$methods)? NotificationController::$methods:[];
                    break;
            }
        }
        catch(\Exception $e){
            return [];
        }
    }

    public static function politicalAccessLevel($default = true){
        $state = [];
        $municipality = [];
        $fed_district = [];
        $loc_district = [];
        $area = [];
        $zone = [];
        $section = [];
        $block = [];

        // obteniendo acceso segun rol
        $perm = auth()->user()->roles()->get();
        foreach ($perm as $p){
            if(!is_null($p->fed_entity_id))
                $state[] = $p->fed_entity_id;
            if(!is_null($p->municipality_id))
                $municipality[] = $p->municipality_id;
            if(!is_null($p->fed_district_id))
                $fed_district[] = $p->fed_district_id;
            if(!is_null($p->loc_district_id))
                $loc_district[] = $p->loc_district_id;
            if(!is_null($p->area_id))
                $area[] = $p->area_id;
            if(!is_null($p->zone_id))
                $zone[] = $p->zone_id;
            if(!is_null($p->section_id))
                $section[] = $p->section_id;
            if(!is_null($p->block_id))
                $block[] = $p->block_id;
        }

        // obteniendo acceso segun permisos politicos
        if(!is_null(auth()->user()->person_id)){
            $politicf = Person::findOrFail(auth()->user()->person_id)->political_functions()->get();
            foreach($politicf as $pf){
                if(!is_null($pf->fed_entity_id))
                    $state[] = $pf->fed_entity_id;
                if(!is_null($pf->municipality_id))
                    $municipality[] = $pf->municipality_id;
                if(!is_null($pf->fed_district_id))
                    $fed_district[] = $pf->fed_district_id;
                if(!is_null($pf->loc_district_id))
                    $loc_district[] = $pf->loc_district_id;
                if(!is_null($pf->area_id))
                    $area[] = $pf->area_id;
                if(!is_null($pf->zone_id))
                    $zone[] = $pf->zone_id;
                if(!is_null($pf->section_id))
                    $section[] = $pf->section_id;
                if(!is_null($pf->block_id))
                    $block[] = $pf->block_id;
            }
        }

        if ($default)
            return [
                'fed_entity_id' => count($state)>0? "fed_entity_id in (".implode(",", array_filter($state)).")": null,
                'municipality_id' => count($municipality)>0? "municipality_id in (".implode(",", array_filter($municipality)).")":null,
                'fed_district_id' => count($fed_district)>0? "fed_district_id in (".implode(",", array_filter($fed_district)).")": null,
                'loc_district_id' => count($loc_district)>0? "loc_district_id in (".implode(",", array_filter($loc_district)).")": null,
                'area_id' => count($area)>0? "area_id in (".implode(",", array_filter($area)).")": null,
                'zone_id' => count($zone)>0? "zone_id in (".implode(",", array_filter($zone)).")": null,
                'section_id' => count($section)>0? "section_id in (".implode(",", array_filter($section)).")": null,
                'block_id' => count($block)>0? "block_id in (".implode(",", array_filter($block)).")": null,
            ];

        return [
            'fed_entity_id' => array_filter($state),
            'municipality_id' => array_filter($municipality),
            'fed_district_id' => array_filter($fed_district),
            'loc_district_id' => array_filter($loc_district),
            'area_id' => array_filter($area),
            'zone_id' => array_filter($zone),
            'section_id' => array_filter($section),
            'block_id' => array_filter($block),
        ];
    }

    public static function checkPoliticalAccess($object, $query){
        try {

            $access = Controller::politicalAccessLevel(false);
            $full = true;
            foreach($access as $a)
                if(!empty($a)){
                    $full = false;
                    break;
                }
            // si no tiene restricciones tiene permiso full
            if($full) return true;

            // si no esta asociado con ninguna persona (usualmente el administrador) tiene permiso full
            if (is_null(Auth::user()->person_id)) return true;

            $data = DB::select(sprintf($query, Auth::user()->person_id))[0];
            $areas =  Area::where('loc_district_id', $data->loc_district_id)->pluck('id')->toArray();
            $zones = Zone::whereIn('area_id', $areas)->pluck('id')->toArray();
            $blocks = Block::where('section_id', $data->section_id)->pluck('id')->toArray();

            return
                in_array($data->fed_entity_id, $access['fed_entity_id']) ||
                in_array($data->municipality_id, $access['municipality_id']) ||
                in_array($data->fed_district_id, $access['fed_district_id']) ||
                in_array($data->loc_district_id, $access['loc_district_id']) ||
                in_array($data->section_id, $access['section_id']) ||
                !empty(array_intersect($areas,$access['area_id'])) ||
                !empty(array_intersect($zones,$access['zone_id'])) ||
                !empty(array_intersect($blocks,$access['block_id']));
        }
        catch(\Exception $e){
            return false;
        }
    }

}
