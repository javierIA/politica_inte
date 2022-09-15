<?php

namespace App\Http\Controllers;

use App\Area;
use App\Block;
use App\Exports\ClassExport;
use App\FedDistrict;
use App\FedEntity;
use App\Group;
use App\History;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Municipality;
use App\PoliticalFunction;
use App\Role;
use App\Section;
use App\SystemFunction;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{

    public static $methods = [
        'assignFunction' => ['assignFunction', 'saveFunctionRole'],
        'assignGroup' => ['assignGroup', 'saveGroupRole'],
        'exportData' => ['exportData'],
        'importData' => ['importData']
    ];

    /**
     * Filter the specified resource in storage.
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

        if(!is_null($selection))
            $items['raw'] = sprintf("id in (%s)", join(',', $selection));

        return Excel::download(new ClassExport(Role::class, $items),'role.'.$format);
    }


    /**
     * Filter the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $format
     * @return BinaryFileResponse
     */
    public function importData(Request $request){
        $file = $request->file;
        $exceptions = ['id_fed_entity'=>['table'=>'fed_entitys', 'param' => 'entity_name'],
                       'id_municipality'=>['table'=>'municipalitys', 'param' => 'municipality_name'],
                       'id_section'=>['table'=>'sections', 'param' => 'section_key']];
        Excel::import(new ExcelImport(Role::class, $exceptions), $file);
        return redirect(route('role.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.role');
        $repo  = Role::all();
        $columns = Role::getTableColumns();
        return view('admin.role.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_role');
        $role_type = array('territorial' => trans('admin.territorial'),
            'electoral' => trans('admin.political'));
        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        return view('admin.role.create', compact('title', 'role_type', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district', 'area', 'zone', 'sections', 'block'));
    }

    /**
     * Filter the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $info = [
            'columns' =>  ['selected' ,'id', 'name', 'description', 'type'],
            'controller' => 'RoleController',
            'table' => with(new Role())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'role.edit',
                    'text' => trans('admin.edit_role'),
                    'col' => 'col-sm-3'
                ],
                'destroy' => [
                    'name' => 'role.destroy',
                    'text' => trans('admin.delete_role'),
                    'col' => 'col-sm-3'
                ],
                'assignFunction' => [
                    'name' => 'role.assignFunction',
                    'text' => trans('admin.assign_functions'),
                    'col' => 'col-sm-3'
                ],
                'assignGroup' => [
                    'name' => 'role.assignGroup',
                    'text' => trans('admin.assign_groups'),
                    'col' => 'col-sm-3'
                ]
            ],
            'replace' =>[
                'type' => [
                    'territorial' => trans('admin.territorial'),
                    'electoral' => trans('admin.electoral')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], ["null as selected"], true );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request->all();
        unset($items['_token']);
        $request->validate([
            'name' => 'unique:roles',
        ]);
        $role = new Role();
        $role->fill($items);
        $role->save();
        $this->savelog($role,'save');
        return redirect(route('role.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $title = $role->name;
        $role_type = array('territorial' => trans('admin.territorial'),
            'electoral' => trans('admin.electoral'));
        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $fed_district = FedDistrict::all();
        $loc_district = LocDistrict::all();
        $area = Area::all();
        $zone = Zone::all();
        $sections = Section::all();
        $block = Block::all();
        return view('admin.role.edit', compact('role','title', 'role_type', 'fed_entitys', 'municipalitys', 'fed_district', 'loc_district', 'area', 'zone', 'sections', 'block'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $items = $request->all();
        unset($items['_token']);
        if(Role::where('name',$items['name'])->count() > 1)
            $request->validate([
                'name' => 'unique:name',
            ]);
        $role->fill($items);
        $role->update();
        $this->savelog($role,'update');
        return redirect(route('role.index'));
    }

    /**
     * Assign system functions to role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignFunction($id)
    {
        try {
            $role = Role::findOrFail($id);
            $title = trans('admin.assign_functions');
            $functions = SystemFunction::all();
            $items = array();

            foreach ($functions as $f) {
                try {
                    $class = __NAMESPACE__ . '\\' . $f->system_function_name;
                    $is_dashboard = $f->system_function_name == 'DashBoardController';
                    $methods = isset($class::$methods) ? array_merge($this::$default_methods, $class::$methods) : $this::$default_methods;

                    if ($is_dashboard)
                        $methods = isset($class::$methods) ? $class::$methods : [];

                    $items[$f->id] = array(
                        'id' => $f->id,
                        'system_function_name' => $f->system_function_name,
                        'active' => false,
                        'methods' => $methods,
                        'method_active' => []
                    );
                }
                catch(\Throwable $e){
                    continue;
                }
            }
            $role_functions = $role->system_functions()->get();

            foreach ($role_functions as $f) {
                $data = is_null($f->pivot->methods) || empty($f->pivot->methods)? []: json_decode($f->pivot->methods);
                $items[$f->id]['active'] = true;
                $items[$f->id]['method_active'] = $data;
            }
            return view('admin.role.assign_function', compact('title', 'role', 'items'));

        }
        catch(Exception $e){
            return redirect(route('role.index'));
        }
    }

    /**
     * Assign system functions to role.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function saveFunctionRole(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $data = $request->all();
            $data_to_sync = [];
            foreach ($data['value'] as $d) {
                if (!is_null($d['methods']))
                    $data_to_sync[$d['controller']] = ['methods' => json_encode($d['methods'])];
            }
            $role->system_functions()->sync($data_to_sync);
            return json_encode(['status'=>true, 'url'=> route('role.index')]);
        }
        catch(\Exception $e){
            return json_encode(['status'=>false , 'error'=> $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignGroup($id)
    {
        $role = Role::findOrFail($id);
        $title = trans('admin.assign_groups');
        $functions = Group::where('default','f')->get();
        $items = array();

        foreach ($functions as $f) {
            $items[$f->id] = array(
                'id' => $f->id,
                'group_name' => $f->group_name,
                'active' => false
            );
        }
        $role_groups = $role->groups()->get();
        foreach ($role_groups as $f)
            $items[$f->id]['active'] = true;

        return view('admin.role.assign_group', compact('title', 'role', 'items'));
    }

    /**
     * Assign system functions to role.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function saveGroupRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $groups = $request->group;
        $role->groups()->sync($groups);
        return redirect(route('role.index'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $this->savelog($role,'delete');
        $role->delete();
        return redirect(route('role.index'));
    }
}
