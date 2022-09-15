<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\PoliticalFunction;
use App\SystemFunction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SystemFunctionController extends Controller
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

        return Excel::download(new ClassExport(SystemFunction::class, $items),'SystemFunction.'.$format);
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
        Excel::import(new ExcelImport(SystemFunction::class), $file);
        return redirect(route('system_function.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.system_function');
        $repo  = SystemFunction::all();
        $columns = SystemFunction::getTableColumns();
        return view('admin.system_function.index', compact('title', 'repo', 'columns'));
    }

    /**s
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_system_function');
        return view('admin.system_function.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'system_function_name'],
            'controller' => 'SystemFunctionController',
            'table' => with(new SystemFunction())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'system_function.edit',
                    'text' => trans('admin.edit_system_function')
                ],
                'destroy' => [
                    'name' => 'system_function.destroy',
                    'text' => trans('admin.delete_system_function')
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
            'system_function_name' => 'unique:system_functions',
        ]);

        $items = $request->all();
        unset($items['_token']);

        $system_function = new SystemFunction();
        $system_function->fill($items);
        $system_function->save();
        $this->savelog($system_function,'update');
        return redirect(route('system_function.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SystemFunction  $systemFunction
     * @return \Illuminate\Http\Response
     */
    public function show(SystemFunction $systemFunction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SystemFunction  $systemFunction
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemFunction $systemFunction)
    {
        $title = $systemFunction->system_function_name;
        return view('admin.system_function.edit', compact('title', 'systemFunction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SystemFunction  $systemFunction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemFunction $systemFunction)
    {
        $items = $request->all();
        unset($items['_token']);
        if(SystemFunction::where('system_function_name',$items['system_function_name'])->count() > 1)
            $request->validate([
                'system_function_name' => 'unique:system_function_name',
            ]);
        $systemFunction->fill($items);
        $systemFunction->update();
        $this->savelog($systemFunction,'update');
        return redirect(route('system_function.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $systemFunction = SystemFunction::findOrFail($id);
        $this->savelog($systemFunction,'delete');
        $systemFunction->delete();
        return redirect(route('system_function.index'));
    }
}
