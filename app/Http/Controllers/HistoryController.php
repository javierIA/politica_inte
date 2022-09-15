<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Group;
use App\History;
use App\PoliticalFunction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData']
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

        return Excel::download(new ClassExport(History::class, $items),'History.'.$format);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.history');
        $repo  = History::all();
        $columns = History::getTableColumns();
        return view('admin.history.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $select = ["to_char(T.created_at, 'HH12:MI AM') as time",
                   "to_char(T.created_at, 'dd/MM/YYYY') as date",
                   "T.user::json->>'name' as name",
                   "null as selected"];
        $info = [
            'columns' =>  ['selected' ,'id', 'action', 'table', 'name', 'time', 'date'],
            'controller' => 'HistoryController',
            'table' => with(new History())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'history.edit',
                    'text' => trans('admin.edit_history')
                ],
                'destroy' => [
                    'name' => 'history.destroy',
                    'text' => trans('admin.delete_history')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $select );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Internal store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save($action, $table, $description)
    {
        $history = new History();
        $history->action = $action;
        $history->table = $table;
        $history->description = $description;
        $history->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(History $history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history)
    {
        $date = date( "d/m/Y", strtotime($history->created_at) );
        $time = date( "h:i:s A", strtotime($history->created_at) );
        $keys_temp = \Schema::getColumnListing($history->table);
        unset($keys_temp[array_search('id', $keys_temp)]);
        unset($keys_temp[array_search('updated_at', $keys_temp)]);

        $keys = '';
        foreach ($keys_temp as $k){
            try {
                if($k == 'created_at') {
                    $keys .= trans('admin.created_date') . ': ' . date("d/m/Y", strtotime(json_decode($history->description)->created_at)) . '&#13;';
                    $keys .= trans('admin.created_time') . ': ' . date("h:i:s A", strtotime(json_decode($history->description)->created_at)) . '&#13;';
                }else
                    $keys .= trans('admin.'.$k).': '.json_decode($history->description)->$k.'&#13;';
            } catch (\Exception $e) {
                continue;
            }
        }
        $title = trans('admin.date').': '.$date.'   '. trans('admin.time').': '.$time;
        return view('admin.history.edit', compact('title', 'keys', 'history'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(History $history)
    {
        //
    }
}
