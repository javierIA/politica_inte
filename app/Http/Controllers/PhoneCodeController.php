<?php

namespace App\Http\Controllers;

use App\BoxType;
use App\Exports\ClassExport;
use App\PhoneCode;
use App\PoliticalFunction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PhoneCodeController extends Controller
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

        return Excel::download(new ClassExport(PhoneCode::class, $items),'PhoneCode.'.$format);
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
        Excel::import(new ExcelImport(PhoneCode::class), $file);
        return redirect(route('phone_code.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.phone_code');
        $repo  = PhoneCode::all();
        $columns = PhoneCode::getTableColumns();
        return view('admin.phone_code.index', compact('title', 'repo','columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fileList = glob('flat/*');
        $arr = array();
        foreach($fileList as $filename){
            $name = explode("/",$filename);
            $name1 = explode(".",$name[1]);
            array_push($arr,$name1[0]);
        }

        $title = trans('admin.create_phone_code');
        return view('admin.phone_code.create', compact('title','arr'));
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
            'columns' =>  ['selected' ,'id', 'phone_code', 'country', 'flag_name'],
            'controller' => 'PhoneCodeController',
            'table' => with(new PhoneCode())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'phone_code.edit',
                    'text' => trans('admin.edit_phone_code')
                ],
                'destroy' => [
                    'name' => 'phone_code.destroy',
                    'text' => trans('admin.delete_phone_code')
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
            'phone_code' => 'unique:phone_codes',
        ]);

        $items = $request->all();
        unset($items['_token']);

        $phone_code = new PhoneCode();
        $phone_code->fill($items);
        $phone_code->save();
        $this->savelog($phone_code,'update');
        return redirect(route('phone_code.index'));
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
    public function edit(PhoneCode  $phoneCode)
    {
        $fileList = glob('flat/*');
        $arr = array();
        foreach($fileList as $filename){
            $name = explode("/",$filename);
            $name1 = explode(".",$name[1]);
            array_push($arr,$name1[0]);
        }

        $title = $phoneCode->phone_code;
        $function_type = array('territorial' => trans('admin.territorial'),
                               'political' => trans('admin.political'));
        return view('admin.phone_code.edit', compact('title', 'phoneCode', 'arr'));

       }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PoliticalFunction  $politicalFunction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhoneCode $phoneCode)
    {
        $items = $request->all();
        unset($items['_token']);
        if(PhoneCode::where('phone_code',$items['phone_code'])->count() > 1)
            $request->validate([
                'phone_code' => 'unique:phone_code',
            ]);
        $phoneCode->fill($items);
        $phoneCode->update();
        $this->savelog($phoneCode,'update');
        return redirect(route('phone_code.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $phone_code = PhoneCode::findOrFail($id);
        $this->savelog($phone_code,'delete');
        $phone_code->delete();
        return redirect(route('phone_code.index'));
    }
}
