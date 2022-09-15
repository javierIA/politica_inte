<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\FedDistrict;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FedDistrictController extends Controller
{

    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData']
    ];

    /**
     * Filter the specified resource in storage.
     *
     * @param Request $request
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

        return Excel::download(new ClassExport(FedDistrict::class, $items),'FedDistrict.'.$format);
    }


    /**
     * Filter the specified resource in storage.
     *
     * @param Request $request
     * @param $format
     * @return BinaryFileResponse
     */
    public function importData(Request $request){
        $file = $request->file;
        $exceptions = ['municipality_id'=>['table'=>'municipalitys', 'param' => 'municipality_key']];
        Excel::import(new ExcelImport(FedDistrict::class, $exceptions), $file);
        return redirect(route('fed_district.index'));
    }

    /**
     * Filter the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $selects = ["(SELECT municipality_name FROM municipalitys WHERE id = T.municipality_id) as municipality",
                    "null as selected"];
        $info = [
            'columns' =>  ['selected' ,'id', 'district_number', 'municipality', 'map_pdf'],
            'controller' => 'FedDistrictController',
            'table' => with(new FedDistrict())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'fed_district.edit',
                    'text' => trans('admin.edit_fed_district')
                ],
                'destroy' => [
                    'name' => 'fed_district.destroy',
                    'text' => trans('admin.delete_fed_district')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                            'name' => 'district_number',
                            'url' => url("pdf/fed_district/")
                        ]
                    ]
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.fed_district');
        $repo  = FedDistrict::all();
        $columns = FedDistrict::getTableColumns();
        return view('admin.fed_district.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_fed_district');
        $municipality = Municipality::all();
        $fed_entity = FedEntity::all();
        return view('admin.fed_district.create', compact('title', 'fed_entity', 'municipality'));
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
            'district_number' => 'unique:fed_districts',
            'municipality_id' => 'required'
        ]);

        $items = $request->all();
        $fedDistrict = new FedDistrict();
        $fedDistrict->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $fedDistrict->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$fedDistrict->municipality_id.'\\';
            $fedDistrict->map_pdf = $this->savefile($request->file, $folder, 'map');
        }

        $fedDistrict->save();
        $this->savelog($fedDistrict,'save');
        return redirect(route('fed_district.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FedDistrict  $fedDistrict
     * @return \Illuminate\Http\Response
     */
    public function show(FedDistrict $fedDistrict)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FedDistrict  $fedDistrict
     * @return \Illuminate\Http\Response
     */
    public function edit(FedDistrict $fedDistrict)
    {
        $title = trans('admin.fed_district').': '.$fedDistrict->district_number;
        $fed_entity = FedEntity::all(['id', 'entity_name']);
        $municipality = Municipality::all(['id', 'municipality_name', 'fed_entity_id']);
        return view('admin.fed_district.edit', compact('title', 'fedDistrict', 'fed_entity', 'municipality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FedDistrict  $fedDistrict
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FedDistrict $fedDistrict)
    {
        $filter = ['municipality_id' => 'required', 'district_number' => 'required'];
        $count = FedDistrict::where('district_number',$request->district_number)->count();
        if($count >1 || $fedDistrict->district_number != $request->district_number)
            $filter['district_number'] = 'required|unique:loc_districts';

        $request->validate($filter);
        $items = $request->all();

        $fedDistrict->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $fedDistrict->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$fedDistrict->municipality_key.'\\';
            $fedDistrict->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $fedDistrict->update();
        $this->savelog($fedDistrict,'update');
        return redirect(route('fed_district.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FedDistrict  $fedDistrict
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fedDistrict = FedDistrict::findOrFail($id);
        $this->savelog($fedDistrict,'delete');
        $fedDistrict->delete();
        return redirect(route('fed_district.index'));
    }
}
