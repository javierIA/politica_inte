<?php

namespace App\Http\Controllers;

use App\Colony;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use App\PostalCode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PostalCodeController extends Controller
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

        return Excel::download(new ClassExport(PostalCode::class, $items),'PostalCode.'.$format);
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
        $exceptions = ['fed_entity_id'=>['table'=>'fed_entitys', 'param' => 'entity_key'],
                       'municipality_id'=>['table'=>'municipalitys', 'param' => 'municipality_key'],
                       'colony_id'=>['table'=>'colonys', 'param' => 'name']];
        Excel::import(new ExcelImport(PostalCode::class, $exceptions), $file);
        return redirect(route('postal_code.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.postal_code');
        $repo  = PostalCode::all();
        $columns = PostalCode::getTableColumns();
        return view('admin.postal_code.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.create_postal_code');
        $fed_entitys= FedEntity::all();
        $municipality= Municipality::all();
        return view('admin.postal_code.create', compact('title', 'fed_entitys', 'municipality'));
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
            'number' => 'required|unique:postal_codes',
        ]);
        $items = $request->all();
        $postalCode = new PostalCode();
        $postalCode->fill($items);
        $postalCode->save();
        $this->savelog($postalCode,'save');

        return redirect(route('postal_code.index'));
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
        $selects = ["(SELECT entity_name FROM fed_entitys WHERE id = T.fed_entity_id) as fed_entity",
                    "(SELECT municipality_name FROM municipalitys WHERE id = T.municipality_id) as municipality",
                    "(SELECT name FROM colonies WHERE id = T.colony_id) as colony",
                    "null as selected"
        ];

        $info = [
            'columns' =>  ['selected' ,'id', 'number','fed_entity', 'municipality', 'colony'],
            'controller' => 'PostalCodeController',
            'table' => with(new PostalCode())->getTable(),
            'route' => [
                'show' => [
                    'name' => 'postal_code.show',
                    'text' => trans('admin.show_postal_code'),
                    'col' => 'col-sm-3'
                ],
                'edit' => [
                    'name' => 'postal_code.edit',
                    'text' => trans('admin.edit_postal_code'),
                    'col' => 'col-sm-3'
                ],
                'destroy' => [
                    'name' => 'postal_code.destroy',
                    'text' => trans('admin.delete_postal_code'),
                    'col' => 'col-sm-3'
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return Response
     */
    public function edit(PostalCode $postalCode)
    {
        $title = $postalCode->number;
        $fed_entitys= FedEntity::all();
        $municipality= Municipality::all();
        return view('admin.postal_code.edit', compact('title', 'postalCode', 'fed_entitys', 'municipality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param PostalCode $postalCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostalCode $postalCode)
    {
        $postalCode->fill($request->all());
        $postalCode->update();
        $this->savelog($postalCode,'update');
        return redirect(route('postal_code.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postalCode = PostalCode::findOrFail($id);
        $this->savelog($postalCode,'delete');
        $postalCode->delete();
        return redirect(route('postal_code.index'));
    }
}
