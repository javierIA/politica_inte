<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Imports\ExcelImport;
use App\Role;
use App\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SettingController extends Controller
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
    public function exportData(Request $request, $format){
        $items = $request->all();
        unset($items['_token']);
        if (ob_get_contents()) ob_end_clean();
        ob_start(); // and this
        return Excel::download(new ClassExport(Setting::class, $items),'Setting.'.$format);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.setting');
        $repo  = Setting::all();
        return view('admin.setting.index', compact('title', 'repo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.add_setting');

        return view('admin.setting.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting = Setting::first();
        if (!is_null($setting))
            Setting::destroy($setting->id);
        Setting::create($request->all());

        return redirect(route('setting.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
       // $setting = Setting::first();
        $title     = trans('admin.edit_setting');
        $roles = Role::all();
        return view('admin.setting.edit', compact('title', 'setting', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $data = $request->all();
        $data['allow_functions'] = isset($data['allow_functions']);

        $setting->fill($data);
        $setting->update();
        $this->savelog($setting,'update');
        return redirect(route('setting.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = Setting::first();
        if (!is_null($setting))
            Setting::destroy($setting->id);

        return redirect(route('setting.index'));
    }
}
