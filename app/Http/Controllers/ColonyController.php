<?php

namespace App\Http\Controllers;

use App\Colony;
use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ColonyController extends Controller
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

        return Excel::download(new ClassExport(Colony::class, $items),'Colony.'.$format);
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
        $exceptions = ['titular_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'vocal_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'id_loc_district'=>['table'=>'loc_districts', 'param' => 'district_number']
        ];
        Excel::import(new ExcelImport(Colony::class), $file);
        return redirect(route('colony.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.colony');
        $repo  = Colony::all();
        $columns = Colony::getTableColumns();
        return view('admin.colony.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.create_colony');
        $items = Colony::all(['id', 'name']);
        return view('admin.colony.create', compact('title', 'items'));
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
            'name' => 'required|unique:colonys',
        ]);
        $items = $request->all();
        $colony = new Colony();
        $colony->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $colony->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$colony->name.'\\';
            $colony->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $colony->save();
        $this->savelog($colony,'save');

        return redirect(route('colony.index'));
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
        $info = [
            'columns' =>  ['selected' ,'id', 'name'],
            'controller' => 'ColonyController',
            'table' => with(new Colony())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'colony.edit',
                    'text' => trans('admin.edit_colony')
                ],
                'destroy' => [
                    'name' => 'colony.destroy',
                    'text' => trans('admin.delete_colony')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], ["null as selected"] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return Response
     */
    public function edit(Colony $colony)
    {
        $title = $colony->name;
        return view('admin.colony.edit', compact('title', 'colony'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Colony $colony)
    {
        $count = Colony::where('name',$request->name)->count();
        if($count >1 || $colony->name != $request->name)
            $request->validate([
                'name' => 'unique:colonys',
            ]);

        $colony->fill($request->all());
        $colony->update();
        $this->savelog($colony,'update');
        return redirect(route('colony.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $colony = Colony::findOrFail($id);
        $this->savelog($colony,'delete');
        $colony->delete();
        return redirect(route('colony.index'));
    }
}
