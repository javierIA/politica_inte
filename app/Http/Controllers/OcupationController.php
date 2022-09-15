<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\Ocupation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OcupationController extends Controller
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

        return Excel::download(new ClassExport(Ocupation::class, $items),'Ocupation.'.$format);
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
        Excel::import(new ExcelImport(Ocupation::class), $file);
        return redirect(route('ocupation.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.ocupation');
        $repo  = Ocupation::all();
        $columns = Ocupation::getTableColumns();
        return view('admin.ocupation.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.create_occupation');
        return view('admin.ocupation.create', compact('title'));
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
            'name' => 'occupation_name|unique:occupations',
        ]);
        $items = $request->all();
        $occupation = new Ocupation();
        $occupation->fill($items);
        $occupation->save();
        $this->savelog($occupation,'save');

        return redirect(route('ocupation.index'));
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
            'columns' =>  ['selected' ,'id', 'occupation_name'],
            'controller' => 'OcupationController',
            'table' => with(new Ocupation())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'ocupation.edit',
                    'text' => trans('admin.edit_ocupation')
                ],
                'destroy' => [
                    'name' => 'ocupation.destroy',
                    'text' => trans('admin.delete_ocupation')
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
    public function edit(Ocupation $ocupation)
    {
        $title = $ocupation->occupation_name;
        return view('admin.ocupation.edit', compact('title', 'ocupation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ocupation $ocupation)
    {
        $count = Ocupation::where('occupation_name',$request->occupation_name)->count();
        if($count >1 || $ocupation->occupation_name != $request->occupation_name)
            $request->validate([
                'occupation_name' => 'required',
            ]);

        $ocupation->fill($request->all());
        $ocupation->update();
        $this->savelog($ocupation,'update');
        return redirect(route('ocupation.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ocupation = Ocupation::findOrFail($id);
        $this->savelog($ocupation,'delete');
        $ocupation->delete();
        return redirect(route('ocupation.index'));
    }
}
