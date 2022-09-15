<?php

namespace App\Http\Controllers;

use App\Area;
use App\BoxType;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Municipality;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AreaController extends Controller
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

        return Excel::download(new ClassExport(Area::class, $items),'area.'.$format);
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
        $exceptions = ['loc_district_id'=>['table'=>'loc_districts', 'param' => 'district_number']];
        Excel::import(new ExcelImport(Area::class, $exceptions), $file);
        return redirect(route('area.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.area');
        $repo  = Area::all();
        $columns = Area::getTableColumns();
        return view('admin.area.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_area');
        $loc_district = LocDistrict::all(['id', 'district_number']);
        return view('admin.area.create', compact('title', 'loc_district'));
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
            'area_key' => 'required|numeric|max:9999|min:0|unique:areas',
            'loc_district_id' => 'required',
        ]);

        $items = $request->all();
        $area = new Area();
        $area->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $area->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$area->area_key.'\\';
            $area->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $area->save();
        $this->savelog($area,'save');
        return redirect(route('area.index'));

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
                    "null as selected"];
        $info = [
            'columns' =>  ['selected' ,'id', 'area_key', 'titular_name', 'vocal_name', 'map_pdf'],
            'controller' => 'AreaController',
            'table' => with(new Area())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'area.edit',
                    'text' => trans('admin.edit_area')
                ],
                'destroy' => [
                    'name' => 'area.destroy',
                    'text' => trans('admin.delete_area')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                            'name' => 'area_key',
                            'url' => url("pdf/areas/")
                        ]
                    ]
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        $title = $area->area_key;
        $loc_district = LocDistrict::all(['id', 'district_number']);

        return view('admin.area.edit', compact('title', 'area', 'loc_district'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $filter = ['loc_district_id' => 'required', 'area_key'=>'required'];
        $count = Area::where('area_key',$request->area_key)->count();
        if($count >1 || $area->area_key != $request->area_key)
            $filter['area_key'] = 'required|numeric|max:9999|min:0|unique:areas';
        $request->validate($filter);

        $items = $request->all();
        $area->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $area->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$area->district_number.'\\';
            $area->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $area->update();
        $this->savelog($area,'update');
        return redirect(route('area.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $this->savelog($area,'delete');
        if(file_exists($area->map_pdf))
            unlink($area->map_pdf);
        $area->delete();
        return redirect(route('area.index'));
    }
}
