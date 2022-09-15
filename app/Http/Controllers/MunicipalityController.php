<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use App\Person;
use App\Validation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MunicipalityController extends Controller
{
    public static $methods = [
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

        return Excel::download(new ClassExport(Municipality::class,$items),'Municipality.'.$format);
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
        $exceptions = ['fed_entity_id'=>['table'=>'fed_entitys', 'param' => 'entity_key']];
        Excel::import(new ExcelImport(Municipality::class, $exceptions), $file);
        return redirect(route('municipality.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.municipality');
        $repo  = Municipality::all();
        $columns = Municipality::getTableColumns();
        return view('admin.municipality.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_municipality');
        $fed_entitys = FedEntity::all();
        return view('admin.municipality.create', compact('title', 'fed_entitys'));
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
        $selects = ["(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.titular_person) as titular_name",
                    "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.vocal_person) as vocal_name",
                    "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.representative) as representative_name",
                    "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.alternate) as alternate_name",
                    "null as selected"
        ];
        $info = [
            'columns' =>  ['selected' ,'id', 'municipality_key', 'municipality_name', 'titular_name', 'vocal_name', 'representative_name', 'alternate_name'],
            'controller' => 'MunicipalityController',
            'table' => with(new Municipality())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'municipality.edit',
                    'text' => trans('admin.edit_municipality')
                ],
                'destroy' => [
                    'name' => 'municipality.destroy',
                    'text' => trans('admin.delete_municipality')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
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
            'municipality_key' => 'numeric|max:9999|min:0|unique:municipalitys',
            'map_pdf' => 'max:10000|mimes:pdf',
        ]);
        $items = $request->all();
        $municipality = new Municipality();
        $municipality->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $municipality->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$municipality->municipality_key.'\\';
            $municipality->map_pdf = $this->savefile($request->file, $folder, 'map');
        }

        $municipality->save();
        $this->savelog($municipality,'save');

        return redirect(route('municipality.index'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function show(Municipality $municipality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipality $municipality)
    {
        $title = $municipality->municipality_key;
        $fed_entitys = FedEntity::all();
        return view('admin.municipality.edit', compact('title', 'municipality', 'fed_entitys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipality $municipality)
    {
        $count = Municipality::where('municipality_key',$request->municipality_key)->count();
        if($count >1 || $municipality->municipality_key != $request->municipality_key)
            $request->validate([
                'municipality_key' => 'numeric|max:9999|min:0|unique:municipalitys',
            ]);

        $municipality->municipality_key = $request->municipality_key;
        $municipality->municipality_name = $request->municipality_name;
        if(!is_null($request->file))
            $municipality->map_pdf = $this->saveImage($request->file, $municipality->municipality_key, $municipality);
        $municipality->update();
        $this->savelog($municipality,'update');
        return redirect(route('municipality.index'));
    }


    /**
     * Assign responsible to municipality.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setResponsible($id){
        $municipality = Municipality::findOrFail($id);
        $municipalities = Municipality::all();
        $title = trans('admin.set_responsible').': '.$municipality->municipality_name;
        return view('admin.municipality.responsible', compact('title', 'municipality', 'municipalities'));
    }

    /**
     * Save responsible to municipality.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveResponsible(Request $request, $id){
        $municipality = Municipality::findOrFail($id);
        $data = $request->all();
        foreach ($data as $key => $value){
            if(is_null($value) || empty($value))
                unset($data[$key]);
            else if($value == -1)
                $data[$key] = null;
        }

        $municipality->fill($data);
        $municipality->update();
        $this->savelog($municipality,'update');
        return redirect(route('municipality.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $municipality = Municipality::findOrFail($id);
        $this->savelog($municipality,'delete');
        if(file_exists($municipality->map_pdf))
            unlink($municipality->map_pdf);
        $municipality->delete();
        return redirect(route('municipality.index'));
    }
}
