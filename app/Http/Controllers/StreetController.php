<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\Section;
use App\Street;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StreetController extends Controller
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

        return Excel::download(new ClassExport(Street::class, $items),'Street.'.$format);
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
        Excel::import(new ExcelImport(Street::class), $file);
        return redirect(route('street.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.street');
        $repo  = Street::all();
        $columns = Street::getTableColumns();
        return view('admin.street.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.create_street');
        $items = Street::all(['id', 'name']);
        return view('admin.street.create', compact('title', 'items'));
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
            'name' => 'required|unique:streets',
        ]);
        $items = $request->all();
        $street = new Street();
        $street->fill($items);
        $street->save();
        $this->savelog($street,'save');

        return redirect(route('street.index'));
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
            'controller' => 'StreetController',
            'table' => with(new Street())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'street.edit',
                    'text' => trans('admin.edit_street')
                ],
                'destroy' => [
                    'name' => 'street.destroy',
                    'text' => trans('admin.delete_street')
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
    public function edit(Street $street)
    {
        $title = $street->name;
        return view('admin.street.edit', compact('title', 'street'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Street $street)
    {
        $count = Street::where('name',$request->name)->count();
        if($count >1 || $street->name != $request->name)
            $request->validate([
                'name' => 'unique:streets',
            ]);

        $street->fill($request->all());
        $street->update();
        $this->savelog($street,'update');
        return redirect(route('street.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $street = Street::findOrFail($id);
        $this->savelog($street,'delete');
        $street->delete();
        return redirect(route('street.index'));
    }

}
