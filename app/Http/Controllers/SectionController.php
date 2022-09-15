<?php

namespace App\Http\Controllers;

use App\Area;
use App\Block;
use App\FedDistrict;
use App\Municipality;
use App\Section;
use App\BoxType;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Zone;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SectionController extends Controller
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
        $exception = ['map_pdf'];

        return Excel::download(new ClassExport(Section::class, $items, $exception),'section.'.$format);
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
        $exceptions = [
            'fed_district_id'=>['table'=>'fed_district', 'param' => 'district_number'],
            'loc_district_id'=>['table'=>'loc_districts', 'param' => 'district_number'],
        ];
        Excel::import(new ExcelImport(Section::class, $exceptions), $file);
        return redirect(route('section.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.section');
        $repo  = Section::all();
        $columns = Section::getTableColumns();
        return view('admin.section.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_section');
        $section_type = ['urban' => trans('admin.urban'), 'rural' => trans('admin.rural'), 'mixed' => trans('admin.mixed'),];
        $fed_district = FedDistrict::all(['id', 'district_number']);
        $loc_district = LocDistrict::all(['id', 'district_number']);
        $fed_entity = FedEntity::all(['id', 'entity_name']);
        $municipality = Municipality::all(['id', 'municipality_name', 'fed_entity_id']);
        return view('admin.section.create', compact('title', 'section_type', 'fed_district', 'loc_district', 'municipality', 'fed_entity'));
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
            'section_key' => 'numeric|max:9999|min:0|unique:sections',
            'map_pdf' => 'max:10000|mimes:pdf',
        ]);
        $items = $request->all();
        $section = new Section();
        $section->fill($items);
        if(!is_null($request->file)){
            $folder = public_path().'\\pdf\\' . $section->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$section->section_key.'\\';
            $section->map_pdf = $this->savefile($request->file, $folder, 'map');
        }
        $section->save();
        $this->savelog($section,'save');

        return redirect(route('section.index'));

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
                    "(SELECT district_number FROM fed_districts WHERE id = T.fed_district_id) as fed_district",
                    "(SELECT district_number FROM loc_districts WHERE id = T.loc_district_id) as loc_district",
                    "(SELECT municipality_name FROM municipalitys WHERE id = T.municipality_id) as municipality",
                    "null as selected"];

        $info = [
            'columns' =>  ['selected' ,'id', 'section_key', 'section_type', 'municipality', 'fed_district', 'loc_district', 'map_pdf'],
            'controller' => 'SectionController',
            'table' => with(new Section())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'section.edit',
                    'text' => trans('admin.edit_section')
                ],
                'destroy' => [
                    'name' => 'section.destroy',
                    'text' => trans('admin.delete_section')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                            'name' => 'section_key',
                            'url' => url("pdf/sections/")
                        ]
                    ]
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        $title = $section->section_key;
        $section_type = ['urban' => trans('admin.urban'), 'rural' => trans('admin.rural'), 'mixed' => trans('admin.mixed')];
        $fed_district = FedDistrict::all(['id', 'district_number']);
        $loc_district = LocDistrict::all(['id', 'district_number']);
        $fed_entity = FedEntity::all(['id', 'entity_name']);
        $municipality = Municipality::all(['id', 'municipality_name', 'fed_entity_id']);
        return view('admin.section.edit', compact('title', 'section', 'section_type', 'fed_district', 'loc_district', 'fed_entity', 'municipality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $count = Section::where('section_key',$request->section_key)->count();
        if($count >1 || $section->section_key != $request->section_key)
            $request->validate([
                'section_key' => 'numeric|max:9999|min:0|unique:sections',
            ]);

        $items = $request->all();
        $section->fill($items);

        if(!is_null($request->file)){
            if(file_exists($section->map_pdf))
                unlink($section->map_pdf);
            $folder = public_path().'\\pdf\\' . $section->getTable();
            if(!is_dir($folder))
                mkdir($folder);
            $folder .= '\\'.$section->section_key.'\\';
            $section->map_pdf = $this->savefile($request->file, $folder, 'map');
        }

        $section->update();
        $this->savelog($section,'update');
        return redirect(route('section.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $this->savelog($section,'delete');
        if(file_exists($section->map_pdf))
            unlink($section->map_pdf);
        $section->delete();
        return redirect(route('section.index'));
    }
}
