<?php

namespace App\Http\Controllers;


use App\Block;
use App\BoxType;
use App\Exports\ClassExport;
use App\FedEntity;
use App\Imports\ExcelImport;
use App\LocDistrict;
use App\Section;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BlockController extends Controller
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

        return Excel::download(new ClassExport(Block::class, $items),'block.'.$format);
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
        ];
        Excel::import(new ExcelImport(Block::class), $file);
        return redirect(route('block.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.block');
        $repo  = Block::all();
        $columns = Block::getTableColumns();
        return view('admin.block.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_block');
        $items = Section::all(['id', 'section_key']);
        return view('admin.block.create', compact('title', 'items'));
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
            'block_key' => 'numeric|max:9999|min:0|unique:blocks',
            'section_id' => 'required',
        ]);
        $items = $request->all();
        $block = new Block();
        $block->fill($items);

        $block->save();
        $this->savelog($block,'save');

        return redirect(route('block.index'));

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
        $selects = ["(SELECT section_key FROM sections WHERE id = T.section_id) as section",
                    "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.titular_person) as titular_name",
                    "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.vocal_person) as vocal_name",
                    "null as selected"];

        $info = [
            'columns' =>  ['selected' ,'id', 'block_key', 'titular_name', 'vocal_name', 'section'],
            'controller' => 'BlockController',
            'table' => with(new Block())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'block.edit',
                    'text' => trans('admin.edit_block')
                ],
                'destroy' => [
                    'name' => 'block.destroy',
                    'text' => trans('admin.delete_block')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Block $block)
    {
        $title = $block->block_key;
        $sections = Section::all(['id', 'section_key']);
        return view('admin.block.edit', compact('title', 'block', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Block $block)
    {
        $filter = ['block_key' => 'required', 'section_id' => 'required'];
        $count = Block::where('block_key',$request->block_key)->count();
        if($count >1 || $block->block_key != $request->block_key)
            $filter['block_key'] = 'required|numeric|max:9999|min:0|unique:blocks';
        $request->validate($filter);

        $items = $request->all();
        $block->fill($items);
        $block->update();
        $this->savelog($block,'update');
        return redirect(route('block.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $block = Block::findOrFail($id);
        $this->savelog($block,'delete');
        $block->delete();
        return redirect(route('block.index'));
    }
}
