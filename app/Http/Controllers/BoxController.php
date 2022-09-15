<?php

namespace App\Http\Controllers;

use App\Address;
use App\Box;
use App\BoxType;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use App\Person;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BoxController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData'],
        'assignRepresenting' => ['assignRepresenting']
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

        return Excel::download(new ClassExport(Box::class, $items),'Box.'.$format);
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
        $exceptions = [ 'id_box_type'=>['table'=>'box_types', 'param' => 'box_type_name'],
                        'id_section'=>['table'=>'sections', 'param' => 'section_key'],
                        'id_gen_representation' => ['table'=>'gen_representations', 'param' => 'gen_representation_key']
                        ];
        Excel::import(new ExcelImport(Box::class, $exceptions), $file);
        return redirect(route('box.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.box');
        $columns = Box::getTableColumns();
        return view('admin.box.index', compact('title',  'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.add_box');
        $section = Section::all(['id', 'section_key']);
        $type = BoxType::all(['id','box_type_name']);
        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $sections = Section::all();
        $columns_address = Address::getTableColumns();
        $columns_person = Person::getTableColumns();
        return view('admin.box.create', compact('title', 'section', 'type', 'sections', 'fed_entitys', 'municipalitys', 'columns_address', 'columns_person'));
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
        $selects = [
            "(Select section_key FROM sections where id = id_section) as section_key",
            "(Select box_type_name from box_types where id = id_box_type) as box_type_name",
            "null as selected"
        ];

        $info = [
            'columns' =>  ['selected' ,
                           'id',
                           'box_type_name',
                           'titular_person1',
                           'titular_person2',
                           'vocal_person',
                           'owner',
                           'id_address',
                           'section_key',
                           'president',
                           'secretary',
                           'teller1',
                           'teller2',
                           'substitute1',
                           'substitute2',
                           'substitute3',
                           'box_index',
                           'owner_name',
                           'address_text'],
            'controller' => 'BoxController',
            'table' => with(new Box())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'box.edit',
                    'text' => trans('admin.edit_box')
                ],
                'assignRepresenting' => [
                    'name' => 'box.assignRepresenting',
                    'text' => trans('admin.assign_representing')
                ],
                'destroy' => [
                    'name' => 'box.destroy',
                    'text' => trans('admin.delete_box')
                ]
            ]
        ];

        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function assignRepresenting($id){
        $box = Box::findOrFail($id);
        $title = trans('admin.assign_representing');
        $columns_person = Person::getTableColumns();
        return view('admin.box.assign_representing', compact('title', 'box', 'columns_person'));
    }

    /**
     * Assign representatives a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRepresenting(Request $request, $id){
        $box = Box::findOrFail($id);
        if(!is_null($box)){
            $items = $request->all();
            $box->fill($items);
            $box->update();
        }
        return redirect(route('box.index'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_section' => 'integer|required',
            'map_pdf' => 'max:10000|mimes:pdf',
        ]);
       $items = $request->all();

        $index =  DB::table('boxs')
            ->select('id')
            ->where('id_box_type', $items['id_box_type'])
            ->count() + 1;

        $allow = DB::table('box_types')
            ->select('quantity_per_box')
            ->where('id', $items['id_box_type'])
            ->where('quantity_per_box','>=',$index)
            ->count() > 0;

        if($allow) $items['box_index'] = $index;
        else{
            $error = ValidationException::withMessages(['field_name_1' => [trans('admin.error_validation')]]);
            throw $error;
        }

       $box = new Box();
       $box->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $box->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$box->id_section.'\\';
            $box->map_pdf = $this->savefile($request->file, $folder, 'map'.$box->id);
        }
       $box->save();
       $this->savelog($box,'save');
       return redirect(route('box.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function show(Box $box)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function edit(Box $box)
    {
        $title = trans('admin.edit_box');
        $section = Section::all(['id', 'section_key']);
        $type = BoxType::all(['id','box_type_name']);
        $sections = Section::all();
        $fed_entitys = FedEntity::all();
        $municipalitys = Municipality::all();
        $columns_address = Address::getTableColumns();
        $columns_person = Person::getTableColumns();
        return view('admin.box.edit', compact('title', 'box', 'section', 'type', 'sections', 'columns_address', 'columns_person', 'fed_entitys', 'municipalitys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Box $box)
    {
        $items = $request->all();
       // var_dump($items);die;
        unset($items['_token']);
        $box->fill($items);
        if(!is_null($request->file))
            $box->map_pdf = $this->saveImage($request->file, 'map'.$box->id, $box);
        $box->update();
        $this->savelog($box,'update');
        return redirect(route('box.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function destroy(Box $box)
    {
        $this->savelog($box,'delete');
        if(file_exists($box->map_pdf))
            unlink($box->map_pdf);
        $box->delete();
        return redirect(route('box.index'));
    }
}
