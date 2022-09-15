<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\SocialNetwork;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SocialNetworkController extends Controller
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

        return Excel::download(new ClassExport(SocialNetwork::class, $items),'SocialNetwork.'.$format);
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
        Excel::import(new ExcelImport(SocialNetwork::class), $file);
        return redirect(route('social_network.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.social_network');
        $repo  = SocialNetwork::all();
        $columns = SocialNetwork::getTableColumns();
        return view('admin.social_network.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_social_network');
        return view('admin.social_network.create', compact('title'));
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
            'columns' =>  ['selected' ,'id', 'name_social_network'],
            'controller' => 'SocialNetworkController',
            'table' => with(new SocialNetwork())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'social_network.edit',
                    'text' => trans('admin.edit_social_network')
                ],
                'destroy' => [
                    'name' => 'social_network.destroy',
                    'text' => trans('admin.delete_social_network')
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
            'name_social_network' => 'unique:social_networks',
        ]);
        $items = $request->all();
        unset($items['_token']);

        $socialNetwork = new SocialNetwork();
        $socialNetwork->fill($items);
        $socialNetwork->save();
        $this->savelog($socialNetwork,'update');
        return redirect(route('social_network.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SocialNetwork  $socialNetwork
     * @return \Illuminate\Http\Response
     */
    public function show(SocialNetwork $socialNetwork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SocialNetwork  $socialNetwork
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialNetwork $socialNetwork)
    {
        $title = $socialNetwork->name_social_network;
        return view('admin.social_network.edit', compact('title', 'socialNetwork'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SocialNetwork  $socialNetwork
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SocialNetwork $socialNetwork)
    {
        $items = $request->all();
        unset($items['_token']);
        if(SocialNetwork::where('name_social_network',$items['name_social_network'])->count() > 1)
            $request->validate([
                'name_social_network' => 'unique:social_networks',
            ]);

        $socialNetwork->fill($items);
        $socialNetwork->update();
        $this->savelog($socialNetwork,'update');
        return redirect(route('social_network.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $socialNetwork = SocialNetwork::findOrFail($id);
        $this->savelog($socialNetwork,'save');
        $socialNetwork->delete();
        return redirect(route('social_network.index'));
    }
}
