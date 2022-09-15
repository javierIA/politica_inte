<?php

namespace App\Http\Controllers;

use App\BoxType;
use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\PoliticalFunction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PoliticalFunctionController extends Controller
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

        return Excel::download(new ClassExport(PoliticalFunction::class, $items),'PoliticalFunction.'.$format);
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
        Excel::import(new ExcelImport(PoliticalFunction::class), $file);
        return redirect(route('political_function.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.political_function');
        $repo  = PoliticalFunction::all();
        $columns = PoliticalFunction::getTableColumns();
        return view('admin.political_function.index', compact('title', 'repo','columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_political_function');
        $function_type = array('territorial' => trans('admin.territorial'),
                               'political' => trans('admin.political'));
        return view('admin.political_function.create', compact('title','function_type'));
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
            'columns' =>  ['selected' ,'id', 'name','description','type','position'],
            'order_by' => 'T.type, T.position',
            'controller' => 'PoliticalFunctionController',
            'table' => with(new PoliticalFunction())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'political_function.edit',
                    'text' => trans('admin.edit_political_function')
                ],
                'destroy' => [
                    'name' => 'political_function.destroy',
                    'text' => trans('admin.delete_political_function')
                ]
            ],
            'replace' =>[
                'type' => [
                    'political' => trans('admin.political'),
                    'territorial' => trans('admin.territorial')
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
            'name' => 'unique:political_functions',
        ]);

        $items = $request->all();
        $items['fed_entity'] = isset($items['fed_entity']);
        $items['municipality'] = isset($items['municipality']);
        $items['section'] = isset($items['section']);
        $items['block'] = isset($items['block']);
        $items['loc_district'] = isset($items['loc_district']);
        $items['area'] = isset($items['area']);
        $items['zone'] = isset($items['zone']);
        $items['fed_district'] = isset($items['fed_district']);
        unset($items['_token']);

        $political_function = new PoliticalFunction();
        $political_function->fill($items);
        $political_function->save();
        $this->savelog($political_function,'update');
        return redirect(route('political_function.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PoliticalFunction  $politicalFunction
     * @return \Illuminate\Http\Response
     */
    public function show(PoliticalFunction $politicalFunction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PoliticalFunction  $politicalFunction
     * @return \Illuminate\Http\Response
     */
    public function edit(PoliticalFunction $politicalFunction)
    {
        $title = $politicalFunction->name;
        $function_type = array('territorial' => trans('admin.territorial'),
                               'political' => trans('admin.political'));
        return view('admin.political_function.edit', compact('title', 'politicalFunction', 'function_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PoliticalFunction  $politicalFunction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PoliticalFunction $politicalFunction)
    {
        $items = $request->all();
        $items['fed_entity'] = isset($items['fed_entity']);
        $items['municipality'] = isset($items['municipality']);
        $items['section'] = isset($items['section']);
        $items['block'] = isset($items['block']);
        $items['loc_district'] = isset($items['loc_district']);
        $items['area'] = isset($items['area']);
        $items['zone'] = isset($items['zone']);
        $items['fed_district'] = isset($items['fed_district']);

        unset($items['_token']);
        if(PoliticalFunction::where('name',$items['name'])->count() > 1)
            $request->validate([
                'name' => 'unique:name',
            ]);
        $politicalFunction->fill($items);
        $politicalFunction->update();
        $this->savelog($politicalFunction,'update');
        return redirect(route('political_function.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $politicalFunction = PoliticalFunction::findOrFail($id);
        $this->savelog($politicalFunction,'delete');
        $politicalFunction->delete();
        return redirect(route('political_function.index'));
    }
}
