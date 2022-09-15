<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\Municipality;
use App\Person;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FedEntityController extends Controller
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

       return Excel::download(new ClassExport(FedEntity::class, $items),'FedEntity.'.$format);
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
        $exceptions = ['titular_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'vocal_person'=>['table'=>'persons', 'param' => 'elector_key'],
            'representative'=>['table'=>'persons', 'param' => 'elector_key'],
            'alternate'=>['table'=>'persons', 'param' => 'elector_key'],
        ];
        Excel::import(new ExcelImport(FedEntity::class), $file);
        return redirect(route('fed_entity.index'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.fed_entity');
        $repo  = FedEntity::all();
        $columns = FedEntity::getTableColumns();
        return view('admin.fed_entity.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_fed_entity');
        return view('admin.fed_entity.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'entity_key','entity_name','map_pdf'],
            'controller' => 'FedEntityController',
            'table' => with(new FedEntity())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'fed_entity.edit',
                    'text' => trans('admin.edit_fed_entity')
                ],
                'destroy' => [
                    'name' => 'fed_entity.destroy',
                    'text' => trans('admin.delete_fed_entity')
                ]
            ],
            'replace' =>[
                'map_pdf' => [
                    'replace_link' => [
                        'text' => '<a href="%s" target="_blank" class="d-flex align-items-center justify-content-center"><span ><i class="entypo-vcard fa-lg">&nbsp;&nbsp;</i> %s</span></a>',
                        'data' => [
                                'name' => 'entity_key',
                                'url' => url("pdf/fed_entitys/")
                             ]
                        ]
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
            'entity_key' => 'numeric|max:9999|min:0|unique:fed_entitys',
            'map_pdf' => 'max:10000|mimes:pdf',
        ]);
        $fed_entity = new FedEntity();
        $fed_entity->entity_key = $request->entity_key;
        $fed_entity->entity_name = $request->entity_name;
        if(!is_null($request->file))
            $fed_entity->map_pdf = $this->saveImage($request->file, $fed_entity->entity_key, $fed_entity);
        $fed_entity->save();
        $this->savelog($fed_entity,'save');

        return redirect(route('fed_entity.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FedEntity  $fedEntity
     * @return \Illuminate\Http\Response
     */
    public function show(FedEntity $fedEntity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FedEntity  $fedEntity
     * @return \Illuminate\Http\Response
     */
    public function edit(FedEntity $fedEntity)
    {
        $title = $fedEntity->entity_key;
        return view('admin.fed_entity.edit', compact('title', 'fedEntity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FedEntity  $fedEntity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FedEntity $fedEntity)
    {
        $count = FedEntity::where('entity_key',$request->entity_key)->count();
        if($count >1 || $fedEntity->entity_key != $request->entity_key)
            $request->validate([
                'entity_key' => 'numeric|max:9999|min:0|unique:fed_entitys',
            ]);

        $fedEntity->entity_key = $request->entity_key;
        $fedEntity->entity_name = $request->entity_name;
        if(!is_null($request->file))
            $fedEntity->map_pdf = $this->saveImage($request->file, $fedEntity->entity_key, $fedEntity);
        $fedEntity->update();
        $this->savelog($fedEntity,'update');
        return redirect(route('fed_entity.index'));
    }

    /**
     * Assign responsible to municipality.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setResponsible($id){
        $fed_entity = FedEntity::findOrFail($id);
        $persons = Person::all();
        $fed_entitys = FedEntity::all();
        $title = trans('admin.set_responsible').': '.$fed_entity->entity_name;
        return view('admin.fed_entity.responsible', compact('title', 'fed_entity', 'fed_entitys', 'persons'));
    }

    /**
     * Save responsible to municipality.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveResponsible(Request $request, $id){
        $fed_entity = FedEntity::findOrFail($id);
        $data = $request->all();
        foreach ($data as $key => $value){
            if(is_null($value) || empty($value))
                unset($data[$key]);
            else if($value == -1)
                $data[$key] = null;
        }

        $fed_entity->fill($data);
        $fed_entity->update();
        $this->savelog($fed_entity,'update');
        return redirect(route('fed_entity.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fedEntity = FedEntity::findOrFail($id);
        $this->savelog($fedEntity,'delete');
        if(file_exists($fedEntity->map_pdf))
            unlink($fedEntity->map_pdf);
        $fedEntity->delete();
        return redirect(route('fed_entity.index'));
    }
}
