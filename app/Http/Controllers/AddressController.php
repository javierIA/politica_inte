<?php

namespace App\Http\Controllers;

use App\Address;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use App\Person;
use App\Section;
use App\SocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AddressController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData'],
        'post_add' => ['post_add']
    ];
    private static $check_query = "Select *, (Select fed_district_id FROM sections where id = T.section_id) as fed_district_id,
                                    (Select loc_district_id FROM sections where id = T.section_id) as loc_district_id
                                    FROM (Select id_fed_entity as fed_entity_id, id_municipality as municipality_id, id_section as section_id
                                    from address WHERE id = %s) as T";

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

        if(is_null($selection)) {
            $data = Controller::politicalAccessLevel();
            $temp = "";
            $temp .= is_null($data['fed_entity_id']) ? '' : $data['fed_entity_id'] . ' OR ';
            $temp .= is_null($data['municipality_id']) ? '' : $data['municipality_id'] . ' OR ';
            $temp .= is_null($data['section_id']) ? '' : $data['section_id'] . ' OR ';
            $temp = rtrim($temp, 'OR ');
            if (!empty($temp))
                $items['raw'] = $temp;
        }
        else
            $items['raw'] = sprintf("id in (%s)", join(',', $selection));

        //verify
        return Excel::download(new ClassExport(Address::class, $items),'Address.'.$format);
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
        $exceptions = ['id_municipality'=>['table'=>'municipalitys', 'param' => 'municipality_name'],
                       'id_fed_entity'=>['table'=>'fed_entitys', 'param' => 'entity_key']
        ];
        Excel::import(new ExcelImport(Address::class), $file);
        return redirect(route('address.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.address');
        $repo = Address::all();
        $columns = Address::getTableColumns();
        return view('admin.address.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.add_address');
        $municipalitys = Municipality::all();
        $fed_entitys = FedEntity::all();
        $sections = Section::all();
        return view('admin.address.create', compact('title', 'municipalitys', 'fed_entitys', 'sections'));
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
        $not_perm = isset($data['remove_permits']);
        unset($data['remove_permits']);

        $join = ['id_municipality' => "municipalitys as M on M.id = T.municipality_id ",
                 'id_fed_entity' => "fed_entitys as F on F.id = T.fed_entity_id "];

        $info = [
            'columns' =>  ['selected' ,'id_address', 'street', 'postal_code','id_municipality','id_fed_entity','id_section', 'municipality_name', 'entity_name', 'external_number', 'neighborhood', 'postal_code','map_pdf'],
            'controller' => 'AddressController',
            'table' => with(new Address())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'address.edit',
                    'text' => trans('admin.edit_address')
                ],
                'destroy' => [
                    'name' => 'address.destroy',
                    'text' => trans('admin.delete_address')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, $join, ["null as selected"], $not_perm );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post_add(Request $request){
        try {
            $request->validate([
                'street' => 'required',
                'external_number' => 'integer|required',
                'id_municipality_address' => 'integer|required',
                'id_fed_entity_address' => 'integer|required',
                'id_section_address' => 'integer|required',
                'postal_code' => 'required|integer',
            ]);

            $request->request->add(['id_municipality' => $request->id_municipality_address,
                                    'id_fed_entity' => $request->id_fed_entity_address,
                                    'id_section' => $request->id_section_address]);
            $items = $request->all();
            unset($items['id_municipality_address']);
            unset($items['id_fed_entity_address']);
            unset($items['id_section_address']);
            unset($items['_token']);

            $exist = Address::where($items)->first();
            if(!is_null($exist))
                return json_encode(['status'=>true , 'object'=> $exist, 'exist'=> true]);

            $address = new Address();
            $address->fill($items);
            $address->save();
            $this->savelog($address, 'save');
            return json_encode(['status'=>true , 'object'=> $address, 'exist'=> false]);
        }
        catch(Exception $e){
            return json_encode(['status'=>false , 'object'=> $e]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'street' => 'required',
            'external_number' => 'integer|required',
            'id_municipality' => 'integer|required',
            'id_fed_entity' => 'integer|required',
            'postal_code' => 'required|integer',
        ]);

        $address = new Address();
        $address->fill($request->all());
        $address->save();
        $this->savelog($address,'save');

        return redirect(route('address.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        if(!Controller::checkPoliticalAccess($address, AddressController::$check_query))
            return redirect(route('address.index'));
        $title = $address->street . ' '. $address->external_number;
        $municipalitys = Municipality::all();
        $fed_entitys = FedEntity::all();
        return view('admin.address.edit', compact('title', 'fed_entitys', 'municipalitys', 'address'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        $request->validate([
            'street' => 'required',
            'external_number' => 'integer|required',
            'id_municipality' => 'integer|required',
            'id_fed_entity' => 'integer|required',
            'postal_code' => 'required|integer',
        ]);

        $address->fill($request->all());
        $address->update();
        $this->savelog($address,'update');
        return redirect(route('address.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if(!Controller::checkPoliticalAccess($address, AddressController::$check_query))
            return redirect(route('address.index'));

        $this->savelog($address,'delete');
        $address->delete();
        return redirect(route('address.index'));
    }
}
