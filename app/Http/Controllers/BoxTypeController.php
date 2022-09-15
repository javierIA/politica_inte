<?php

namespace App\Http\Controllers;

use App\BoxType;
use App\Exports\ClassExport;
use App\Group;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BoxTypeController extends Controller
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

        return Excel::download(new ClassExport(BoxType::class, $items),'BoxType.'.$format);
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
        Excel::import(new ExcelImport(BoxType::class), $file);
        return redirect(route('box_type.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.box_type');
        $repo  = BoxType::all();
        $columns = BoxType::getTableColumns();
        return view('admin.box_type.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_box_type');
        return view('admin.box_type.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'box_type_name', 'quantity_per_box'],
            'controller' => 'BoxTypeController',
            'table' => with(new BoxType())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'box_type.edit',
                    'text' => trans('admin.edit_box_type')
                ],
                'destroy' => [
                    'name' => 'box_type.destroy',
                    'text' => trans('admin.delete_box_type')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], ["null as selected"] );
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
            'box_type_name' => 'unique:box_types',
        ]);

        $items = $request->all();
        unset($items['_token']);

        $box_type = new BoxType();
        $box_type->fill($items);
        $box_type->save();
        $this->savelog($box_type,'update');
        return redirect(route('box_type.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BoxType  $boxType
     * @return \Illuminate\Http\Response
     */
    public function show(BoxType $boxType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BoxType  $boxType
     * @return \Illuminate\Http\Response
     */
    public function edit(BoxType $boxType)
    {
        $title = $boxType->box_type_name;
        return view('admin.box_type.edit', compact('title', 'boxType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BoxType  $boxType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BoxType $boxType)
    {
        $items = $request->all();
        unset($items['_token']);
        if(BoxType::where('box_type_name',$items['box_type_name'])->count() > 1)
            $request->validate([
                'box_type_name' => 'unique:box_type_name',
            ]);
        $boxType->fill($items);
        $boxType->update();
        $this->savelog($boxType,'update');
        return redirect(route('box_type.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $boxType = BoxType::findOrFail($id);
        $this->savelog($boxType,'delete');
        $boxType->delete();
        return redirect(route('box_type.index'));
    }
}
