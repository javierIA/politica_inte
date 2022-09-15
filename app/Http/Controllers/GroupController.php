<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Group;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GroupController extends Controller
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
     * @return BinaryFileResponse
     */
    public function exportData(Request $request, $format, $ids){
        $selection = json_decode($ids);
        $items = $request->all();
        unset($items['_token']);
        if (ob_get_contents()) ob_end_clean();
        ob_start(); // and this

        if(!is_null($selection))
            $items['raw'] = sprintf("id in (%s)", join(',', $selection));

       return Excel::download(new ClassExport(Group::class, $items),'group.'.$format);
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
        Excel::import(new ExcelImport(Group::class), $file);
        return redirect(route('group.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.group');
        $repo  = Group::all();
        $columns = Group::getTableColumns();
        return view('admin.group.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_group');
        return view('admin.group.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'group_name', 'description', 'default'],
            'controller' => 'GroupController',
            'table' => with(new Group())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'group.edit',
                    'text' => trans('admin.edit_group')
                ],
                'destroy' => [
                    'name' => 'group.destroy',
                    'text' => trans('admin.delete_group')
                ]
            ],
            'replace' => [
                'default' => [
                    true => '<i style="color:green;" class="entypo-check"></i>',
                    false => '<i style="color:red;" class="entypo-cancel"></i>'
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
            'group_name' => 'unique:groups',
        ]);

        $items = $request->all();
        unset($items['_token']);

        $group = new Group();
        $group->fill($items);
        $group->save();
        $this->savelog($group,'save');
        return redirect(route('group.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $title = $group->group_name;
        return view('admin.group.edit', compact('title', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $items = $request->all();
        $items['default'] = isset($items['default'])? $items['default']: false;
        unset($items['_token']);
        if(Group::where('group_name',$items['group_name'])->count() > 1)
            $request->validate([
                'group_name' => 'unique:group_name',
            ]);
        $group->fill($items);
        $group->update();
        $this->savelog($group,'update');
        return redirect(route('group.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $this->savelog($group,'delete');
        $group->delete();
        return redirect(route('group.index'));
    }
}
