<?php

namespace App\Http\Controllers;

use App\Address;
use App\Area;
use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\Zone;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ZoneController extends Controller
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

        return Excel::download(new ClassExport(Zone::class, $items),'Zone.'.$format);
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
        $exceptions = ['area_id'=>['table'=>'areas', 'param' => 'area_key']];
        Excel::import(new ExcelImport(Zone::class, $exceptions), $file);
        return redirect(route('zone.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.zone');
        $repo = Zone::all();
        $columns = Zone::getTableColumns();
        return view('admin.zone.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_zone');
        $areas = Area::all();
        return view('admin.zone.create', compact('title', 'areas'));
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
            'columns' =>  ['selected' ,'id', 'zone_key', 'titular_name', 'vocal_name', 'map_pdf'],
            'controller' => 'ZoneController',
            'table' => with(new Zone())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'zone.edit',
                    'text' => trans('admin.edit_zone')
                ],
                'destroy' => [
                    'name' => 'zone.destroy',
                    'text' => trans('admin.delete_zone')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                            'name' => 'zone_key',
                            'url' => url("pdf/zones/")
                        ]
                    ]
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
            'zone_key' => 'required|unique:zones',
            'area_id' => 'required'
        ]);

        $items = $request->all();
        $zone = new Zone();
        $zone->fill($items);
        $zone->save();
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $zone->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$zone->zone_key.'\\';
            $zone->map_pdf = $this->savefile($request->file, $folder, 'map');
            $zone->update();
        }
        $this->savelog($zone,'save');
        return redirect(route('zone.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        $title = trans('admin.zone').': '.$zone->zone_key;
        $areas = Area::all();
        return view('admin.zone.edit', compact('title', 'zone', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        $filter = [ 'zone_key' => 'required', 'area_id' => 'required'];
        $count = Zone::where('zone_key',$request->zone_key)->count();
        if($count >1 || $zone->zone_key != $request->zone_key)
            $filter['zone_key'] = 'required|unique:zones';
        $request->validate($filter);

        $items = $request->all();
        $zone->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $zone->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$zone->zone_key.'\\';
            $zone->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $zone->update();
        $this->savelog($zone,'update');
        return redirect(route('zone.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $this->savelog($zone,'delete');
        $zone->delete();
        return redirect(route('zone.index'));
    }
}
