<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\SystemFunction;
use App\Validation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ValidationController extends Controller
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

        return Excel::download(new ClassExport(Validation::class, $items),'validation.'.$format);
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
        //$exceptions = ['icon'=>['table'=>'groups', 'param' => 'group_name']];
        Excel::import(new ExcelImport(Validation::class), $file);
        return redirect(route('validation.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.validation');
        $repo  = Validation::all();
        $columns = Validation::getTableColumns();
        return view('admin.validation.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_validation');
        return view('admin.validation.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'name', 'active'],
            'controller' => 'ValidationController',
            'table' => with(new Validation())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'validation.edit',
                    'text' => trans('admin.edit_validation')
                ],
                'destroy' => [
                    'name' => 'validation.destroy',
                    'text' => trans('admin.delete_validation')
                ]
            ],
            'replace' => [
                'active' =>[
                    true => '<i class="entypo-check" style="color: green"></i>',
                    false => '<i class="entypo-cancel" style="color: red"></i>'
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
            'name' => 'unique:validations',
        ]);

        $items = $request->all();
        $items['active'] = isset($items['active']);
        unset($items['_token']);

        $validation = new Validation();
        $validation->fill($items);
        $validation->save();
        $this->savelog($validation,'update');
        return redirect(route('validation.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Validation  $validation
     * @return \Illuminate\Http\Response
     */
    public function show(Validation $validation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Validation  $validation
     * @return \Illuminate\Http\Response
     */
    public function edit(Validation $validation)
    {
        $title = $validation->name;
        return view('admin.validation.edit', compact('title', 'validation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Validation  $validation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validation $validation)
    {
        $items = $request->all();
        $items['active'] = isset($items['active']);
        unset($items['_token']);
        if(Validation::where('name',$items['name'])->count() > 1)
            $request->validate([
                'name' => 'unique:name',
            ]);
        $validation->fill($items);
        $validation->update();
        $this->savelog($validation,'update');
        return redirect(route('validation.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validation = Validation::findOrFail($id);
        $this->savelog($validation,'delete');
        $validation->delete();
        return redirect(route('validation.index'));
    }
}
