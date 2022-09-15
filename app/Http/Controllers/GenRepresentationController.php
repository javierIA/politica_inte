<?php

namespace App\Http\Controllers;

use App\Box;
use App\Exports\ClassExport;
use App\FedDistrict;
use App\GenRepresentation;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Person;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GenRepresentationController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData'],
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

        return Excel::download(new ClassExport(GenRepresentation::class, $items),'GenRepresentation.'.$format);
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
        Excel::import(new ExcelImport(GenRepresentation::class), $file);
        return redirect(route('gen_representation.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.gen_representation');
        $columns = GenRepresentation::getTableColumns();
        return view('admin.gen_representation.index', compact('title', 'columns'));
    }

    /**
     * Filter the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $selects = [
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons where id = titular_person) as titular",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons where id = vocal_person) as vocal",
            "(Select district_number FROM fed_district where id = id_fed_district) as fed_district",
            "(Select district_number FROM loc_districts where id = id_loc_district) as loc_district",
            "null as selected"
        ];

        $info = [
            'columns' =>  ['selected' ,
                'id',
                'gen_representation_key',
                'fed_district',
                'loc_district',
                'map_pdf',
                'titular',
                'vocal'],
            'controller' => 'GenRepresentationController',
            'table' => with(new GenRepresentation())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'gen_representation.edit',
                    'text' => trans('admin.edit_gen_representation')
                ],
                'script_function' =>[
                    'name' => 'representing_table.filter',
                    'text' => trans('admin.assign_boxes'),
                    'function' => 'assignBoxes',
                    'icon' => 'fa fa-building',
                    'data' => 'id'
                ],
                'destroy' => [
                    'name' => 'gen_representation.destroy',
                    'text' => trans('admin.delete_gen_representation')
                ]
            ]
        ];

        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.add_gen_representation');
        $columns_person = Person::getTableColumns();
        $fed_district = FedDistrict::all(['id', 'district_number']);
        $loc_district = LocDistrict::all(['id', 'district_number']);
        return view('admin.gen_representation.create', compact('title', 'columns_person', 'fed_district', 'loc_district'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'gen_representation_key' => 'integer|required|unique:gen_representations',
            'map_pdf' => 'max:10000|mimes:pdf',
        ]);
        $items = $request->all();
        $gr = new GenRepresentation();
        $gr->fill($items);

        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $gr->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$gr->gen_representation_key.'\\';
            $gr->map_pdf = $this->savefile($request->file, $folder, 'map'.$gr->id);
        }
        $gr->save();
        $this->savelog($gr,'save');
        return redirect(route('gen_representation.index'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return Response
     */
    public function assignBoxes($id){
        $title = trans('admin.assign_boxes');
        $gen_representation = GenRepresentation::findOrFail($id);
        $sections = Section::all(['id','section_key','section_type']);
        $boxes = DB::table('boxs')
            ->leftJoin('box_types as bt','boxs.id_box_type','=','bt.id')
            ->leftJoin('sections as s','s.id','=','boxs.id_section')
            ->leftJoin('gen_representations as gr','gr.id','=','boxs.id_gen_representation')
            ->select(
                DB::raw("s.section_key || ' - ' || bt.box_type_name || boxs.box_index AS name"),
                'boxs.id',
                'boxs.id_section',
                's.section_key',
                'boxs.id_gen_representation',
                'gr.gen_representation_key'
            )->get(['name','id','id_section','section_key','id_gen_representation','gen_representation_key']);
        return view('admin.gen_representation.assign_boxes', compact('title', 'gen_representation', 'boxes', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return void
     */
    public function saveAssignBoxes(Request $request, $id){
        $items = $request->all();
        $gen_representation = GenRepresentation::findOrFail($id);
        $data = ['id_gen_representation' => $gen_representation->id];
        foreach ($items['boxes'] as $b){
            $box = Box::findOrFail($b);
            $box->fill($data);
            $box->update();
            $this->savelog($box,'update');
        }
        return redirect(route('gen_representation.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GenRepresentation  $genRepresentation
     * @return Response
     */
    public function show(GenRepresentation $genRepresentation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GenRepresentation  $genRepresentation
     * @return Response
     */
    public function edit(GenRepresentation $genRepresentation)
    {
        $title = trans('admin.edit_gen_representation');
        $columns_person = Person::getTableColumns();
        $fed_district = FedDistrict::all(['id', 'district_number']);
        $loc_district = LocDistrict::all(['id', 'district_number']);
        return view('admin.gen_representation.edit', compact('title', 'columns_person', 'fed_district', 'loc_district', 'genRepresentation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GenRepresentation  $genRepresentation
     * @return Response
     */
    public function update(Request $request, GenRepresentation $genRepresentation)
    {
        $items = $request->all();
        unset($items['_token']);
        $genRepresentation->fill($items);
        if(!is_null($request->file))
            $genRepresentation->map_pdf = $this->saveImage($request->file, 'map'.$genRepresentation->id, $genRepresentation);
        $genRepresentation->update();
        $this->savelog($genRepresentation,'update');
        return redirect(route('gen_representation.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GenRepresentation  $genRepresentation
     * @return Response
     */
    public function destroy(GenRepresentation $genRepresentation)
    {
        $this->savelog($genRepresentation,'delete');
        if(file_exists($genRepresentation->map_pdf))
            unlink($genRepresentation->map_pdf);
        $genRepresentation->delete();
        return redirect(route('gen_representation.index'));
    }
}
