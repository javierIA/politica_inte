<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\FedDistrict;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Municipality;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class LocDistrictController extends Controller
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

        return Excel::download(new ClassExport(LocDistrict::class, $items),'LocDistrict.'.$format);
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
        Excel::import(new ExcelImport(LocDistrict::class), $file);
        return redirect(route('loc_district.index'));
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
        $selects =  $selects = ["(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.titular_person) as titular_name",
            "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.vocal_person) as vocal_name",
            "null as selected"
        ];
        $info = [
            'columns' =>  ['selected' ,'id', 'district_number', 'titular_name', 'vocal_name', 'map_pdf'],
            'controller' => 'LocDistrictController',
            'table' => with(new LocDistrict())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'loc_district.edit',
                    'text' => trans('admin.edit_loc_district')
                ],
                'destroy' => [
                    'name' => 'loc_district.destroy',
                    'text' => trans('admin.delete_loc_district')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                            'name' => 'district_number',
                            'url' => url("pdf/loc_districts/")
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
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.loc_district');
        $repo  = LocDistrict::all();
        $columns = LocDistrict::getTableColumns();
        return view('admin.loc_district.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.create_loc_district');
        $fed_entity = FedEntity::all();
        $municipality = Municipality::all();
        return view('admin.loc_district.create', compact('title', 'fed_entity', 'municipality'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'district_number' => 'unique:loc_district',
            'municipality_id' => 'required',
        ]);

        $items = $request->all();
        $locDistrict = new LocDistrict();
        $locDistrict->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $locDistrict->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$locDistrict->district_number.'\\';
            $locDistrict->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $locDistrict->save();
        $this->savelog($locDistrict,'save');
        return redirect(route('loc_district.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LocDistrict  $locDistrict
     * @return Response
     */
    public function show(LocDistrict $locDistrict)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LocDistrict  $locDistrict
     * @return Response
     */
    public function edit(LocDistrict $locDistrict)
    {
        $title = trans('admin.loc_district').': '.$locDistrict->district_number;
        $fed_entity = FedEntity::all();
        $municipality = Municipality::all();
        return view('admin.loc_district.edit', compact('title', 'fed_entity', 'municipality', 'locDistrict'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\LocDistrict  $locDistrict
     * @return Response
     */
    public function update(Request $request, LocDistrict $locDistrict)
    {
        $request->validate([
            'district_number' => 'required',//unique:loc_districts
        ]);

        $items = $request->all();
        $locDistrict->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $locDistrict->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$locDistrict->district_number.'\\';
            $locDistrict->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $locDistrict->update();
        $this->savelog($locDistrict,'update');
        return redirect(route('loc_district.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $loc_district = LocDistrict::findOrFail($id);
        $this->savelog($loc_district,'delete');
        $loc_district->delete();
        return redirect(route('loc_district.index'));
    }
}
